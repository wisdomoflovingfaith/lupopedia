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

class PDO_DB
{
    private $pdo = null;
    private $lastError = '';
    private $lastQuery = '';
    private $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_STRINGIFY_FETCHES => false
    );

    /**
     * Constructor
     * @param string|PDO $host Database host or existing PDO instance
     * @param string $user Database username
     * @param string $pass Database password
     * @param string $dbname Database name
     * @param string $type Database type (mysql, pgsql)
     * @throws PDOException On connection failure
     */
    public function __construct($host, $user = null, $pass = null, $dbname = '', $type = 'mysql')
    {
        if ($host instanceof PDO) {
            $this->pdo = $host;
        } else {
            $this->connect($host, $user, $pass, $dbname, $type);
        }
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
    private function connect($host, $user, $pass, $dbname, $type)
    {
        $dsn = $this->getDsn($host, $dbname, $type);

        try {
            $opts = $this->options;
            if ($type === 'mysql' && extension_loaded('pdo_mysql')) {
                $opts[PDO::MYSQL_ATTR_USE_BUFFERED_QUERY] = true;
            }
            $this->pdo = new PDO($dsn, $user, $pass, $opts);
            if ($type === 'mysql' && extension_loaded('pdo_mysql')) {
                try {
                    $this->pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
                } catch (Exception $e) {
                    // Rare unsupported build; constructor option still preferred.
                }
            }
            // Must match DSN charset=utf8mb4 and utf8mb4_* table collations (avoid utf8mb3 vs utf8mb4 mix).
            if ($type === 'mysql') {
                $this->pdo->exec("SET NAMES 'utf8mb4'");
                $this->pdo->exec("SET CHARACTER SET 'utf8mb4'");
            }
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
    private function getDsn($host, $dbname, $type)
    {
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
    public function query($sql, $params = array())
    {
        $this->lastQuery = $sql;
        error_log("[LUPO_TRACER_123] [PDO_DB] QUERY: " . $sql . " | PARAMS: " . json_encode($params));
        
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
    public function fetchAll($sql, $params = array())
    {
        $stmt = $this->query($sql, $params);
        try {
            return $stmt->fetchAll();
        } finally {
            $stmt->closeCursor();
        }
    }

    /**
     * Execute a query and return first row
     * @param string $sql
     * @param array $params
     * @return array|null
     */
    public function fetchRow($sql, $params = array())
    {
        $stmt = $this->query($sql, $params);
        try {
            $result = $stmt->fetch();
            return ($result === false) ? null : $result;
        } finally {
            $stmt->closeCursor();
        }
    }

    /**
     * Execute a query and return a single value
     * @param string $sql
     * @param array $params
     * @return mixed
     */
    public function fetchOne($sql, $params = array())
    {
        $stmt = $this->query($sql, $params);
        try {
            return $stmt->fetchColumn(0);
        } finally {
            $stmt->closeCursor();
        }
    }

    /**
     * Insert data into a table
     * @param string $table
     * @param array $data
     * @return string|false Last insert ID or false on failure
     */
    public function insert($table, $data)
    {
        $columns = array_keys($data);
        $placeholders = array_fill(0, count($columns), '?');

        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $this->quoteIdentifier($table),
            implode(', ', $this->quoteIdentifiers($columns)),
            implode(', ', $placeholders)
        );

        $stmt = null;
        try {
            // Pass values only for positional binding
            $stmt = $this->query($sql, array_values($data));
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            error_log("[PDO_DB] INSERT ERROR in $table: " . $this->lastError);
            return false;
        } finally {
            if ($stmt !== null) {
                $stmt->closeCursor();
            }
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
    public function update($table, $data, $where, $whereParams = array())
    {
        $set = array();
        foreach (array_keys($data) as $column) {
            $set[] = $this->quoteIdentifier($column) . " = ?";
        }

        $sql = sprintf(
            "UPDATE %s SET %s WHERE %s",
            $this->quoteIdentifier($table),
            implode(', ', $set),
            $where
        );

        // Merge values from data and whereParams (must be positional in where)
        $params = array_merge(array_values($data), array_values($whereParams));

        $stmt = null;
        try {
            $stmt = $this->query($sql, $params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            error_log("[PDO_DB] UPDATE ERROR in $table: " . $this->lastError);
            return 0;
        } finally {
            if ($stmt !== null) {
                $stmt->closeCursor();
            }
        }
    }

    /**
     * Delete records from a table
     * @param string $table
     * @param string $where
     * @param array $params
     * @return int Number of affected rows
     */
    public function delete($table, $where, $params = array())
    {
        $sql = sprintf(
            "DELETE FROM %s WHERE %s",
            $this->quoteIdentifier($table),
            $where
        );

        $stmt = null;
        try {
            $stmt = $this->query($sql, $params);
            return $stmt->rowCount();
        } catch (PDOException $e) {
            $this->lastError = $e->getMessage();
            error_log("[PDO_DB] DELETE ERROR in $table: " . $this->lastError);
            return 0;
        } finally {
            if ($stmt !== null) {
                $stmt->closeCursor();
            }
        }
    }

    /**
     * Begin a transaction
     * @return bool
     */
    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * Commit a transaction
     * @return bool
     */
    public function commit()
    {
        return $this->pdo->commit();
    }

    /**
     * Rollback a transaction
     * @return bool
     */
    public function rollBack()
    {
        return $this->pdo->rollBack();
    }

    /**
     * Quote a string for use in a query
     * @param string $value
     * @return string
     */
    public function quote($value)
    {
        return $this->pdo->quote($value);
    }

    /**
     * Quote an identifier (table/column name)
     * @param string $identifier
     * @return string
     */
    public function quoteIdentifier($identifier)
    {
        $parts = explode('.', $identifier);
        $quoted = array_map(function ($part) {
            return '`' . str_replace('`', '``', $part) . '`';
        }, $parts);
        return implode('.', $quoted);
    }

    /**
     * Quote multiple identifiers
     * @param array $identifiers
     * @return array
     */
    public function quoteIdentifiers($identifiers)
    {
        return array_map([$this, 'quoteIdentifier'], $identifiers);
    }

    /**
     * Get the last error message
     * @return string
     */
    public function getLastError()
    {
        if ($this->lastError !== '') {
            return $this->lastError;
        }
        $info = $this->pdo->errorInfo();
        return isset($info[2]) ? $info[2] : '';
    }

    /**
     * Get the last query executed
     * @return string
     */
    public function getLastQuery()
    {
        return $this->lastQuery;
    }

    /**
     * Prepare parameters for PDO
     * @param array $params
     * @return array
     */
    private function prepareParams($params)
    {
        return $params;
    }

    /**
     * Get the PDO instance
     * @return PDO
     */
    public function getPdo()
    {
        return $this->pdo;
    }

    /**
     * Prepare a statement (for compatibility with code using prepare/execute/fetch).
     * Prefer query(), fetchRow(), fetchAll() for new code.
     * @param string $sql
     * @return PDOStatement
     */
    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }

    /**
     * Execute a raw SQL statement (e.g. SET time_zone). Prefer parameterized query() where possible.
     * @param string $sql
     * @return int|false
     */
    public function exec($sql)
    {
        return $this->pdo->exec($sql);
    }

    /**
     * Get last insert ID (for compatibility with code using PDO::lastInsertId).
     * @return string|false
     */
    public function lastInsertId($name = null)
    {
        return $name !== null ? $this->pdo->lastInsertId($name) : $this->pdo->lastInsertId();
    }
}
?>
