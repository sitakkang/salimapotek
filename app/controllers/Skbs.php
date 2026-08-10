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
            1 => 'skbs_patient_name',
            2 => 'skbs_patient_nik',
            3 => 'skbs_patient_company',
            4 => 'skbs_patient_department',
            5 => 'skbs_result_name',
            6 => 'skbs_doct_name',
            7 => 'skbs_doc_date',
        );

        $order_col = '';
        $order_dir = 'ASC';
        $order = $this->input->get('order');
        if (isset($order[0]) && isset($col_map[$order[0]['column']])) {
            $order_col = $col_map[$order[0]['column']];
            $order_dir = (strtoupper($order[0]['dir']) === 'DESC') ? 'DESC' : 'ASC';
        }

        $records_total    = $this->M_skbs->count_all();
        $records_filtered = $this->M_skbs->count_filtered($search);

        $rows = $this->M_skbs->get_datatables($search, $order_col, $order_dir, $start, $length);

        $data = array();
        $i    = $start + 1;

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
            'recordsTotal'    => $records_total,
            'recordsFiltered' => $records_filtered,
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
     * Load form tambah SKBS (via AJAX ke modal)
     */
    public function add() {
        $dokter = $this->M_skbs->get_all_doctor();
        $data['dokter'] = $dokter->result();

        $this->load->view($this->dir_v.'add', $data);
    }

    /**
     * Load form edit SKBS (via AJAX ke modal)
     */
    public function edit() {
        $id = intval($this->input->get('id'));
        $dokter = $this->M_skbs->get_all_doctor();
        $data['dokter'] = $dokter->result();
        $data['row'] = $this->M_skbs->get_by_id($id);
        if (!$data['row']) {
            show_404();
        }
        $this->load->view($this->dir_v.'edit', $data);
    }

    /**
     * Proses simpan SKBS baru
     */
    public function act_add() {

        if (empty(trim($this->input->post('patient_name')))) {
            echo json_encode(array('status' => 1, 'notif' => 'Nama pasien wajib diisi!'));
            return;
        }
        if (empty(trim($this->input->post('skbs_result')))) {
            echo json_encode(array('status' => 1, 'notif' => 'Hasil pemeriksaan wajib dipilih!'));
            return;
        }

        $doct_by_id = intval($this->input->post('doctby'));
        $doct = $this->db->get_where('conf_users', array('id_user' => $doct_by_id))->row();

        $data = array(
            'visit_id'                => null,
            'skbs_patient_name'       => strtoupper(trim($this->input->post('patient_name'))),
            'skbs_patient_nik'        => trim($this->input->post('patient_nik')),
            'skbs_patient_department' => strtoupper(trim($this->input->post('patient_department'))),
            'skbs_patient_company'    => strtoupper(trim($this->input->post('patient_company'))),
            'skbs_patient_ktp'        => trim($this->input->post('patient_ktp')),
            'skbs_patient_age'        => trim($this->input->post('patient_age')),
            'skbs_result_id'          => null,
            'skbs_result_name'        => strtoupper(trim($this->input->post('skbs_result'))),
            'skbs_desc'               => trim($this->input->post('skbs_desc')),
            'skbs_note'               => trim($this->input->post('skbs_note')),
            'skbs_td'                 => trim($this->input->post('skbs_td')),
            'skbs_bw'                 => trim($this->input->post('skbs_bw')),
            'skbs_tb'                 => trim($this->input->post('skbs_tb')),
            'skbs_bb'                 => trim($this->input->post('skbs_bb')),
            'skbs_r'                  => trim($this->input->post('skbs_r')),
            'skbs_l'                  => trim($this->input->post('skbs_l')),
            'skbs_koreksi_r'          => trim($this->input->post('skbs_koreksi_r')),
            'skbs_koreksi_l'          => trim($this->input->post('skbs_koreksi_l')),
            'skbs_doc_date'           => $this->format_date_db($this->input->post('docdate')),
            'skbs_doct_id'            => $doct_by_id,
            'skbs_doct_name'          => $doct ? $doct->fullname : '',
            'skbs_status'             => 1,
            'insert_dt'               => date('Y-m-d H:i:s'),
            'insert_by'               => $this->session->userdata('sess_id'),
        );

        $this->M_skbs->insert($data);

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'SKBS untuk ' . $data['skbs_patient_name'] . ' berhasil ditambahkan!',
        ));
    }

    /**
     * Proses update SKBS
     */
    public function act_edit() {
        $id = intval($this->input->post('id'));

        if (empty(trim($this->input->post('patient_name')))) {
            echo json_encode(array('status' => 1, 'notif' => 'Nama pasien wajib diisi!'));
            return;
        }
        if (empty(trim($this->input->post('skbs_result')))) {
            echo json_encode(array('status' => 1, 'notif' => 'Hasil pemeriksaan wajib dipilih!'));
            return;
        }

        $doct_by_id = intval($this->input->post('doctby'));
        $doct = $this->db->get_where('conf_users', array('id_user' => $doct_by_id))->row();

        $data = array(
            'skbs_patient_name'       => strtoupper(trim($this->input->post('patient_name'))),
            'skbs_patient_nik'        => trim($this->input->post('patient_nik')),
            'skbs_patient_department' => strtoupper(trim($this->input->post('patient_department'))),
            'skbs_patient_company'    => strtoupper(trim($this->input->post('patient_company'))),
            'skbs_patient_ktp'        => trim($this->input->post('patient_ktp')),
            'skbs_patient_age'        => trim($this->input->post('patient_age')),
            'skbs_result_id'          => null,
            'skbs_result_name'        => strtoupper(trim($this->input->post('skbs_result'))),
            'skbs_desc'               => trim($this->input->post('skbs_desc')),
            'skbs_note'               => trim($this->input->post('skbs_note')),
            'skbs_td'                 => trim($this->input->post('skbs_td')),
            'skbs_bw'                 => trim($this->input->post('skbs_bw')),
            'skbs_tb'                 => trim($this->input->post('skbs_tb')),
            'skbs_bb'                 => trim($this->input->post('skbs_bb')),
            'skbs_r'                  => trim($this->input->post('skbs_r')),
            'skbs_l'                  => trim($this->input->post('skbs_l')),
            'skbs_koreksi_r'          => trim($this->input->post('skbs_koreksi_r')),
            'skbs_koreksi_l'          => trim($this->input->post('skbs_koreksi_l')),
            'skbs_doc_date'           => $this->format_date_db($this->input->post('docdate')),
            'skbs_doct_id'            => $doct_by_id,
            'skbs_doct_name'          => $doct ? $doct->fullname : '',
            'update_dt'               => date('Y-m-d H:i:s'),
            'update_by'               => $this->session->userdata('sess_id'),
        );

        $this->M_skbs->update($id, $data);

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'SKBS untuk ' . $data['skbs_patient_name'] . ' berhasil diperbarui!',
        ));
    }

    /**
     * Proses hapus SKBS
     */
    public function act_del() {
        $id  = intval($this->input->post('id'));
        $row = $this->M_skbs->get_by_id($id);

        if (!$row) {
            echo json_encode(array('status' => 1, 'notif' => 'Data SKBS tidak ditemukan!'));
            return;
        }

        $this->M_skbs->delete($id);

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'SKBS ' . htmlspecialchars($row->skbs_patient_name) . ' berhasil dihapus!',
        ));
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

    /**
     * Konversi tanggal dari format dd/mm/yyyy ke yyyy-mm-dd untuk MySQL
     */
    private function format_date_db($date) {
        if (empty($date)) return null;
        $clean = str_replace('/', '-', $date);
        return date('Y-m-d', strtotime($clean));
    }
}
