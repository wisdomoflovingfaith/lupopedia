<?php
require_once dirname(__DIR__) . '/lupo-includes/classes/MemoryGraph.php';

$cmd = isset($argv[1]) ? $argv[1] : '';

switch ($cmd) {
    case 'load-context':
        // Optional: php memory.php load-context lupo-memory/2026/04/M-foo.toon
        $startRef = isset($argv[2]) ? $argv[2] : null;
        $graph    = new MemoryGraph();
        $context  = $graph->loadContext($startRef);
        echo json_encode($context, JSON_PRETTY_PRINT) . "\n";
        break;

    case 'set-active-ref':
        // php memory.php set-active-ref lupo-memory/2026/04/M-foo.toon
        if (empty($argv[2])) {
            fwrite(STDERR, "Usage: php memory.php set-active-ref <memory-ref>\n");
            exit(1);
        }
        $graph = new MemoryGraph();
        $graph->setActiveMemoryRef($argv[2]);
        echo "[OK] active_memory_ref set to: {$argv[2]}\n";
        break;

    default:
        echo "Usage:\n";
        echo "  php memory.php load-context [ref]\n";
        echo "  php memory.php set-active-ref <lupo-memory/YYYY/MM/M-id.toon>\n";
        break;
}
