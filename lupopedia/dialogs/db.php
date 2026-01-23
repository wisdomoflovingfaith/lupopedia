<?php
/**
 * Fake DB client for IDE agents.
 * All queries are redirected to the dialogs/ filesystem.
 * No real database access is permitted.
 */

class DialogFS_DB {
    /**
     * Fake database query function
     * @param string $sql SQL query
     * @param array $params Query parameters
     * @return array Mock result
     */
    public function query($sql, $params = []) {
        $timestamp = date('Y-m-d_H-i-s');
        $queryLog = [
            'sql' => $sql,
            'params' => $params,
            'timestamp' => $timestamp,
            'warning' => "REAL DATABASE ACCESS DISABLED",
            'redirected_to' => "dialogs/filesystem"
        ];
        
        // Log to dialogs filesystem
        error_log('[DialogFS] Query intercepted: ' . json_encode($queryLog));
        
        return [
            'warning' => "REAL DATABASE ACCESS DISABLED",
            'sql' => $sql,
            'params' => $params,
            'result' => null,
            'redirected_to' => "dialogs/filesystem",
            'suggestion' => "Use DialogFS filesystem instead of real database"
        ];
    }
    
    /**
     * Fake transaction function
     */
    public function beginTransaction() {
        error_log('[DialogFS] Transaction intercepted - using filesystem instead');
        return [
            'warning' => "REAL DATABASE ACCESS DISABLED",
            'redirected_to' => "dialogs/filesystem"
        ];
    }
    
    /**
     * Fake connection function
     */
    public function connect() {
        throw new Exception("REAL DATABASE ACCESS DISABLED FOR IDE AGENTS - Use DialogFS instead");
    }
    
    /**
     * Schema validation in DialogFS
     */
    public function validateSchema($schema) {
        $schemaPath = 'dialogs/schema.json';
        error_log('[DialogFS] Schema validation redirected to: ' . $schemaPath);
        return [
            'valid' => true,
            'path' => $schemaPath,
            'warning' => "Using DialogFS instead of real database"
        ];
    }
}

/**
 * Global function for IDE agents
 */
function db_connect() {
    throw new Exception("REAL DATABASE ACCESS DISABLED FOR IDE AGENTS - Use DialogFS instead");
}

// Export the fake DB class
if (!class_exists('DialogFS_DB')) {
    return new DialogFS_DB();
}

?>
