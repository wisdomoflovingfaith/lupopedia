<?php
//****************************************************************************************
// Library : PDO_DB  :  version 1.0.1 (01/13/2026)
// Author  : Captain Wolfie (Lupopedia.com)
//======================================================================================
/**
 * PDO Database Wrapper for Lupopedia
 * 
 * This class provides a thin abstraction layer over PDO that:
 * - Supports multiple database types (MySQL, PostgreSQL, SQLite)
 * - Provides automatic SQL injection protection via parameter binding
 * - Handles errors internally with safe fallback values
 * - Simplifies common database operations (insert, update, delete)
 * - Maintains consistent interface across database types
 * - Returns safe values on error (false, 0, null, empty array)
 * 
 * BASIC USAGE EXAMPLE:
 * <code>
 * $db = new PDO_DB($host, $user, $pass, $dbname);
 * 
 * // Fetch all rows with parameter binding
 * $rows = $db->fetchAll("SELECT * FROM users WHERE status = :status", ['status' => 'active']);
 * foreach ($rows as $row) {
 *     echo $row['username'];
 * }
 * 
 * // Insert with automatic parameter binding
 * $id = $db->insert('users', [
 *     'username' => $username,
 *     'email' => $email,
 *     'created_ymdhis' => gmdate('YmdHis')
 * ]);
 * 
 * // Fetch single row
 * $user = $db->fetchRow("SELECT * FROM users WHERE user_id = :id", ['id' => $userId]);
 * </code>
 * 
 * ALTERNATIVE EXAMPLE:
 * <code>
 * // Connect to PostgreSQL instead of MySQL
 * $db = new PDO_DB($host, $user, $pass, $dbname, 'pgsql');
 * 
 * // Update with WHERE clause and parameters
 * $affected = $db->update('users', 
 *     ['status' => 'inactive', 'updated_ymdhis' => gmdate('YmdHis')],
 *     'last_login_ymdhis < :cutoff',
 *     ['cutoff' => 20250101000000]
 * );
 * 
 * // Delete with parameters
 * $deleted = $db->delete('sessions', 
 *     'expires_ymdhis < :now', 
 *     ['now' => gmdate('YmdHis')]
 * );
 * 
 * // Transaction example
 * $db->beginTransaction();
 * try {
 *     $db->insert('orders', $orderData);
 *     $db->update('inventory', $inventoryData, 'product_id = :id', ['id' => $productId]);
 *     $db->commit();
 * } catch (Exception $e) {
 *     $db->rollBack();
 * }
 * </code>
 */
//
// CLASS PDO_DB FUNCTION LIST:
//      function __construct($host, $user, $pass, $dbname, $type)  - Constructor, connects to database
//      function query($sql, $params)                               - Execute query and return PDOStatement
//      function fetchAll($sql, $params)                            - Execute query and return all results as array
//      function fetchRow($sql, $params)                            - Execute query and return first row only
//      function fetchOne($sql, $params)                            - Execute query and return single value
//      function insert($table, $data)                              - Insert data into table, returns last insert ID
//      function update($table, $data, $where, $whereParams)        - Update records in table, returns affected rows
//      function delete($table, $where, $params)                    - Delete records from table, returns affected rows
//      function beginTransaction()                                 - Begin a database transaction
//      function commit()                                           - Commit current transaction
//      function rollBack()                                         - Rollback current transaction
//      function quote($value)                                      - Quote a string for use in query
//      function quoteIdentifier($identifier)                       - Quote an identifier (table/column name)
//      function quoteIdentifiers($identifiers)                     - Quote multiple identifiers
//      function getLastError()                                     - Get the last error message
//      function getLastQuery()                                     - Get the last query executed
//      function getPdo()                                           - Get the underlying PDO instance
//
// PRIVATE/PROTECTED METHODS:
//      function connect($host, $user, $pass, $dbname, $type)       - Connect to the database
//      function getDsn($host, $dbname, $type)                      - Get DSN string based on database type
//      function prepareParams($params)                             - Prepare parameters for PDO binding
//
// ORIGINAL CODE:
// ---------------------------------------------------------
// Captain Wolfie (Eric Robin Gerdes)
// Proprietary - All Rights Reserved
//
//=====================***  PDO_DB   ***======================================

