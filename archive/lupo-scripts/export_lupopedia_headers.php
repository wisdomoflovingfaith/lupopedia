<?php
/**
lupopedia.headers:
  when_updated: "20260324175911"
  file_path_from_root: "lupo-scripts/export_lupopedia_headers.php"
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
  file_path_from_root: "lupo-scripts/export_lupopedia_headers.php"
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
/**
 * Export LUPOPEDIA HEADERS from a Markdown file.
 * Usage: php export_lupopedia_headers.php <path/to/file.md> [--output=path] [--json]
 * Or: from lupo.php "headers export <path>", require this file and call export_lupopedia_headers($path, $options).
 * Returns: array with 'yaml' (raw YAML block), 'body' (content after closing ---), 'success' (bool), 'error' (string if failed).
 * 4.0.77: exports the single front-matter YAML block for round-trip use with import_lupopedia_headers.php.
 *
 * @param string $path Path to .md file
 * @param array $options Optional: 'output' => path to write YAML file, 'json' => true to emit JSON
 * @return array 'yaml' => string, 'body' => string, 'success' => bool, 'error' => string
 */
function export_lupopedia_headers($path, $options = array()) {
    $result = array('yaml' => '', 'body' => '', 'success' => false, 'error' => '');
    if (!is_file($path)) {
        $result['error'] = 'File not found: ' . $path;
        return $result;
    }
    $raw = file_get_contents($path);
    if ($raw === false) {
        $result['error'] = 'Could not read file.';
        return $result;
    }
    $lines = explode("\n", $raw);
    if (count($lines) < 2) {
        $result['error'] = 'File too short; expected --- then YAML block.';
        return $result;
    }
    if (rtrim($lines[0]) !== '---') {
        $result['error'] = "First line must be exactly '---'.";
        return $result;
    }
    $yaml_block = '';
    $body_lines = array();
    $found_closing = false;
    for ($i = 1; $i < count($lines); $i++) {
        $line = $lines[$i];
        if (!$found_closing && preg_match('/^---\s*$/', trim($line))) {
            $found_closing = true;
            for ($j = $i + 1; $j < count($lines); $j++) {
                $body_lines[] = $lines[$j];
            }
            break;
        }
        if (!$found_closing) {
            $yaml_block .= $line . "\n";
        }
    }
    if (!$found_closing) {
        $result['error'] = "No closing '---' found for YAML block.";
        return $result;
    }
    $result['yaml'] = rtrim($yaml_block);
    $result['body'] = implode("\n", $body_lines);
    $result['success'] = true;
    return $result;
}

if (php_sapi_name() === 'cli' && isset($argv) && isset($argv[1])) {
    $path = trim($argv[1]);
    $output_path = null;
    $as_json = false;
    for ($i = 2; $i < (isset($argv) ? count($argv) : 0); $i++) {
        if (strpos($argv[$i], '--output=') === 0) {
            $output_path = trim(substr($argv[$i], 9));
        }
        if ($argv[$i] === '--json') {
            $as_json = true;
        }
    }
    if (!is_file($path)) {
        $path = (defined('ABSPATH') ? ABSPATH : dirname(dirname(__FILE__)) . '/') . $path;
    }
    $result = export_lupopedia_headers($path, array('output' => $output_path, 'json' => $as_json));
    if (!$result['success']) {
        fwrite(STDERR, "[export_lupopedia_headers] " . $result['error'] . "\n");
        exit(1);
    }
    if ($as_json) {
        $out = json_encode(array('yaml' => $result['yaml'], 'body' => $result['body']));
        if ($output_path) {
            file_put_contents($output_path, $out);
        } else {
            echo $out;
        }
    } else {
        $out = $result['yaml'];
        if ($output_path) {
            file_put_contents($output_path, $out);
        } else {
            echo $out;
        }
    }
    exit(0);
}
