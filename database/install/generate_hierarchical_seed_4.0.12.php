<?php
/**
 * Generate Lupopedia 4.0.12 Hierarchical Tab Structure Seed
 * 
 * Uses the canonical JSON mapping as source of truth.
 * Generates SQL using slugs for joins, not hard-coded content_ids.
 * 
 * Creates:
 * 1. seed_collection_0_hierarchical_tabs_4.0.12.sql - Tab hierarchy
 * 2. seed_collection_0_hierarchical_tab_map_4.0.12.sql - Content mappings (slug-based)
 */

$basePath = __DIR__ . '/../../';
// Try fixed version first, fall back to original
$mappingFile = __DIR__ . '/documentation_mapping_fixed.json';
if (!file_exists($mappingFile)) {
    $mappingFile = __DIR__ . '/documentation_mapping.json';
}

// Load JSON mapping
if (!file_exists($mappingFile)) {
    die("ERROR: documentation_mapping.json not found. Run map_documentation_files_v2.php first.\n");
}

if (!file_exists($mappingFile)) {
    die("ERROR: documentation_mapping.json not found. Run map_documentation_files_v2.php first.\n");
}

// Read file and clean UTF-8
$mappingJson = file_get_contents($mappingFile);
// Remove BOM if present
$mappingJson = preg_replace('/^\xEF\xBB\xBF/', '', $mappingJson);
// Remove invalid UTF-8 sequences
$mappingJson = mb_convert_encoding($mappingJson, 'UTF-8', 'UTF-8');
// Remove any remaining invalid UTF-8 bytes
$mappingJson = filter_var($mappingJson, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH);
// Actually, let's use a regex to remove invalid UTF-8
$mappingJson = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $mappingJson);

$mapping = json_decode($mappingJson, true);

if (!$mapping || json_last_error() !== JSON_ERROR_NONE) {
    $error = json_last_error_msg();
    die("ERROR: Failed to parse documentation_mapping.json: $error (code: " . json_last_error() . ")\n");
}

// Create filename → slug lookup
$filenameToSlug = [];
foreach ($mapping as $category => $items) {
    if (!is_array($items)) continue;
    foreach ($items as $item) {
        if (isset($item['filename']) && isset($item['slug'])) {
            $filenameToSlug[$item['filename']] = $item['slug'];
        }
    }
}

if (count($filenameToSlug) === 0) {
    die("ERROR: No filename→slug mappings loaded. Check documentation_mapping.json format.\n");
}

