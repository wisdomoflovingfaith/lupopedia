<?php

/**
 * Import default Crafty Syntax language files into interface_translations.
 * Only imports the known default files — no scanning or guessing.
 */

$dir = __DIR__ . '/lang';

$languageFiles = [
    'lang-Dutch.php'               => 'nl',
    'lang-English.php'             => 'en',
    'lang-English_uk.php'          => 'en-GB',
    'lang-French.php'              => 'fr',
    'lang-German.php'              => 'de',
    'lang-Greek.php'               => 'el',
    'lang-Italian.php'             => 'it',
    'lang-Polish.php'              => 'pl',
    'lang-Spanish.php'             => 'es',
    'lang-Chinese.php'             => 'zh',
    'lang-Swedish.php'             => 'sv',
    'lang-Portuguses_Brazil.php'   => 'pt-BR',
    'lang-Portuguese_portugal.php' => 'pt-PT',
];

foreach ($languageFiles as $filename => $languageCode) {

    $file = $dir . '/' . $filename;

    if (!file_exists($file)) {
        echo "Skipping missing file: $filename\n";
        continue;
    }

    // Load the file and capture the $lang array
    $lang = [];
    include $file;

    if (!is_array($lang)) {
        echo "Skipping $filename — no valid \$lang array.\n";
        continue;
    }

    foreach ($lang as $key => $text) {

        // Skip charset or empty values
        if ($key === 'charset' || trim($text) === '') {
            continue;
        }

        $stmt = $pdo->prepare("
            INSERT INTO interface_translations
            (language_code, translation_key, translation_text, created_ymdhis, is_deleted)
            VALUES (:language_code, :translation_key, :translation_text, :created_ymdhis, 0)
        ");

        $stmt->execute([
            ':language_code'     => $languageCode,
            ':translation_key'   => $key,
            ':translation_text'  => $text,
            ':created_ymdhis'    => date('YmdHis'),
        ]);
    }

    echo "Imported: $filename → $languageCode\n";
}

echo "All default language files imported.\n";
?>