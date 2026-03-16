<?php
/**
 * Import LUPOPEDIA HEADERS into a Markdown file (replace existing header with supplied YAML).
 * Usage: php import_lupopedia_headers.php <path/to/target.md> [path/to/source.yaml]
 *   Or: cat headers.yaml | php import_lupopedia_headers.php <path/to/target.md> --
 * If no source file and no stdin, exits with error.
 * Validates replacement YAML has lupopedia.headers and required fields before writing.
 * 4.0.77: preserves body (content after closing ---); respects canonical block order.
 *
 * @param string $target_path Path to .md file to update
 * @param string $yaml_content Raw YAML block (without --- delimiters) to set as new header
 * @return array 'success' => bool, 'error' => string
 */
function import_lupopedia_headers($target_path, $yaml_content) {
    $result = array('success' => false, 'error' => '');
    $yaml_content = trim($yaml_content);
    if ($yaml_content === '') {
        $result['error'] = 'Empty YAML content.';
        return $result;
    }
    // Strip optional surrounding --- for convenience
    if (preg_match('/^---\s*\n(.*)\n---\s*$/s', $yaml_content, $m)) {
        $yaml_content = trim($m[1]);
    }
    // Minimal validation: required block and fields
    if (strpos($yaml_content, 'lupopedia.headers:') === false) {
        $result['error'] = 'Replacement YAML must contain lupopedia.headers block.';
        return $result;
    }
    $required = array('lupopedia.version', 'file_path_from_root', 'last_modified_utc', 'system_version');
    foreach ($required as $key) {
        if (!preg_match('/^\s*' . preg_quote($key, '/') . '\s*:/m', $yaml_content)) {
            $result['error'] = 'Replacement YAML missing required field in lupopedia.headers: ' . $key;
            return $result;
        }
    }
    if (!is_file($target_path)) {
        $result['error'] = 'Target file not found: ' . $target_path;
        return $result;
    }
    $raw = file_get_contents($target_path);
    if ($raw === false) {
        $result['error'] = 'Could not read target file.';
        return $result;
    }
    $lines = explode("\n", $raw);
    $body_start = null;
    for ($i = 1; $i < count($lines); $i++) {
        if (preg_match('/^---\s*$/', trim($lines[$i]))) {
            $body_start = $i + 1;
            break;
        }
    }
    if ($body_start === null) {
        $result['error'] = 'Target file has no closing --- for header block.';
        return $result;
    }
    $body = array_slice($lines, $body_start);
    $body_str = implode("\n", $body);
    $new_content = "---\n" . $yaml_content . "\n---\n" . $body_str;
    if (file_put_contents($target_path, $new_content) === false) {
        $result['error'] = 'Could not write target file.';
        return $result;
    }
    $result['success'] = true;
    return $result;
}

if (php_sapi_name() === 'cli' && isset($argv) && isset($argv[1])) {
    $target = trim($argv[1]);
    $source_path = null;
    if (isset($argv[2]) && trim($argv[2]) !== '--') {
        $source_path = trim($argv[2]);
    }
    if (!is_file($target)) {
        $abspath = defined('ABSPATH') ? ABSPATH : dirname(dirname(__FILE__)) . '/';
        $target = $abspath . $target;
    }
    if (!is_file($target)) {
        fwrite(STDERR, "[import_lupopedia_headers] Target file not found: " . $argv[1] . "\n");
        exit(1);
    }
    $yaml_content = '';
    if ($source_path !== null && is_file($source_path)) {
        $yaml_content = file_get_contents($source_path);
    } else {
        $yaml_content = @stream_get_contents(STDIN);
    }
    if (trim($yaml_content) === '') {
        fwrite(STDERR, "[import_lupopedia_headers] No source: provide path to YAML file or pipe YAML via stdin (use -- as second arg).\n");
        exit(1);
    }
    $result = import_lupopedia_headers($target, $yaml_content);
    if (!$result['success']) {
        fwrite(STDERR, "[import_lupopedia_headers] " . $result['error'] . "\n");
        exit(1);
    }
    echo "OK: Header imported into " . $target . "\n";
    exit(0);
}
