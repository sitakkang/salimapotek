<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_sks extends CI_Model {

    protected $table = 'sks';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get all SKS records for DataTable
     */
    public function get_all() {
        return $this->db->query(
            'SELECT id, patient_name, sks_nik, company_name, patient_job, age, gender, alamat, diagnosa, datefrom, dateto,
                    docdate, doctby, docnumb, insertby, insertdt
             FROM sks
             ORDER BY insertdt DESC'
        );
    }

    /**
     * Hitung total SKS — untuk recordsTotal DataTables
     */
    public function count_all() {
        return $this->db->count_all($this->table);
    }

    /**
     * Hitung total SKS setelah filter pencarian — untuk recordsFiltered
     */
    public function count_filtered($search) {
        $this->db->from($this->table);
        $this->apply_search($search);
        return $this->db->count_all_results();
    }

    /**
     * Ambil data SKS ter-pagination + filter untuk DataTables server-side
     */
    public function get_datatables($search, $order_col = '', $order_dir = 'ASC', $start = 0, $length = 10) {
        $this->db->select('id, patient_name, sks_nik, company_name, patient_job, age, gender, alamat, diagnosa, datefrom, dateto,
                           docdate, doctby, docnumb, insertby, insertdt');
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
        $this->db->or_like('patient_job', $search);
        $this->db->or_like('gender', $search);
        $this->db->or_like('diagnosa', $search);
        $this->db->or_like('docnumb', $search);
        $this->db->or_like('doctby', $search);
        $this->db->group_end();
    }

    /**
     * Get all SKS records for DataTable
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
     * Get single SKS by ID
     */
    public function get_by_id($id) {
        return $this->db->get_where($this->table, array('id' => intval($id)))->row();
    }

    public function get_sks_by_id($id) {
        $this->db->select('sks.*, doctor.fullname AS fullname, doctor.nip AS nip, creator.fullname AS insert_name, updater.fullname AS update_name');
        $this->db->from('sks');
        $this->db->join('conf_users AS doctor', 'sks.doctby = doctor.id_user', 'left');
        $this->db->join('conf_users AS creator', 'sks.insertby = creator.id_user', 'left');
        $this->db->join('conf_users AS updater', 'sks.updateby = updater.id_user', 'left');
        $this->db->where('sks.id', $id);
        return $this->db->get()->row();
    }

    /**
     * Get patient_job from ms_patient by patient name (match terbaru)
     */
    public function get_patient_job_by_name($name) {
        if (empty($name)) return '';
        $row = $this->db->query(
            'SELECT patient_job FROM ms_patient
             WHERE patient_name = ?
             ORDER BY id_patient DESC LIMIT 1',
            array(strtoupper(trim($name)))
        )->row();
        return $row ? $row->patient_job : '';
    }

    /**
     * Insert new SKS
     */
    public function insert($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Update existing SKS
     */
    public function update($id, $data) {
        $this->db->where('id', intval($id));
        $this->db->update($this->table, $data);
        return $this->db->affected_rows();
    }

    /**
     * Delete SKS by ID
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
     * Generate default nomor dokumen SKS.
     * Running number default 00000, yang otomatis hanya bulan & tahun.
     * Format: 00000/SKS/PMDMH/VII/2026
     */
    public function generate_docnumb() {
        return '00000/SKS/PMDMH/' . $this->month_roman(date('n')) . '/' . date('Y');
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
