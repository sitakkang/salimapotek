<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patient extends CI_Controller {

    public $dir_v = 'patient/';

    public function __construct() {
        parent::__construct();
        $this->m_auth->check_login();
        $this->m_auth->check_akses();
        $this->load->model('M_patient');
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
            'src/js/admin/patient.js',
        );
        $data['panel'] = '<i class="fa fa-user-injured"></i> &nbsp;<b>Manajemen Pendaftaran</b>';
        $this->l_skin->main($this->dir_v.'view', $data);
    }

    /**
     * DataTables JSON source — with search filters
     */
    public function table() {
        $rm   = trim($this->input->get('rm'));
        $ktp  = trim($this->input->get('ktp'));
        $nama = trim($this->input->get('nama'));

        // If no search params, return empty result set
        if (empty($rm) && empty($ktp) && empty($nama)) {
            echo json_encode(array(
                'draw'            => intval($this->input->get('draw')),
                'recordsTotal'    => 0,
                'recordsFiltered' => 0,
                'data'            => array(),
            ));
            exit();
        }

        $rows = $this->M_patient->search($rm, $ktp, $nama);

        $draw = intval($this->input->get('draw'));
        $data = array();
        $i    = 1;

        $status_label = array(
            1 => '<span class="badge badge-success">Aktif</span>',
            0 => '<span class="badge badge-secondary">Nonaktif</span>',
        );

        foreach ($rows->result() as $row) {
            $status = isset($status_label[$row->patient_status])
                ? $status_label[$row->patient_status]
                : '<span class="badge badge-secondary">-</span>';

            $data[] = array(
                'DT_RowId'  => $row->id_patient,
                '0'         => $i++,
                '1'         => htmlspecialchars($row->patient_code ?? ''),
                '2'         => htmlspecialchars($row->patient_name ?? ''),
                'jk'        => $row->patient_gender,
                '3'         => htmlspecialchars($row->patient_ktp ?? ''),
                '4'         => htmlspecialchars($row->patient_nik ?? ''),
                '5'         => htmlspecialchars($row->patient_company ?? ''),
                '6'         => htmlspecialchars($row->patient_phone ?? ''),
                '7'         => $status,
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
     * Load form tambah Patient (via AJAX ke modal)
     */
    public function add() {
        $data['districts'] = $this->db->select('district_name')
            ->from('ms_district')
            ->where('city_id', 3)
            ->order_by('district_name', 'ASC')
            ->get()->result();
        $this->load->view($this->dir_v.'add', $data);
    }

    /**
     * Load form edit Patient (via AJAX ke modal)
     */
    public function edit() {
        $id = intval($this->input->get('id'));
        $data['row'] = $this->M_patient->get_by_id($id);
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
     * View detail Patient (via AJAX ke modal)
     */
    public function detail() {
        $id = intval($this->input->get('id'));
        $data['row'] = $this->M_patient->get_by_id($id);
        if (!$data['row']) {
            show_404();
        }
        $this->load->view($this->dir_v.'detail', $data);
    }

    /**
     * Proses simpan Patient baru
     */
    public function act_add() {
        $ktp = trim($this->input->post('patient_ktp'));
        $nik = trim($this->input->post('patient_nik'));

        // Validasi duplikat KTP / NIK (hanya jika tidak kosong)
        if (!empty($ktp) || !empty($nik)) {
            $existing = $this->M_patient->check_duplicate($ktp, $nik);
            if ($existing) {
                $field = '';
                if (!empty($ktp) && $existing->patient_ktp === $ktp) {
                    $field = 'KTP';
                } elseif (!empty($nik) && $existing->patient_nik === $nik) {
                    $field = 'No. ID';
                }
                echo json_encode(array(
                    'status' => 1,
                    'notif'  => $field . ' sudah terdaftar atas nama <strong>' . htmlspecialchars($existing->patient_name ?? '') . '</strong> (NO. RM: ' . htmlspecialchars($existing->patient_code ?? '') . '). Data tidak dapat disimpan.',
                ));
                return;
            }
        }

        $data = array(
            'patient_code'        => $this->M_patient->generate_patient_code(),
            'patient_name'        => strtoupper(trim($this->input->post('patient_name') ?? '')),
            'patient_nik'         => $nik,
            'patient_company'     => strtoupper(trim($this->input->post('patient_company') ?? '')),
            'patient_job'         => strtoupper(trim($this->input->post('patient_job') ?? '')),
            'patient_ktp'         => $ktp,
            'patient_birth_place' => strtoupper(trim($this->input->post('patient_birth_place') ?? '')),
            'patient_gender'      => $this->input->post('patient_gender'),
            'patient_bod'         => $this->format_date_db($this->input->post('patient_bod')),
            'patient_address'     => trim($this->input->post('patient_address') ?? ''),
            'patient_phone'       => trim($this->input->post('patient_phone')),
            'patient_status'      => 1,
            'insert_by'            => $this->session->userdata('sess_id'),
            'insert_dt'            => date('Y-m-d H:i:s'),
        );

        $this->M_patient->insert($data);

        header('Content-Type: application/json');
        echo json_encode(array(
            'status'       => 2,
            'notif'        => 'Pasien ' . $data['patient_name'] . ' berhasil ditambahkan! (Kode: ' . $data['patient_code'] . ')',
            'patient_code' => $data['patient_code'],
        ));
    }

    /**
     * Proses update Patient
     */
    public function act_edit() {
        $id = intval($this->input->post('id'));

        $data = array(
            'patient_name'        => strtoupper(trim($this->input->post('patient_name') ?? '')),
            'patient_nik'         => trim($this->input->post('patient_nik')),
            'patient_company'     => strtoupper(trim($this->input->post('patient_company') ?? '')),
            'patient_job'         => strtoupper(trim($this->input->post('patient_job') ?? '')),
            'patient_ktp'         => trim($this->input->post('patient_ktp')),
            'patient_birth_place' => strtoupper(trim($this->input->post('patient_birth_place') ?? '')),
            'patient_gender'      => $this->input->post('patient_gender'),
            'patient_bod'         => $this->format_date_db($this->input->post('patient_bod')),
            'patient_address'     => trim($this->input->post('patient_address') ?? ''),
            'patient_phone'       => trim($this->input->post('patient_phone')),
            'updateby'            => $this->session->userdata('sess_id'),
            'updatedt'            => date('Y-m-d H:i:s'),
        );

        $this->M_patient->update($id, $data);

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'Pasien ' . $data['patient_name'] . ' berhasil diperbarui!',
        ));
    }

    /**
     * Proses hapus Patient
     */
    public function act_del() {
        $id  = intval($this->input->post('id'));
        $row = $this->M_patient->get_by_id($id);

        if (!$row) {
            echo json_encode(array('status' => 1, 'notif' => 'Data pasien tidak ditemukan!'));
            return;
        }

        $this->M_patient->delete($id);

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'Pasien ' . htmlspecialchars($row->patient_name ?? '') . ' berhasil dinonaktifkan!',
        ));
    }

    // ----------------------------------------------------------------
    // Pendaftaran (Daftar) — buat trans_visit + trans_medical_record
    // ----------------------------------------------------------------

    /**
     * Load form pendaftaran (via AJAX ke modal)
     */
    public function daftar() {
        $id = intval($this->input->get('id'));
        $data['row'] = $this->M_patient->get_by_id($id);
        if (!$data['row']) {
            show_404();
        }
        $this->load->view($this->dir_v.'daftar', $data);
    }

    /**
     * Proses simpan pendaftaran pasien
     */
    public function act_daftar() {
        $id = intval($this->input->post('id'));
        $trans_doc = trim($this->input->post('trans_doc'));

        if (empty($id) || empty($trans_doc)) {
            echo json_encode(array('status' => 1, 'notif' => 'Data tidak lengkap!'));
            return;
        }

        $row = $this->M_patient->get_by_id($id);
        if (!$row) {
            echo json_encode(array('status' => 1, 'notif' => 'Data pasien tidak ditemukan!'));
            return;
        }

        $now = date('Y-m-d H:i:s');
        $insert_by = $this->session->userdata('sess_id');

        // 1. Insert trans_visit
        $visit_data = array(
            'patient_id'              => $row->id_patient,
            'trans_patient_code'      => $row->patient_code,
            'trans_patient_company'   => $row->patient_company,
            'trans_patient_department'=> $row->patient_department,
            'trans_patient_city_id'   => $row->patient_city_id,
            'trans_patient_city_name' => $row->patient_city_name,
            'trans_patient_district_id' => $row->patient_district_id,
            'trans_patient_district_name' => $row->patient_district_name,
            'trans_patient_phone'     => $row->patient_phone,
            'trans_vst_type'          => 1,
            'trans_doc'               => $this->format_date_db($trans_doc),
            'trans_insert_dt'         => $now,
            'trans_insert_by'         => $insert_by,
            'trans_status'            => 1,
        );
        $visit_id = $this->M_patient->insert_visit($visit_data);

        // 2. Insert trans_medical_record
        $mrd_data = array(
            'visit_id'        => $visit_id,
            'mrd_status'      => 1,
            'mrd_vst_type'    => 1,
            'mrd_doct_by'     => null,
            'mrd_insert_dt'   => $now,
            'mrd_insert_by'   => $insert_by,
        );
        $this->M_patient->insert_medical_record($mrd_data);

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'Pasien <strong>' . htmlspecialchars($row->patient_name ?? '') . '</strong> (NO. RM: ' . htmlspecialchars($row->patient_code ?? '') . ') berhasil didaftarkan!',
        ));
    }

    /**
     * Konversi tanggal dari format dd/mm/yyyy ke yyyy-mm-dd untuk MySQL
     */
    private function format_date_db($date) {
        if (empty($date)) return null;
        $clean = str_replace('/', '-', $date);
        $timestamp = strtotime($clean);
        if (!$timestamp) return null;
        return date('Y-m-d', $timestamp);
    }

}
