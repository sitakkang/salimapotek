<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_obat extends CI_Model {

    protected $table = 'ms_obat';

    public function __construct() {
        parent::__construct();
    }

    public function get_all() {
        return $this->db->query(
            'SELECT id_obat, obat_name, obat_price, obat_satuan, obat_status
             FROM ms_obat
             ORDER BY id_obat DESC'
        );
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, array('id_obat' => intval($id)))->row();
    }

    public function insert($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        $this->db->where('id_obat', intval($id));
        $this->db->update($this->table, $data);
        return $this->db->affected_rows();
    }

    public function delete($id) {
        $this->db->where('id_obat', intval($id));
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }
}
