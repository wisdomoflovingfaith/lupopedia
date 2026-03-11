<?php
/**
 * One-time script: sync lupo-rules/root/*.md rule content into .cursor/rules/*.mdc.
 * Reads each root .md, extracts purpose (description) and body, writes Cursor .mdc format.
 * Run from repo root: php scripts/sync_root_rules_to_cursor.php
 */

$repoRoot = dirname(__DIR__);
$rootDir = $repoRoot . DIRECTORY_SEPARATOR . 'lupo-rules' . DIRECTORY_SEPARATOR . 'root';
$cursorDir = $repoRoot . DIRECTORY_SEPARATOR . '.cursor' . DIRECTORY_SEPARATOR . 'rules';

if (!is_dir($rootDir) || !is_dir($cursorDir)) {
    fwrite(STDERR, "Missing lupo-rules/root or .cursor/rules\n");
    exit(1);
}

$files = glob($rootDir . DIRECTORY_SEPARATOR . '*.md');
$count = 0;
foreach ($files as $path) {
    $base = basename($path, '.md');
    if ($base === 'README') {
        continue;
    }
    $content = file_get_contents($path);
    if ($content === false) {
        fwrite(STDERR, "Read failed: $path\n");
        continue;
    }
    $parts = explode('---', $content, 3);
    if (count($parts) < 3) {
        fwrite(STDERR, "Skip (no --- blocks): $base\n");
        continue;
    }
    $yaml = $parts[1];
    $after = $parts[2];
    $description = '';
    if (preg_match('/^\s*purpose:\s*["\']([^"\']+)["\']/m', $yaml, $m)) {
        $description = trim($m[1]);
    } elseif (preg_match('/^\s*rule_name:\s*["\']?([^"\'\n]+)["\']?/m', $yaml, $m)) {
        $description = trim($m[1]);
    }
    if ($description === '') {
        $description = "Root rule: $base";
    }
    $lines = explode("\n", trim($after));
    $bodyLines = array();
    $skipIdentity = true;
    foreach ($lines as $line) {
        if ($skipIdentity && preg_match('/^#\s*file:\s*/', $line)) {
            $skipIdentity = false;
            continue;
        }
        $bodyLines[] = $line;
    }
    $body = implode("\n", $bodyLines);
    $body = str_replace('../../../', '../../', $body);
    $mdc = "---\ndescription: " . $description . "\nalwaysApply: true\n---\n\n" . $body . "\n";
    $outPath = $cursorDir . DIRECTORY_SEPARATOR . $base . '.mdc';
    if (file_put_contents($outPath, $mdc) === false) {
        fwrite(STDERR, "Write failed: $outPath\n");
        continue;
    }
    echo "Written: .cursor/rules/$base.mdc\n";
    $count++;
}
echo "Done. $count rules synced.\n";
