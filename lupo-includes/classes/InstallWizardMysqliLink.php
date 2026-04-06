<?php
/**
 * mysqli-backed database handle for install.php / install_wizard_classes.php only.
 * Mimics the subset of PDO used by the wizard (exec, query, prepare, quote) so
 * install code stays unchanged while avoiding MySQL PDO error 2014 (unbuffered queries).
 *
 * Pattern: WordPress wpdb — buffer or free results before the next query on the same link.
 *
 * @package Lupopedia
 */

if (!defined('LUPOPEDIA_PATH')) {
    return;
}

/**
 * Buffered result set (all rows read in PHP; no server-side cursor).
 */
class InstallWizardMysqliBufferedResult
{
    private $rows;
    private $idx;

    public function __construct($rows)
    {
        $this->rows = is_array($rows) ? $rows : array();
        $this->idx = 0;
    }

    public function rowCount()
    {
        return count($this->rows);
    }

    public function fetch($mode = null)
    {
        if ($mode === null) {
            $mode = PDO::FETCH_ASSOC;
        }
        if (!isset($this->rows[$this->idx])) {
            return false;
        }
        $row = $this->rows[$this->idx];
        $this->idx++;
        if (!is_array($row)) {
            return false;
        }
        if ($mode === PDO::FETCH_NUM) {
            return array_values($row);
        }
        if ($mode === PDO::FETCH_ASSOC) {
            $out = array();
            foreach ($row as $k => $v) {
                if (is_string($k)) {
                    $out[$k] = $v;
                }
            }
            return $out;
        }
        return $row;
    }

    public function fetchColumn($columnIndex = 0)
    {
        $row = $this->fetch(PDO::FETCH_NUM);
        if ($row === false) {
            return false;
        }
        return isset($row[$columnIndex]) ? $row[$columnIndex] : false;
    }

    public function fetchAll($mode = null)
    {
        if ($mode === null) {
            $mode = PDO::FETCH_ASSOC;
        }
        if ($mode === PDO::FETCH_COLUMN) {
            $flat = array();
            for ($i = $this->idx; $i < count($this->rows); $i++) {
                $r = $this->rows[$i];
                $flat[] = is_array($r) ? reset($r) : null;
            }
            $this->idx = count($this->rows);
            return $flat;
        }
        $out = array();
        for ($i = $this->idx; $i < count($this->rows); $i++) {
            $row = $this->rows[$i];
            if (!is_array($row)) {
                continue;
            }
            if ($mode === PDO::FETCH_NUM) {
                $out[] = array_values($row);
            } else {
                $out[] = $row;
            }
        }
        $this->idx = count($this->rows);
        return $out;
    }

    public function closeCursor()
    {
        $this->idx = count($this->rows);
    }
}

/**
 * Prepared statement (positional ? and named :name placeholders).
 */
class InstallWizardMysqliStatement
{
    private $link;
    private $mysqli;
    private $stmt;
    private $namedOrder;
    private $bufferRows;
    private $bufferIdx;

    public function __construct($link, $mysqli, $stmt, $namedOrder)
    {
        $this->link = $link;
        $this->mysqli = $mysqli;
        $this->stmt = $stmt;
        $this->namedOrder = $namedOrder;
        $this->bufferRows = null;
        $this->bufferIdx = 0;
    }

    public function execute($params = null)
    {
        $this->bufferRows = null;
        $this->bufferIdx = 0;
        if ($params === null) {
            $params = array();
        }
        $bindValues = array();
        if (!empty($this->namedOrder)) {
            foreach ($this->namedOrder as $name) {
                $bindValues[] = array_key_exists($name, $params) ? $params[$name] : null;
            }
        } else {
            $bindValues = array_values($params);
        }

        // All 's' avoids mysqli bind edge cases with NULL/int across PHP versions; MySQL coerces as needed.
        $types = str_repeat('s', count($bindValues));

        if ($types !== '') {
            $refs = array($types);
            foreach ($bindValues as $k => $v) {
                $refs[] = &$bindValues[$k];
            }
            call_user_func_array(array($this->stmt, 'bind_param'), $refs);
        }

        if (!mysqli_stmt_execute($this->stmt)) {
            throw new Exception('mysqli_stmt_execute: ' . mysqli_stmt_error($this->stmt));
        }

        if (function_exists('mysqli_stmt_get_result')) {
            $res = mysqli_stmt_get_result($this->stmt);
            if ($res instanceof mysqli_result) {
                $this->bufferRows = mysqli_fetch_all($res, MYSQLI_ASSOC);
                if ($this->bufferRows === null) {
                    $this->bufferRows = array();
                }
                mysqli_free_result($res);
            }
        } else {
            $meta = mysqli_stmt_result_metadata($this->stmt);
            if ($meta) {
                $fields = mysqli_fetch_fields($meta);
                mysqli_free_result($meta);
                $row = array();
                $bind = array();
                foreach ($fields as $i => $f) {
                    $row[$f->name] = null;
                    $bind[] = &$row[$f->name];
                }
                call_user_func_array(array($this->stmt, 'bind_result'), $bind);
                $this->bufferRows = array();
                while (mysqli_stmt_fetch($this->stmt)) {
                    $copy = array();
                    foreach ($row as $k => $v) {
                        $copy[$k] = $v;
                    }
                    $this->bufferRows[] = $copy;
                }
            }
        }
    }