// Hierarchical structure definition (matches user's design)
$hierarchy = [
    // Main tab 1: Doctrine (ID 2) - 12 sub-tabs
    'doctrine' => [
        'main_tab_id' => 2,
        'main_tab_slug' => 'doctrine',
        'sub_tabs' => [
            ['name' => 'SQL & Database Doctrine', 'slug' => 'sql-database-doctrine', 'sort' => 1, 'files' => [
                'SQL_TYPE_DOCTRINE.md',
                'SQL_REWRITE_DOCTRINE.md',
                'SQL_REFACTOR_MAPPING_DOCTRINE.md',
                'TABLE_PREFIXING_DOCTRINE.md',
                'CHARSET_COLLATION_DOCTRINE.md',
                'NO_FOREIGN_KEYS_DOCTRINE.md',
                'NO_STORED_PROCEDURES_DOCTRINE.md',
                'NO_TRIGGERS_DOCTRINE.md',
                'DATABASE_SECURITY_DOCTRINE.md',
                'DATABASE_SCHEMA.md' // moved from Schema
            ]],
            ['name' => 'Cursor Doctrine', 'slug' => 'cursor-doctrine', 'sort' => 2, 'files' => [
                'CURSOR_ROLE_DOCTRINE.md',
                'CURSOR_CONTEXT_METADATA.md',
                'CURSOR_REFACTOR_DOCTRINE.md',
                'CURSOR_CASCADE_ROLE_SEPARATION_DOCTRINE.md'
            ]],
            ['name' => 'Agent Doctrine', 'slug' => 'agent-doctrine', 'sort' => 3, 'files' => [
                'AGENT_CLASSIFICATION.md',
                'AGENT_LIFECYCLE_DOCTRINE.md',
                'AGENT_PROMPT_DOCTRINE.md',
                'AGENT_FILESYSTEM_DOCTRINE.md',
                'emotional-agent-range.md',
                'SYSTEM_AGENT_SAFETY_DOCTRINE.md',
                'LUPOPEDIA_AGENT_DEDICATED_SLOT_RANGES.md'
            ]],
            ['name' => 'Documentation Doctrine', 'slug' => 'documentation-doctrine', 'sort' => 4, 'files' => [
                'DOCUMENTATION_DOCTRINE.md',
                'DOCUMENTATION_AS_CODE_MANIFESTO.md',
                'DOCUMENTATION_REORGANIZATION_PROPOSAL.md'
            ]],
            ['name' => 'Installation & Migration Doctrine', 'slug' => 'installation-migration-doctrine', 'sort' => 5, 'files' => [
                'INSTALLATION_LIFECYCLE_DOCTRINE.md',
                'MIGRATION_DOCTRINE.md',
                'SUBDIRECTORY_INSTALLATION_DOCTRINE.md'
            ]],
            ['name' => 'Naming & Structure Doctrine', 'slug' => 'naming-structure-doctrine', 'sort' => 6, 'files' => [
                'FOLDER_NAMING_DOCTRINE.md',
                'MODULE_DOCTRINE.md',
                'NAVIGATION_TAB_DOCTRINE.md',
                'CHANNEL_DOCTRINE.md'
            ]],
            ['name' => 'Semantic & Ingestion Doctrine', 'slug' => 'semantic-ingestion-doctrine', 'sort' => 7, 'files' => [
                'INGESTION_DOCTRINE.md',
                'SEMANTIC_GRAPH_DOCTRINE.md',
                'ATOMIZATION_DOCTRINE.md',
                'ATOM_RESOLUTION_SPECIFICATION.md',
                'GLOBAL_ATOMS_DOCTRINE.md'
            ]],
            ['name' => 'UI & Operator Doctrine', 'slug' => 'ui-operator-doctrine', 'sort' => 8, 'files' => [
                'OPERATOR_UI_DOCTRINE.md',
                'UI_LIBRARY_DOCTRINE.md',
                'MOOD_RGB_DOCTRINE.md'
            ]],
            ['name' => 'Safety & Integration Doctrine', 'slug' => 'safety-integration-doctrine', 'sort' => 9, 'files' => [
                'AI_INTEGRATION_SAFETY_DOCTRINE.md',
                'SYSTEM_AGENT_SAFETY_DOCTRINE.md',
                'AI_UNCERTAINTY_EXPRESSION_DOCTRINE.md'
            ]],
            ['name' => 'Mythic Doctrine', 'slug' => 'mythic-doctrine', 'sort' => 10, 'files' => [
                'MYTHIC_NAMES_DOCTRINE.md',
                'WOLFIE_DOCTRINE.md',
                'WOLFIE_HEADER_DOCTRINE.md',
                'WOLFMIND_DOCTRINE.md',
                'ANIBUS_DOCTRINE.md',
                'TOON_DOCTRINE.md'
            ]],
            ['name' => 'Versioning Doctrine', 'slug' => 'versioning-doctrine', 'sort' => 11, 'files' => [
                'VERSIONING_DOCTRINE.md',
                'VERSION_DOCTRINE.md'
            ]],
            ['name' => 'Misc Doctrine', 'slug' => 'misc-doctrine', 'sort' => 12, 'files' => [
                'as_above_so_below.md',
                'non_religious_position.md'
            ]]
        ]
    ],
    
    // Main tab 2: Agents (ID 5) - 4 sub-tabs
    'agents' => [
        'main_tab_id' => 5,
        'main_tab_slug' => 'agents',
        'sub_tabs' => [
            ['name' => 'Agent Runtime', 'slug' => 'agent-runtime', 'sort' => 1, 'files' => [
                'AGENT_RUNTIME.md',
                'AGENT_PROMPT_TEMPLATING_STANDARD.md'
            ]],
            ['name' => 'Dialog & History', 'slug' => 'dialog-history', 'sort' => 2, 'files' => [
                'DIALOG_HISTORY_SPEC.md',
                'INLINE_DIALOG_SPECIFICATION.md'
            ]],
            ['name' => 'WOLFIE Header System', 'slug' => 'wolfie-header-system', 'sort' => 3, 'files' => [
                'WOLFIE_HEADER_SPECIFICATION.md',
                'WOLFIE_HEADER_SECTIONS_GUIDE.md',
                'WOLFIE_HEADER_GLOBAL_ATOMS_GUIDE.md'
            ]],
            ['name' => 'Mythic AI Entities', 'slug' => 'mythic-ai-entities', 'sort' => 4, 'files' => [
                'lilith.md',
                'thoth.md',
                'wolfie.md',
                'ARA.md',
                'HERMES_AND_CADUCEUS.md'
            ]]
        ]
    ],
    
    // Main tab 3: Architecture (ID 3) - single page, no sub-tabs
    'architecture' => [
        'main_tab_id' => 3,
        'main_tab_slug' => 'architecture',
        'sub_tabs' => [],
        'direct_files' => ['multi-ide-workflow.md']
    ],
    
    // Main tab 4: Schema (ID 4) - 1 sub-tab
    'schema' => [
        'main_tab_id' => 4,
        'main_tab_slug' => 'schema',
        'sub_tabs' => [
            ['name' => 'Schema Reference', 'slug' => 'schema-reference', 'sort' => 1, 'files' => [
                'AI_SCHEMA_GUIDE.md',
                'DATABASE_SCHEMA.md'
            ]]
        ]
    ],
    
    // Main tab 5: History (ID 8) - single page, no sub-tabs
    'history' => [
        'main_tab_id' => 8,
        'main_tab_slug' => 'history',
        'sub_tabs' => [],
        'direct_files' => ['HISTORY.md']
    ],
    
    // Main tab 6: Appendix (ID 9) - 4 sub-tabs
    'appendix' => [
        'main_tab_id' => 9,
        'main_tab_slug' => 'appendix',
        'sub_tabs' => [
            ['name' => 'Company & Legal', 'slug' => 'company-legal', 'sort' => 1, 'files' => [
                'COMPANY_REGISTRATIONS.md'
            ]],
            ['name' => 'Press & Public', 'slug' => 'press-public', 'sort' => 2, 'files' => [
                'PRESS_RELEASE_DRAFT.md',
                'FOUNDERS_NOTE.md'
            ]],
            ['name' => 'Technical Reference', 'slug' => 'technical-reference', 'sort' => 3, 'files' => [
                'MYSQL_TO_POSTGRES_MEMORY.md',
                'COUNTING_IN_LIGHT.md',
                'TERMINOLOGY.md'
            ]],
            ['name' => 'Mythic & Personal', 'slug' => 'mythic-personal', 'sort' => 4, 'files' => [
                'WHO_IS_CAPTAIN_WOLFIE.md',
                'WHAT_NOT_TO_DO_AND_WHY.md',
                'MY_FIRST_PYTHON_PROGRAM.md',
                'wolfie.md'
            ]]
        ]
    ]
];

