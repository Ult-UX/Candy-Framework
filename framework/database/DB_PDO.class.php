<?php
namespace Spark\core\database;

use Spark\core\database\SQLBuilder;

class DB_PDO extends SQLBuilder
{
    private $stmt = false;
    public function connect($database)
    {
        $dsn = $database['dbms'].':host='.$database['host'].';dbname='.$database['dbname'].';port='.$database['port'].';charset='.$database['charset'];
        try {
            //初始化一个PDO对象
            $this->db = new \PDO($dsn, $database['username'], $database['password'], array(\PDO::ATTR_PERSISTENT => true));
            // 设置 PDO 错误模式为异常
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error!: " . $e->getMessage() . "<br/>");
        }
    }
    public function get($tables = null, $release = true)
    {
        if ($tables !== null) {
            $this->from($tables);
        }
        $sql = $this->set_SQL($release);
        $this->stmt = $this->db->query($sql);
        if (!$this->stmt) {
            return false;
        }
        return $this;
    }
    public function count($tables = null, $release = false)
    {
        if ($this->stmt == false) {
            $this->get($tables, $release);
        }
        return $this->stmt->rowCount();
    }
    public function result_array()
    {
        return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function result_obj()
    {
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);
    }
    public function result($obj = false)
    {
        if ($obj) {
            return $this->stmt->fetch(\PDO::FETCH_OBJ);
        } else {
            return $this->stmt->fetch(\PDO::FETCH_ASSOC);
        }
    }
    public function insert($table = null, $data)
    {
        $sql = $this->get_compiled_insert($table, $data);
        $this->stmt = $this->db->prepare($sql);
        $this->stmt->execute();
        var_dump($this->db->lastInsertId());
    }

    public function trans_start()
    {
        $this->db->beginTransaction();
    }
    public function get_compiled_insert($table, $data)
    {
        $keys = implode(',', array_keys($data));
        $values = implode(',', array_map(function ($value) {
            return $this->db->quote($value);
        }, array_values($data)));
        $sql = "INSERT INTO $table ($keys) VALUES ($values)";
        return $sql;
    }
    public function trans_complete()
    {
        $this->db->commit();
    }
}
