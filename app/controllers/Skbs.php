<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Skbs extends CI_Controller {

    public $dir_v = 'skbs/';

    public function __construct() {
        parent::__construct();
        $this->m_auth->check_login();
        $this->m_auth->check_akses();
        $this->load->model('M_skbs');
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
            'src/js/admin/skbs.js',
        );
        $data['panel'] = '<i class="fa fa-heartbeat"></i> &nbsp;<b>Manajemen SKBS</b>';
        $this->l_skin->main($this->dir_v.'view', $data);
    }

    /**
     * DataTables JSON source
     */
    public function table() {
        $rows = $this->M_skbs->get_all();

        $draw = intval($this->input->get('draw'));
        $data = array();
        $i    = 1;

        foreach ($rows->result() as $row) {
            $data[] = array(
                'DT_RowId'      => $row->id_skbs,
                '0'             => $i++,
                '1'             => htmlspecialchars($row->skbs_patient_name),
                '2'             => htmlspecialchars($row->skbs_patient_nik),
                '3'             => htmlspecialchars($row->skbs_patient_company),
                '4'             => htmlspecialchars($row->skbs_patient_department),
                '5'             => htmlspecialchars($row->skbs_result_name),
                '6'             => htmlspecialchars($row->skbs_doct_name),
                '7'             => !empty($row->skbs_doc_date) ? date('d/m/Y', strtotime($row->skbs_doc_date)) : '-',
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
     * View detail SKBS (via AJAX ke modal)
     */
    public function detail() {
        $id = intval($this->input->get('id'));
        $data['row'] = $this->M_skbs->get_skbs_by_id($id);
        if (!$data['row']) {
            show_404();
        }
        $this->load->view($this->dir_v.'detail', $data);
    }

    /**
     * Cetak / preview SKBS
     */
    public function cetak($id) {
        $row = $this->M_skbs->get_by_id($id);
        if (!$row) show_404();

        // Ambil NIP dokter dari conf_users
        $doct = $this->db->get_where('conf_users', array('id_user' => intval($row->skbs_doct_id)))->row();
        $row->nip = $doct ? $doct->nip : '';

        // Generate nomor dokumen
        $month_roman = $this->month_roman(date('n'));
        $year = date('Y');
        $data['docnumb'] = sprintf('%05d', $row->id_skbs) . '/IMIP-SKBS/' . $month_roman . '/' . $year;
        $data['row'] = $row;
        $data['qrcode'] = $this->generate_qrcode($id);

        $this->load->view($this->dir_v.'cetak', $data);
    }

    /**
     * Generate QRCode untuk cetak SKBS
     */
    private function generate_qrcode($id) {
        $this->load->helper('string');
        $this->load->library('ciqrcode');
        $encript      = str_replace('=', '', base64_encode($id . '|' . date('Ymd') . '|SKBS'));
        $codeContents = base_url('sks_validate/verify/' . $encript);
        $filename     = "SKBS-" . random_string('alnum', 50) . ".png";
        $tempdir      = "img/temp-qrcode/";
        if (!file_exists($tempdir)) mkdir($tempdir);
        QRcode::png($codeContents, $tempdir . $filename, QR_ECLEVEL_H, 4, 2);
        return base_url($tempdir . $filename);
    }

    private function month_roman($n) {
        $map = array(1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV',
                     5 => 'V', 6 => 'VI', 7 => 'VII', 8 => 'VIII',
                     9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII');
        return isset($map[$n]) ? $map[$n] : '';
    }
}