// Generate SQL
$now = 20260113000000; // Version 4.0.12 timestamp
$nodeId = 1;
$collectionId = 0;

// Start tab IDs at 100 (main tabs 1-9 already exist, sub-tabs start at 100)
$nextTabId = 100;

$tabSql = "-- ============================================================\n";
$tabSql .= "-- Lupopedia 4.0.12 — Collection 0 Hierarchical Tab Structure\n";
$tabSql .= "-- Rebuilds Collection 0 tabs with hierarchical sub-tabs\n";
$tabSql .= "-- ============================================================\n";
$tabSql .= "--\n";
$tabSql .= "-- wolfie.headers.version: \"4.0.12\"\n";
$tabSql .= "-- header_atoms:\n";
$tabSql .= "--   - GLOBAL_CURRENT_LUPOPEDIA_VERSION\n";
$tabSql .= "-- dialog:\n";
$tabSql .= "--   - speaker: CURSOR\n";
$tabSql .= "--     target: @everyone\n";
$tabSql .= "--     message: \"Version 4.0.12: Rebuilt Collection 0 with hierarchical tab structure. Doctrine has 12 sub-tabs, Agents has 4 sub-tabs, Schema has 1 sub-tab, Appendix has 4 sub-tabs. Overview, UI-UX, and Developer Guide are hidden.\"\n";
$tabSql .= "--     mood: \"00FF00\"\n";
$tabSql .= "-- tags:\n";
$tabSql .= "--   categories: [\"database\", \"seed\", \"hierarchy\"]\n";
$tabSql .= "--   collections: [\"core-modules\"]\n";
$tabSql .= "--   channels: [\"dev\"]\n";
$tabSql .= "-- file:\n";
$tabSql .= "--   title: \"Collection 0 Hierarchical Tabs Seed\"\n";
$tabSql .= "--   description: \"Seed file for hierarchical tab structure with sub-tabs\"\n";
$tabSql .= "--   version: GLOBAL_CURRENT_LUPOPEDIA_VERSION\n";
$tabSql .= "--   status: active\n";
$tabSql .= "--   author: GLOBAL_CURRENT_AUTHORS\n";
$tabSql .= "--\n";
$tabSql .= "-- ============================================================\n\n";

