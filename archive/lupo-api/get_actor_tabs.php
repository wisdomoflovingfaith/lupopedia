<?php
/**
 * Alias entrypoint for IDE/docs: same JSON as load_collection_tabs.php.
 * Dynamic shortcut menu and nav may call either URL; canonical logic lives in load_collection_tabs.php.
 *
 * Query: collection_id (int, required) — forwarded via GET when this file is required.
 */
require __DIR__ . '/load_collection_tabs.php';
