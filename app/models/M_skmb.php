<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_skmb extends CI_Model {

    protected $table = 'skmb';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get all SKMB records for DataTable
     */
    public function get_all() {
        return $this->db->query(
            'SELECT id, patient_name, nik, bagian, company_name, pengantar, nik_pengantar,
                    company_pengantar, hubungan, tgl_datang, jam,
                    docdate, doct_by_name, docnumb, insertby, insertdt
             FROM skmb
             ORDER BY insertdt DESC'
        );
    }

    /**
     * Hitung total SKMB — untuk recordsTotal DataTables
     */
    public function count_all() {
        return $this->db->count_all($this->table);
    }

    /**
     * Hitung total SKMB setelah filter pencarian — untuk recordsFiltered
     */
    public function count_filtered($search) {
        $this->db->from($this->table);
        $this->apply_search($search);
        return $this->db->count_all_results();
    }

    /**
     * Ambil data SKMB ter-pagination + filter untuk DataTables server-side
     */
    public function get_datatables($search, $order_col = '', $order_dir = 'ASC', $start = 0, $length = 10) {
        $this->db->select('id, patient_name, nik, bagian, company_name, pengantar, nik_pengantar,
                           company_pengantar, hubungan, tgl_datang, jam,
                           docdate, doct_by_name, docnumb, insertby, insertdt');
        $this->db->from($this->table);
        $this->apply_search($search);

        if (!empty($order_col)) {
            $this->db->order_by($order_col, $order_dir);
        } else {
            $this->db->order_by('insertdt', 'DESC');
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
        $this->db->like('patient_name', $search);
        $this->db->or_like('nik', $search);
        $this->db->or_like('company_name', $search);
        $this->db->or_like('pengantar', $search);
        $this->db->or_like('docnumb', $search);
        $this->db->or_like('doct_by_name', $search);
        $this->db->group_end();
    }

    /**
     * Get all doctors
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
     * Get single SKMB by ID
     */
    public function get_by_id($id) {
        return $this->db->get_where($this->table, array('id' => intval($id)))->row();
    }

    /**
     * Get SKMB by ID with doctor join
     */
    public function get_skmb_by_id($id) {
        $this->db->select('skmb.*, doctor.fullname AS doct_name, creator.fullname AS insert_name, updater.fullname AS update_name');
        $this->db->from('skmb');
        $this->db->join('conf_users AS doctor', 'skmb.doct_by_id = doctor.id_user', 'left');
        $this->db->join('conf_users AS creator', 'skmb.insertby = creator.id_user', 'left');
        $this->db->join('conf_users AS updater', 'skmb.updateby = updater.id_user', 'left');
        $this->db->where('skmb.id', $id);
        return $this->db->get()->row();
    }

    /**
     * Insert new SKMB
     */
    public function insert($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Update existing SKMB
     */
    public function update($id, $data) {
        $this->db->where('id', intval($id));
        $this->db->update($this->table, $data);
        return $this->db->affected_rows();
    }

    /**
     * Delete SKMB by ID
     */
    public function delete($id) {
        $this->db->where('id', intval($id));
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }

    /**
     * Check if docnumb already exists (for validation)
     */
    public function is_docnumb_unique($docnumb, $exclude_id = 0) {
        $this->db->where('docnumb', $docnumb);
        if ($exclude_id > 0) {
            $this->db->where('id !=', intval($exclude_id));
        }
        return $this->db->get($this->table)->num_rows() === 0;
    }

    /**
     * Generate nomor dokumen SKMB (default 00000, nomor diisi manual)
     * Format: 00000/SKMB/VII/2026
     */
    public function generate_docnumb() {
        return '00000/SKMB/' . $this->month_roman(date('n')) . '/' . date('Y');
    }

    /**
     * Konversi angka bulan (1–12) ke Romawi
     */
    private function month_roman($n) {
        $map = array(
            1  => 'I',   2  => 'II',  3  => 'III', 4  => 'IV',
            5  => 'V',   6  => 'VI',  7  => 'VII', 8  => 'VIII',
            9  => 'IX',  10 => 'X',   11 => 'XI',  12 => 'XII',
        );
        return isset($map[$n]) ? $map[$n] : '';
    }
}
