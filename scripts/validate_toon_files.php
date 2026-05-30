<?php
/**
lupopedia.headers:
  when_updated: "20260324175911"
  file_path_from_root: "scripts/validate_toon_files.php"
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
  file_path_from_root: "scripts/validate_toon_files.php"
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
 * Validate TOON JSON files
 */

$toon_dir = __DIR__ . '/../database/toon_data';
$files = glob($toon_dir . '/*.toon');

echo "Validating TOON files...\n\n";

$errors = 0;
foreach ($files as $file) {
    $content = file_get_contents($file);
    $json = json_decode($content, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "❌ ERROR in " . basename($file) . ": " . json_last_error_msg() . "\n";
        $errors++;
    } else {
        echo "✅ OK: " . basename($file) . "\n";
    }
}

echo "\n";
if ($errors === 0) {
    echo "✅ All TOON files are valid JSON!\n";
} else {
    echo "❌ Found $errors error(s).\n";
    exit(1);
}
