<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Skmb extends CI_Controller {

    public $dir_v = 'skmb/';

    public function __construct() {
        parent::__construct();
        $this->m_auth->check_login();
        $this->m_auth->check_akses();
        $this->load->model('M_skmb');
    }

    public function index() {
        $data['css'] = array(
            'lib/datatables/dataTables.bootstrap.min.css',
            'lib/datatables/fixedColumns.bootstrap.min.css',
            'lib/datepicker/datepicker.min.css',
            'lib/select/component-chosen.min.css',
            'lib/clockpicker/clockpicker.min.css',
        );
        $data['js'] = array(
            'lib/datatables/datatables.min.js',
            'lib/datatables/dataTables.bootstrap.min.js',
            'lib/datatables/dataTables.fixedColumns.min.js',
            'lib/sweetalert/sweetalert2.all.min.js',
            'lib/datepicker/datepicker.min.js',
            'lib/select/chosen.jquery.min.js',
            'lib/mask/jquery.mask.min.js',
            'lib/clockpicker/clockpicker.min.js',
            'src/js/admin/skmb.js',
        );
        $data['panel'] = '<i class="fa fa-ambulance"></i> &nbsp;<b>Manajemen SKMB</b>';
        $this->l_skin->main($this->dir_v.'view', $data);
    }

    /**
     * DataTables JSON source — server-side processing
     */
    public function table() {
        $draw   = intval($this->input->get('draw'));
        $start  = intval($this->input->get('start'));
        $length = intval($this->input->get('length'));
        if ($length <= 0) $length = 10;

        $search = $this->input->get('search');
        $search = !empty($search['value']) ? trim($search['value']) : '';

        // Mapping kolom DataTables -> kolom database.
        // Indeks 0 ("No") tidak dipetakan => tidak bisa di-sort dari DB.
        $col_map = array(
            1 => 'patient_name',
            2 => 'nik',
            3 => 'company_name',
            4 => 'pengantar',
            5 => 'tgl_datang',
            6 => 'jam',
            7 => 'docnumb',
            8 => 'docdate',
        );

        $order_col = '';
        $order_dir = 'ASC';
        $order = $this->input->get('order');
        if (isset($order[0]) && isset($col_map[$order[0]['column']])) {
            $order_col = $col_map[$order[0]['column']];
            $order_dir = (strtoupper($order[0]['dir']) === 'DESC') ? 'DESC' : 'ASC';
        }

        $records_total    = $this->M_skmb->count_all();
        $records_filtered = $this->M_skmb->count_filtered($search);

        $rows = $this->M_skmb->get_datatables($search, $order_col, $order_dir, $start, $length);

        $data = array();
        $i    = $start + 1;

        foreach ($rows->result() as $row) {
            $data[] = array(
                'DT_RowId'  => $row->id,
                '0'         => $i++,
                '1'         => htmlspecialchars($row->patient_name),
                '2'         => htmlspecialchars($row->nik),
                '3'         => htmlspecialchars($row->company_name),
                '4'         => htmlspecialchars($row->pengantar),
                '5'         => !empty($row->tgl_datang) ? date('d/m/Y', strtotime($row->tgl_datang)) : '-',
                '6'         => htmlspecialchars($row->jam),
                '7'         => htmlspecialchars($row->docnumb),
                '8'         => !empty($row->docdate) ? date('d/m/Y', strtotime($row->docdate)) : '-',
            );
        }

        echo json_encode(array(
            'draw'            => $draw,
            'recordsTotal'    => $records_total,
            'recordsFiltered' => $records_filtered,
            'data'            => $data,
        ));
        exit();
    }

    /**
     * Load form tambah SKMB (via AJAX ke modal)
     */
    public function add() {
        $dokter = $this->M_skmb->get_all_doctor();
        $data['dokter'] = $dokter->result();
        
        $this->load->view($this->dir_v.'add', $data);
    }

    /**
     * Load form edit SKMB (via AJAX ke modal)
     */
    public function edit() {
        $id = intval($this->input->get('id'));
        $dokter = $this->M_skmb->get_all_doctor();
        $data['dokter'] = $dokter->result();
        $data['row'] = $this->M_skmb->get_by_id($id);
        if (!$data['row']) {
            show_404();
        }
        $this->load->view($this->dir_v.'edit', $data);
    }

    /**
     * View detail SKMB (via AJAX ke modal)
     */
    public function detail() {
        $id = intval($this->input->get('id'));
        $data['row'] = $this->M_skmb->get_skmb_by_id($id);
        if (!$data['row']) {
            show_404();
        }
        $this->load->view($this->dir_v.'detail', $data);
    }

    /**
     * Proses simpan SKMB baru
     */
    public function act_add() {
        $doct_by_id = intval($this->input->post('doctby'));
        $doct = $this->db->get_where('conf_users', array('id_user' => $doct_by_id))->row();

        $data = array(
            'patient_name'    => strtoupper(trim($this->input->post('patient_name'))),
            'nik'             => trim($this->input->post('nik')),
            'company_name'    => strtoupper(trim($this->input->post('company_name'))),
            'pengantar'       => strtoupper(trim($this->input->post('pengantar'))),
            'nik_pengantar'   => trim($this->input->post('nik_pengantar')),
            'company_pengantar' => strtoupper(trim($this->input->post('company_pengantar'))),
            'hubungan'        => $this->input->post('hubungan'),
            'tgl_datang'      => $this->format_date_db($this->input->post('tgl_datang')),
            'jam'             => trim($this->input->post('jam')),
            'docdate'         => $this->format_date_db($this->input->post('docdate')),
            'doct_by_id'      => $doct_by_id,
            'doct_by_name'    => $doct ? $doct->fullname : '',
            'docnumb'         => $this->M_skmb->generate_docnumb(),
            'insertby'        => $this->session->userdata('sess_id'),
            'insertdt'        => date('Y-m-d H:i:s'),
        );

        $this->M_skmb->insert($data);

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'SKMB untuk ' . $data['patient_name'] . ' berhasil ditambahkan! (No. ' . $data['docnumb'] . ')',
        ));
    }

    /**
     * Proses update SKMB
     */
    public function act_edit() {
        $id = intval($this->input->post('id'));
        $doct_by_id = intval($this->input->post('doctby'));
        $doct = $this->db->get_where('conf_users', array('id_user' => $doct_by_id))->row();

        $data = array(
            'patient_name'    => strtoupper(trim($this->input->post('patient_name'))),
            'nik'             => trim($this->input->post('nik')),
            'company_name'    => strtoupper(trim($this->input->post('company_name'))),
            'pengantar'       => strtoupper(trim($this->input->post('pengantar'))),
            'nik_pengantar'   => trim($this->input->post('nik_pengantar')),
            'company_pengantar' => strtoupper(trim($this->input->post('company_pengantar'))),
            'hubungan'        => $this->input->post('hubungan'),
            'tgl_datang'      => $this->format_date_db($this->input->post('tgl_datang')),
            'jam'             => trim($this->input->post('jam')),
            'docdate'         => $this->format_date_db($this->input->post('docdate')),
            'doct_by_id'      => $doct_by_id,
            'doct_by_name'    => $doct ? $doct->fullname : '',
            'updateby'        => $this->session->userdata('sess_id'),
            'updatedt'        => date('Y-m-d H:i:s'),
        );

        $this->M_skmb->update($id, $data);

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'SKMB untuk ' . $data['patient_name'] . ' berhasil diperbarui!',
        ));
    }

    /**
     * Proses hapus SKMB
     */
    public function act_del() {
        $id  = intval($this->input->post('id'));
        $row = $this->M_skmb->get_by_id($id);

        if (!$row) {
            echo json_encode(array('status' => 1, 'notif' => 'Data SKMB tidak ditemukan!'));
            return;
        }

        $this->M_skmb->delete($id);

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'SKMB ' . htmlspecialchars($row->patient_name) . ' berhasil dihapus!',
        ));
    }

    /**
     * Cetak / preview SKMB
     */
    public function cetak($id) {
        $data['row'] = $this->M_skmb->get_skmb_by_id($id);
        if (!$data['row']) {
            show_404();
        }

        // Ambil NIP dokter dari conf_users
        $doct = $this->db->get_where('conf_users', array('id_user' => intval($data['row']->doct_by_id)))->row();
        $data['row']->nip = $doct ? $doct->nip : '';

        $data['qrcode'] = $this->generate_qrcode($id);
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

    function generate_qrcode($skmb_number)
    {
        $this->load->helper('string');
        $this->load->library('ciqrcode');
        $encript                        = str_replace('=', '', base64_encode($skmb_number . '|' . date('Ymd') . '|SKMB'));
        $codeContents                   = base_url('sks_validate/verify/' . $encript);
        $filename                       = "SKMB-" . random_string('alnum', 50) . ".png";
        $tempdir                        = "img/temp-qrcode/";
        if (!file_exists($tempdir)) {
            mkdir($tempdir);
        }

        QRcode::png($codeContents, $tempdir . $filename, QR_ECLEVEL_H, 4, 2);
        return base_url($tempdir . $filename);
    }
}
