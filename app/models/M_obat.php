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

    /**
     * Hitung total obat — untuk recordsTotal DataTables
     */
    public function count_all() {
        return $this->db->count_all($this->table);
    }

    /**
     * Hitung total obat setelah filter pencarian — untuk recordsFiltered
     */
    public function count_filtered($search) {
        $this->db->from($this->table);
        $this->apply_search($search);
        return $this->db->count_all_results();
    }

    /**
     * Ambil data obat ter-pagination + filter untuk DataTables server-side
     */
    public function get_datatables($search, $order_col = '', $order_dir = 'ASC', $start = 0, $length = 10) {
        $this->db->select('id_obat, obat_name, obat_price, obat_satuan, obat_status');
        $this->db->from($this->table);
        $this->apply_search($search);

        if (!empty($order_col)) {
            $this->db->order_by($order_col, $order_dir);
        } else {
            $this->db->order_by('id_obat', 'DESC');
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
        $this->db->like('obat_name', $search);
        $this->db->or_like('obat_satuan', $search);
        $this->db->group_end();
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