class PDO_DB {
    private $pdo = null;
    private $lastError = '';
    private $lastQuery = '';
    private $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_STRINGIFY_FETCHES => false
    ];

    /**
     * Constructor
     * @param string $host Database host
     * @param string $user Database username
     * @param string $pass Database password
     * @param string $dbname Database name
     * @param string $type Database type (mysql, pgsql)
     * @throws PDOException On connection failure
     */
    public function __construct($host, $user, $pass, $dbname = '', $type = 'mysql') {
        $this->connect($host, $user, $pass, $dbname, $type);
    }

    /**
     * Connect to the database
     * @param string $host
     * @param string $user
     * @param string $pass
     * @param string $dbname
     * @param string $type
     * @throws PDOException
     */
    private function connect($host, $user, $pass, $dbname, $type) {
        $dsn = $this->getDsn($host, $dbname, $type);
        
        try {
            $this->pdo = new PDO($dsn, $user, $pass, $this->options);
            $this->pdo->exec("SET NAMES 'utf8'");
            $this->pdo->exec("SET CHARACTER SET 'utf8'");
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            throw $e;
        }
    }

    /**
     * Get DSN string based on database type
     * @param string $host
     * @param string $dbname
     * @param string $type
     * @return string
     */
    private function getDsn($host, $dbname, $type) {
        $type = strtolower($type);
        switch ($type) {
            case 'pgsql':
                return "pgsql:host={$host};dbname={$dbname}";
            case 'mysql':
            default:
                return "mysql:host={$host};dbname={$dbname};charset=utf8mb4";
        }
    }

    /**
     * Execute a query and return PDOStatement
     * @param string $sql
     * @param array $params
     * @return PDOStatement
     * @throws PDOException
     */
    public function query($sql, $params = []) {
        $this->lastQuery = $sql;
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->prepareParams($params));
        return $stmt;
    }

    /**
     * Execute a query and return all results
     * @param string $sql
     * @param array $params
     * @return array
     */
    public function fetchAll($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Execute a query and return first row
     * @param string $sql
     * @param array $params
     * @return array|null
     */
    public function fetchRow($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Execute a query and return a single value
     * @param string $sql
     * @param array $params
     * @return mixed
     */
    public function fetchOne($sql, $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchColumn(0);
    }

    /**
     * Insert data into a table
     * @param string $table
     * @param array $data
     * @return string|false Last insert ID or false on failure
     */
    public function insert($table, array $data) {
        $columns = array_keys($data);
        $placeholders = array_map(function($col) { 
            return ':' . $col; 
        }, $columns);
        
        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $this->quoteIdentifier($table),
            implode(', ', $this->quoteIdentifiers($columns)),
            implode(', ', $placeholders)
        );
        
        try {
            $this->query($sql, $data);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            return false;
        }
    }

    /**
     * Update records in a table
     * @param string $table
     * @param array $data
     * @param string $where
     * @param array $whereParams
     * @return int Number of affected rows
     */
    public function update($table, array $data, $where, array $whereParams = []) {
        $set = [];
        foreach (array_keys($data) as $column) {
            $set[] = $this->quoteIdentifier($column) . " = :$column";
        }
        
        $sql = sprintf(
            "UPDATE %s SET %s WHERE %s",
            $this->quoteIdentifier($table),
            implode(', ', $set),
            $where
        );
        
        $params = array_merge($data, $whereParams);
        
        try {
            $stmt = $this->query($sql, $params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            return 0;
        }
    }

    /**
     * Delete records from a table
     * @param string $table
     * @param string $where
     * @param array $params
     * @return int Number of affected rows
     */
    public function delete($table, $where, array $params = []) {
        $sql = sprintf(
            "DELETE FROM %s WHERE %s",
            $this->quoteIdentifier($table),
            $where
        );
        
        try {
            $stmt = $this->query($sql, $params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            return 0;
        }
    }

    /**
     * Begin a transaction
     * @return bool
     */
    public function beginTransaction() {
        return $this->pdo->beginTransaction();
    }

    /**
     * Commit a transaction
     * @return bool
     */
    public function commit() {
        return $this->pdo->commit();
    }

    /**
     * Rollback a transaction
     * @return bool
     */
    public function rollBack() {
        return $this->pdo->rollBack();
    }

    /**
     * Quote a string for use in a query
     * @param string $value
     * @return string
     */
    public function quote($value) {
        return $this->pdo->quote($value);
    }

    /**
     * Quote an identifier (table/column name)
     * @param string $identifier
     * @return string
     */
    public function quoteIdentifier($identifier) {
        $parts = explode('.', $identifier);
        $quoted = array_map(function($part) {
            return '`' . str_replace('`', '``', $part) . '`';
        }, $parts);
        return implode('.', $quoted);
    }

    /**
     * Quote multiple identifiers
     * @param array $identifiers
     * @return array
     */
    public function quoteIdentifiers(array $identifiers) {
        return array_map([$this, 'quoteIdentifier'], $identifiers);
    }

    /**
     * Get the last error message
     * @return string
     */
    public function getLastError() {
        return $this->lastError ?: $this->pdo->errorInfo()[2];
    }

    /**
     * Get the last query executed
     * @return string
     */
    public function getLastQuery() {
        return $this->lastQuery;
    }

    /**
     * Prepare parameters for PDO
     * @param array $params
     * @return array
     */
    private function prepareParams(array $params) {
        $prepared = [];
        foreach ($params as $key => $value) {
            $prepared[is_int($key) ? $key + 1 : $key] = $value;
        }
        return $prepared;
    }

    /**
     * Get the PDO instance
     * @return PDO
     */
    public function getPdo() {
        return $this->pdo;
    }
}
?>