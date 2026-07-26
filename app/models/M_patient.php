<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_patient extends CI_Model {

    protected $table = 'ms_patient';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Search Patient records by RM, KTP, or Nama
     */
    public function search($rm = '', $ktp = '', $nama = '') {
        $sql = "SELECT id_patient, patient_code, patient_name, patient_nik, patient_company,
                       patient_job, patient_ktp, patient_bod, patient_gender, patient_city_id,
                       patient_city_name, patient_district_id, patient_district_name,
                       patient_address, patient_phone, patient_status,
                       insert_by, insert_dt
                FROM ms_patient
                WHERE 1=1 ";
        $params = array();

        if (!empty($rm)) {
            $sql .= "AND patient_code LIKE ? ";
            $params[] = '%' . $rm . '%';
        }
        if (!empty($ktp)) {
            $sql .= "AND (patient_ktp LIKE ? OR patient_nik LIKE ?) ";
            $params[] = '%' . $ktp . '%';
            $params[] = '%' . $ktp . '%';
        }
        if (!empty($nama)) {
            $sql .= "AND patient_name LIKE ? ";
            $params[] = '%' . $nama . '%';
        }

        $sql .= "ORDER BY id_patient DESC";

        return $this->db->query($sql, $params);
    }

    /**
     * Get all Patient records for DataTable
     */
    public function get_all() {
        return $this->db->query(
            'SELECT id_patient, patient_code, patient_name, patient_nik, patient_company,
                    patient_job, patient_ktp, patient_bod, patient_gender, patient_city_id,
                    patient_city_name, patient_district_id, patient_district_name,
                    patient_address, patient_phone, patient_status,
                    insert_by, insert_dt
             FROM ms_patient
             ORDER BY insert_dt DESC'
        );
    }

    /**
     * Check if KTP or NIK already exists (for duplicate validation)
     * Returns the existing patient row if found, or FALSE if not found.
     * Pass $exclude_id to exclude a specific patient (e.g., when editing).
     */
    public function check_duplicate($ktp, $nik, $exclude_id = 0) {
        if (empty($ktp) && empty($nik)) {
            return false;
        }

        $this->db->select('id_patient, patient_code, patient_name, patient_ktp, patient_nik');
        $this->db->from($this->table);

        $conditions = array();
        if (!empty($ktp)) {
            $conditions[] = "patient_ktp = '" . $this->db->escape_str($ktp) . "'";
        }
        if (!empty($nik)) {
            $conditions[] = "patient_nik = '" . $this->db->escape_str($nik) . "'";
        }

        $this->db->where('(' . implode(' OR ', $conditions) . ')');

        if ($exclude_id > 0) {
            $this->db->where('id_patient !=', intval($exclude_id));
        }

        return $this->db->get()->row();
    }

    /**
     * Get single Patient by ID
     */
    public function get_by_id($id) {
        $this->db->select('ms_patient.*, creator.fullname AS insert_name, updater.fullname AS update_name');
        $this->db->from('ms_patient');
        $this->db->join('conf_users AS creator', 'ms_patient.insert_by = creator.id_user', 'left');
        $this->db->join('conf_users AS updater', 'ms_patient.updateby = updater.id_user', 'left');
        $this->db->where('ms_patient.id_patient', intval($id));
        return $this->db->get()->row();
    }

    /**
     * Insert new Patient
     */
    public function insert($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Update existing Patient
     */
    public function update($id, $data) {
        $this->db->where('id_patient', intval($id));
        $this->db->update($this->table, $data);
        return $this->db->affected_rows();
    }

    /**
     * Delete Patient by ID (soft delete — set status to 0)
     */
    public function delete($id) {
        $this->db->where('id_patient', intval($id));
        $this->db->update($this->table, array('patient_status' => 0));
        return $this->db->affected_rows();
    }



    /**
     * Generate patient code (NO.RM) automatically
     * Format: YYMMDD + 4-digit running number
     * Example: 2601010001 (26=2026, 01=Jan, 01=tgl, 0001=running)
     */
    public function generate_patient_code() {
        $prefix = date('ymd'); // 260721 untuk 21 Juli 2026

        $last = $this->db->query(
            "SELECT patient_code FROM ms_patient
             WHERE patient_code LIKE '$prefix%'
             ORDER BY id_patient DESC LIMIT 1"
        )->row();

        if ($last) {
            $running = intval(substr($last->patient_code, -4)) + 1;
        } else {
            $running = 1;
        }

        return $prefix . sprintf('%04d', $running);
    }

    // ----------------------------------------------------------------
    // Registration (Pendaftaran) — trans_visit + trans_medical_record
    // ----------------------------------------------------------------

    /**
     * Insert into trans_visit
     */
    public function insert_visit($data) {
        $this->db->insert('trans_visit', $data);
        return $this->db->insert_id();
    }

    /**
     * Insert into trans_medical_record
     */
    public function insert_medical_record($data) {
        $this->db->insert('trans_medical_record', $data);
        return $this->db->insert_id();
    }
}
