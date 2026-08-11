<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sks extends CI_Controller {

    public $dir_v = 'sks/';

    public function __construct() {
        parent::__construct();
        $this->m_auth->check_login();
        $this->m_auth->check_akses();
        $this->load->model('M_sks');
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
            'src/js/admin/sks.js',
        );
        $data['panel'] = '<i class="fa fa-file-text"></i> &nbsp;<b>Manajemen SKS</b>';
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
            2 => 'gender',
            3 => 'patient_job',
            4 => 'diagnosa',
            5 => 'docnumb',
            6 => 'docdate',
            7 => 'datefrom',
            8 => 'dateto',
        );

        $order_col = '';
        $order_dir = 'ASC';
        $order = $this->input->get('order');
        if (isset($order[0]) && isset($col_map[$order[0]['column']])) {
            $order_col = $col_map[$order[0]['column']];
            $order_dir = (strtoupper($order[0]['dir'] ?? '') === 'DESC') ? 'DESC' : 'ASC';
        }

        $records_total    = $this->M_sks->count_all();
        $records_filtered = $this->M_sks->count_filtered($search);

        $rows = $this->M_sks->get_datatables($search, $order_col, $order_dir, $start, $length);

        $data = array();
        $i    = $start + 1;

        $gender_label = array(
            'L' => '<span class="badge badge-info">Laki-laki</span>',
            'P' => '<span class="badge badge-danger">Perempuan</span>',
        );

        foreach ($rows->result() as $row) {
            $gender = isset($gender_label[$row->gender])
                ? $gender_label[$row->gender]
                : '<span class="badge badge-secondary">-</span>';

            $data[] = array(
                'DT_RowId'  => $row->id,
                '0'         => $i++,
                '1'         => htmlspecialchars($row->patient_name ?? ''),
                '2'         => $gender,
                '3'         => htmlspecialchars($row->patient_job ?? ''),
                '4'         => htmlspecialchars($row->diagnosa ?? ''),
                '5'         => htmlspecialchars($row->docnumb ?? ''),
                '6'         => !empty($row->docdate) ? date('d/m/Y', strtotime($row->docdate)) : '-',
                '7'         => !empty($row->datefrom) ? date('d/m/Y', strtotime($row->datefrom)) : '-',
                '8'         => !empty($row->dateto) ? date('d/m/Y', strtotime($row->dateto)) : '-',
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
     * Load form tambah SKS (via AJAX ke modal)
     */
    public function add() {
        $dokter = $this->M_sks->get_all_doctor();
        $data['dokter'] = $dokter->result();
        $data['docnumb'] = $this->M_sks->generate_docnumb();
        $data['districts'] = $this->db->select('district_name')
            ->from('ms_district')
            ->where('city_id', 3)
            ->order_by('district_name', 'ASC')
            ->get()->result();
        
        $this->load->view($this->dir_v.'add', $data);
    }

    /**
     * Load form edit SKS (via AJAX ke modal)
     */
    public function edit() {
        $id = intval($this->input->get('id'));
        $dokter = $this->M_sks->get_all_doctor();
        $data['dokter'] = $dokter->result();
        $data['row'] = $this->M_sks->get_by_id($id);
        if (!$data['row']) {
            show_404();
        }
        $data['districts'] = $this->db->select('district_name')
            ->from('ms_district')
            ->where('city_id', 3)
            ->order_by('district_name', 'ASC')
            ->get()->result();
        $this->load->view($this->dir_v.'edit', $data);
    }

    /**
     * View detail SKS (via AJAX ke modal)
     */
    public function detail() {
        $id = intval($this->input->get('id'));
        $data['row'] = $this->M_sks->get_sks_by_id($id);
        if (!$data['row']) {
            show_404();
        }
        $this->load->view($this->dir_v.'detail', $data);
    }

    /**
     * Proses simpan SKS baru
     */
    public function act_add() {
        
        if (empty(trim($this->input->post('alamat')))) {
            echo json_encode(array('status' => 1, 'notif' => 'Alamat pasien wajib diisi!'));
            return;
        }

        // Nomor dokumen: boleh diisi manual, jika kosong di-generate otomatis
        $docnumb = strtoupper(trim($this->input->post('docnumb') ?? ''));
        if (empty($docnumb)) {
            $docnumb = $this->M_sks->generate_docnumb();
        } elseif (!$this->M_sks->is_docnumb_unique($docnumb)) {
            echo json_encode(array('status' => 1, 'notif' => 'Nomor dokumen "' . $docnumb . '" sudah digunakan!'));
            return;
        }

        $data = array(
            'patient_name' => strtoupper(trim($this->input->post('patient_name') ?? '')),
            'sks_nik'      => trim($this->input->post('sks_nik')),
            'patient_job'  => strtoupper(trim($this->input->post('patient_job') ?? '')) ?: 'KARYAWAN',
            'age'          => trim($this->input->post('age')),
            'gender'       => $this->input->post('gender'),
            'alamat'       => trim($this->input->post('alamat')),
            'diagnosa'     => strtoupper(trim($this->input->post('diagnosa') ?? '')),
            'terapi'       => trim($this->input->post('terapi')),
            'docnumb'      => $docnumb,
            'datefrom'     => $this->format_date_db($this->input->post('datefrom')),
            'dateto'       => $this->format_date_db($this->input->post('dateto')),
            'docdate'      => $this->format_date_db($this->input->post('docdate')),
            'doctby'       => strtoupper(trim($this->input->post('doctby') ?? '')),
            'insertby'     => $this->session->userdata('sess_id'),
            'insertdt'     => date('Y-m-d H:i:s'),
        );

        $this->M_sks->insert($data);

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'SKS untuk ' . $data['patient_name'] . ' berhasil ditambahkan! (No. ' . $data['docnumb'] . ')',
        ));
    }

    /**
     * Proses update SKS
     */
    public function act_edit() {
        $id = intval($this->input->post('id'));

        if (empty(trim($this->input->post('alamat')))) {
            echo json_encode(array('status' => 1, 'notif' => 'Alamat pasien wajib diisi!'));
            return;
        }

        // Nomor dokumen wajib diisi & harus unik (kecuali record ini sendiri)
        $docnumb = strtoupper(trim($this->input->post('docnumb') ?? ''));
        if (empty($docnumb)) {
            echo json_encode(array('status' => 1, 'notif' => 'Nomor dokumen wajib diisi!'));
            return;
        }
        if (!$this->M_sks->is_docnumb_unique($docnumb, $id)) {
            echo json_encode(array('status' => 1, 'notif' => 'Nomor dokumen "' . $docnumb . '" sudah digunakan!'));
            return;
        }

        $data = array(
            'patient_name' => strtoupper(trim($this->input->post('patient_name') ?? '')),
            'sks_nik'      => trim($this->input->post('sks_nik')),
            'patient_job'  => strtoupper(trim($this->input->post('patient_job') ?? '')) ?: 'KARYAWAN',
            'age'          => trim($this->input->post('age')),
            'gender'       => $this->input->post('gender'),
            'alamat'       => trim($this->input->post('alamat')),
            'diagnosa'     => strtoupper(trim($this->input->post('diagnosa') ?? '')),
            'terapi'       => trim($this->input->post('terapi')),
            'docnumb'      => $docnumb,
            'datefrom'     => $this->format_date_db($this->input->post('datefrom')),
            'dateto'       => $this->format_date_db($this->input->post('dateto')),
            'docdate'      => $this->format_date_db($this->input->post('docdate')),
            'doctby'       => strtoupper(trim($this->input->post('doctby') ?? '')),
            'updateby'     => $this->session->userdata('sess_id'),
            'updatedt'     => date('Y-m-d H:i:s'),
        );

        $this->M_sks->update($id, $data);

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'SKS untuk ' . $data['patient_name'] . ' berhasil diperbarui!',
        ));
    }

    /**
     * Proses hapus SKS
     */
    public function act_del() {
        $id  = intval($this->input->post('id'));
        $row = $this->M_sks->get_by_id($id);

        if (!$row) {
            echo json_encode(array('status' => 1, 'notif' => 'Data SKS tidak ditemukan!'));
            return;
        }

        $this->M_sks->delete($id);

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'SKS ' . htmlspecialchars($row->patient_name ?? '') . ' berhasil dihapus!',
        ));
    }

    /**
     * Cetak / preview SKS
     */
    public function cetak($id) {
        $data['row'] = $this->M_sks->get_sks_by_id($id);
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

    public function get_age()
    {
        $age                            = $this->input->post('age');
        if(strlen($age) == 10){
            echo $this->l_registrasi->age(date("Y-m-d", strtotime($age))); 
        }else{
            echo "...";
        } 
    }

    function generate_qrcode($sks_number)
    {
        $this->load->helper('string');
        $this->load->library('ciqrcode');
        $encript                        = str_replace('=', '', base64_encode($sks_number . '|' . date('Ymd') . '|SKS'));
        $codeContents                   = base_url('sks_validate/verify/' . $encript);
        $filename                       = "SKS-" . random_string('alnum', 50) . ".png";
        $tempdir                        = "img/temp-qrcode/";
        if (!file_exists($tempdir)) {
            mkdir($tempdir);
        }

        QRcode::png($codeContents, $tempdir . $filename, QR_ECLEVEL_H, 4, 2);
        return base_url($tempdir . $filename);
    }
}
