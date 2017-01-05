<?php
namespace Candy\mvc;

abstract class Model
{
    protected $db;
    public function __construct()
    {
        $database = array(
            'dbms' => 'mysql',
            'host' => 'localhost',
            'port' => '3306',
            'dbname' => 'spark',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        );
        $this->db = new DB_PDO();
        $this->db->connect($database);
    }
}
