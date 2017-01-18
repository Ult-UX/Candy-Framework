<?php
namespace Candy\framework;

use Candy\component\Config;
use Candy\bundle\QueryBuilder;
use PDO;

/**
 * Model Abstract Class
 *
 * @package     Candy Framework
 * @subpackage  Candy\mvc
 * @category    MVC
 * @author  ult-ux@outook.com
 * @link    http://ultux.com
 */
abstract class Model
{
    protected $config;
    protected $db;
    protected $builder;
    public function __construct()
    {
        $config = new Config();
        $config->load('database');
        $this->config = $config->database->get();
        $dsn = $this->config['dbms'].':host='.$this->config['host'].';dbname='.$this->config['dbname'].';port='.$this->config['port'].';charset='.$this->config['charset'];
        try {
            $this->db = new PDO($dsn, $this->config['username'], $this->config['password']);
            $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Error!: " . $e->getMessage());
        }
        $this->builder = new QueryBuilder();
    }
}
