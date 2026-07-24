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
            'SELECT id, patient_name, company_name, age, gender, desa, kecamatan, kelurahan,
                    kabupaten, provinsi, diagnosa, datefrom, dateto,
                    docdate, doctby, docnumb, insertby, insertdt
             FROM sks
             ORDER BY insertdt DESC'
        );
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
     * Generate nomor dokumen otomatis (running number per bulan)
     * Format: 00001/SKS/VII/2026
     */
    public function generate_docnumb() {
        $month = date('n');  // 1–12
        $year  = date('Y');

        // Cari nomor terakhir di bulan & tahun ini
        $last = $this->db->query(
            "SELECT docnumb FROM sks
             WHERE docnumb LIKE '%/SKS/" . $this->db->escape_like_str($this->month_roman($month)) . "/$year'
             ORDER BY id DESC LIMIT 1"
        )->row();

        if ($last) {
            // Ambil angka dari depan: 00001/SKS/...
            $parts = explode('/', $last->docnumb);
            $next  = intval($parts[0]) + 1;
        } else {
            $next = 1;
        }

        return sprintf('%05d', $next) . '/SKS/' . $this->month_roman($month) . '/' . $year;
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

    function get_kecamatan()
    {
        $this->db->select();
        $this->db->from('ms_city');
        return $this->db->get()->result();
    }

    function get_kelurahan_not_default($city_id)
    {
        $this->db->select();
        $this->db->from('ms_district');
        $this->db->where('city_id', $city_id);
        return $this->db->get()->result();
    }

     function get_kelurahan()
    {
        $this->db->select();
        $this->db->from('ms_district');
        return $this->db->get()->result();
    }

    function get_kecamatan_by_id($id_city)
    {
        $this->db->select();
        $this->db->from('ms_city');
        $this->db->where('id_city', $id_city);
        return $this->db->get()->row();
    }

    function get_kelurahan_by_id($id_district)
    {
        $this->db->select();
        $this->db->from('ms_district');
        $this->db->where('id_district', $id_district);
        return $this->db->get()->row();
    }
}
