<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_skbs extends CI_Model {

    protected $table = 'trans_skbs';

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get all SKBS records for DataTable (only active)
     */
    public function get_all() {
        return $this->db->query(
            'SELECT id_skbs, visit_id, skbs_patient_name, skbs_patient_nik,
                    skbs_patient_department, skbs_patient_company,
                    skbs_patient_ktp, skbs_patient_age,
                    skbs_result_id, skbs_result_name,
                    skbs_desc, skbs_note,
                    skbs_td, skbs_bw, skbs_tb, skbs_bb,
                    skbs_r, skbs_l, skbs_koreksi_r, skbs_koreksi_l,
                    skbs_doc_date, skbs_doct_id, skbs_doct_name,
                    skbs_status, insert_dt, insert_by
             FROM trans_skbs
             WHERE skbs_status = 1
             ORDER BY insert_dt DESC'
        );
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
}