$tabSql .= "-- Set deterministic timestamp\n";
$tabSql .= "SET @now = $now;\n";
$tabSql .= "SET @node_id = $nodeId;\n\n";

$tabSql .= "-- ============================================================\n";
$tabSql .= "-- CLEAR EXISTING COLLECTION 0 TABS AND MAPPINGS\n";
$tabSql .= "-- ============================================================\n\n";

$tabSql .= "-- Soft delete all existing Collection 0 tabs\n";
$tabSql .= "UPDATE `lupo_collection_tabs` SET `is_deleted` = 1, `deleted_ymdhis` = @now WHERE `collection_id` = 0;\n\n";

$tabSql .= "-- Soft delete all existing Collection 0 tab mappings\n";
$tabSql .= "UPDATE `lupo_collection_tab_map` SET `is_deleted` = 1, `deleted_ymdhis` = @now WHERE `collection_tab_id` IN (SELECT `collection_tab_id` FROM `lupo_collection_tabs` WHERE `collection_id` = 0);\n\n";

$tabSql .= "-- ============================================================\n";
$tabSql .= "-- MAIN TABS (Root Level)\n";
$tabSql .= "-- ============================================================\n\n";

// Main tabs: Doctrine (2), Agents (5), Architecture (3), Schema (4), History (8), Appendix (9)
// Hide: Overview (1), UI-UX (6), Developer Guide (7)
$mainTabs = [
    ['id' => 1, 'name' => 'Overview', 'slug' => 'overview', 'sort' => 1, 'hidden' => 1],
    ['id' => 2, 'name' => 'Doctrine', 'slug' => 'doctrine', 'sort' => 2, 'hidden' => 0],
    ['id' => 3, 'name' => 'Architecture', 'slug' => 'architecture', 'sort' => 3, 'hidden' => 0],
    ['id' => 4, 'name' => 'Schema', 'slug' => 'schema', 'sort' => 4, 'hidden' => 0],
    ['id' => 5, 'name' => 'Agents', 'slug' => 'agents', 'sort' => 5, 'hidden' => 0],
    ['id' => 6, 'name' => 'UI-UX', 'slug' => 'ui-ux', 'sort' => 6, 'hidden' => 1],
    ['id' => 7, 'name' => 'Developer Guide', 'slug' => 'developer-guide', 'sort' => 7, 'hidden' => 1],
    ['id' => 8, 'name' => 'History', 'slug' => 'history', 'sort' => 8, 'hidden' => 0],
    ['id' => 9, 'name' => 'Appendix', 'slug' => 'appendix', 'sort' => 9, 'hidden' => 0]
];

