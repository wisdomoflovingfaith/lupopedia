<?php
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
