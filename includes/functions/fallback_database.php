<?php
/**
 * Fallback Database Functions
 * 
 * File-based database alternative for when main DB is offline
 * Provides read/write operations using MD/CSV files
 * 
 * @package Lupopedia\Functions
 * @version 4.0.55
 * @author Windsurf (1002)
 */

/**
 * Check if system is in fallback mode
 * 
 * @return bool
 */
function lupo_is_fallback_mode()
{
    // Check for fallback mode constant or environment variable
    if (defined('LUPO_FALLBACK_MODE') && LUPO_FALLBACK_MODE === true) {
        return true;
    }
    
    // Check if main database is available
    try {
        if (isset($GLOBALS['mydatabase']) && $GLOBALS['mydatabase']) {
            $GLOBALS['mydatabase']->query("SELECT 1");
            return false;
        }
    } catch (Exception $e) {
        return true;
    }
    
    return false;
}

/**
 * Fallback channel reader
 * 
 * @param int $channel_id
 * @return array|false
 */
function fallback_read_channel($channel_id)
{
    if (!lupo_is_fallback_mode()) {
        return false;
    }
    
    $channel_file = LUPO_CHANNEL_DIR . "/channel_{$channel_id}.md";
    
    if (!file_exists($channel_file)) {
        return false;
    }
    
    $content = file_get_contents($channel_file);
    if ($content === false) {
        return false;
    }
    
    // Parse frontmatter from markdown file
    $lines = explode("\n", $content);
    $frontmatter = array();
    $body = array();
    $in_frontmatter = false;
    
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '---') {
            $in_frontmatter = !$in_frontmatter;
            continue;
        }
        
        if ($in_frontmatter && strpos($line, ':') !== false) {
            list($key, $value) = explode(':', $line, 2);
            $frontmatter[trim($key)] = trim($value);
        } elseif (!$in_frontmatter) {
            $body[] = $line;
        }
    }
    
    return array_merge($frontmatter, array(
        'channel_id' => $channel_id,
        'body' => implode("\n", $body),
        'file_path' => $channel_file
    ));
}

/**
 * Fallback channel writer
 * 
 * @param array $channel_data
 * @return bool
 */
function fallback_write_channel($channel_data)
{
    if (!lupo_is_fallback_mode()) {
        return false;
    }
    
    $channel_id = $channel_data['channel_id'];
    $channel_file = LUPO_CHANNEL_DIR . "/channel_{$channel_id}.md";
    
    // Prepare frontmatter
    $frontmatter = array();
    $body = '';
    
    foreach ($channel_data as $key => $value) {
        if ($key === 'body' || $key === 'file_path') {
            continue;
        }
        $frontmatter[$key] = $value;
    }
    
    // Build markdown content
    $content = "---\n";
    foreach ($frontmatter as $key => $value) {
        $content .= "{$key}: {$value}\n";
    }
    $content .= "---\n";
    $content .= isset($channel_data['body']) ? $channel_data['body'] : '';
    
    // Ensure directory exists
    $dir = dirname($channel_file);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    return file_put_contents($channel_file, $content) !== false;
}

/**
 * Fallback actor reader
 * 
 * @param int $actor_id
 * @return array|false
 */
function fallback_read_actor($actor_id)
{
    if (!lupo_is_fallback_mode()) {
        return false;
    }
    
    $actor_file = LUPO_ACTORS_DIR . "/actor_{$actor_id}.json";
    
    if (!file_exists($actor_file)) {
        return false;
    }
    
    $content = file_get_contents($actor_file);
    if ($content === false) {
        return false;
    }
    
    $actor_data = json_decode($content, true);
    return $actor_data !== null ? $actor_data : false;
}

/**
 * Fallback actor writer
 * 
 * @param array $actor_data
 * @return bool
 */
function fallback_write_actor($actor_data)
{
    if (!lupo_is_fallback_mode()) {
        return false;
    }
    
    $actor_id = $actor_data['actor_id'];
    $actor_file = LUPO_ACTORS_DIR . "/actor_{$actor_id}.json";
    
    // Ensure directory exists
    $dir = dirname($actor_file);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    $content = json_encode($actor_data, JSON_PRETTY_PRINT);
    return file_put_contents($actor_file, $content) !== false;
}

/**
 * Fallback unified log reader
 * 
 * @param array $filters
 * @return array
 */