foreach ($mainTabs as $tab) {
    $tabSql .= "-- Main Tab: {$tab['name']}\n";
    $tabSql .= "INSERT INTO `lupo_collection_tabs` (\n";
    $tabSql .= "    `collection_tab_id`,\n";
    $tabSql .= "    `collection_tab_parent_id`,\n";
    $tabSql .= "    `collection_id`,\n";
    $tabSql .= "    `federations_node_id`,\n";
    $tabSql .= "    `group_id`,\n";
    $tabSql .= "    `user_id`,\n";
    $tabSql .= "    `sort_order`,\n";
    $tabSql .= "    `name`,\n";
    $tabSql .= "    `slug`,\n";
    $tabSql .= "    `color`,\n";
    $tabSql .= "    `description`,\n";
    $tabSql .= "    `is_hidden`,\n";
    $tabSql .= "    `created_ymdhis`,\n";
    $tabSql .= "    `updated_ymdhis`,\n";
    $tabSql .= "    `is_active`,\n";
    $tabSql .= "    `is_deleted`,\n";
    $tabSql .= "    `deleted_ymdhis`\n";
    $tabSql .= ") VALUES (\n";
    $tabSql .= "    {$tab['id']},\n";
    $tabSql .= "    NULL,\n";
    $tabSql .= "    $collectionId,\n";
    $tabSql .= "    @node_id,\n";
    $tabSql .= "    NULL,\n";
    $tabSql .= "    NULL,\n";
    $tabSql .= "    {$tab['sort']},\n";
    $tabSql .= "    '{$tab['name']}',\n";
    $tabSql .= "    '{$tab['slug']}',\n";
    $tabSql .= "    '4caf50',\n";
    $tabSql .= "    NULL,\n";
    $tabSql .= "    {$tab['hidden']},\n";
    $tabSql .= "    @now,\n";
    $tabSql .= "    @now,\n";
    $tabSql .= "    1,\n";
    $tabSql .= "    0,\n";
    $tabSql .= "    NULL\n";
    $tabSql .= ") ON DUPLICATE KEY UPDATE\n";
    $tabSql .= "    `name` = VALUES(`name`),\n";
    $tabSql .= "    `slug` = VALUES(`slug`),\n";
    $tabSql .= "    `is_hidden` = VALUES(`is_hidden`),\n";
    $tabSql .= "    `sort_order` = VALUES(`sort_order`),\n";
    $tabSql .= "    `updated_ymdhis` = @now,\n";
    $tabSql .= "    `is_active` = 1,\n";
    $tabSql .= "    `is_deleted` = 0,\n";
    $tabSql .= "    `deleted_ymdhis` = NULL;\n\n";
}

$tabSql .= "-- ============================================================\n";
$tabSql .= "-- SUB-TABS (Hierarchical)\n";
$tabSql .= "-- ============================================================\n\n";

$subTabMap = []; // Maps (category, sub-tab-slug) to tab_id for content mapping

foreach ($hierarchy as $category => $config) {
    $mainTabId = $config['main_tab_id'];
    
    foreach ($config['sub_tabs'] as $subTab) {
        $subTabId = $nextTabId++;
        $subTabMap[$category][$subTab['slug']] = $subTabId;
        
        $tabSql .= "-- Sub-tab: {$subTab['name']} (under {$category})\n";
        $tabSql .= "INSERT INTO `lupo_collection_tabs` (\n";
        $tabSql .= "    `collection_tab_id`,\n";
        $tabSql .= "    `collection_tab_parent_id`,\n";
        $tabSql .= "    `collection_id`,\n";
        $tabSql .= "    `federations_node_id`,\n";
        $tabSql .= "    `group_id`,\n";
        $tabSql .= "    `user_id`,\n";
        $tabSql .= "    `sort_order`,\n";
        $tabSql .= "    `name`,\n";
        $tabSql .= "    `slug`,\n";
        $tabSql .= "    `color`,\n";
        $tabSql .= "    `description`,\n";
        $tabSql .= "    `is_hidden`,\n";
        $tabSql .= "    `created_ymdhis`,\n";
        $tabSql .= "    `updated_ymdhis`,\n";
        $tabSql .= "    `is_active`,\n";
        $tabSql .= "    `is_deleted`,\n";
        $tabSql .= "    `deleted_ymdhis`\n";
        $tabSql .= ") VALUES (\n";
        $tabSql .= "    $subTabId,\n";
        $tabSql .= "    $mainTabId,\n";
        $tabSql .= "    $collectionId,\n";
        $tabSql .= "    @node_id,\n";
        $tabSql .= "    NULL,\n";
        $tabSql .= "    NULL,\n";
        $tabSql .= "    {$subTab['sort']},\n";
        $tabSql .= "    '{$subTab['name']}',\n";
        $tabSql .= "    '{$subTab['slug']}',\n";
        $tabSql .= "    '4caf50',\n";
        $tabSql .= "    NULL,\n";
        $tabSql .= "    0,\n";
        $tabSql .= "    @now,\n";
        $tabSql .= "    @now,\n";
        $tabSql .= "    1,\n";
        $tabSql .= "    0,\n";
        $tabSql .= "    NULL\n";
        $tabSql .= ") ON DUPLICATE KEY UPDATE\n";
        $tabSql .= "    `name` = VALUES(`name`),\n";
        $tabSql .= "    `slug` = VALUES(`slug`),\n";
        $tabSql .= "    `collection_tab_parent_id` = VALUES(`collection_tab_parent_id`),\n";
        $tabSql .= "    `sort_order` = VALUES(`sort_order`),\n";
        $tabSql .= "    `updated_ymdhis` = @now,\n";
        $tabSql .= "    `is_active` = 1,\n";
        $tabSql .= "    `is_deleted` = 0,\n";
        $tabSql .= "    `deleted_ymdhis` = NULL;\n\n";
    }
}

