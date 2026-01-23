<?php
/**
 * wolfie.header.identity: setup-help-list-modules
 * wolfie.header.placement: /scripts/setup_help_list_modules.php
 * wolfie.header.version: 4.1.1
 * wolfie.header.dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "Created setup script to initialize HELP and LIST modules: creates database table and seeds initial help topics. Run this after deploying the modules."
 *   mood: "00FF00"
 */

/**
 * Setup script for HELP and LIST modules
 * 
 * This script:
 * 1. Creates the lupo_help_topics table
 * 2. Seeds initial help topics
 * 
 * Run: php scripts/setup_help_list_modules.php
 */

require_once __DIR__ . '/../lupopedia-config.php';
require_once __DIR__ . '/../lupo-includes/bootstrap.php';

echo "🔧 Setting up HELP and LIST modules...\n\n";

// Get database connection
if (empty($GLOBALS['mydatabase'])) {
    die("❌ Database connection not available.\n");
}

$db = $GLOBALS['mydatabase'];

// Get table prefix
$table_prefix = defined('LUPO_TABLE_PREFIX') ? LUPO_TABLE_PREFIX : str_replace('-', '_', LUPO_PREFIX);

// Step 1: Create lupo_help_topics table
echo "Step 1: Creating lupo_help_topics table...\n";

