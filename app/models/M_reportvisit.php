<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_reportvisit extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get visit report grouped by date
     */
    public function get_report($date_from, $date_to) {
        $sql = "SELECT 
                    C.trans_doc AS tgl,
                    COUNT(DISTINCT C.id_visit) AS jumlah
                FROM trans_medical_record A
                LEFT JOIN trans_visit C ON A.visit_id = C.id_visit
                WHERE C.trans_doc BETWEEN ? AND ?
                  AND C.trans_status = 1
                  AND A.mrd_status = 1
                GROUP BY C.trans_doc
                ORDER BY C.trans_doc ASC";

        return $this->db->query($sql, array($date_from, $date_to));
    }

    /**
     * Get patient list by date
     */
    public function get_patients_by_date($date) {
        $sql = "SELECT 
                    C.trans_patient_code,
                    D.patient_name,
                    D.patient_gender,
                    C.trans_patient_company,
                    C.trans_patient_phone,
                    C.trans_doc,
                    E.fullname AS doct_name
                FROM trans_medical_record A
                LEFT JOIN trans_visit C ON A.visit_id = C.id_visit
                LEFT JOIN ms_patient D ON C.patient_id = D.id_patient
                LEFT JOIN conf_users E ON A.mrd_doct_by = E.id_user
                WHERE C.trans_doc = ?
                  AND C.trans_status = 1
                  AND A.mrd_status = 1
                ORDER BY C.id_visit ASC";

        return $this->db->query($sql, array($date));
    }
}
