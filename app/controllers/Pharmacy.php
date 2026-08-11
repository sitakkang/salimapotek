<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pharmacy extends CI_Controller {

    public $dir_v = 'pharmacy/';

    public function __construct() {
        parent::__construct();
        $this->m_auth->check_login();
        $this->m_auth->check_akses();
        $this->load->model('M_pharmacy');
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
            'src/js/admin/pharmacy.js',
        );
        $data['panel'] = '<i class="fa fa-pills"></i> &nbsp;<b>Pharmacy</b>';
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

        $date_db = $this->format_date_db($date);
        $rows = $this->M_pharmacy->get_visits_by_date($date_db);

        $draw = intval($this->input->get('draw'));
        $data = array();
        $i    = 1;

        foreach ($rows->result() as $row) {
            $data[] = array(
                'DT_RowId'        => $row->id_visit,
                '0'               => $i++,
                '1'               => htmlspecialchars($row->trans_patient_code ?? ''),
                '2'               => htmlspecialchars($row->patient_name ?? ''),
                '3'               => htmlspecialchars($row->trans_patient_company ?? ''),
                '4'               => htmlspecialchars($row->trans_patient_phone ?? ''),
                '5'               => !empty($row->trans_doc) ? date('d/m/Y', strtotime($row->trans_doc)) : '-',
                'id_medical_record' => $row->id_medical_record,
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
     * Detail obat & racikan (via AJAX ke modal)
     */
    public function detail() {
        $mrd_id = intval($this->input->get('mrd_id'));
        if (!$mrd_id) {
            show_404();
        }

        $data['row']      = $this->M_pharmacy->get_medical_record($mrd_id);
        $data['obat']     = $this->M_pharmacy->get_obat_racikan($mrd_id);
        $data['racikan']  = $this->M_pharmacy->get_pulv_list($mrd_id);
        $data['pulv_items'] = $this->M_pharmacy->get_pulv_items_grouped($mrd_id);

        if (!$data['row']) {
            show_404();
        }

        $this->load->view($this->dir_v.'detail', $data);
    }

    /**
     * Konversi tanggal dd/mm/yyyy ke yyyy-mm-dd
     */
    private function format_date_db($date) {
        if (empty($date)) return null;
        $clean = str_replace('/', '-', $date);
        $timestamp = strtotime($clean);
        if (!$timestamp) return null;
        return date('Y-m-d', $timestamp);
    }
}
