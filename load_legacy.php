<?php
try {
    $db = new PDO('mysql:host=localhost;dbname=lupopedia', 'root', 'ServBay.dev');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sqlFile = 'lupo-database/lupopedia/mysql/import/old_crafty_syntax_3_7_5_start.sql';
    if (!file_exists($sqlFile)) {
        die("SQL file not found: $sqlFile");
    }
    
    echo "Loading SQL from $sqlFile...\n";
    
    $file = fopen($sqlFile, 'r');
    $query = '';
    while (($line = fgets($file)) !== false) {
        // Skip comments and empty lines
        if (trim($line) == '' || strpos(trim($line), '--') === 0 || strpos(trim($line), '/*') === 0 || strpos(trim($line), '#') === 0) {
            continue;
        }
        
        $query .= $line;
        if (substr(trim($line), -1) == ';') {
            try {
                $db->exec($query);
            } catch (Exception $ex) {
                echo "Error in query: " . substr($query, 0, 100) . "...\n";
                echo $ex->getMessage() . "\n";
            }
            $query = '';
        }
    }
    fclose($file);
    
    echo "Legacy SQL loaded.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
