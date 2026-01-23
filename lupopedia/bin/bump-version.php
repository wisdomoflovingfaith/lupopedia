#!/usr/bin/env php
<?php
/**
 * Version Bump Automation Script
 * 
 * Automates the version bump process according to VERSION_DOCTRINE.md
 * Ensures all version references are updated consistently
 * 
 * Usage: php bin/bump-version.php 4.0.36
 * 
 * @package Lupopedia
 * @version 4.0.35
 */

// Prevent web access
if (php_sapi_name() !== 'cli') {
    die("This script can only be run from the command line.\n");
}

// Get version from command line
if ($argc < 2) {
    echo "Usage: php bin/bump-version.php <version>\n";
    echo "Example: php bin/bump-version.php 4.0.36\n";
    exit(1);
}

$new_version = trim($argv[1]);

// Validate version format (semantic versioning: MAJOR.MINOR.PATCH)
if (!preg_match('/^\d+\.\d+\.\d+$/', $new_version)) {
    echo "ERROR: Invalid version format. Must be MAJOR.MINOR.PATCH (e.g., 4.0.36)\n";
    exit(1);
}

// Get project root (assuming script is in bin/)
$project_root = dirname(__DIR__);
$atoms_file = $project_root . '/config/global_atoms.yaml';
$version_file = $project_root . '/lupo-includes/version.php';
$changelog_file = $project_root . '/CHANGELOG.md';
$dialog_file = $project_root . '/dialogs/changelog_dialog.md';

// Load current version from atoms
function load_current_version($atoms_file) {
    if (!file_exists($atoms_file)) {
        return null;
    }
    
    $content = file_get_contents($atoms_file);
    if (preg_match('/^version:\s*["\']?([^"\'\n]+)["\']?/m', $content, $matches)) {
        return trim($matches[1], '"\'');
    }
    
    // Try GLOBAL_CURRENT_LUPOPEDIA_VERSION atom
    if (preg_match('/^GLOBAL_CURRENT_LUPOPEDIA_VERSION:\s*["\']?([^"\'\n]+)["\']?/m', $content, $matches)) {
        return trim($matches[1], '"\'');
    }
    
    return null;
}

$current_version = load_current_version($atoms_file);

if ($current_version === null) {
    echo "ERROR: Could not determine current version from {$atoms_file}\n";
    exit(1);
}

echo "Current version: {$current_version}\n";
echo "New version: {$new_version}\n";
echo "\n";

// Confirm
echo "This will update:\n";
echo "  - config/global_atoms.yaml (atom and version fields)\n";
echo "  - lupo-includes/version.php (constants - now loads from atom)\n";
echo "  - CHANGELOG.md (add entry and update summary)\n";
echo "  - dialogs/changelog_dialog.md (add dialog entry)\n";
echo "\n";
echo "Continue? (yes/no): ";

$handle = fopen("php://stdin", "r");
$line = fgets($handle);
$confirmation = trim(strtolower($line));
fclose($handle);

if ($confirmation !== 'yes' && $confirmation !== 'y') {
    echo "Aborted.\n";
    exit(0);
}

echo "\nStarting version bump...\n\n";

// Step 1: Update global_atoms.yaml
echo "Step 1: Updating config/global_atoms.yaml...\n";
$atoms_content = file_get_contents($atoms_file);

// Update version field
$atoms_content = preg_replace(
    '/^version:\s*["\']?[^"\'\n]+["\']?/m',
    "version: \"{$new_version}\"",
    $atoms_content
);

// Update versions section
$atoms_content = preg_replace(
    '/(\s+lupopedia:\s*)["\']?[^"\'\n]+["\']?/m',
    "\$1\"{$new_version}\"",
    $atoms_content
);
$atoms_content = preg_replace(
    '/(\s+crafty_syntax:\s*)["\']?[^"\'\n]+["\']?/m',
    "\$1\"{$new_version}\"",
    $atoms_content
);
$atoms_content = preg_replace(
    '/(\s+wolfie_headers:\s*)["\']?[^"\'\n]+["\']?/m',
    "\$1\"{$new_version}\"",
    $atoms_content
);
$atoms_content = preg_replace(
    '/(\s+schema:\s*)["\']?[^"\'\n]+["\']?/m',
    "\$1\"{$new_version}\"",
    $atoms_content
);

// Update last_updated date
$today = date('Ymd');
$atoms_content = preg_replace(
    '/^last_updated:\s*\d+/m',
    "last_updated: {$today}",
    $atoms_content
);

// Update GLOBAL_CURRENT_LUPOPEDIA_VERSION atom
$atoms_content = preg_replace(
    '/^(GLOBAL_CURRENT_LUPOPEDIA_VERSION:\s*)["\']?[^"\'\n]+["\']?/m',
    "\$1\"{$new_version}\"",
    $atoms_content
);

file_put_contents($atoms_file, $atoms_content);
echo "  ✓ Updated atom and version fields\n";

// Step 2: Update version.php (now just update the date, version loads from atom)
echo "Step 2: Updating lupo-includes/version.php...\n";
$version_content = file_get_contents($version_file);

