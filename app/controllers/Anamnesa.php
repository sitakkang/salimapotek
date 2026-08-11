<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Anamnesa extends CI_Controller {

    public $dir_v = 'anamnesa/';

    public function __construct() {
        parent::__construct();
        $this->m_auth->check_login();
        $this->load->model('M_anamnesa');
    }

    public function index() {
        $data['css'] = array(
            'lib/datatables/dataTables.bootstrap.min.css',
            'lib/datatables/fixedColumns.bootstrap.min.css',
            'lib/datepicker/datepicker.min.css',
            'lib/select/component-chosen.min.css',
        );
        $data['js'] = array(
            'lib/datatables/datatables.min.js',
            'lib/datatables/dataTables.bootstrap.min.js',
            'lib/datatables/dataTables.fixedColumns.min.js',
            'lib/sweetalert/sweetalert2.all.min.js',
            'lib/datepicker/datepicker.min.js',
            'lib/select/chosen.jquery.min.js',
            'lib/mask/jquery.mask.min.js',
            'src/js/admin/anamnesa.js',
        );
        $data['panel'] = '<i class="fa fa-stethoscope"></i> &nbsp;<b>Anamnesa</b>';
        $this->l_skin->main($this->dir_v.'view', $data);
    }

    /**
     * DataTables JSON source — filter by date
     */
    public function table() {
        $date = trim($this->input->get('date'));

        if (empty($date)) {
            echo json_encode(array(
                'draw'            => intval($this->input->get('draw')),
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => array(),
            ));
            exit();
        }

        // Convert dari dd/mm/yyyy ke yyyy-mm-dd
        $date_db = $this->format_date_db($date);
        $rows = $this->M_anamnesa->get_by_date($date_db);

        $draw = intval($this->input->get('draw'));
        $data = array();
        $i    = 1;

        foreach ($rows->result() as $row) {
            $has_anamnesa = !empty($row->id_trans_anm);

            $data[] = array(
                'DT_RowId'        => $row->id_visit,
                '0'               => $i++,
                '1'               => htmlspecialchars($row->trans_patient_code ?? ''),
                '2'               => htmlspecialchars($row->patient_name ?? ''),
                '3'               => htmlspecialchars($row->trans_patient_company ?? ''),
                '4'               => htmlspecialchars($row->trans_patient_phone ?? ''),
                '5'               => !empty($row->trans_doc) ? date('d/m/Y', strtotime($row->trans_doc)) : '-',
                '6'               => !empty($row->trans_insert_dt) ? date('d/m/Y H:i', strtotime($row->trans_insert_dt)) : '-',
                'id_medical_record' => $row->id_medical_record,
                'id_trans_anm'    => $row->id_trans_anm,
                'has_anamnesa'    => $has_anamnesa,
            );
        }

        echo json_encode(array(
            'draw'            => $draw,
            'recordsTotal'    => $rows->num_rows(),
            'recordsFiltered' => $rows->num_rows(),
            'data'            => $data,
        ));
        exit();
    }

    /**
     * View detail Anamnesa (via AJAX ke modal)
     */
    public function detail() {
        $id = intval($this->input->get('id'));
        $data['row'] = $this->M_anamnesa->get_by_visit_id($id);
        if (!$data['row']) {
            show_404();
        }
        $this->load->view($this->dir_v.'detail', $data);
    }

    /**
     * Load form anamnesa (Tambah / Ubah) via AJAX ke modal
     */
    public function anamnesa_form() {
        $visit_id = intval($this->input->get('visit_id'));
        $row = $this->M_anamnesa->get_by_visit_id($visit_id);
        if (!$row) {
            show_404();
        }

        // Cek apakah anamnesa sudah ada
        $anamnesa = $this->M_anamnesa->get_anamnesa_by_medical_record($row->id_medical_record);
        $data['row'] = $row;
        $data['anamnesa'] = $anamnesa;

        $this->load->view($this->dir_v.'anamnesa_form', $data);
    }

    /**
     * Proses simpan / update anamnesa
     */
    public function act_anamnesa() {
        $id_trans_anm = intval($this->input->post('id_trans_anm'));
        $medical_record_id = intval($this->input->post('medical_record_id'));

        if (empty($medical_record_id)) {
            echo json_encode(array('status' => 1, 'notif' => 'Data tidak lengkap!'));
            return;
        }

        $data = array(
            'medical_record_id' => $medical_record_id,
            'anm_temp'          => trim($this->input->post('anm_temp')),
            'anm_pulse'         => trim($this->input->post('anm_pulse')),
            'anm_respirasi'     => trim($this->input->post('anm_respirasi')),
            'anm_blood_press'   => trim($this->input->post('anm_blood_press')),
            'anm_height'        => trim($this->input->post('anm_height')),
            'anm_weight'        => trim($this->input->post('anm_weight')),
            'anm_stomatch_wide' => trim($this->input->post('anm_stomatch_wide')),
            'anm_note'          => trim($this->input->post('anm_note')),
        );

        if ($id_trans_anm > 0) {
            // UPDATE
            $data['anm_insert_dt'] = date('Y-m-d H:i:s');
            $this->M_anamnesa->update_anamnesa($id_trans_anm, $data);
            $msg = 'Data anamnesa berhasil diperbarui!';
        } else {
            // INSERT
            $data['anm_status'] = 1;
            $data['anm_insert_dt'] = date('Y-m-d H:i:s');
            $data['anm_insert_by'] = $this->session->userdata('sess_id');
            $this->M_anamnesa->insert_anamnesa($data);
            $msg = 'Data anamnesa berhasil disimpan!';
        }

        echo json_encode(array(
            'status' => 2,
            'notif'  => $msg,
        ));
    }

    /**
     * Batalkan visit dan medical record
     */
    public function act_batal() {
        $visit_id = intval($this->input->post('visit_id'));
        $now = date('Y-m-d H:i:s');
        $user_id = $this->session->userdata('sess_id');

        // Update trans_visit
        $this->db->where('id_visit', $visit_id);
        $this->db->update('trans_visit', array(
            'trans_status'    => 0,
            'trans_cancel_dt' => $now,
            'trans_cancel_by' => $user_id,
        ));

        // Update trans_medical_record
        $this->db->where('visit_id', $visit_id);
        $this->db->update('trans_medical_record', array(
            'mrd_status'    => 0,
            'mrd_cancel_dt' => $now,
            'mrd_cancel_by' => $user_id,
        ));

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'Data berhasil dibatalkan.',
        ));
    }

    /**
     * Konversi tanggal dari format dd/mm/yyyy ke yyyy-mm-dd untuk MySQL
     */
    private function format_date_db($date) {
        if (empty($date)) return null;
        $clean = str_replace('/', '-', $date);
        return date('Y-m-d', strtotime($clean));
    }
}
