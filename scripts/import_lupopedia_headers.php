<?php
/**
lupopedia.headers:
  when_updated: "20260324175911"
  file_path_from_root: "scripts/import_lupopedia_headers.php"
  questions_toon: null
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "tooling"
  artifact_kind: "script"
lupopedia.footer:
  last_verified: "20260324175911"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
*/
/**
lupopedia.headers:
  when_updated: "20260324175617"
  file_path_from_root: "scripts/import_lupopedia_headers.php"
  questions_toon: null
  channel_id: 42
  actor_id: 102
  actor_name: "cursor"
  delegation_chain: "cursor:root"
  artifact_type: "tooling"
  artifact_kind: "script"
lupopedia.footer:
  last_verified: "20260324175617"
  last_verified_by: "cursor"
  last_verified_by_actor_id: 102
*/
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'lupopedia' . DIRECTORY_SEPARATOR . 'content' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Services' . DIRECTORY_SEPARATOR . 'Validation' . DIRECTORY_SEPARATOR . 'HeaderValidationService.php';
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
    $header = parse_lupopedia_header_block($yaml_content);
    if (!is_array($header) || empty($header)) {
        $validation = array(
            'valid' => false,
            'errors' => array('Malformed header: unable to parse lupopedia.headers block.')
        );
        $result['error'] = json_encode($validation, JSON_UNESCAPED_SLASHES);
        return $result;
    }
    $actorService = isset($GLOBALS['lupo_actor_service']) ? $GLOBALS['lupo_actor_service'] : null;
    $validator = new \App\Services\Validation\HeaderValidationService($actorService);
    $validation = $validator->validate($header);
    if (!isset($validation['valid']) || !$validation['valid']) {
        $result['error'] = json_encode($validation, JSON_UNESCAPED_SLASHES);
        return $result;
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

/**
 * Parse lupopedia.headers block into flat key=>value map.
 *
 * @param string $yaml_content
 * @return array
 */
function parse_lupopedia_header_block($yaml_content)
{
    if (function_exists('yaml_parse')) {
        $parsed = @yaml_parse($yaml_content);
        if (is_array($parsed) && isset($parsed['lupopedia.headers']) && is_array($parsed['lupopedia.headers'])) {
            return $parsed['lupopedia.headers'];
        }
    }

    $lines = explode("\n", str_replace("\r\n", "\n", $yaml_content));
    $inHeaders = false;
    $out = array();
    foreach ($lines as $line) {
        if (!$inHeaders) {
            if (preg_match('/^\s*lupopedia\.headers\s*:\s*$/', $line)) {
                $inHeaders = true;
            }
            continue;
        }

        if (preg_match('/^\S/', $line)) {
            break;
        }

        if (preg_match('/^\s{2,}([A-Za-z0-9_\.]+)\s*:\s*(.*)\s*$/', $line, $m)) {
            $key = trim($m[1]);
            $val = trim($m[2]);
            if (preg_match('/^["\'](.*)["\']$/', $val, $v)) {
                $val = $v[1];
            }
            $out[$key] = $val;
        }
    }
    return $out;
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
