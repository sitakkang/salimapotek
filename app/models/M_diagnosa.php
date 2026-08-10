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

    /**
     * Hitung total diagnosa — untuk recordsTotal DataTables
     */
    public function count_all() {
        return $this->db->count_all($this->table);
    }

    /**
     * Hitung total diagnosa setelah filter pencarian — untuk recordsFiltered
     */
    public function count_filtered($search) {
        $this->db->from($this->table);
        $this->apply_search($search);
        return $this->db->count_all_results();
    }

    /**
     * Ambil data diagnosa ter-pagination + filter untuk DataTables server-side
     */
    public function get_datatables($search, $order_col = '', $order_dir = 'ASC', $start = 0, $length = 10) {
        $this->db->select('id_diagnosa, dgn_name, dgn_cat, dgn_status, dgn_insert_dt, dgn_insert_by');
        $this->db->from($this->table);
        $this->apply_search($search);

        if (!empty($order_col)) {
            $this->db->order_by($order_col, $order_dir);
        } else {
            $this->db->order_by('id_diagnosa', 'DESC');
        }

        if (intval($length) > 0) {
            $this->db->limit(intval($length), intval($start));
        }

        return $this->db->get();
    }

    /**
     * Terapkan pencarian global DataTables ke query (pakai GROUP untuk OR antar kolom)
     */
    private function apply_search($search) {
        if (empty($search)) return;
        $search = trim($search);

        $this->db->group_start();
        $this->db->like('dgn_cat', $search);
        $this->db->or_like('dgn_name', $search);
        $this->db->group_end();
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
