<?php
/**
 * Generate seed SQL files for Lupopedia 4.0.11
 * 
 * Scans documentation directories and generates:
 * 1. seed_collection_0_content.sql - Inserts content into lupo_contents
 * 2. seed_collection_0_tab_map.sql - Maps content to tabs in lupo_collection_tab_map
 */

// Set base path
$basePath = __DIR__ . '/../../';
$docsPath = $basePath . 'docs/';

// Tab mapping: directory => tab_id
$tabMapping = [
    'core' => 1,        // Overview tab (using /docs/core as overview)
    'doctrine' => 2,    // Doctrine tab
    'ARCHITECTURE' => 3, // Architecture tab
    'schema' => 4,      // Schema tab
    'agents' => 5,      // Agents tab
    'dev' => 7,         // Developer Guide tab (using /docs/dev)
    'history' => 8,     // History tab
    'appendix' => 9,    // Appendix tab
];

// Note: ui-ux directory doesn't exist, so tab 6 will have no content initially

// Set timestamp
$now = 20260113000000; // Version 4.0.11 timestamp
$nodeId = 1;

// Content counter (start after existing content)
$contentIdStart = 2000; // Safe starting point
$contentId = $contentIdStart;

// Map counter
$mapIdStart = 1;
$mapId = $mapIdStart;

// Arrays to store SQL
$contentInserts = [];
$mapInserts = [];

// Function to derive title from filename
function deriveTitle($filename) {
    // Remove .md extension
    $title = preg_replace('/\.md$/', '', $filename);
    // Replace hyphens/underscores with spaces
    $title = str_replace(['-', '_'], ' ', $title);
    // Capitalize words
    $title = ucwords($title);
    return $title;
}

// Function to derive slug from filename
function deriveSlug($filename) {
    // Remove .md extension
    $slug = preg_replace('/\.md$/', '', $filename);
    // Convert to lowercase
    $slug = strtolower($slug);
    // Replace spaces/underscores with hyphens
    $slug = preg_replace('/[\s_]+/', '-', $slug);
    // Remove invalid characters
    $slug = preg_replace('/[^a-z0-9\-]/', '', $slug);
    // Collapse multiple hyphens
    $slug = preg_replace('/-+/', '-', $slug);
    // Trim hyphens
    $slug = trim($slug, '-');
    return $slug;
}

// Function to escape SQL string
function escapeSql($str) {
    return str_replace(["'", "\\", "\n", "\r"], ["''", "\\\\", "\\n", "\\r"], $str);
}

// Scan directories
foreach ($tabMapping as $dir => $tabId) {
    $dirPath = $docsPath . $dir;
    
    if (!is_dir($dirPath)) {
        echo "Warning: Directory not found: $dirPath\n";
        continue;
    }
    
    // Get all .md files
    $files = glob($dirPath . '/*.md');
    
    if (empty($files)) {
        echo "Warning: No .md files found in $dirPath\n";
        continue;
    }
    
    // Sort files alphabetically for consistent sort_order
    sort($files);
    
    $sortOrder = 0;
    
    foreach ($files as $file) {
        $filename = basename($file);
        
        // Skip README.md and other special files if needed
        if ($filename === 'README.md') {
            continue;
        }
        
        // Read file content
        $body = file_get_contents($file);
        if ($body === false) {
            echo "Warning: Could not read file: $file\n";
            continue;
        }
        
        // Derive title and slug
        $title = deriveTitle($filename);
        $slug = deriveSlug($filename);
        
        // Ensure unique slug by prefixing with directory
        $uniqueSlug = $dir . '-' . $slug;
        
        // Generate content insert
        $contentIdValue = $contentId++;
        $sortOrder++;
        
        $contentInserts[] = [
            'content_id' => $contentIdValue,
            'federation_node_id' => $nodeId,
            'user_id' => 'NULL',
            'group_id' => 'NULL',
            'title' => $title,
            'slug' => $uniqueSlug,
            'content_type' => 'markdown',
            'format' => 'markdown',
            'body' => $body,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_deleted' => 0,
            'tab_id' => $tabId,
            'sort_order' => $sortOrder
        ];
        
        // Generate map insert
        $mapIdValue = $mapId++;
        
        $mapInserts[] = [
            'collection_tab_map_id' => $mapIdValue,
            'collection_tab_id' => $tabId,
            'federations_node_id' => $nodeId,
            'item_type' => 'content',
            'item_id' => $contentIdValue,
            'sort_order' => $sortOrder,
            'created_ymdhis' => $now,
            'updated_ymdhis' => $now,
            'is_deleted' => 0
        ];
    }
}

