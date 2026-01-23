<?php
//****************************************************************************************/
// Library : Postgres_DB  :    version 1.0 (11/16/2025): Eric Gerdes ( lupopedia.com/craftysyntax/ )
//======================================================================================
/**  UPDATED TO USE native pg_* functions mirroring Mysql_DB
  *  Postgres DB class by Eric Gerdes:
  *
  *   This class is used to create a workable recordset connection with a PostgreSQL database.
  *   It mirrors the Mysql_DB interface so Crafty Syntax can switch drivers without touching
  *   higher-level code. It keeps the zero-dependency ethos while enabling full Postgres installs.
  *
  * BASIC PEAR LIKE EXAMPLE :
  *<code>
  *   $mydatabase = new Postgres_DB;
  *   $dsn = "pgsql://username:password@hostspec/database";
  *   $mydatabase->connect($dsn);
  *
  *   $query = "SELECT * FROM mytable";
  *   $result = $mydatabase->query($query);
  *   while($row = $result->fetchRow(DB_FETCHMODE_ASSOC) ){
  *      // do something with associative array $row[]
  *   }
  *   $query = "INSERT INTO mytable (this,that) VALUES ('something','somethingelse')";
  *   $mydatabase->query($query);
  *</code>
  *
  * ALTERNATIVE EXAMPLE :
  *<code>
  *   $mydatabase = new Postgres_DB;
  *   $mydatabase->connectdb($server,$user,$pass);
  *   $mydatabase->selectdb($dbase);
  *
  *   $query = "SELECT * FROM mytable";
  *   $rs = $mydatabase->getrecordset($query);
  *   while($rs->next()){
  *      $row = $rs->getCurrentValuesAsHash();
  *      // do something with associative array $row[]
  *   }
  *   $query = "INSERT INTO mytable (this,that) VALUES ('something','somethingelse')";
  *   $primary_key_id = $mydatabase->insert($query);
  *</code>
  */
// CLASS Postgres_DB FUNCTION LIST:
//      function Postgres_DB()                 - The constructor for this class.
//      function connect($dsn)                 - opens the database connection to a dsn
//      function connectdb($server,$user,$pass)- opens the database connection.
//      function getconnid()
//      function selectdb($dbase)              - selects out the database
//      function getrecordset($sql="")         - opens a record set and returns it.
//      function insert($sql="")               - inserts a row into the database.
//      function sql_query($sql="")            - run a general query.
//      function fetchRow($type)               - alias via Postgres_RS for next row.
//      function showdbs()                     - lists the databases for PostgreSQL
//      function showtables($dbname)           - lists the tables for the database in an array.
//      function error($text)                  - Shows the error message if any occur from sql query.
//      function close_connect()               - closes the connection.
//      function parseDSN($dsn)                - parses a DSN string (shared logic).
//
// CLASS Postgres_RS FUNCTION LIST:
//      function Postgres_RS($conn='')        - constructor for the class.
//      function setrecordset()               - set the recordset.
//      function next()                       - moves the recordset up one element.
//      function field()                      - return one element.
//      function getCurrentValuesAsHash()     - Returns an array of the current recordset row.
//      function numrows()                    - number of rows.
//      function fetchRow()                   - returns the next associative array row.

if (!defined('DB_FETCHMODE_ORDERED')) {
    define('DB_FETCHMODE_ORDERED', 1);
}
if (!defined('DB_FETCHMODE_ASSOC')) {
    define('DB_FETCHMODE_ASSOC', 2);
}
if (!defined('DB_DEBUG')) {
    define('DB_DEBUG', false);
}

class Postgres_DB
{
    var $HOST = '';
    var $PASS = '';
    var $USER = '';
    var $DATABASE = '';
    var $PORT = '';
    var $CONN = null;

    function Postgres_DB($host = 'localhost', $user = '', $pass = '')
    {
        $this->USER = $user;
        $this->PASS = $pass;
        $this->setHostAndPort($host);
    }

    function setHostAndPort($host)
    {
        if (strpos($host, ':') !== false) {
            list($hostOnly, $port) = explode(':', $host, 2);
            $this->HOST = $hostOnly;
            $this->PORT = $port;
        } else {
            $this->HOST = $host;
        }
    }

    function connectdb($host = '', $user = '', $pass = '')
    {
        if ($host != '') {
            $this->setHostAndPort($host);
        }
        if ($user != '') {
            $this->USER = $user;
        }
        if ($pass != '') {
            $this->PASS = $pass;
        }

        $conn = $this->openConnection($this->DATABASE);
        if (!$conn) {
            $this->error("Postgres connection failed");
            return false;
        }
        $this->CONN = $conn;
        return true;
    }

