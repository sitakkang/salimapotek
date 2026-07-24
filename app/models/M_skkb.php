<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_skkb extends CI_Model {

    protected $table = 'skkb';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get all SKKB records for DataTable
     */
    public function get_all() {
        return $this->db->query(
            'SELECT id, patient_name, age, nik, company_name, bagian, jabatan,
                    catatan, docdate, doctby, docnumb, insertby, insertdt
             FROM skkb
             ORDER BY insertdt DESC'
        );
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
     * Get single SKKB by ID
     */
    public function get_by_id($id) {
        return $this->db->get_where($this->table, array('id' => intval($id)))->row();
    }

    /**
     * Get SKKB by ID with doctor join
     */
    public function get_skkb_by_id($id) {
        $this->db->select();
        $this->db->from('skkb');
        $this->db->join('conf_users', 'skkb.doctby = conf_users.id_user', 'left');
        $this->db->where('skkb.id', $id);
        return $this->db->get()->row();
    }

    /**
     * Insert new SKKB
     */
    public function insert($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Update existing SKKB
     */
    public function update($id, $data) {
        $this->db->where('id', intval($id));
        $this->db->update($this->table, $data);
        return $this->db->affected_rows();
    }

    /**
     * Delete SKKB by ID
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
     * Generate nomor dokumen otomatis (running number per bulan)
     * Format: 00001/SKKB/VII/2026
     */
    public function generate_docnumb() {
        $month = date('n');
        $year  = date('Y');

        $last = $this->db->query(
            "SELECT docnumb FROM skkb
             WHERE docnumb LIKE '%/SKKB/" . $this->db->escape_like_str($this->month_roman($month)) . "/$year'
             ORDER BY id DESC LIMIT 1"
        )->row();

        if ($last) {
            $parts = explode('/', $last->docnumb);
            $next  = intval($parts[0]) + 1;
        } else {
            $next = 1;
        }

        return sprintf('%05d', $next) . '/SKKB/' . $this->month_roman($month) . '/' . $year;
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