// Generate content seed SQL file
$contentSql = "-- ============================================================\n";
$contentSql .= "-- Lupopedia 4.0.11 — Collection 0 System Documentation Content Seed\n";
$contentSql .= "-- Inserts system documentation files as content items\n";
$contentSql .= "-- ============================================================\n";
$contentSql .= "--\n";
$contentSql .= "-- wolfie.headers.version: \"4.0.11\"\n";
$contentSql .= "-- header_atoms:\n";
$contentSql .= "--   - GLOBAL_CURRENT_LUPOPEDIA_VERSION\n";
$contentSql .= "-- dialog:\n";
$contentSql .= "--   - speaker: CURSOR\n";
$contentSql .= "--     target: @everyone\n";
$contentSql .= "--     message: \"Version 4.0.11: Created seed SQL file for inserting system documentation content into lupo_contents. All inserts are idempotent using ON DUPLICATE KEY UPDATE. Uses BIGINT UTC timestamp format.\"\n";
$contentSql .= "--     mood: \"00FF00\"\n";
$contentSql .= "-- tags:\n";
$contentSql .= "--   categories: [\"database\", \"seed\", \"content\"]\n";
$contentSql .= "--   collections: [\"core-modules\"]\n";
$contentSql .= "--   channels: [\"dev\"]\n";
$contentSql .= "-- file:\n";
$contentSql .= "--   title: \"Collection 0 System Documentation Content Seed\"\n";
$contentSql .= "--   description: \"Seed file for inserting system documentation .md files as content items in lupo_contents\"\n";
$contentSql .= "--   version: GLOBAL_CURRENT_LUPOPEDIA_VERSION\n";
$contentSql .= "--   status: active\n";
$contentSql .= "--   author: GLOBAL_CURRENT_AUTHORS\n";
$contentSql .= "--\n";
$contentSql .= "-- Purpose: Insert all system documentation .md files as content items\n";
$contentSql .= "-- in lupo_contents table for Collection 0 (System Collection).\n";
$contentSql .= "--\n";
$contentSql .= "-- Rules:\n";
$contentSql .= "-- - All inserts are idempotent using INSERT ... ON DUPLICATE KEY UPDATE\n";
$contentSql .= "-- - Uses BIGINT UTC timestamp format (YYYYMMDDHHMMSS)\n";
$contentSql .= "-- - Content type: 'markdown'\n";
$contentSql .= "-- - Format: 'markdown'\n";
$contentSql .= "-- - federations_node_id = 1\n";
$contentSql .= "-- - user_id = NULL, group_id = NULL\n";
$contentSql .= "--\n";
$contentSql .= "-- ============================================================\n\n";

$contentSql .= "-- Set deterministic timestamp for seed operations\n";
$contentSql .= "SET @now = $now;\n\n";
$contentSql .= "-- Set domain/node ID (default to 1)\n";
$contentSql .= "SET @node_id = $nodeId;\n\n";