    function connect($dsn)
    {
        $dsn_parsed = $this->parseDSN($dsn);
        if ($dsn_parsed['hostspec']) {
            $host = $dsn_parsed['hostspec'];
            if (!empty($dsn_parsed['port'])) {
                $host .= ':' . $dsn_parsed['port'];
            }
            $this->setHostAndPort($host);
        }
        if (!empty($dsn_parsed['username'])) {
            $this->USER = $dsn_parsed['username'];
        }
        if (!empty($dsn_parsed['password'])) {
            $this->PASS = $dsn_parsed['password'];
        }
        if (!empty($dsn_parsed['database'])) {
            $this->DATABASE = $dsn_parsed['database'];
        }

        if ($this->CONN == "") {
            $this->connectdb();
        }
    }

    function getconnid()
    {
        if ($this->CONN) {
            return $this->CONN;
        } else {
            return false;
        }
    }

    function selectdb($dbase)
    {
        if (empty($dbase)) {
            return;
        }
        $this->DATABASE = $dbase;
        if ($this->CONN) {
            $this->close_connect();
        }
        $this->connectdb();
    }

    function query($sql = "", $debug = DB_DEBUG)
    {
        if (empty($sql)) {
            if ($debug) {
                $fa = fopen("sqllog.txt", 'a+');
                fwrite($fa, date("YmdHis") . " " . $_SERVER['PHP_SELF'] . " $sql\n", 1000);
                fclose($fa);
            }
            exit;
        }

        if ($debug) {
            $fa = fopen("sqllog.txt", 'a+');
            fwrite($fa, date("YmdHis") . " " . $_SERVER['PHP_SELF'] . " $sql\n", 1000);
            fclose($fa);
        }

        if (preg_match("/^SELECT/i", $sql)) {
            return $this->getrecordset($sql);
        } elseif (preg_match("/^INSERT/i", $sql)) {
            return $this->insert($sql);
        } else {
            return $this->sql_query($sql);
        }
    }

    function getrecordset($sql = "")
    {
        if (empty($sql)) {
            $this->error("No SQL query sent to getrecordset");
        }
        if (empty($this->CONN)) {
            $this->error("No Postgres Connection");
        }
        $conn = $this->CONN;
        $rs = new Postgres_RS;
        $rs->setrecordset($conn, $sql);
        return $rs;
    }

    function insert($sql = "")
    {
        if (empty($sql)) {
            return false;
        }
        if (!preg_match("/^INSERT/i", $sql)) {
            return false;
        }
        if (empty($this->CONN)) {
            echo "<H2>No connection!</H2>\n";
            return false;
        }
        $conn = $this->CONN;
        $results = pg_query($conn, $sql);
        if (!$results) {
            return false;
        }

        $idResult = pg_query($conn, "SELECT LASTVAL() AS insert_id");
        if ($idResult) {
            $row = pg_fetch_assoc($idResult);
            pg_free_result($idResult);
            if ($row && isset($row['insert_id'])) {
                return $row['insert_id'];
            }
        }
        return true;
    }

    function sql_query($sql = "")
    {
        if (empty($sql)) {
            return false;
        }
        if (empty($this->CONN)) {
            return false;
        }
        $conn = $this->CONN;
        $results = pg_query($conn, $sql);
        if (!$results) {
            return false;
        }
        return $results;
    }

    function showdbs()
    {
        $sql = "SELECT datname FROM pg_database WHERE datistemplate = false ORDER BY datname";
        $result = pg_query($this->CONN, $sql);
        $data = array();
        if ($result) {
            while ($row = pg_fetch_row($result)) {
                $data[] = $row[0];
            }
            pg_free_result($result);
        }
        return $data;
    }

    function showtables($dbname)
    {
        if ($dbname && $dbname !== $this->DATABASE) {
            $this->selectdb($dbname);
        }
        $sql = "SELECT tablename FROM pg_catalog.pg_tables WHERE schemaname NOT IN ('pg_catalog','information_schema')";
        $results = pg_query($this->CONN, $sql);
        $data = array();
        if (!$results) {
            exit;
        }
        while ($row = pg_fetch_row($results)) {
            $data[] = $row[0];
        }
        pg_free_result($results);
        return $data;
    }

    function error($text)
    {
        $msg = pg_last_error($this->CONN);
        echo "[$text] ( $msg )<BR>\n";
        exit;
    }

    function close_connect()
    {
        if ($this->CONN) {
            @pg_close($this->CONN);
            $this->CONN = null;
        }
    }

