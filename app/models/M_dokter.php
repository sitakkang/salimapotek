<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_dokter extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get visit + medical record + anamnesa + patient
     * Hanya menampilkan yang sudah memiliki anamnesa
     */
    public function get_by_date($date) {
        $sql = "SELECT 
                    A.*,
                    B.id_medical_record,
                    B.mrd_status,
                    B.mrd_vst_type,
                    B.mrd_doct_by,
                    B.mrd_insert_dt AS mrd_insert_dt,
                    B.mrd_insert_by AS mrd_insert_by,
                    C.*,
                    D.id_trans_anm,
                    D.anm_temp,
                    D.anm_pulse,
                    D.anm_respirasi,
                    D.anm_blood_press,
                    D.anm_height,
                    D.anm_weight,
                    D.anm_stomatch_wide,
                    D.anm_note,
                    D.anm_status AS anm_status
                FROM trans_visit A
                LEFT JOIN trans_medical_record B ON A.id_visit = B.visit_id
                LEFT JOIN ms_patient C ON A.patient_id = C.id_patient
                LEFT JOIN trans_anamnesa D ON B.id_medical_record = D.medical_record_id AND D.anm_status = 1
                WHERE A.trans_doc = ?
                  AND A.trans_status = 1
                  AND B.mrd_status = 1
                  AND D.id_trans_anm IS NOT NULL
                ORDER BY A.id_visit DESC";

        return $this->db->query($sql, array($date));
    }

    /**
     * Get single record by visit_id (lengkap dengan anamnesa & dokter)
     */
    public function get_by_visit_id($visit_id) {
        $sql = "SELECT 
                    A.*,
                    B.id_medical_record,
                    B.mrd_status,
                    B.mrd_vst_type,
                    B.mrd_doct_by,
                    B.mrd_insert_dt AS mrd_insert_dt,
                    B.mrd_insert_by AS mrd_insert_by,
                    C.*,
                    D.id_trans_anm,
                    D.anm_temp,
                    D.anm_pulse,
                    D.anm_respirasi,
                    D.anm_blood_press,
                    D.anm_height,
                    D.anm_weight,
                    D.anm_stomatch_wide,
                    D.anm_note,
                    D.anm_status AS anm_status,
                    E.fullname AS mrd_doct_name,
                    F.fullname AS trans_doct_name
                FROM trans_visit A
                LEFT JOIN trans_medical_record B ON A.id_visit = B.visit_id
                LEFT JOIN ms_patient C ON A.patient_id = C.id_patient
                LEFT JOIN trans_anamnesa D ON B.id_medical_record = D.medical_record_id AND D.anm_status = 1
                LEFT JOIN conf_users E ON B.mrd_doct_by = E.id_user
                LEFT JOIN conf_users F ON A.trans_doct_by = F.id_user
                WHERE A.id_visit = ?
                AND A.trans_status = 1
                LIMIT 1";

        return $this->db->query($sql, array(intval($visit_id)))->row();
    }

    // ----------------------------------------------------------------
    // Diagnosa
    // ----------------------------------------------------------------

    /**
     * Get all diagnosa (ms_diagnosa) for select option
     */
    public function get_all_diagnosa() {
        return $this->db->get_where('ms_diagnosa', array('dgn_status' => 1))->result();
    }

    /**
     * Get dokter
     */
    public function get_doct_by($id_user) {
        return $this->db->get_where('conf_users', array('id_user' => $id_user))->row();
    }

    /**
     * Get diagnosa by medical_record_id
     */
    public function get_diagnosa_by_medical_record($mrd_id) {
        return $this->db->get_where('trans_diagnosa', array(
            'medical_record_id' => intval($mrd_id),
            'trans_dgn_status' => 1
        ))->result();
    }

    /**
     * Insert trans_diagnosa
     */
    public function insert_diagnosa($data) {
        $this->db->insert('trans_diagnosa', $data);
        return $this->db->insert_id();
    }

    /**
     * Delete trans_diagnosa
     */
    public function delete_diagnosa($id) {
        $this->db->where('id_trans_dgn', intval($id));
        $this->db->delete('trans_diagnosa');
        return $this->db->affected_rows();
    }

    // ----------------------------------------------------------------
    // Obat
    // ----------------------------------------------------------------

    /**
     * Get all obat (ms_obat) for select option
     */
    public function get_all_obat() {
        return $this->db->get_where('ms_obat', array('obat_status' => 1))->result();
    }

    /**
     * Get obat by medical_record_id
     */
    public function get_obat_by_medical_record($mrd_id) {
        return $this->db->get_where('trans_obat', array(
            'medical_record_id' => intval($mrd_id),
            'trans_obat_status' => 1
        ))->result();
    }

    /**
     * Insert trans_obat
     */
    public function insert_obat($data) {
        $this->db->insert('trans_obat', $data);
        return $this->db->insert_id();
    }

    /**
     * Delete trans_obat
     */
    public function delete_obat($id) {
        $this->db->where('id_trans_obat', intval($id));
        $this->db->delete('trans_obat');
        return $this->db->affected_rows();
    }

    // ----------------------------------------------------------------
    // Doctor list (conf_users level=3)
    // ----------------------------------------------------------------
    public function get_all_doctor() {
        return $this->db->query(
            'SELECT * FROM conf_users WHERE level = 3 AND status = 1 ORDER BY fullname ASC'
        )->result();
    }

    /**
     * Get users by level
     */
    public function get_users_by_level($level) {
        return $this->db->query(
            'SELECT * FROM conf_users WHERE level = ? AND status = 1 ORDER BY fullname ASC',
            array(intval($level))
        )->result();
    }

    // ----------------------------------------------------------------
    // SKS
    // ----------------------------------------------------------------
    public function get_all_sks_by_visit($visit_id) {
        return $this->db->get_where('sks', array(
            'visit_id' => intval($visit_id)
        ))->result();
    }

    public function get_sks_by_id($id) {
        return $this->db->get_where('sks', array('id' => intval($id)))->row();
    }

    public function get_sks_by_visit_id($visit_id) {
        return $this->db->get_where('sks', array('visit_id' => intval($visit_id)))->row();
    }

    public function update_sks($id, $data) {
        $this->db->where('id', intval($id));
        $this->db->update('sks', $data);
        return $this->db->affected_rows();
    }

    public function delete_sks($id) {
        $this->db->where('id', intval($id));
        $this->db->delete('sks');
        return $this->db->affected_rows();
    }

    /**
     * Generate nomor dokumen SKS (copied from M_sks)
     */
    public function generate_docnumb_sks() {
        $month = date('n');
        $year  = date('Y');
        $roman = $this->month_roman($month);

        $last = $this->db->query(
            "SELECT docnumb FROM sks
             WHERE docnumb LIKE '%/SKS/$roman/$year'
             ORDER BY id DESC LIMIT 1"
        )->row();

        if ($last) {
            $parts = explode('/', $last->docnumb);
            $next  = intval($parts[0]) + 1;
        } else {
            $next = 1;
        }

        return sprintf('%05d', $next) . '/SKS/' . $roman . '/' . $year;
    }

    /**
     * Insert SKS
     */
    public function insert_sks($data) {
        $this->db->insert('sks', $data);
        return $this->db->insert_id();
    }

    // ----------------------------------------------------------------
    // SKBS
    // ----------------------------------------------------------------
    public function get_skbs_by_visit_id($visit_id) {
        return $this->db->get_where('trans_skbs', array(
            'visit_id' => intval($visit_id),
            'skbs_status' => 1
        ))->row();
    }

    public function insert_skbs($data) {
        $this->db->insert('trans_skbs', $data);
        return $this->db->insert_id();
    }

    public function update_skbs($id, $data) {
        $this->db->where('id_skbs', intval($id));
        $this->db->update('trans_skbs', $data);
        return $this->db->affected_rows();
    }

    // ----------------------------------------------------------------
    // SKMB
    // ----------------------------------------------------------------
    public function get_skmb_by_visit_id($visit_id) {
        return $this->db->get_where('skmb', array(
            'visit_id' => intval($visit_id)
        ))->row();
    }

    public function insert_skmb($data) {
        $this->db->insert('skmb', $data);
        return $this->db->insert_id();
    }

    public function update_skmb($id, $data) {
        $this->db->where('id', intval($id));
        $this->db->update('skmb', $data);
        return $this->db->affected_rows();
    }

    public function delete_skmb($id) {
        $this->db->where('id', intval($id));
        $this->db->delete('skmb');
        return $this->db->affected_rows();
    }

    private function month_roman($n) {
        $map = array(
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
            5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
            9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
        );
        return isset($map[$n]) ? $map[$n] : '';
    }
}
