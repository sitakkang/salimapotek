<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_pharmacy extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get visits by date, only those with medical_record (already examined)
     */
    public function get_visits_by_date($date) {
        $sql = "SELECT 
                    A.*,
                    B.id_medical_record,
                    B.mrd_status,
                    C.patient_name,
                    C.patient_nik,
                    C.patient_ktp,
                    C.patient_bod
                FROM trans_visit A
                LEFT JOIN trans_medical_record B ON A.id_visit = B.visit_id
                LEFT JOIN ms_patient C ON A.patient_id = C.id_patient
                WHERE A.trans_doc = ?
                  AND A.trans_status = 1
                  AND B.mrd_status = 1
                  AND B.id_medical_record IS NOT NULL
                ORDER BY A.id_visit DESC";

        return $this->db->query($sql, array($date));
    }

    /**
     * Get medical record by id
     */
    public function get_medical_record($mrd_id) {
        $sql = "SELECT 
                    A.*,
                    B.trans_patient_code,
                    B.trans_patient_company,
                    B.trans_patient_phone,
                    C.patient_name,
                    D.fullname AS doct_name
                FROM trans_medical_record A
                LEFT JOIN trans_visit B ON A.visit_id = B.id_visit
                LEFT JOIN ms_patient C ON B.patient_id = C.id_patient
                LEFT JOIN conf_users D ON A.mrd_doct_by = D.id_user
                WHERE A.id_medical_record = ?
                LIMIT 1";

        return $this->db->query($sql, array(intval($mrd_id)))->row();
    }

    /**
     * Get obat (non-racikan & racikan) by medical_record_id
     * Non-racikan: trans_obat_pulv_id IS NULL
     * Racikan: trans_obat_pulv_id IS NOT NULL
     */
    public function get_obat_racikan($mrd_id) {
        return $this->db->query(
            'SELECT A.* FROM trans_obat A
             WHERE A.medical_record_id = ?
               AND A.trans_obat_status = 1
               AND A.trans_obat_pulv_id IS NULL
             ORDER BY A.id_trans_obat ASC',
            array(intval($mrd_id))
        )->result();
    }

    /**
     * Get all racikan by medical_record_id
     */
    public function get_pulv_list($mrd_id) {
        return $this->db->get_where('trans_obat_racikan', array(
            'medical_record_id' => intval($mrd_id)
        ))->result();
    }

    /**
     * Get all obat grouped by pulv_id
     */
    public function get_pulv_items_grouped($mrd_id) {
        $items = $this->db->query(
            'SELECT A.* FROM trans_obat A
             WHERE A.medical_record_id = ?
               AND A.trans_obat_pulv_id IS NOT NULL
               AND A.trans_obat_status = 1
             ORDER BY A.trans_obat_pulv_id, A.id_trans_obat ASC',
            array(intval($mrd_id))
        )->result();

        $grouped = array();
        foreach ($items as $item) {
            $grouped[$item->trans_obat_pulv_id][] = $item;
        }
        return $grouped;
    }
}