$tabSql .= "-- ============================================================\n";
$tabSql .= "-- END OF TAB SEED\n";
$tabSql .= "-- ============================================================\n";

// Generate mapping SQL using slug-based SELECTs
$mapSql = "-- ============================================================\n";
$mapSql .= "-- Lupopedia 4.0.12 — Collection 0 Hierarchical Tab Content Mapping\n";
$mapSql .= "-- Maps content items to hierarchical tabs and sub-tabs using slugs\n";
$mapSql .= "-- ============================================================\n";
$mapSql .= "--\n";
$mapSql .= "-- wolfie.headers.version: \"4.0.12\"\n";
$mapSql .= "-- header_atoms:\n";
$mapSql .= "--   - GLOBAL_CURRENT_LUPOPEDIA_VERSION\n";
$mapSql .= "-- dialog:\n";
$mapSql .= "--   - speaker: CURSOR\n";
$mapSql .= "--     target: @everyone\n";
$mapSql .= "--     message: \"Version 4.0.12: Mapped all documentation content to hierarchical tab structure. Content items are organized under appropriate sub-tabs using slug-based joins.\"\n";
$mapSql .= "--     mood: \"00FF00\"\n";
$mapSql .= "-- tags:\n";
$mapSql .= "--   categories: [\"database\", \"seed\", \"mapping\"]\n";
$mapSql .= "--   collections: [\"core-modules\"]\n";
$mapSql .= "--   channels: [\"dev\"]\n";
$mapSql .= "-- file:\n";
$mapSql .= "--   title: \"Collection 0 Hierarchical Tab Content Mapping\"\n";
$mapSql .= "--   description: \"Seed file for mapping content items to hierarchical tabs using slugs\"\n";
$mapSql .= "--   version: GLOBAL_CURRENT_LUPOPEDIA_VERSION\n";
$mapSql .= "--   status: active\n";
$mapSql .= "--   author: GLOBAL_CURRENT_AUTHORS\n";
$mapSql .= "--\n";
$mapSql .= "-- ============================================================\n\n";

$mapSql .= "SET @now = $now;\n";
$mapSql .= "SET @node_id = $nodeId;\n\n";

$mapSql .= "-- ============================================================\n";
$mapSql .= "-- CONTENT MAPPINGS (Slug-based SELECTs)\n";
$mapSql .= "-- ============================================================\n\n";

$nextMapId = 1;