function fallback_read_unified_log($filters = array())
{
    if (!lupo_is_fallback_mode()) {
        return array();
    }
    
    $log_file = LUPO_DATABASE_DIR . '/lupopedia/logs/unified_log.csv';
    
    if (!file_exists($log_file)) {
        return array();
    }
    
    $logs = array();
    $handle = fopen($log_file, 'r');
    
    if ($handle === false) {
        return array();
    }
    
    // Skip header row
    $header = fgetcsv($handle);
    
    while (($row = fgetcsv($handle)) !== false) {
        $log_entry = array_combine($header, $row);
        
        // Apply filters
        $match = true;
        foreach ($filters as $key => $value) {
            if (isset($log_entry[$key]) && $log_entry[$key] != $value) {
                $match = false;
                break;
            }
        }
        
        if ($match) {
            $logs[] = $log_entry;
        }
    }
    
    fclose($handle);
    return array_reverse($logs); // Most recent first
}

/**
 * Fallback unified log writer
 * 
 * @param array $log_entry
 * @return bool
 */
function fallback_write_unified_log($log_entry)
{
    if (!lupo_is_fallback_mode()) {
        return false;
    }
    
    $log_file = LUPO_DATABASE_DIR . '/lupopedia/logs/unified_log.csv';
    
    // Ensure directory exists
    $dir = dirname($log_file);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    // Prepare CSV row
    $fields = array('log_id', 'log_type', 'log_level', 'log_message', 'log_context', 'actor_id', 'channel_id', 'session_id', 'ip_address', 'user_agent', 'created_ymdhis');
    $row = array();
    
    foreach ($fields as $field) {
        $row[] = isset($log_entry[$field]) ? $log_entry[$field] : '';
    }
    
    $handle = fopen($log_file, 'a');
    if ($handle === false) {
        return false;
    }
    
    // Write header if file is new
    if (filesize($log_file) === 0) {
        fputcsv($handle, $fields);
    }
    
    $result = fputcsv($handle, $row);
    fclose($handle);
    
    return $result !== false;
}

/**
 * Fallback collection reader
 * 
 * @param string $collection_name
 * @return array|false
 */
function fallback_read_collection($collection_name)
{
    if (!lupo_is_fallback_mode()) {
        return false;
    }
    
    $collection_file = LUPO_COLLECTIONS_DIR . "/{$collection_name}.csv";
    
    if (!file_exists($collection_file)) {
        return false;
    }
    
    $collections = array();
    $handle = fopen($collection_file, 'r');
    
    if ($handle === false) {
        return false;
    }
    
    $header = fgetcsv($handle);
    
    while (($row = fgetcsv($handle)) !== false) {
        $collection = array_combine($header, $row);
        $collections[] = $collection;
    }
    
    fclose($handle);
    return $collections;
}

/**
 * Fallback atom reader
 * 
 * @param string $atom_type
 * @return array|false
 */
function fallback_read_atom($atom_type)
{
    if (!lupo_is_fallback_mode()) {
        return false;
    }
    
    $atom_file = LUPO_ATOMS_DIR . "/{$atom_type}.csv";
    
    if (!file_exists($atom_file)) {
        return false;
    }
    
    $atoms = array();
    $handle = fopen($atom_file, 'r');
    
    if ($handle === false) {
        return false;
    }
    
    $header = fgetcsv($handle);
    
    while (($row = fgetcsv($handle)) !== false) {
        $atom = array_combine($header, $row);
        $atoms[] = $atom;
    }
    
    fclose($handle);
    return $atoms;
}

/**
 * Initialize fallback system
 * 
 * @return void
 */
function fallback_initialize()
{
    if (!lupo_is_fallback_mode()) {
        return;
    }
    
    // Create placeholder files if they don't exist
    $placeholder_files = array(
        LUPO_DATABASE_DIR . '/lupopedia/logs/unified_log.csv' => "log_id,log_type,log_level,log_message,log_context,actor_id,channel_id,session_id,ip_address,user_agent,created_ymdhis\n",
        LUPO_COLLECTIONS_DIR . '/collections.csv' => "collection_id,collection_name,collection_type,description,created_ymdhis,updated_ymdhis\n",
        LUPO_ATOMS_DIR . '/atoms.csv' => "atom_id,atom_type,atom_value,description,created_ymdhis,updated_ymdhis\n"
    );
    
    foreach ($placeholder_files as $file => $content) {
        $dir = dirname($file);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        if (!file_exists($file)) {
            file_put_contents($file, $content);
        }
    }
}

?>
