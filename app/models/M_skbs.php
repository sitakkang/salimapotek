<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_skbs extends CI_Model {

    protected $table = 'trans_skbs';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Generate nomor dokumen SKBS (default 00000, nomor diisi manual)
     */
    public function generate_docnumb_skbs() {
        return '00000/SKBS/PDUKRWP-SAC/' . $this->month_roman(date('n')) . '/' . date('Y');
    }

    private function month_roman($n) {
        $map = array(1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
                     7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII');
        return isset($map[$n]) ? $map[$n] : '';
    }

    /**
     * Get all SKBS records for DataTable (only active)
     */
    public function get_all() {
        return $this->db->query(
            'SELECT id_skbs, visit_id, skbs_patient_name, skbs_patient_nik,
                    skbs_patient_ktp, skbs_patient_age,
                    skbs_result_id, skbs_result_name,
                    skbs_desc, skbs_note,
                    skbs_blood_press, skbs_pulse, skbs_respirasi, skbs_temp,
                    skbs_bw, skbs_tb, skbs_bb,
                    skbs_r, skbs_l,
                    skbs_doc_date, skbs_doct_id, skbs_doct_name,
                    skbs_status, insert_dt, insert_by
             FROM trans_skbs
             WHERE skbs_status = 1
             ORDER BY insert_dt DESC'
        );
    }

    /**
     * Hitung total SKBS aktif (tanpa filter) — untuk recordsTotal DataTables
     */
    public function count_all() {
        $this->db->where('skbs_status', 1);
        return $this->db->count_all_results($this->table);
    }

    /**
     * Hitung total SKBS aktif setelah filter pencarian — untuk recordsFiltered
     */
    public function count_filtered($search) {
        $this->db->from($this->table);
        $this->db->where('skbs_status', 1);
        $this->apply_search($search);
        return $this->db->count_all_results();
    }

    /**
     * Ambil data SKBS ter-pagination + filter untuk DataTables server-side
     */
    public function get_datatables($search, $order_col = '', $order_dir = 'ASC', $start = 0, $length = 10) {
        $this->db->select('id_skbs, visit_id, skbs_patient_name, skbs_patient_nik,
                           skbs_patient_ktp, skbs_patient_age,
                           skbs_result_id, skbs_result_name,
                           skbs_desc, skbs_note,
                           skbs_blood_press, skbs_pulse, skbs_respirasi, skbs_temp,
                           skbs_bw, skbs_tb, skbs_bb,
                           skbs_r, skbs_l,
                           skbs_doc_date, skbs_doct_id, skbs_doct_name,
                           skbs_status, insert_dt, insert_by');
        $this->db->from($this->table);
        $this->db->where('skbs_status', 1);
        $this->apply_search($search);

        // Kolom yang boleh di-sort harus sudah dipetakan di controller
        if (!empty($order_col)) {
            $this->db->order_by($order_col, $order_dir);
        } else {
            $this->db->order_by('insert_dt', 'DESC');
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
        $this->db->like('skbs_patient_name', $search);
        $this->db->or_like('skbs_patient_nik', $search);
        $this->db->or_like('skbs_result_name', $search);
        $this->db->or_like('skbs_doct_name', $search);
        $this->db->or_like('skbs_doc_date', $search);
        $this->db->group_end();
    }

    /**
     * Get single SKBS by ID
     */
    public function get_by_id($id) {
        return $this->db->get_where($this->table, array('id_skbs' => intval($id)))->row();
    }

    /**
     * Get SKBS by ID with audit info
     */
    public function get_skbs_by_id($id) {
        $this->db->select('trans_skbs.*, 
                           creator.fullname AS insert_name,
                           updater.fullname AS update_name');
        $this->db->from('trans_skbs');
        $this->db->join('conf_users AS creator', 'trans_skbs.insert_by = creator.id_user', 'left');
        $this->db->join('conf_users AS updater', 'trans_skbs.update_by = updater.id_user', 'left');
        $this->db->where('trans_skbs.id_skbs', intval($id));
        return $this->db->get()->row();
    }

    /**
     * Get all doctors for dropdown (level 3 = dokter, status aktif)
     */
    public function get_all_doctor() {
        return $this->db->query(
            'SELECT *
             FROM conf_users
             WHERE level = 3 and status = 1
             ORDER BY fullname DESC'
        );
    }

    /**
     * Insert new SKBS
     */
    public function insert($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Update existing SKBS
     */
    public function update($id, $data) {
        $this->db->where('id_skbs', intval($id));
        $this->db->update($this->table, $data);
        return $this->db->affected_rows();
    }

    /**
     * Soft delete SKBS by ID (skbs_status = 0)
     */
    public function delete($id) {
        $this->db->where('id_skbs', intval($id));
        $this->db->update($this->table, array('skbs_status' => 0));
        return $this->db->affected_rows();
    }
}
