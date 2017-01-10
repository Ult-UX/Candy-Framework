<?php
namespace APP\model;

use Spark\mvc\Model;

class MetaModel extends Model
{
    public function getBySlug($slug)
    {
        $this->db->where('slug', $slug);
        $query = $this->db->get('typecho_metas');
        $result = $query->result();
        return $result;
    }
    public function getMetasByContent($cid, $type='category')
    {
        $this->db->from('typecho_metas');
        $this->db->join('typecho_relationships', 'typecho_relationships.mid = typecho_metas.mid');
        $this->db->where('cid', $cid);
        $this->db->where('type', $type);
        $query = $this->db->get();
        $result = $query->result_array();
        return $result;
    }
}