<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_reportobat extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get report data by date range and optional obat name
     */
    public function get_report($date_from, $date_to, $obat_name = '') {
        $sql = "SELECT 
                    A.*,
                    D.patient_name,
                    C.trans_patient_code,
                    C.trans_doc
                FROM trans_obat A
                LEFT JOIN trans_medical_record B ON A.medical_record_id = B.id_medical_record
                LEFT JOIN trans_visit C ON B.visit_id = C.id_visit
                LEFT JOIN ms_patient D ON C.patient_id = D.id_patient
                WHERE A.trans_obat_status = 1
                  AND C.trans_doc BETWEEN ? AND ?
                  AND C.trans_status = 1";
        $params = array($date_from, $date_to);

        if (!empty($obat_name)) {
            $sql .= " AND A.trans_obat_name = ?";
            $params[] = $obat_name;
        }

        $sql .= " ORDER BY C.trans_doc DESC, A.id_trans_obat DESC";

        return $this->db->query($sql, $params);
    }

    /**
     * Get distinct obat names from trans_obat
     */
    public function get_distinct_obat() {
        return $this->db->query(
            "SELECT DISTINCT trans_obat_name 
             FROM trans_obat 
             WHERE trans_obat_status = 1 
             ORDER BY trans_obat_name ASC"
        );
    }
}
