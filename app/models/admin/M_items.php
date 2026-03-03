<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_items extends CI_Model {

    public function __construct(){
        parent::__construct();
    }

    // Get all items
    function get_all_items($search = null)
    {
        $this->db->select('id as id_item, kodeitem, namaitem, hargasatuan, deskripsi, itemimage');
        $this->db->from('items');
        
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('namaitem', $search);
            $this->db->or_like('kodeitem', $search);
            $this->db->group_end();
        }
        
        $this->db->order_by('id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    // Get item by ID
    function get_item_by_id($id)
    {
        $query = $this->db->query('SELECT id, kodeitem, namaitem, hargasatuan, deskripsi, itemimage, createby, updateby, createdt, updatedt FROM items WHERE id = ? LIMIT 1', array($id));
        return $query->row();
    }

    // Get item by kode
    function get_item_by_kode($kodeitem)
    {
        $query = $this->db->query('SELECT id, kodeitem, namaitem, hargasatuan, deskripsi, itemimage FROM items WHERE kodeitem = ? LIMIT 1', array($kodeitem));
        return $query->row();
    }

    // List items for dropdown/select
    function list_items($data = null)
    {
        $query = $this->db->query('SELECT id, kodeitem, namaitem, hargasatuan FROM items ORDER BY namaitem ASC');
        if(empty($data)){
            foreach($query->result() as $item) {
                echo '<option value="'.$item->id.'">'.$item->kodeitem.' - '.$item->namaitem.'</option>';
            }
        }else{
            foreach($query->result() as $item) {
                if($data == $item->id){
                    echo '<option value="'.$item->id.'" selected="selected">'.$item->kodeitem.' - '.$item->namaitem.'</option>';
                }else{
                    echo '<option value="'.$item->id.'">'.$item->kodeitem.' - '.$item->namaitem.'</option>';
                }
            }
        }
    }

    // Insert new item
    function insert_item($data)
    {
        $this->db->insert('items', $data);
        return $this->db->insert_id();
    }

    // Update item
    function update_item($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('items', $data);
    }

    // Delete item
    function delete_item($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('items');
    }

    // Check if kodeitem exists
    function is_kodeitem_exists($kodeitem, $exclude_id = null)
    {
        $this->db->where('kodeitem', $kodeitem);
        if($exclude_id) {
            $this->db->where('id !=', $exclude_id);
        }
        $query = $this->db->get('items');
        return $query->num_rows() > 0;
    }

    // Get total items count
    function count_items()
    {
        return $this->db->count_all('items');
    }
}
