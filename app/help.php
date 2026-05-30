<?php
/**
 * Lupopedia Help System
 * Displays structured information about Lupopedia features and commands.
 * PHP 5.3 Compatible.
 * @package Lupopedia
 */

if (!defined('LUPOPEDIA_CONFIG_LOADED')) {
    die("Config not loaded.");
}

function lupo_show_help($topic = null)
{
    $base_path = defined('ABSPATH') ? ABSPATH : dirname(__DIR__) . '/';

    if ($topic && preg_match('/^[a-z0-9_\-]+$/i', $topic)) {
        // Try to load topic-specific help from docs/ or lupo-docs/
        $found = false;
        $doc_dirs = array('docs', 'lupo-docs', 'lupo-docs/doctrine');

        foreach ($doc_dirs as $dir) {
            $path = $base_path . $dir . '/' . strtolower($topic) . '.md';
            if (file_exists($path)) {
                echo "\nHelp for Topic: " . ucfirst($topic) . "\n";
                echo str_repeat("=", strlen($topic) + 16) . "\n\n";
                echo file_get_contents($path) . "\n";
                $found = true;
                break;
            }
        }

        if (!$found) {
            echo "\nNo detailed help found for topic: '$topic'. Showing general help.\n";
            lupo_show_general_help();
        }
    } else {
        lupo_show_general_help();
    }
}

function lupo_show_general_help()
{
    echo <<<HELP
Lupopedia Help
==============

Lupopedia is a federated knowledge platform with actors, FLARE headers, and modular directories.

Basic Usage:
- lupopedia init: Initialize the system (creates directories, configs, and default actors like 0 and 1).
- lupopedia scan: Run system scans (e.g., for FLARE headers via actor 0's prompts).
- lupopedia actor list: List all installed actors (e.g., 0: System, 1: WOLFIE, 42: Antigravity).
- lupopedia actor add <id> <name>: Add a new actor (creates directory in LUPO_ACTORS_DIR/<id>/ with subdirs like apps/, tools/, etc.).
- lupopedia sync: Synchronize filesystem and DB (handles drifts based on UTC and policies).
- lupopedia help <topic>: Detailed help on a topic (e.g., actors, flare, config).

Key Concepts:
- Actors: Modular agents in LUPO_ACTORS_DIR. Each has subdirs for apps, tools, docs, db-changes, api, needs, prompts.
- FLARE Headers: YAML frontmatter for metadata (e.g., actor_id, edges, hooks). Scanned and enforced by system actor.
- Config: Defined in lupopedia-config.php (prefix 'lupo-', dirs like content, database, etc.).
- Nodes: Content separated by federation_node_id (e.g., lupo-content/federation_node_id/0 for root).

For more details, check docs/actors.md or README.md.
Run 'lupopedia help actors' for actor-specific info.

HELP;
}

// If this file is included, we might want to run it based on global argv
if (isset($argv)) {
    $topic = isset($argv[2]) ? $argv[2] : null;
    if (isset($argv[1]) && $argv[1] == 'help') {
        lupo_show_help($topic);
    }
}
