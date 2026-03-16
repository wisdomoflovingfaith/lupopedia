<?php
/**
 * Validate LUPOPEDIA HEADERS in a Markdown file.
 * Usage: php validate_lupopedia_headers.php <path/to/file.md>
 * Or: from lupo.php "headers validate <path>", require this file and call validate_lupopedia_headers($path).
 * Returns: array of error strings (empty if valid). Exit 0 if valid, 1 if invalid; errors to stderr when run as CLI.
 * 4.0.77: partial implementation — checks file order, required block/fields, snapshot comment.
 *
 * @param string $path Path to .md file
 * @return array List of error messages
 */
function validate_lupopedia_headers($path) {
    $errors = array();
    if (!is_file($path)) {
        $errors[] = "File not found: " . $path;
        return $errors;
    }
    $raw = file_get_contents($path);
    if ($raw === false) {
        $errors[] = "Could not read file.";
        return $errors;
    }
    $lines = explode("\n", $raw);

    // 1. First line must be ---
    if (count($lines) < 2) {
        $errors[] = "File too short; expected --- then YAML block.";
    } else {
        $first = rtrim($lines[0]);
        if ($first !== '---') {
            $errors[] = "First line must be exactly '---'; got: " . (strlen($first) > 40 ? substr($first, 0, 40) . '...' : $first);
        }
    }

    // 2. Find closing --- and extract YAML block
    $yaml_block = '';
    $closing_line = null;
    $body_start = null;
    for ($i = 1; $i < count($lines); $i++) {
        $line = $lines[$i];
        if (preg_match('/^---\s*$/', trim($line))) {
            $closing_line = $i;
            $body_start = $i + 1;
            break;
        }
        $yaml_block .= $line . "\n";
    }

    if ($closing_line === null) {
        $errors[] = "No closing '---' found for YAML block.";
    } else {
        // 3. Identity line must be first line of body
        if ($body_start < count($lines)) {
            $identity = trim($lines[$body_start]);
            if (strpos($identity, '# file:') !== 0) {
                $errors[] = "First line after closing --- must be identity line (# file: ...); got: " . (strlen($identity) > 50 ? substr($identity, 0, 50) . '...' : $identity);
            }
        }
        // 4. Exactly one front matter block
        $delim_count = 0;
        for ($i = 0; $i < min(50, count($lines)); $i++) {
            if (preg_match('/^---\s*$/', trim($lines[$i]))) {
                $delim_count++;
            }
        }
        if ($delim_count > 2) {
            $errors[] = "Duplicate header block: more than two '---' delimiters in header area.";
        }
    }

    // 5. Required: lupopedia.headers block
    if (strpos($yaml_block, 'lupopedia.headers:') === false) {
        $errors[] = "Missing required block: lupopedia.headers";
    }

    // 6. Required fields in lupopedia.headers
    $required_in_headers = array('lupopedia.version', 'file_path_from_root', 'last_modified_utc', 'system_version');
    foreach ($required_in_headers as $key) {
        if (!preg_match('/^\s*' . preg_quote($key, '/') . '\s*:/m', $yaml_block)) {
            $errors[] = "Missing required field in lupopedia.headers: " . $key;
        }
    }

    // 7. If lupopedia.edges present, must have comment with snapshot or static
    if (preg_match('/lupopedia\.edges\s*:/', $yaml_block)) {
        if (!preg_match('/comment\s*:.*(snapshot|static)/im', $yaml_block)) {
            $errors[] = "lupopedia.edges block must contain a comment field with 'snapshot' or 'static'.";
        }
    }

    // 8. If lupopedia.engagement present, must have comment
    if (preg_match('/lupopedia\.engagement\s*:/', $yaml_block)) {
        if (!preg_match('/comment\s*:/', $yaml_block)) {
            $errors[] = "lupopedia.engagement block must contain a comment field.";
        }
    }

    return $errors;
}

if (php_sapi_name() === 'cli' && isset($argv) && isset($argv[1])) {
    $path = trim($argv[1]);
    $errors = validate_lupopedia_headers($path);
    if (count($errors) > 0) {
        foreach ($errors as $e) {
            fwrite(STDERR, "[validate_lupopedia_headers] " . $e . "\n");
        }
        exit(1);
    }
    exit(0);
}