// Update version date (YYYYMMDDHHMMSS format)
$version_date = date('Ymd') . '000000';
$version_content = preg_replace(
    "/define\('LUPOPEDIA_VERSION_DATE',\s*\d+\);/",
    "define('LUPOPEDIA_VERSION_DATE', {$version_date});",
    $version_content
);

// Update @version docblock
$version_content = preg_replace(
    '/@version\s+\d+\.\d+\.\d+/',
    "@version {$new_version}",
    $version_content
);

file_put_contents($version_file, $version_content);
echo "  ✓ Updated version date and docblock (version loads from atom)\n";

// Step 3: Add CHANGELOG entry
echo "Step 3: Adding CHANGELOG.md entry...\n";
$changelog_content = file_get_contents($changelog_file);
$today_formatted = date('Y-m-d');

// Create changelog entry template
$changelog_entry = <<<ENTRY
## [{$new_version}] - {$today_formatted}

### Added
- (Describe new features)

### Changed
- (Describe changes to existing functionality)

### Fixed
- (Describe bug fixes)

### Files Changed
- `config/global_atoms.yaml` - Version updated to {$new_version}
- `lupo-includes/version.php` - Version date updated
- `CHANGELOG.md` - Added {$new_version} entry
- `dialogs/changelog_dialog.md` - Added {$new_version} dialog entry

### Next Steps
- (Describe planned work for next version)

ENTRY;

// Insert after line 92 (after the "---" separator before version entries)
$changelog_content = preg_replace(
    '/(^---\s*\n\n)/m',
    "\$1{$changelog_entry}\n",
    $changelog_content,
    1
);

// Update consolidated summary
echo "  Step 3a: Updating consolidated summary...\n";
$changelog_content = preg_replace(
    '/## 📊 Consolidated Summary: Version \d+\.\d+\.\d+ → \d+\.\d+\.\d+/',
    "## 📊 Consolidated Summary: Version 4.0.19 → {$new_version}",
    $changelog_content
);

// Calculate version count (from 4.0.19 to new version)
list($major, $minor, $patch) = explode('.', $new_version);
$version_count = (($major - 4) * 1000) + ($minor * 100) + $patch - 19;
$changelog_content = preg_replace(
    '/This represents \d+ version increments/',
    "This represents {$version_count} version increments",
    $changelog_content
);

// Update "System State at" section
$changelog_content = preg_replace(
    '/### System State at \d+\.\d+\.\d+/',
    "### System State at {$new_version}",
    $changelog_content
);

// Update "Next Steps" version reference
$next_version = $major . '.' . $minor . '.' . ($patch + 1);
$changelog_content = preg_replace(
    '/\*\*Next Steps \(\d+\.\d+\.\d+\+\):\*\*/',
    "**Next Steps ({$next_version}+):**",
    $changelog_content
);

file_put_contents($changelog_file, $changelog_content);
echo "  ✓ Added CHANGELOG entry and updated summary\n";

// Step 4: Update dialog file
echo "Step 4: Updating dialogs/changelog_dialog.md...\n";
if (file_exists($dialog_file)) {
    $dialog_content = file_get_contents($dialog_file);
    
    $dialog_entry = <<<DIALOG
## {$today_formatted} — Version {$new_version}: (Describe changes)

**Speaker:** WOLFIE  
**Target:** @everyone  
**Mood:** "00FF00"  
**Message:** "Version {$new_version}: (Brief description of changes)"

**Changes:**
- (List key changes)

**Files Updated:**
- Updated all version references to {$new_version}

DIALOG;
    
    // Prepend to file
    $dialog_content = $dialog_entry . "\n\n" . $dialog_content;
    file_put_contents($dialog_file, $dialog_content);
    echo "  ✓ Added dialog entry\n";
} else {
    echo "  ⚠ Dialog file not found, skipping\n";
}

// Step 5: Validation
echo "\nStep 5: Validating version consistency...\n";

// Check atoms file
$atoms_check = load_current_version($atoms_file);
if ($atoms_check === $new_version) {
    echo "  ✓ config/global_atoms.yaml version correct\n";
} else {
    echo "  ✗ ERROR: config/global_atoms.yaml version mismatch (found: {$atoms_check}, expected: {$new_version})\n";
    exit(1);
}

// Check version.php loads correctly (if atom loader is available)
if (file_exists($project_root . '/lupo-includes/functions/load_atoms.php')) {
    require_once $project_root . '/lupo-includes/functions/load_atoms.php';
    if (function_exists('get_lupopedia_version')) {
        $loaded_version = get_lupopedia_version();
        if ($loaded_version === $new_version) {
            echo "  ✓ Atom loader returns correct version\n";
        } else {
            echo "  ⚠ WARNING: Atom loader returned: {$loaded_version} (expected: {$new_version})\n";
        }
    }
}

echo "\n✅ Version bump complete!\n";
echo "\nNext steps:\n";
echo "  1. Review and complete CHANGELOG.md entry (replace template placeholders)\n";
echo "  2. Review and complete dialogs/changelog_dialog.md entry\n";
echo "  3. Update WOLFIE Headers for any files you modified in this version\n";
echo "  4. Test that version loads correctly: php -r \"require 'lupo-includes/version.php'; echo LUPOPEDIA_VERSION;\"\n";
echo "\n";
