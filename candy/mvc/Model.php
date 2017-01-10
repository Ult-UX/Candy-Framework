<?php
namespace Candy\mvc;

use Candy\core\Config;
use PDO;

abstract class Model
{
    protected $config;
    protected $db;
    public function __construct()
    {
        $config = new Config();
        $config->load('database');
        $this->config = $config->database->get();
        $dsn = $this->config['dbms'].':host='.$this->config['host'].';dbname='.$this->config['dbname'].';port='.$this->config['port'].';charset='.$this->config['charset'];
        try {
            $this->db = new PDO($dsn, $this->config['username'], $this->config['password'], array(
                PDO::ATTR_PERSISTENT => true
            ));
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error!: " . $e->getMessage());
        }
    }
}
