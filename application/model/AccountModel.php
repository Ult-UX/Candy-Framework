<?php
namespace App\model;

use Candy\framework\Model;
use PDO;

class AccountModel extends Model
{
    public function get($email)
    {
        $field = (filter_var($email, FILTER_VALIDATE_EMAIL)) ? 'mail' : 'name';
        $value = $this->db->quote($email);
        $sql = 'SELECT * FROM '.$this->config['table_prefix'].'accounts WHERE '.$field.' = '.$value;
        $query = $this->db->query($sql, PDO::FETCH_ASSOC);
        return $query->fetch();
    }
    public function update($id, $data)
    {
        $set = array();
        foreach ($data as $key=>$value) {
            $set[] = $key.'='.$this->db->quote($value);
        }
        $set = implode(', ', $set);
        $sql = 'UPDATE '.$this->config['table_prefix'].'accounts SET '.$set.' WHERE id = :id';
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $this->db->commit();
        } catch (Exception $e) {
            $this->db->rollBack();
            echo "Failed: " . $e->getMessage();
        }
    }
}
