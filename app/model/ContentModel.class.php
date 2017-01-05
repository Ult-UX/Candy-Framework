<?php
namespace APP\model;

use Spark\mvc\Model;

class ContentModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function getContents($where, $offset, $limit = 5, $order=null)
    {
        $this->db->from('typecho_contents');
        $this->db->where($where);
        $result['quantity'] = $this->db->count();
        $this->db->order_by($order);
        $this->db->limit($limit, ($offset-1)*$limit);
        $query = $this->db->get();
        $result['contents'] = $query->result_array();
        return $result;
    }
    public function getContentsByMeta($mid,$where, $offset, $limit = 5, $order=null)
    {
        $this->db->from('typecho_relationships');
        $this->db->join('typecho_contents', 'typecho_contents.cid = typecho_relationships.cid');
        $this->db->where('mid', $mid);
        $result['quantity'] = $this->db->count();
        $this->db->order_by($order);
        $this->db->limit($limit, ($offset-1)*$limit);
        $query = $this->db->get();
        $result['contents'] = $query->result_array();
        return $result;
    }
    public function getBySlug($slug)
    {
        $this->db->where('slug', $slug);
        $query = $this->db->get('typecho_contents');
        $result = $query->result();
        return $result;
    }
    public function search($keywords, $offset, $limit = 5, $order=null)
    {
        $this->db->from('typecho_contents');
        foreach ($keywords as $keyword) {
            $this->db->group_start();
            $this->db->like('title', '%'.$keyword.'%');
            $this->db->or_like('text', '%'.$keyword.'%');
            $this->db->group_end();
        }
        $result['quantity'] = $this->db->count();
        $this->db->order_by($order);
        $this->db->limit($limit, ($offset-1)*$limit);
        $query = $this->db->get();
        $result['contents'] = $query->result_array();
        return $result;
    }
    public function create($data)
    {
        // var_dump($data);
        $this->db->trans_start();
        $this->db->insert('typecho_contents', $data);
        $this->db->get_compiled_insert();
        $this->db->trans_complete();
    }
}
