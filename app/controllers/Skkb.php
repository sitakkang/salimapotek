<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Skkb extends CI_Controller {

    public $dir_v = 'skkb/';

    public function __construct() {
        parent::__construct();
        $this->m_auth->check_login();
        $this->m_auth->check_akses();
        $this->load->model('M_skkb');
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
            'src/js/admin/skkb.js',
        );
        $data['panel'] = '<i class="fa fa-check-circle"></i> &nbsp;<b>Manajemen SKKB</b>';
        $this->l_skin->main($this->dir_v.'view', $data);
    }

    /**
     * DataTables JSON source
     */
    public function table() {
        $rows = $this->M_skkb->get_all();

        $draw = intval($this->input->get('draw'));
        $data = array();
        $i    = 1;

        foreach ($rows->result() as $row) {
            $data[] = array(
                'DT_RowId'  => $row->id,
                '0'         => $i++,
                '1'         => htmlspecialchars($row->patient_name),
                '2'         => htmlspecialchars($row->nik),
                '3'         => htmlspecialchars($row->company_name),
                '4'         => htmlspecialchars($row->bagian),
                '5'         => htmlspecialchars($row->jabatan),
                '6'         => htmlspecialchars($row->docnumb),
                '7'         => !empty($row->docdate) ? date('d/m/Y', strtotime($row->docdate)) : '-',
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
     * Load form tambah SKKB (via AJAX ke modal)
     */
    public function add() {
        $dokter = $this->M_skkb->get_all_doctor();
        $data['dokter'] = $dokter->result();
        
        $this->load->view($this->dir_v.'add', $data);
    }

    /**
     * Load form edit SKKB (via AJAX ke modal)
     */
    public function edit() {
        $id = intval($this->input->get('id'));
        $dokter = $this->M_skkb->get_all_doctor();
        $data['dokter'] = $dokter->result();
        $data['row'] = $this->M_skkb->get_by_id($id);
        if (!$data['row']) {
            show_404();
        }
        $this->load->view($this->dir_v.'edit', $data);
    }

    /**
     * View detail SKKB (via AJAX ke modal)
     */
    public function detail() {
        $id = intval($this->input->get('id'));
        $data['row'] = $this->M_skkb->get_skkb_by_id($id);
        if (!$data['row']) {
            show_404();
        }
        $this->load->view($this->dir_v.'detail', $data);
    }

    /**
     * Proses simpan SKKB baru
     */
    public function act_add() {
        $data = array(
            'patient_name'    => strtoupper(trim($this->input->post('patient_name'))),
            'age'             => trim($this->input->post('age')),
            'nik'             => trim($this->input->post('nik')),
            'company_name'    => strtoupper(trim($this->input->post('company_name'))),
            'bagian'          => strtoupper(trim($this->input->post('bagian'))),
            'jabatan'         => strtoupper(trim($this->input->post('jabatan'))),
            'catatan'         => trim($this->input->post('catatan')),
            'docdate'         => $this->format_date_db($this->input->post('docdate')),
            'doctby'          => strtoupper(trim($this->input->post('doctby'))),
            'docnumb'         => $this->M_skkb->generate_docnumb(),
            'insertby'        => $this->session->userdata('sess_id'),
            'insertdt'        => date('Y-m-d H:i:s'),
        );

        $this->M_skkb->insert($data);

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'SKKB untuk ' . $data['patient_name'] . ' berhasil ditambahkan! (No. ' . $data['docnumb'] . ')',
        ));
    }

    /**
     * Proses update SKKB
     */
    public function act_edit() {
        $id = intval($this->input->post('id'));

        $data = array(
            'patient_name'    => strtoupper(trim($this->input->post('patient_name'))),
            'age'             => trim($this->input->post('age')),
            'nik'             => trim($this->input->post('nik')),
            'company_name'    => strtoupper(trim($this->input->post('company_name'))),
            'bagian'          => strtoupper(trim($this->input->post('bagian'))),
            'jabatan'         => strtoupper(trim($this->input->post('jabatan'))),
            'catatan'         => trim($this->input->post('catatan')),
            'docdate'         => $this->format_date_db($this->input->post('docdate')),
            'doctby'          => strtoupper(trim($this->input->post('doctby'))),
            'updateby'        => $this->session->userdata('sess_id'),
            'updatedt'        => date('Y-m-d H:i:s'),
        );

        $this->M_skkb->update($id, $data);

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'SKKB untuk ' . $data['patient_name'] . ' berhasil diperbarui!',
        ));
    }

    /**
     * Proses hapus SKKB
     */
    public function act_del() {
        $id  = intval($this->input->post('id'));
        $row = $this->M_skkb->get_by_id($id);

        if (!$row) {
            echo json_encode(array('status' => 1, 'notif' => 'Data SKKB tidak ditemukan!'));
            return;
        }

        $this->M_skkb->delete($id);

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'SKKB ' . htmlspecialchars($row->patient_name) . ' berhasil dihapus!',
        ));
    }

    /**
     * Cetak / preview SKKB
     */
    public function cetak($id) {
        $data['row'] = $this->M_skkb->get_skkb_by_id($id);
        $data['qrcode'] = $this->generate_qrcode($id);
        if (!$data['row']) {
            show_404();
        }
        $this->load->view($this->dir_v.'cetak', $data);
    }

    /**
     * Konversi tanggal dari format dd/mm/yyyy ke yyyy-mm-dd untuk MySQL
     */
    private function format_date_db($date) {
        if (empty($date)) return null;
        $clean = str_replace('/', '-', $date);
        return date('Y-m-d', strtotime($clean));
    }

    function generate_qrcode($skkb_number)
    {
        $this->load->helper('string');
        $this->load->library('ciqrcode');
        $encript                        = str_replace('=', '', base64_encode($skkb_number . '|' . date('Ymd') . '|SKKB'));
        $codeContents                   = base_url('sks_validate/verify/' . $encript);
        $filename                       = "SKKB-" . random_string('alnum', 50) . ".png";
        $tempdir                        = "img/temp-qrcode/";
        if (!file_exists($tempdir)) {
            mkdir($tempdir);
        }

        QRcode::png($codeContents, $tempdir . $filename, QR_ECLEVEL_H, 4, 2);
        return base_url($tempdir . $filename);
    }
}