    public function fetch($mode = null)
    {
        if ($this->bufferRows === null) {
            return false;
        }
        if ($mode === null) {
            $mode = PDO::FETCH_ASSOC;
        }
        if (!isset($this->bufferRows[$this->bufferIdx])) {
            return false;
        }
        $row = $this->bufferRows[$this->bufferIdx];
        $this->bufferIdx++;
        if ($mode === PDO::FETCH_NUM) {
            return array_values($row);
        }
        return $row;
    }

    public function fetchColumn($columnIndex = 0)
    {
        $row = $this->fetch(PDO::FETCH_NUM);
        if ($row === false) {
            return false;
        }
        return isset($row[$columnIndex]) ? $row[$columnIndex] : false;
    }

    public function fetchAll($mode = null)
    {
        if ($this->bufferRows === null) {
            return array();
        }
        if ($mode === null) {
            $mode = PDO::FETCH_ASSOC;
        }
        if ($mode === PDO::FETCH_COLUMN) {
            $flat = array();
            for ($i = $this->bufferIdx; $i < count($this->bufferRows); $i++) {
                $r = $this->bufferRows[$i];
                $flat[] = is_array($r) ? reset($r) : null;
            }
            $this->bufferIdx = count($this->bufferRows);
            return $flat;
        }
        $out = array();
        for ($i = $this->bufferIdx; $i < count($this->bufferRows); $i++) {
            $row = $this->bufferRows[$i];
            if (!is_array($row)) {
                continue;
            }
            if ($mode === PDO::FETCH_NUM) {
                $out[] = array_values($row);
            } else {
                $out[] = $row;
            }
        }
        $this->bufferIdx = count($this->bufferRows);
        return $out;
    }

    public function rowCount()
    {
        return mysqli_stmt_affected_rows($this->stmt);
    }

    public function closeCursor()
    {
        $this->bufferRows = null;
        $this->bufferIdx = 0;
    }
}

/**
 * Main install-time DB link (mysqli).
 */
class InstallWizardMysqliLink
{
    private $mysqli;

    public function __construct($vars)
    {
        if (!extension_loaded('mysqli')) {
            throw new Exception('mysqli extension is required for the install wizard (WordPress-style DB layer).');
        }
        $host = isset($vars['host']) ? $vars['host'] : 'localhost';
        $port = isset($vars['port']) ? (int) $vars['port'] : 3306;
        $user = isset($vars['user']) ? $vars['user'] : '';
        $pass = isset($vars['password']) ? $vars['password'] : '';
        $name = isset($vars['name']) ? $vars['name'] : '';
        $charset = isset($vars['charset']) ? $vars['charset'] : 'utf8mb4';

        mysqli_report(MYSQLI_REPORT_OFF);
        $this->mysqli = mysqli_init();
        if (!$this->mysqli) {
            throw new Exception('mysqli_init failed.');
        }
        mysqli_options($this->mysqli, MYSQLI_OPT_CONNECT_TIMEOUT, 30);
        if (!mysqli_real_connect($this->mysqli, $host, $user, $pass, $name, $port)) {
            throw new Exception(mysqli_connect_error());
        }
        if (!mysqli_set_charset($this->mysqli, $charset)) {
            throw new Exception('mysqli_set_charset failed: ' . mysqli_error($this->mysqli));
        }
        mysqli_query($this->mysqli, "SET time_zone = '+00:00'");
    }

    public function getMysqli()
    {
        return $this->mysqli;
    }

    public function quote($value)
    {
        if ($value === null) {
            return 'NULL';
        }
        return "'" . mysqli_real_escape_string($this->mysqli, (string) $value) . "'";
    }

    private function drainMultiResults()
    {
        while (mysqli_more_results($this->mysqli)) {
            mysqli_next_result($this->mysqli);
            if ($r = mysqli_store_result($this->mysqli)) {
                mysqli_free_result($r);
            }
        }
    }

    public function exec($sql)
    {
        $this->drainMultiResults();
        if (!mysqli_query($this->mysqli, $sql)) {
            throw new Exception('mysqli exec: ' . mysqli_error($this->mysqli));
        }
        if ($r = mysqli_store_result($this->mysqli)) {
            mysqli_free_result($r);
        }
        $this->drainMultiResults();
        return true;
    }

    public function query($sql)
    {
        $this->drainMultiResults();
        $r = mysqli_query($this->mysqli, $sql);
        if ($r === false) {
            throw new Exception('mysqli query: ' . mysqli_error($this->mysqli));
        }
        if ($r === true) {
            return new InstallWizardMysqliBufferedResult(array());
        }
        if ($r instanceof mysqli_result) {
            if (function_exists('mysqli_fetch_all')) {
                $rows = mysqli_fetch_all($r, MYSQLI_ASSOC);
                if ($rows === null) {
                    $rows = array();
                }
            } else {
                $rows = array();
                while ($row = mysqli_fetch_assoc($r)) {
                    $rows[] = $row;
                }
            }
            mysqli_free_result($r);
            $this->drainMultiResults();
            return new InstallWizardMysqliBufferedResult($rows);
        }
        return new InstallWizardMysqliBufferedResult(array());
    }

    public function prepare($sql)
    {
        $namedOrder = array();
        $converted = preg_replace_callback('/:([a-zA-Z_][a-zA-Z0-9_]*)/', function ($m) use (&$namedOrder) {
            $namedOrder[] = $m[1];
            return '?';
        }, $sql);

        $stmt = mysqli_prepare($this->mysqli, $converted);
        if (!$stmt) {
            throw new Exception('mysqli_prepare: ' . mysqli_error($this->mysqli));
        }
        return new InstallWizardMysqliStatement($this, $this->mysqli, $stmt, $namedOrder);
    }
}
