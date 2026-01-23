<?php
/**
 * wolfie.header.identity: insert-changelog-entry
 * wolfie.header.placement: /scripts/insert_changelog_entry.php
 * wolfie.header.version: 4.1.1
 * wolfie.header.dialog:
 *   speaker: CURSOR
 *   target: @everyone
 *   message: "Script to insert new changelog entries at the top of changelog_dialog.md, maintaining reverse chronological order."
 *   mood: "00FF00"
 */

/**
 * Insert Changelog Entry
 * 
 * Inserts a new entry at the top of dialogs/changelog_dialog.md
 * after the YAML frontmatter and before the first existing entry.
 */

$changelog_file = __DIR__ . '/../dialogs/changelog_dialog.md';

if (!file_exists($changelog_file)) {
    die("❌ Changelog file not found: {$changelog_file}\n");
}

// Read the entire file
$content = file_get_contents($changelog_file);

// Find the end of the YAML frontmatter
// Look for the pattern: "---\n\n## " which marks the start of the first entry
$pattern = "/^---\s*\n\s*\n## /m";
if (preg_match($pattern, $content, $matches, PREG_OFFSET_CAPTURE)) {
    $insert_pos = $matches[0][1] + strlen($matches[0][0]) - strlen("## ");
} else {
    // Fallback: look for "---" followed by newline(s) and then "##"
    $second_dash = strpos($content, "---", 3); // Skip first "---"
    if ($second_dash === false) {
        die("❌ Could not find YAML frontmatter end marker.\n");
    }
    
    // Find the next "##" after the second "---"
    $first_entry = strpos($content, "\n## ", $second_dash);
    if ($first_entry === false) {
        die("❌ Could not find first changelog entry.\n");
    }
    
    $insert_pos = $first_entry + 1; // Position at start of "## "
}

// Skip any blank lines before the first entry
while ($insert_pos < strlen($content) && ($content[$insert_pos] === "\n" || $content[$insert_pos] === "\r")) {
    $insert_pos++;
}

// New entry to insert
$new_entry = <<<'ENTRY'
## 2026-01-18 — UI Rendering Anomaly + Audio Bridge Note

**Speaker:** WOLFIE / CAPTAIN / SYSTEM  
**Target:** @everyone  
**Mood:** `00FF00`  
**Message:** "UI anomaly observed and resolved. Audio bridge context noted."

**WOLFIE:** Captain, I am observing a visual interface that resembles a starship control panel. Multiple font sizes, inconsistent colors, and layout distortions are present.

**CAPTAIN:** That's just the HELP/LIST modules loading before the CSS stabilizes. Nothing cosmic.

**WOLFIE:** Acknowledged. Logging as a transient UI anomaly caused by stylesheet delay. No doctrine violation detected.

**SYSTEM NOTE:** During this event, an external audio stream was active through the "Pandora Bridge" — a real-world audio channel connected to the Captain's human ears. The stream was tuned to the K‑LOVE station. This does not affect system logic but is noted for environmental context.

**STATUS:** UI stabilized after stylesheet load. Audio bridge functioning normally. No further action required.

---

ENTRY;

// Insert the new entry
$new_content = substr($content, 0, $insert_pos) . $new_entry . "\n" . substr($content, $insert_pos);

// Write back to file
file_put_contents($changelog_file, $new_content);

echo "✅ Changelog entry inserted successfully!\n";
echo "   Location: dialogs/changelog_dialog.md\n";
echo "   Entry date: 2026-01-18\n";