    function parseDSN($dsn)
    {
        // Same implementation as mysqli_db.php for compatibility
        $parsed = array(
            'phptype'  => false,
            'dbsyntax' => false,
            'username' => false,
            'password' => false,
            'protocol' => false,
            'hostspec' => false,
            'port'     => false,
            'socket'   => false,
            'database' => false,
        );

        if (is_array($dsn)) {
            $dsn = array_merge($parsed, $dsn);
            if (!$dsn['dbsyntax']) {
                $dsn['dbsyntax'] = $dsn['phptype'];
            }
            return $dsn;
        }

        if (($pos = strpos($dsn, '://')) !== false) {
            $str = substr($dsn, 0, $pos);
            $dsn = substr($dsn, $pos + 3);
        } else {
            $str = $dsn;
            $dsn = null;
        }

        if (preg_match('|^(.+?)\((.*?)\)$|', $str, $arr)) {
            $parsed['phptype']  = $arr[1];
            $parsed['dbsyntax'] = !$arr[2] ? $arr[1] : $arr[2];
        } else {
            $parsed['phptype']  = $str;
            $parsed['dbsyntax'] = $str;
        }

        if (!count($dsn)) {
            return $parsed;
        }

        if (($at = strrpos($dsn, '@')) !== false) {
            $str = substr($dsn, 0, $at);
            $dsn = substr($dsn, $at + 1);
            if (($pos = strpos($str, ':')) !== false) {
                $parsed['username'] = rawurldecode(substr($str, 0, $pos));
                $parsed['password'] = rawurldecode(substr($str, $pos + 1));
            } else {
                $parsed['username'] = rawurldecode($str);
            }
        }

        if (preg_match('|^([^(]+)\((.*?)\)/?(.*?)$|', $dsn, $match)) {
            $proto       = $match[1];
            $proto_opts  = $match[2] ? $match[2] : false;
            $dsn         = $match[3];
        } else {
            if (strpos($dsn, '+') !== false) {
                list($proto, $dsn) = explode('+', $dsn, 2);
            }
            if (strpos($dsn, '/') !== false) {
                list($proto_opts, $dsn) = explode('/', $dsn, 2);
            } else {
                $proto_opts = $dsn;
                $dsn = null;
            }
        }

        $parsed['protocol'] = (!empty($proto)) ? $proto : 'tcp';
        $proto_opts = rawurldecode($proto_opts);
        if ($parsed['protocol'] == 'tcp') {
            if (strpos($proto_opts, ':') !== false) {
                list($parsed['hostspec'], $parsed['port']) = explode(':', $proto_opts);
            } else {
                $parsed['hostspec'] = $proto_opts;
            }
        } elseif ($parsed['protocol'] == 'unix') {
            $parsed['socket'] = $proto_opts;
        }

        if ($dsn) {
            if (($pos = strpos($dsn, '?')) === false) {
                $parsed['database'] = $dsn;
            } else {
                $parsed['database'] = substr($dsn, 0, $pos);
                $dsn = substr($dsn, $pos + 1);
                if (strpos($dsn, '&') !== false) {
                    $opts = explode('&', $dsn);
                } else {
                    $opts = array($dsn);
                }
                foreach ($opts as $opt) {
                    list($key, $value) = explode('=', $opt);
                    if (!isset($parsed[$key])) {
                        $parsed[$key] = rawurldecode($value);
                    }
                }
            }
        }

        return $parsed;
    }

    function openConnection($database = '')
    {
        $parts = array();
        if (!empty($this->HOST)) {
            $parts[] = "host=" . $this->HOST;
        }
        if (!empty($this->PORT)) {
            $parts[] = "port=" . $this->PORT;
        }
        if (!empty($database)) {
            $parts[] = "dbname=" . $database;
        }
        if (!empty($this->USER)) {
            $parts[] = "user=" . $this->USER;
        }
        if (!empty($this->PASS)) {
            $parts[] = "password=" . $this->PASS;
        }
        $connString = implode(' ', $parts);
        return @pg_connect($connString);
    }
}

class Postgres_RS
{
    var $CONN = null;
    var $RECORDSET = array();
    var $QID = null;

    function Postgres_RS($conn = '')
    {
        $this->CONN = $conn;
    }

    function setrecordset($conn, $sql)
    {
        $this->CONN = $conn;
        $this->QID = pg_query($conn, $sql);
        if ((!$this->QID) or (empty($this->QID))) {
            $this->error($sql);
            return false;
        }
        return true;
    }

    function next($fetchmode = DB_FETCHMODE_ASSOC)
    {
        if ($fetchmode == DB_FETCHMODE_ORDERED) {
            $this->RECORDSET = pg_fetch_array($this->QID, null, PGSQL_NUM);
        } else {
            $this->RECORDSET = pg_fetch_array($this->QID, null, PGSQL_ASSOC);
        }

        return $this->RECORDSET;
    }

    function field($fieldname)
    {
        return $this->RECORDSET[$fieldname];
    }

    function getCurrentValuesAsHash()
    {
        return $this->RECORDSET;
    }

    function fetchRow($fetchmode = DB_FETCHMODE_ASSOC)
    {
        $this->next($fetchmode);
        return $this->RECORDSET;
    }

    function numrows()
    {
        if ($this->QID) {
            return pg_num_rows($this->QID);
        } else {
            return 0;
        }
    }

    function error($text)
    {
        $msg = pg_last_error($this->CONN);
        echo "[$text] ( $msg )<BR>\n";
        exit;
    }
}
?>