foreach ($contentInserts as $insert) {
    $bodyEscaped = escapeSql($insert['body']);
    
    $contentSql .= "-- Content: {$insert['title']} (Tab {$insert['tab_id']})\n";
    $contentSql .= "INSERT INTO `lupo_contents` (\n";
    $contentSql .= "    `content_id`,\n";
    $contentSql .= "    `content_parent_id`,\n";
    $contentSql .= "    `federation_node_id`,\n";
    $contentSql .= "    `group_id`,\n";
    $contentSql .= "    `user_id`,\n";
    $contentSql .= "    `title`,\n";
    $contentSql .= "    `slug`,\n";
    $contentSql .= "    `description`,\n";
    $contentSql .= "    `seo_keywords`,\n";
    $contentSql .= "    `body`,\n";
    $contentSql .= "    `content_type`,\n";
    $contentSql .= "    `format`,\n";
    $contentSql .= "    `content_url`,\n";
    $contentSql .= "    `source_url`,\n";
    $contentSql .= "    `source_title`,\n";
    $contentSql .= "    `is_template`,\n";
    $contentSql .= "    `status`,\n";
    $contentSql .= "    `visibility`,\n";
    $contentSql .= "    `view_count`,\n";
    $contentSql .= "    `share_count`,\n";
    $contentSql .= "    `created_ymdhis`,\n";
    $contentSql .= "    `updated_ymdhis`,\n";
    $contentSql .= "    `is_deleted`,\n";
    $contentSql .= "    `is_active`,\n";
    $contentSql .= "    `deleted_ymdhis`,\n";
    $contentSql .= "    `content_sections`,\n";
    $contentSql .= "    `version_number`\n";
    $contentSql .= ")\n";
    $contentSql .= "VALUES (\n";
    $contentSql .= "    {$insert['content_id']},\n";
    $contentSql .= "    NULL,\n";
    $contentSql .= "    @node_id,\n";
    $contentSql .= "    {$insert['group_id']},\n";
    $contentSql .= "    {$insert['user_id']},\n";
    $contentSql .= "    '{$insert['title']}',\n";
    $contentSql .= "    '{$insert['slug']}',\n";
    $contentSql .= "    NULL,\n";
    $contentSql .= "    NULL,\n";
    $contentSql .= "    '" . str_replace("'", "''", str_replace("\\", "\\\\", $bodyEscaped)) . "',\n";
    $contentSql .= "    '{$insert['content_type']}',\n";
    $contentSql .= "    '{$insert['format']}',\n";
    $contentSql .= "    NULL,\n";
    $contentSql .= "    NULL,\n";
    $contentSql .= "    NULL,\n";
    $contentSql .= "    0,\n";
    $contentSql .= "    'published',\n";
    $contentSql .= "    'public',\n";
    $contentSql .= "    0,\n";
    $contentSql .= "    0,\n";
    $contentSql .= "    @now,\n";
    $contentSql .= "    @now,\n";
    $contentSql .= "    {$insert['is_deleted']},\n";
    $contentSql .= "    1,\n";
    $contentSql .= "    NULL,\n";
    $contentSql .= "    NULL,\n";
    $contentSql .= "    1\n";
    $contentSql .= ")\n";
    $contentSql .= "ON DUPLICATE KEY UPDATE\n";
    $contentSql .= "    `title` = VALUES(`title`),\n";
    $contentSql .= "    `slug` = VALUES(`slug`),\n";
    $contentSql .= "    `body` = VALUES(`body`),\n";
    $contentSql .= "    `content_type` = VALUES(`content_type`),\n";
    $contentSql .= "    `format` = VALUES(`format`),\n";
    $contentSql .= "    `updated_ymdhis` = @now,\n";
    $contentSql .= "    `is_deleted` = 0,\n";
    $contentSql .= "    `is_active` = 1;\n\n";
}

$contentSql .= "-- ============================================================\n";
$contentSql .= "-- END OF CONTENT SEED\n";
$contentSql .= "-- ============================================================\n";

// Generate map seed SQL file
$mapSql = "-- ============================================================\n";
$mapSql .= "-- Lupopedia 4.0.11 — Collection 0 Tab Content Mapping Seed\n";
$mapSql .= "-- Maps system documentation content to Collection 0 tabs\n";
$mapSql .= "-- ============================================================\n";
$mapSql .= "--\n";
$mapSql .= "-- wolfie.headers.version: \"4.0.11\"\n";
$mapSql .= "-- header_atoms:\n";
$mapSql .= "--   - GLOBAL_CURRENT_LUPOPEDIA_VERSION\n";
$mapSql .= "-- dialog:\n";
$mapSql .= "--   - speaker: CURSOR\n";
$mapSql .= "--     target: @everyone\n";
$mapSql .= "--     message: \"Version 4.0.11: Created seed SQL file for mapping system documentation content to Collection 0 tabs in lupo_collection_tab_map. All inserts are idempotent using ON DUPLICATE KEY UPDATE.\"\n";
$mapSql .= "--     mood: \"00FF00\"\n";
$mapSql .= "-- tags:\n";
$mapSql .= "--   categories: [\"database\", \"seed\", \"mapping\"]\n";
$mapSql .= "--   collections: [\"core-modules\"]\n";
$mapSql .= "--   channels: [\"dev\"]\n";
$mapSql .= "-- file:\n";
$mapSql .= "--   title: \"Collection 0 Tab Content Mapping Seed\"\n";
$mapSql .= "--   description: \"Seed file for mapping content items to tabs in lupo_collection_tab_map\"\n";
$mapSql .= "--   version: GLOBAL_CURRENT_LUPOPEDIA_VERSION\n";
$mapSql .= "--   status: active\n";
$mapSql .= "--   author: GLOBAL_CURRENT_AUTHORS\n";
$mapSql .= "--\n";
$mapSql .= "-- Purpose: Map all system documentation content items to their\n";
$mapSql .= "-- corresponding tabs in Collection 0 (System Collection).\n";
$mapSql .= "--\n";
$mapSql .= "-- Rules:\n";
$mapSql .= "-- - All inserts are idempotent using INSERT ... ON DUPLICATE KEY UPDATE\n";
$mapSql .= "-- - Uses BIGINT UTC timestamp format (YYYYMMDDHHMMSS)\n";
$mapSql .= "-- - item_type = 'content'\n";
$mapSql .= "-- - sort_order = alphabetical by filename\n";
$mapSql .= "--\n";
$mapSql .= "-- Tab Mapping:\n";
$mapSql .= "-- Tab 1 (Overview)         → /docs/core/*.md\n";
$mapSql .= "-- Tab 2 (Doctrine)         → /docs/doctrine/*.md\n";
$mapSql .= "-- Tab 3 (Architecture)     → /docs/ARCHITECTURE/*.md\n";
$mapSql .= "-- Tab 4 (Schema)           → /docs/schema/*.md\n";
$mapSql .= "-- Tab 5 (Agents)           → /docs/agents/*.md\n";
$mapSql .= "-- Tab 6 (UI-UX)            → (no content - directory doesn't exist)\n";
$mapSql .= "-- Tab 7 (Developer Guide)  → /docs/dev/*.md\n";
$mapSql .= "-- Tab 8 (History)          → /docs/history/*.md\n";
$mapSql .= "-- Tab 9 (Appendix)         → /docs/appendix/*.md\n";
$mapSql .= "--\n";
$mapSql .= "-- ============================================================\n\n";

