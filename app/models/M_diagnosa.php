<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_diagnosa extends CI_Model {

    protected $table = 'ms_diagnosa';

    public function __construct() {
        parent::__construct();
    }

    public function get_all() {
        return $this->db->query(
            'SELECT id_diagnosa, dgn_name, dgn_cat, dgn_status, dgn_insert_dt, dgn_insert_by
             FROM ms_diagnosa
             ORDER BY id_diagnosa DESC'
        );
    }

    public function get_by_id($id) {
        return $this->db->get_where($this->table, array('id_diagnosa' => intval($id)))->row();
    }

    public function insert($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        $this->db->where('id_diagnosa', intval($id));
        $this->db->update($this->table, $data);
        return $this->db->affected_rows();
    }

    public function delete($id) {
        $this->db->where('id_diagnosa', intval($id));
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }
}