// Map content to tabs
foreach ($hierarchy as $category => $config) {
    $mainTabId = $config['main_tab_id'];
    $mainTabSlug = $config['main_tab_slug'];
    
    // Map direct files to main tab (Architecture, History)
    if (isset($config['direct_files'])) {
        $sortOrder = 1;
        foreach ($config['direct_files'] as $filename) {
            if (!isset($filenameToSlug[$filename])) {
                echo "WARNING: File not found in mapping: $filename\n";
                continue;
            }
            $contentSlug = $filenameToSlug[$filename];
            
            $mapSql .= "-- Map {$filename} to {$category} main tab\n";
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
            $mapSql .= ") SELECT\n";
            $mapSql .= "    $nextMapId,\n";
            $mapSql .= "    t.`collection_tab_id`,\n";
            $mapSql .= "    @node_id,\n";
            $mapSql .= "    'content',\n";
            $mapSql .= "    c.`content_id`,\n";
            $mapSql .= "    $sortOrder,\n";
            $mapSql .= "    NULL,\n";
            $mapSql .= "    @now,\n";
            $mapSql .= "    @now,\n";
            $mapSql .= "    0,\n";
            $mapSql .= "    NULL\n";
            $mapSql .= "FROM `lupo_collection_tabs` t\n";
            $mapSql .= "JOIN `lupo_contents` c ON c.`slug` = '$contentSlug'\n";
            $mapSql .= "WHERE t.`slug` = '$mainTabSlug' AND t.`collection_id` = 0\n";
            $mapSql .= "ON DUPLICATE KEY UPDATE\n";
            $mapSql .= "    `sort_order` = VALUES(`sort_order`),\n";
            $mapSql .= "    `updated_ymdhis` = @now,\n";
            $mapSql .= "    `is_deleted` = 0,\n";
            $mapSql .= "    `deleted_ymdhis` = NULL;\n\n";
            
            $nextMapId++;
            $sortOrder++;
        }
    }
    
    // Map files to sub-tabs
    foreach ($config['sub_tabs'] as $subTab) {
        $subTabId = $subTabMap[$category][$subTab['slug']];
        $subTabSlug = $subTab['slug'];
        $sortOrder = 1;
        
        foreach ($subTab['files'] as $filename) {
            if (!isset($filenameToSlug[$filename])) {
                echo "WARNING: File not found in mapping: $filename\n";
                continue;
            }
            $contentSlug = $filenameToSlug[$filename];
            
            $mapSql .= "-- Map {$filename} to {$subTab['name']} sub-tab\n";
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
            $mapSql .= ") SELECT\n";
            $mapSql .= "    $nextMapId,\n";
            $mapSql .= "    t.`collection_tab_id`,\n";
            $mapSql .= "    @node_id,\n";
            $mapSql .= "    'content',\n";
            $mapSql .= "    c.`content_id`,\n";
            $mapSql .= "    $sortOrder,\n";
            $mapSql .= "    NULL,\n";
            $mapSql .= "    @now,\n";
            $mapSql .= "    @now,\n";
            $mapSql .= "    0,\n";
            $mapSql .= "    NULL\n";
            $mapSql .= "FROM `lupo_collection_tabs` t\n";
            $mapSql .= "JOIN `lupo_contents` c ON c.`slug` = '$contentSlug'\n";
            $mapSql .= "WHERE t.`slug` = '$subTabSlug' AND t.`collection_id` = 0\n";
            $mapSql .= "ON DUPLICATE KEY UPDATE\n";
            $mapSql .= "    `sort_order` = VALUES(`sort_order`),\n";
            $mapSql .= "    `updated_ymdhis` = @now,\n";
            $mapSql .= "    `is_deleted` = 0,\n";
            $mapSql .= "    `deleted_ymdhis` = NULL;\n\n";
            
            $nextMapId++;
            $sortOrder++;
        }
    }
}

$mapSql .= "-- ============================================================\n";
$mapSql .= "-- END OF MAPPING SEED\n";
$mapSql .= "-- ============================================================\n";

// Write files
file_put_contents(__DIR__ . '/seed_collection_0_hierarchical_tabs_4.0.12.sql', $tabSql);
file_put_contents(__DIR__ . '/seed_collection_0_hierarchical_tab_map_4.0.12.sql', $mapSql);

echo "Generated seed SQL files:\n";
echo "  - seed_collection_0_hierarchical_tabs_4.0.12.sql\n";
echo "  - seed_collection_0_hierarchical_tab_map_4.0.12.sql\n";
