<?php
namespace App\model;

use Candy\mvc\Model;
use PDO;
class UserModel extends Model
{
    public function get($email)
    {
        $field = (filter_var($email, FILTER_VALIDATE_EMAIL)) ? 'mail' : 'name';
        $value = $this->db->quote($email);
        $sql = 'SELECT * FROM '.$this->config['table_prefix'].'users Where '.$field.' = '.$value;
        $query = $this->db->query($sql,PDO::FETCH_ASSOC);
        return $query->fetch();
    }
    public function update($uid)
    {
        # code...
    }
}