$mapSql .= "-- Set deterministic timestamp for seed operations\n";
$mapSql .= "SET @now = $now;\n\n";
$mapSql .= "-- Set domain/node ID (default to 1)\n";
$mapSql .= "SET @node_id = $nodeId;\n\n";

foreach ($mapInserts as $insert) {
    $mapSql .= "-- Map content {$insert['item_id']} to tab {$insert['collection_tab_id']}\n";
    $mapSql .= "INSERT INTO `lupo_collection_tab_map` (\n";
    $mapSql .= "    `collection_tab_map_id`,\n";
    $mapSql .= "    `collection_tab_id`,\n";
    $mapSql .= "    `federations_node_id`,\n";
    $mapSql .= "    `item_type`,\n";
    $mapSql .= "    `item_id`,\n";
    $mapSql .= "    `sort_order`,\n";
    $mapSql .= "    `properties`,\n";
    $mapSql .= "    `created_ymdhis`,\n";
    $mapSql .= "    `updated_ymdhis`,\n";
    $mapSql .= "    `is_deleted`,\n";
    $mapSql .= "    `deleted_ymdhis`\n";
    $mapSql .= ")\n";
    $mapSql .= "VALUES (\n";
    $mapSql .= "    {$insert['collection_tab_map_id']},\n";
    $mapSql .= "    {$insert['collection_tab_id']},\n";
    $mapSql .= "    @node_id,\n";
    $mapSql .= "    '{$insert['item_type']}',\n";
    $mapSql .= "    {$insert['item_id']},\n";
    $mapSql .= "    {$insert['sort_order']},\n";
    $mapSql .= "    NULL,\n";
    $mapSql .= "    @now,\n";
    $mapSql .= "    @now,\n";
    $mapSql .= "    {$insert['is_deleted']},\n";
    $mapSql .= "    NULL\n";
    $mapSql .= ")\n";
    $mapSql .= "ON DUPLICATE KEY UPDATE\n";
    $mapSql .= "    `sort_order` = VALUES(`sort_order`),\n";
    $mapSql .= "    `updated_ymdhis` = @now,\n";
    $mapSql .= "    `is_deleted` = 0;\n\n";
}

$mapSql .= "-- ============================================================\n";
$mapSql .= "-- END OF MAPPING SEED\n";
$mapSql .= "-- ============================================================\n";

// Write files
file_put_contents(__DIR__ . '/seed_collection_0_content.sql', $contentSql);
file_put_contents(__DIR__ . '/seed_collection_0_tab_map.sql', $mapSql);

echo "Generated seed SQL files:\n";
echo "  - seed_collection_0_content.sql (" . count($contentInserts) . " content items)\n";
echo "  - seed_collection_0_tab_map.sql (" . count($mapInserts) . " mappings)\n";