$create_table_sql = "
CREATE TABLE IF NOT EXISTS `{$table_prefix}help_topics` (
    `help_topic_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'Primary key for help topic',
    `slug` VARCHAR(255) NOT NULL COMMENT 'URL-friendly unique identifier',
    `title` VARCHAR(255) NOT NULL COMMENT 'Topic title',
    `content_html` LONGTEXT COMMENT 'HTML content of the help topic',
    `category` VARCHAR(100) COMMENT 'Category for grouping topics',
    `created_ymdhis` BIGINT UNSIGNED NOT NULL COMMENT 'UTC creation timestamp (YYYYMMDDHHMMSS)',
    `updated_ymdhis` BIGINT UNSIGNED NOT NULL COMMENT 'UTC last update timestamp (YYYYMMDDHHMMSS)',
    `is_deleted` TINYINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Soft delete flag (1=deleted, 0=active)',
    `deleted_ymdhis` BIGINT UNSIGNED DEFAULT NULL COMMENT 'UTC deletion timestamp (YYYYMMDDHHMMSS)',
    PRIMARY KEY (`help_topic_id`),
    UNIQUE KEY `uniq_slug` (`slug`),
    KEY `idx_category` (`category`),
    KEY `idx_is_deleted` (`is_deleted`),
    KEY `idx_category_deleted` (`category`, `is_deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Help documentation topics for Lupopedia';
";

try {
    $db->exec($create_table_sql);
    echo "✅ Table {$table_prefix}help_topics created successfully.\n\n";
} catch (PDOException $e) {
    if (strpos($e->getMessage(), 'already exists') !== false) {
        echo "⚠️  Table {$table_prefix}help_topics already exists. Skipping creation.\n\n";
    } else {
        die("❌ Error creating table: " . $e->getMessage() . "\n");
    }
}

// Step 2: Check if help topics already exist
echo "Step 2: Checking for existing help topics...\n";
$check_sql = "SELECT COUNT(*) FROM `{$table_prefix}help_topics`";
$count = $db->query($check_sql)->fetchColumn();

if ($count > 0) {
    echo "⚠️  Found {$count} existing help topics. Skipping seed data.\n\n";
    echo "✅ Setup complete!\n";
    exit(0);
}

// Step 3: Seed initial help topics
echo "Step 3: Seeding initial help topics...\n";

$seed_sql = "
INSERT INTO `{$table_prefix}help_topics` (`slug`, `title`, `content_html`, `category`, `created_ymdhis`, `updated_ymdhis`, `is_deleted`) VALUES
('getting-started', 'Getting Started with Lupopedia', '<h1>Getting Started with Lupopedia</h1><p>Welcome to Lupopedia! This guide will help you get started.</p><h2>What is Lupopedia?</h2><p>Lupopedia is a semantic operating system that provides a structured way to organize and access knowledge.</p><h2>First Steps</h2><ol><li>Explore the help topics</li><li>Browse available entities using the List module</li><li>Search for specific information</li></ol>', 'General', 20260118190000, 20260118190000, 0),
('using-help', 'Using the Help System', '<h1>Using the Help System</h1><p>The help system provides documentation and guides for using Lupopedia.</p><h2>Searching Help</h2><p>Use the search box at the top of the help page to find topics by keyword.</p><h2>Categories</h2><p>Browse help topics by category to find related documentation.</p>', 'General', 20260118190000, 20260118190000, 0),
('using-list', 'Using the List Module', '<h1>Using the List Module</h1><p>The List module allows you to browse and inspect all entities in the Lupopedia system.</p><h2>Available Entities</h2><ul><li><strong>Actors:</strong> Users, AI agents, and services</li><li><strong>Agents:</strong> AI agent registry entries</li><li><strong>Help Topics:</strong> Help documentation</li><li><strong>Content:</strong> Content items</li><li><strong>Channels:</strong> Communication channels</li><li><strong>Collections:</strong> Content collections</li></ul><h2>Navigation</h2><p>Click on any entity type to view all records in that table. Use pagination to browse through large datasets.</p>', 'General', 20260118190000, 20260118190000, 0),
('architecture', 'Lupopedia Architecture', '<h1>Lupopedia Architecture</h1><p>Lupopedia follows a grounded, doctrine-aligned architecture.</p><h2>Five Pillars</h2><ul><li><strong>Actor Pillar:</strong> All entities that can perform actions (users, AI agents, services)</li><li><strong>Temporal Pillar:</strong> BIGINT UTC timestamps in YYYYMMDDHHIISS format</li><li><strong>Edge Pillar:</strong> Relationship management</li><li><strong>Doctrine Pillar:</strong> Architecture rules and enforcement</li><li><strong>Emergence Pillar:</strong> System evolution</li></ul><h2>Database Doctrine</h2><ul><li>No foreign keys (app-managed relationships)</li><li>BIGINT timestamps</li><li>Soft deletes with is_deleted flags</li></ul>', 'Technical', 20260118190000, 20260118190000, 0),
('modules', 'Understanding Modules', '<h1>Understanding Modules</h1><p>Modules are self-contained components that provide specific functionality.</p><h2>Module Structure</h2><p>Each module includes:</p><ul><li>Controllers for handling routes</li><li>Models for database access</li><li>Views for rendering output</li></ul><h2>Available Modules</h2><ul><li><strong>Help:</strong> Documentation system</li><li><strong>List:</strong> Entity introspection</li><li><strong>Content:</strong> Content management</li><li><strong>Truth:</strong> Question-answer system</li></ul>', 'Technical', 20260118190000, 20260118190000, 0);
";

try {
    $db->exec($seed_sql);
    echo "✅ Seeded 5 initial help topics.\n\n";
} catch (PDOException $e) {
    die("❌ Error seeding help topics: " . $e->getMessage() . "\n");
}

// Step 4: Verify setup
echo "Step 4: Verifying setup...\n";
$verify_sql = "SELECT COUNT(*) FROM `{$table_prefix}help_topics` WHERE is_deleted = 0";
$final_count = $db->query($verify_sql)->fetchColumn();
echo "✅ Found {$final_count} active help topics.\n\n";

echo "==========================================\n";
echo "✅ Setup complete!\n";
echo "==========================================\n\n";
echo "Next steps:\n";
echo "1. Visit /help to see the help index\n";
echo "2. Visit /list to see the entity list\n";
echo "3. Test search functionality at /help/search?q=getting\n";
echo "\n";
