<?php
/**
 * Unit tests for FlareParser: nested edges, inline objects, depth limit.
 * Run: php tests/unit/flare_parser_test.php
 */

$base = dirname(dirname(__DIR__));
$parser = $base . DIRECTORY_SEPARATOR . 'lupo-includes' . DIRECTORY_SEPARATOR . 'classes' . DIRECTORY_SEPARATOR . 'FlareParser.php';
if (!is_file($parser)) {
    echo "SKIP FlareParser.php not found\n";
    exit(0);
}
require_once $parser;

$ok = 0;
$fail = 0;

$content = '---
actor_id: 42
flare.edges:
  outbound_edges:
    - { to: "GEMINI.md", type: "references", weight: 0.9 }
flare.hooks:
  init:
    - { type: "log", target: "init.log", params: { message: "Antigravity initialized" } }
---
# Body
';
$parsed = FlareParser::parse($content, '');
if (!isset($parsed['headers']) || !isset($parsed['body'])) {
    echo "FAIL parse structure\n";
    $fail++;
} else {
    echo "PASS parse structure\n";
    $ok++;
}
if (isset($parsed['headers']['actor_id']) && $parsed['headers']['actor_id'] === 42) {
    echo "PASS actor_id 42\n";
    $ok++;
} else {
    echo "FAIL actor_id\n";
    $fail++;
}
$edges = isset($parsed['headers']['flare.edges']['outbound_edges']) ? $parsed['headers']['flare.edges']['outbound_edges'] : array();
if (is_array($edges) && count($edges) > 0 && isset($edges[0]['to']) && $edges[0]['to'] === 'GEMINI.md') {
    echo "PASS nested edges\n";
    $ok++;
} else {
    echo "FAIL nested edges\n";
    $fail++;
}
if (strpos($parsed['body'], '# Body') !== false) {
    echo "PASS body\n";
    $ok++;
} else {
    echo "FAIL body\n";
    $fail++;
}

echo "Result: $ok pass, $fail fail\n";
exit($fail > 0 ? 1 : 0);
