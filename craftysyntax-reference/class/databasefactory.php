<?php
//****************************************************************************************/
// Library : DatabaseFactory  :    version 1.0 (11/16/2025): Eric Gerdes ( lupopedia.com/craftysyntax/ )
//======================================================================================

class DatabaseFactory
{
    var $db = null;
    var $type = 'mysql';

    function DatabaseFactory($type = 'mysql', $host = 'localhost', $user = '', $pass = '', $database = '')
    {
        $this->type = strtolower($type);

        if ($this->type === 'mysql') {
            $this->db = new MySQL_DB($host, $user, $pass);
        } elseif ($this->type === 'postgres') {
            $this->db = new Postgres_DB($host, $user, $pass);
        } else {
            $this->error("Unsupported DB type: $type");
        }

        if ($this->db) {
            $this->db->connectdb();
            if (!empty($database)) {
                $this->db->selectdb($database);
            }
        }
    }

    function query($sql = "", $debug = DB_DEBUG)
    {
        $sql = $this->adjustQuery($sql);
        return $this->db->query($sql, $debug);
    }

    function insert($sql = "")
    {
        $sql = $this->adjustQuery($sql);
        return $this->db->insert($sql);
    }

    function getrecordset($sql = "")
    {
        $sql = $this->adjustQuery($sql);
        return $this->db->getrecordset($sql);
    }

    function sql_query($sql = "")
    {
        $sql = $this->adjustQuery($sql);
        return $this->db->sql_query($sql);
    }

    function adjustQuery($sql)
    {
        if (empty($sql)) {
            return $sql;
        }

        if ($this->type === 'postgres') {
            $sql = str_replace('`', '"', $sql);
            $sql = preg_replace(
                '/DATE_ADD\(([^,]+),\s*INTERVAL\s*(\d+)\s*(\w+)\)/i',
                '$1 + INTERVAL \'$2 $3\'',
                $sql
            );
        } elseif ($this->type === 'mysql') {
            $sql = preg_replace('/"(\w+)"/', '`$1`', $sql);
        }

        return $sql;
    }

    function __call($method, $args)
    {
        if (!method_exists($this->db, $method)) {
            $this->error("Call to undefined method {$method}");
        }

        return call_user_func_array(array($this->db, $method), $args);
    }

    function error($text)
    {
        echo "[DatabaseFactory] $text<BR>\n";
        exit;
    }
}
?>

