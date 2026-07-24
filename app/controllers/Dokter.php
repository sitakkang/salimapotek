<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dokter extends CI_Controller {

    public $dir_v = 'dokter/';

    public function __construct() {
        parent::__construct();
        $this->m_auth->check_login();
        $this->m_auth->check_akses();
        $this->load->model('M_dokter');
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
            'src/js/admin/dokter.js',
        );
        $data['panel'] = '<i class="fa fa-user-md"></i> &nbsp;<b>Dokter</b>';
        $this->l_skin->main($this->dir_v.'view', $data);
    }

    /**
     * DataTables JSON source
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
        $rows = $this->M_dokter->get_by_date($date_db);

        $draw = intval($this->input->get('draw'));
        $data = array();
        $i    = 1;

        foreach ($rows->result() as $row) {
            $data[] = array(
                'DT_RowId'  => $row->id_visit,
                '0'         => $i++,
                '1'         => htmlspecialchars($row->trans_patient_code),
                '2'         => htmlspecialchars($row->patient_name),
                '3'         => htmlspecialchars($row->trans_patient_company),
                '4'         => htmlspecialchars($row->trans_patient_phone),
                '5'         => !empty($row->trans_doc) ? date('d/m/Y', strtotime($row->trans_doc)) : '-',
                'patient_name_raw' => htmlspecialchars($row->patient_name),
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

    // ----------------------------------------------------------------
    // Pemeriksaan — halaman utama pemeriksaan pasien
    // ----------------------------------------------------------------

    /**
     * Halaman pemeriksaan pasien
     */
    public function pemeriksaan($visit_id) {
        $data['row'] = $this->M_dokter->get_by_visit_id($visit_id);
        if (!$data['row']) {
            show_404();
        }

        $mrd_id = $data['row']->id_medical_record;

        $data['diagnosa_list']  = $this->M_dokter->get_all_diagnosa();
        $data['obat_list']      = $this->M_dokter->get_all_obat();
        $data['dokter_list']    = $this->M_dokter->get_all_doctor();
        $data['diagnosa_terpilih'] = $this->M_dokter->get_diagnosa_by_medical_record($mrd_id);
        $data['obat_terpilih']  = $this->M_dokter->get_obat_by_medical_record($mrd_id);

        // Generate default diagnosa text for SKS
        $sks_diagnosa_default = '';
        if (!empty($data['diagnosa_terpilih'])) {
            $no = 1;
            foreach ($data['diagnosa_terpilih'] as $d) {
                $sks_diagnosa_default .= $no . '. ' . $d->trans_dgn_name . "\r\n";
                $no++;
            }
        }
        $data['sks_diagnosa_default'] = $sks_diagnosa_default;
        // Generate default terapi dari obat
        $sks_terapi_default = '';
        if (!empty($data['obat_terpilih'])) {
            $no = 1;
            foreach ($data['obat_terpilih'] as $o) {
                $item = trim($o->trans_obat_name);
                if (!empty($o->trans_obat_dosis)) {
                    $item .= ' ' . trim($o->trans_obat_dosis);
                }
                $sks_terapi_default .= $no . '. ' . $item . "\r\n";
                $no++;
            }
        }
        $data['sks_terapi_default'] = $sks_terapi_default;

        $data['sks'] = $this->M_dokter->get_sks_by_visit_id($visit_id);
        $data['sks_list'] = $data['sks'] ? array($data['sks']) : array();
        $data['skbs'] = $this->M_dokter->get_skbs_by_visit_id($visit_id);
        $data['skmb'] = $this->M_dokter->get_skmb_by_visit_id($visit_id);
        $data['dokter_level6'] = $this->M_dokter->get_users_by_level(3);

        $data['css'] = array(
            'lib/datatables/dataTables.bootstrap.min.css',
            'lib/datatables/fixedColumns.bootstrap.min.css',
            'lib/datepicker/datepicker.min.css',
            'lib/select/component-chosen.min.css',
        );
        $data['js'] = array(
            'lib/bootstrap/js/bootstrap.min.js',
            'lib/datatables/datatables.min.js',
            'lib/datatables/dataTables.bootstrap.min.js',
            'lib/datepicker/datepicker.min.js',
            'lib/select/chosen.jquery.min.js',
            'lib/mask/jquery.mask.min.js',
            'lib/sweetalert/sweetalert2.all.min.js',
            'src/js/admin/dokter.js',
            'src/js/admin/pemeriksaandokter.js',
        );
        $data['panel'] = '<i class="fa fa-stethoscope"></i> &nbsp;<b>Pemeriksaan Pasien</b>';
        $this->l_skin->main($this->dir_v.'pemeriksaan', $data);
    }

    // ----------------------------------------------------------------
    // Diagnosa AJAX
    // ----------------------------------------------------------------

    /**
     * Simpan diagnosa via AJAX
     */
    public function act_add_diagnosa() {
        $medical_record_id = intval($this->input->post('medical_record_id'));
        $dgn_id = intval($this->input->post('dgn_id'));

        if (!$medical_record_id || !$dgn_id) {
            echo json_encode(array('status' => 1, 'notif' => 'Data tidak lengkap!'));
            return;
        }

        // Ambil nama & cat dari ms_diagnosa
        $dgn = $this->db->get_where('ms_diagnosa', array('id_diagnosa' => $dgn_id))->row();
        if (!$dgn) {
            echo json_encode(array('status' => 1, 'notif' => 'Diagnosa tidak ditemukan!'));
            return;
        }

        // Cek duplikat
        $exists = $this->db->get_where('trans_diagnosa', array(
            'medical_record_id' => $medical_record_id,
            'trans_dgn_name' => $dgn->dgn_name,
            'trans_dgn_status' => 1
        ))->row();
        if ($exists) {
            echo json_encode(array('status' => 1, 'notif' => 'Diagnosa sudah ditambahkan!'));
            return;
        }

        $data = array(
            'medical_record_id'  => $medical_record_id,
            'trans_dgn_name'     => $dgn->dgn_name,
            'trans_dgn_cat'      => $dgn->dgn_cat,
            'trans_dgn_note'     => trim($this->input->post('dgn_note')),
            'trans_dgn_status'   => 1,
            'trans_dgn_insert_dt' => date('Y-m-d H:i:s'),
            'trans_dgn_insert_by' => $this->session->userdata('sess_id'),
        );
        $this->M_dokter->insert_diagnosa($data);

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'Diagnosa berhasil ditambahkan!',
        ));
    }

    /**
     * Hapus diagnosa via AJAX
     */
    public function act_del_diagnosa() {
        $id = intval($this->input->post('id'));
        $this->M_dokter->delete_diagnosa($id);
        echo json_encode(array('status' => 2, 'notif' => 'Diagnosa berhasil dihapus!'));
    }

    // ----------------------------------------------------------------
    // Obat AJAX
    // ----------------------------------------------------------------

    /**
     * Simpan obat via AJAX
     */
    public function act_add_obat() {
        $medical_record_id = intval($this->input->post('medical_record_id'));
        $obat_id = intval($this->input->post('obat_id'));

        if (!$medical_record_id || !$obat_id) {
            echo json_encode(array('status' => 1, 'notif' => 'Data tidak lengkap!'));
            return;
        }

        $obat = $this->db->get_where('ms_obat', array('id_obat' => $obat_id))->row();
        if (!$obat) {
            echo json_encode(array('status' => 1, 'notif' => 'Obat tidak ditemukan!'));
            return;
        }

        $qty = intval($this->input->post('qty'));
        if ($qty < 1) $qty = 1;

        $dosis = trim($this->input->post('dosis'));

        $data = array(
            'medical_record_id'    => $medical_record_id,
            'obat_id'              => $obat_id,
            'trans_obat_name'      => $obat->obat_name,
            'trans_obat_satuan'    => $obat->obat_satuan,
            'trans_obat_price'     => $obat->obat_price,
            'trans_obat_qty'       => $qty,
            'trans_obat_dosis'     => $dosis,
            'trans_obat_total_price' => $qty * $obat->obat_price,
            'trans_obat_status'    => 1,
            'trans_obat_insert_dt' => date('Y-m-d H:i:s'),
            'trans_obat_insert_by' => $this->session->userdata('sess_id'),
        );
        $this->M_dokter->insert_obat($data);

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'Obat berhasil ditambahkan!',
        ));
    }

    /**
     * Hapus obat via AJAX
     */
    public function act_del_obat() {
        $id = intval($this->input->post('id'));
        $this->M_dokter->delete_obat($id);
        echo json_encode(array('status' => 2, 'notif' => 'Obat berhasil dihapus!'));
    }

    // ----------------------------------------------------------------
    // SKS AJAX
    // ----------------------------------------------------------------

    /**
     * Buat SKS dari halaman pemeriksaan
     */
    public function act_buat_sks() {
        $visit_id = intval($this->input->post('visit_id'));
        $row = $this->M_dokter->get_by_visit_id($visit_id);
        if (!$row) {
            echo json_encode(array('status' => 1, 'notif' => 'Data pasien tidak ditemukan!'));
            return;
        }

        if (!$row->trans_doct_by) {
            echo json_encode(array('status' => 1, 'notif' => 'Data dokter pemeriksa belum ditentukan!'));
            return;
        }

        $existing = $this->M_dokter->get_sks_by_visit_id($visit_id);
        $now      = date('Y-m-d H:i:s');
        $insertby = $this->session->userdata('sess_id');
        $doct_by  = $row->trans_doct_by;

        $age    = !empty($row->patient_bod) ? date_diff(date_create($row->patient_bod), date_create('now'))->y : ($existing->age ?? '');
        $gender = !empty($row->patient_gender) ? $row->patient_gender : ($existing->gender ?? '');

        $data = array(
            'visit_id'     => $visit_id,
            'patient_name' => strtoupper(trim($row->patient_name)),
            'company_name' => strtoupper(trim($row->trans_patient_company)),
            'age'          => $age,
            'gender'       => $gender,
            'diagnosa'     => strtoupper(trim($this->input->post('diagnosa'))),
            'terapi'       => trim($this->input->post('terapi')),
            'datefrom'     => $this->format_date_db($this->input->post('datefrom')),
            'dateto'       => $this->format_date_db($this->input->post('dateto')),
            'docdate'      => $this->format_date_db($this->input->post('docdate')),
            'doctby'       => $doct_by,
            'desa'         => strtoupper(trim($row->patient_address)),
            'kecamatan_id' => $row->patient_city_id,
            'kecamatan'    => strtoupper(trim($row->trans_patient_city_name)),
            'kelurahan_id' => $row->patient_district_id,
            'kelurahan'    => strtoupper(trim($row->trans_patient_district_name)),
            'kabupaten'    => 'MOROWALI',
            'provinsi'     => 'SULAWESI TENGAH',
        );

        if ($existing) {
            // UPDATE
            $data['updateby'] = $insertby;
            $data['updatedt'] = $now;
            $this->M_dokter->update_sks($existing->id, $data);
            $msg = 'SKS berhasil diperbarui! (No. ' . htmlspecialchars($existing->docnumb) . ')';
        } else {
            // INSERT
            $data['docnumb']  = $this->M_dokter->generate_docnumb_sks();
            $data['insertby'] = $insertby;
            $data['insertdt'] = $now;
            $this->M_dokter->insert_sks($data);
            $msg = 'SKS berhasil dibuat! (No. ' . $data['docnumb'] . ')';
        }

        echo json_encode(array(
            'status' => 2,
            'notif'  => $msg,
        ));
    }

    /**
     * Hapus SKS
     */
    public function act_del_sks() {
        $id  = intval($this->input->post('id'));
        $row = $this->M_dokter->get_sks_by_id($id);

        if (!$row) {
            echo json_encode(array('status' => 1, 'notif' => 'Data SKS tidak ditemukan!'));
            return;
        }

        $this->M_dokter->delete_sks($id);

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'SKS ' . htmlspecialchars($row->docnumb) . ' berhasil dihapus!',
        ));
    }

    /**
     * Cetak / preview SKS (sama seperti module SKS)
     */
    public function cetak_sks($id) {
        $this->load->model('M_sks');
        $data['row'] = $this->M_sks->get_sks_by_id($id);
        $data['qrcode'] = $this->generate_qrcode($id);
        if (!$data['row']) {
            show_404();
        }
        $this->load->view('sks/cetak', $data);
    }

    /**
     * Generate QRCode untuk cetak SKS
     */
    private function generate_qrcode($sks_number) {
        $this->load->helper('string');
        $this->load->library('ciqrcode');
        $encript      = str_replace('=', '', base64_encode($sks_number . '|' . date('Ymd') . '|SKS'));
        $codeContents = base_url('sks_validate/verify/' . $encript);
        $filename     = "SKS-" . random_string('alnum', 50) . ".png";
        $tempdir      = "img/temp-qrcode/";
        if (!file_exists($tempdir)) {
            mkdir($tempdir);
        }
        QRcode::png($codeContents, $tempdir . $filename, QR_ECLEVEL_H, 4, 2);
        return base_url($tempdir . $filename);
    }

    // ----------------------------------------------------------------
    // AJAX partial reloads
    // ----------------------------------------------------------------

    /**
     * Reload diagnosa table HTML
     */
    public function reload_diagnosa() {
        $visit_id = intval($this->input->get('visit_id'));
        $row = $this->M_dokter->get_by_visit_id($visit_id);
        if (!$row) return;
        $diagnosa_terpilih = $this->M_dokter->get_diagnosa_by_medical_record($row->id_medical_record);
        $this->load->view($this->dir_v.'_diagnosa_table', array('diagnosa_terpilih' => $diagnosa_terpilih));
    }

    /**
     * Reload obat table HTML
     */
    public function reload_obat() {
        $visit_id = intval($this->input->get('visit_id'));
        $row = $this->M_dokter->get_by_visit_id($visit_id);
        if (!$row) return;
        $obat_terpilih = $this->M_dokter->get_obat_by_medical_record($row->id_medical_record);
        $this->load->view($this->dir_v.'_obat_table', array('obat_terpilih' => $obat_terpilih));
    }

    /**
     * Reload SKS section HTML (form + list)
     */
    public function reload_sks() {
        $visit_id = intval($this->input->get('visit_id'));
        $row = $this->M_dokter->get_by_visit_id($visit_id);
        if (!$row) return;

        $mrd_id = $row->id_medical_record;
        $sks = $this->M_dokter->get_sks_by_visit_id($visit_id);
        $data['row'] = $row;
        $data['sks'] = $sks;
        $data['sks_list'] = $sks ? array($sks) : array();
        $data['dokter_list'] = $this->M_dokter->get_all_doctor();

        // Diagnosa default text
        $diagnosa_terpilih = $this->M_dokter->get_diagnosa_by_medical_record($mrd_id);
        $sks_diagnosa_default = '';
        if (!empty($diagnosa_terpilih)) {
            $no = 1;
            foreach ($diagnosa_terpilih as $d) {
                $sks_diagnosa_default .= $no . '. ' . $d->trans_dgn_name . "\r\n";
                $no++;
            }
        }
        $data['sks_diagnosa_default'] = $sks_diagnosa_default;

        // Generate default terapi dari obat
        $obat_terpilih = $this->M_dokter->get_obat_by_medical_record($mrd_id);
        $sks_terapi_default = '';
        if (!empty($obat_terpilih)) {
            $no = 1;
            foreach ($obat_terpilih as $o) {
                $item = trim($o->trans_obat_name);
                if (!empty($o->trans_obat_dosis)) {
                    $item .= ' ' . trim($o->trans_obat_dosis);
                }
                $sks_terapi_default .= $no . '. ' . $item . "\r\n";
                $no++;
            }
        }
        $data['sks_terapi_default'] = $sks_terapi_default;

        $this->load->view($this->dir_v.'_sks_section', $data);
    }

    // ----------------------------------------------------------------
    // SKBS AJAX
    // ----------------------------------------------------------------

    /**
     * Simpan / Update SKBS
     */
    public function act_simpan_skbs() {
        $visit_id = intval($this->input->post('visit_id'));
        $row = $this->M_dokter->get_by_visit_id($visit_id);
        if (!$row) {
            echo json_encode(array('status' => 1, 'notif' => 'Data pasien tidak ditemukan!'));
            return;
        }
        if(!$row->trans_doct_by){
            echo json_encode(array('status' => 1, 'notif' => 'Data dokter pemeriksa belum ditentukan!'));
            return;
        }

        $existing = $this->M_dokter->get_skbs_by_visit_id($visit_id);
        $now = date('Y-m-d H:i:s');
        $insert_by = $row->trans_doct_by;
        $doct_by = $this->M_dokter->get_doct_by($insert_by)->fullname;


        $data = array(
            'visit_id'             => $visit_id,
            'skbs_patient_name'    => strtoupper(trim($row->patient_name)),
            'skbs_patient_nik'     => $row->patient_nik,
            'skbs_patient_department' => strtoupper(trim($row->trans_patient_department)),
            'skbs_patient_company' => strtoupper(trim($row->trans_patient_company)),
            'skbs_patient_ktp'     => $row->patient_ktp,
            'skbs_patient_age'     => !empty($row->patient_bod) ? date_diff(date_create($row->patient_bod), date_create('now'))->y : '0',
            'skbs_result_id'       => null,
            'skbs_result_name'     => strtoupper(trim($this->input->post('skbs_result'))),
            'skbs_desc'            => trim($this->input->post('skbs_desc')),
            'skbs_note'            => trim($this->input->post('skbs_note')),
            'skbs_td'              => trim($this->input->post('skbs_td')),
            'skbs_bw'              => trim($this->input->post('skbs_bw')),
            'skbs_tb'              => trim($this->input->post('skbs_tb')),
            'skbs_bb'              => trim($this->input->post('skbs_bb')),
            'skbs_r'               => trim($this->input->post('skbs_r')),
            'skbs_l'               => trim($this->input->post('skbs_l')),
            'skbs_koreksi_r'       => trim($this->input->post('skbs_koreksi_r')),
            'skbs_koreksi_l'       => trim($this->input->post('skbs_koreksi_l')),
            'skbs_doc_date'        => date('Y-m-d'),
            'skbs_doct_id'         => $insert_by,
            'skbs_doct_name'       => $doct_by,
            'skbs_status'          => 1,
        );

        if ($existing) {
            $data['update_dt'] = $now;
            $data['update_by'] = $insert_by;
            $this->M_dokter->update_skbs($existing->id_skbs, $data);
            $msg = 'SKBS berhasil diperbarui!';
        } else {
            $data['insert_dt'] = $now;
            $data['insert_by'] = $insert_by;
            $this->M_dokter->insert_skbs($data);
            $msg = 'SKBS berhasil disimpan!';
        }

        echo json_encode(array('status' => 2, 'notif' => $msg));
    }

    /**
     * Cetak SKBS
     */
    public function cetak_skbs($id) {
        $row = $this->db->get_where('trans_skbs', array('id_skbs' => intval($id)))->row();
        if (!$row) show_404();

        // Ambil NIP dokter dari conf_users
        $doct = $this->db->get_where('conf_users', array('id_user' => intval($row->skbs_doct_id)))->row();
        $row->nip = $doct ? $doct->nip : '';

        // Generate nomor dokumen
        $month_roman = $this->month_roman(date('n'));
        $year = date('Y');
        $data['docnumb'] = sprintf('%05d', $row->id_skbs) . '/IMIP-SKBS/' . $month_roman . '/' . $year;
        $data['row'] = $row;
        $data['qrcode'] = $this->generate_qrcode_skbs($id);

        $this->load->view('dokter/_skbs_cetak', $data);
    }

    /**
     * Generate QRCode untuk cetak SKBS
     */
    private function generate_qrcode_skbs($id) {
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
        $map = array(1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
                     7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII');
        return isset($map[$n]) ? $map[$n] : '';
    }

    /**
     * Hapus SKBS
     */
    public function act_del_skbs() {
        $id = intval($this->input->post('id'));
        $this->db->where('id_skbs', $id);
        $this->db->update('trans_skbs', array('skbs_status' => 0));
        echo json_encode(array('status' => 2, 'notif' => 'SKBS berhasil dihapus!'));
    }

    /**
     * Reload SKBS section HTML
     */
    public function reload_skbs() {
        $visit_id = intval($this->input->get('visit_id'));
        $row = $this->M_dokter->get_by_visit_id($visit_id);
        if (!$row) return;

        $data['row'] = $row;
        $data['skbs'] = $this->M_dokter->get_skbs_by_visit_id($visit_id);
        $data['dokter_list'] = $this->M_dokter->get_all_doctor();
        $this->load->view($this->dir_v.'_skbs_section', $data);
    }

    // ----------------------------------------------------------------
    // SKMB AJAX
    // ----------------------------------------------------------------

    /**
     * Simpan / Update SKMB
     */
    public function act_simpan_skmb() {
        $visit_id = intval($this->input->post('visit_id'));
        $row = $this->M_dokter->get_by_visit_id($visit_id);
        if (!$row) {
            echo json_encode(array('status' => 1, 'notif' => 'Data pasien tidak ditemukan!'));
            return;
        }
        if (!$row->trans_doct_by) {
            echo json_encode(array('status' => 1, 'notif' => 'Data dokter pemeriksa belum ditentukan!'));
            return;
        }

        $existing = $this->M_dokter->get_skmb_by_visit_id($visit_id);
        $now = date('Y-m-d H:i:s');
        $insert_by = $row->trans_doct_by;
        $doct = $this->M_dokter->get_doct_by($insert_by);

        $data = array(
            'visit_id'          => $visit_id,
            'patient_name'      => strtoupper(trim($this->input->post('patient_name'))),
            'nik'               => trim($this->input->post('nik')),
            'company_name'      => strtoupper(trim($this->input->post('company_name'))),
            'bagian'            => strtoupper(trim($this->input->post('bagian'))),
            'patient_diantar'   => strtoupper(trim($this->input->post('patient_diantar'))),
            'age_diantar'       => trim($this->input->post('age_diantar')),
            'alamat_diantar'    => strtoupper(trim($this->input->post('alamat_diantar'))),
            'hubungan'          => $this->input->post('hubungan'),
            'tgl_datang'        => $this->format_date_db($this->input->post('tgl_datang')),
            'jam'               => trim($this->input->post('jam')),
            'docdate'           => date('Y-m-d'),
            'doct_by_id'        => $insert_by,
            'doct_by_name'      => $doct ? $doct->fullname : '',
        );

        if ($existing) {
            $data['updateby'] = $insert_by;
            $data['updatedt'] = $now;
            $this->M_dokter->update_skmb($existing->id, $data);
            $msg = 'SKMB berhasil diperbarui!';
        } else {
            $data['docnumb']  = $this->generate_docnumb_skmb();
            $data['insertby'] = $insert_by;
            $data['insertdt'] = $now;
            $this->M_dokter->insert_skmb($data);
            $msg = 'SKMB berhasil disimpan!';
        }

        echo json_encode(array('status' => 2, 'notif' => $msg));
    }

    /**
     * Cetak SKMB
     */
    public function cetak_skmb($id) {
        $row = $this->db->get_where('skmb', array('id' => intval($id)))->row();
        if (!$row) show_404();

        // Ambil NIP & nama dokter dari conf_users
        $doct = $this->db->get_where('conf_users', array('id_user' => intval($row->doct_by_id)))->row();
        $row->nip = $doct ? $doct->nip : '';
        $row->fullname = $doct ? $doct->fullname : '';

        // Generate nomor dokumen
        $month_roman = $this->month_roman(date('n'));
        $year = date('Y');
        $data['docnumb'] = !empty($row->docnumb) ? $row->docnumb : sprintf('%05d', $row->id) . '/SKMB/' . $month_roman . '/' . $year;
        $data['row'] = $row;
        $data['qrcode'] = $this->generate_qrcode_skmb($id);

        $this->load->view('skmb/cetak', $data);
    }

    /**
     * Generate QRCode untuk cetak SKMB
     */
    private function generate_qrcode_skmb($id) {
        $this->load->helper('string');
        $this->load->library('ciqrcode');
        $encript      = str_replace('=', '', base64_encode($id . '|' . date('Ymd') . '|SKMB'));
        $codeContents = base_url('sks_validate/verify/' . $encript);
        $filename     = "SKMB-" . random_string('alnum', 50) . ".png";
        $tempdir      = "img/temp-qrcode/";
        if (!file_exists($tempdir)) mkdir($tempdir);
        QRcode::png($codeContents, $tempdir . $filename, QR_ECLEVEL_H, 4, 2);
        return base_url($tempdir . $filename);
    }

    /**
     * Hapus SKMB
     */
    public function act_del_skmb() {
        $id = intval($this->input->post('id'));
        $this->M_dokter->delete_skmb($id);
        echo json_encode(array('status' => 2, 'notif' => 'SKMB berhasil dihapus!'));
    }

    /**
     * Reload SKMB section HTML
     */
    public function reload_skmb() {
        $visit_id = intval($this->input->get('visit_id'));
        $row = $this->M_dokter->get_by_visit_id($visit_id);
        if (!$row) return;

        $data['row'] = $row;
        $data['skmb'] = $this->M_dokter->get_skmb_by_visit_id($visit_id);
        $data['dokter_list'] = $this->M_dokter->get_all_doctor();
        $this->load->view($this->dir_v.'_skmb_section', $data);
    }

    /**
     * Generate nomor dokumen SKMB (running number per bulan)
     */
    private function generate_docnumb_skmb() {
        $month_roman = $this->month_roman(date('n'));
        $year = date('Y');

        $last = $this->db->query(
            "SELECT docnumb FROM skmb
             WHERE docnumb LIKE '%/SKMB/" . $month_roman . "/$year'
             ORDER BY id DESC LIMIT 1"
        )->row();

        if ($last) {
            $parts = explode('/', $last->docnumb);
            $next  = intval($parts[0]) + 1;
        } else {
            $next = 1;
        }

        return sprintf('%05d', $next) . '/SKMB/' . $month_roman . '/' . $year;
    }

    /**
     * Batalkan pemeriksaan
     */
    public function act_batal() {
        $visit_id = intval($this->input->post('visit_id'));
        $now = date('Y-m-d H:i:s');
        $user_id = $this->session->userdata('sess_id');

        // Update trans_visit
        $this->db->where('id_visit', $visit_id);
        $this->db->update('trans_visit', array(
            'trans_status'   => 0,
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

        // Hapus SKS dengan visit_id tersebut
        $this->db->where('visit_id', $visit_id);
        $this->db->delete('sks');

        // Hapus SKBS dengan visit_id tersebut
        $this->db->where('visit_id', $visit_id);
        $this->db->delete('trans_skbs');

        // Hapus SKMB dengan visit_id tersebut
        $this->db->where('visit_id', $visit_id);
        $this->db->delete('skmb');

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'Pemeriksaan berhasil dibatalkan.',
        ));
    }

    // ----------------------------------------------------------------
    // Update Dokter Pemeriksa
    // ----------------------------------------------------------------

    /**
     * Update dokter pemeriksa (mrd_doct_by) via AJAX
     */
    public function act_update_doctor() {
        $visit_id = intval($this->input->post('visit_id'));
        $doctor_id = intval($this->input->post('doctor_id'));

        if (!$visit_id || !$doctor_id) {
            echo json_encode(array('status' => 1, 'notif' => 'Data tidak lengkap!'));
            return;
        }

        $this->db->where('visit_id', $visit_id);
        $this->db->update('trans_medical_record', array(
            'mrd_doct_by' => $doctor_id,
        ));

        $this->db->where('id_visit', $visit_id);
        $this->db->update('trans_visit', array(
            'trans_doct_by' => $doctor_id,
        ));

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'Dokter pemeriksa berhasil diubah.',
        ));
    }

    // ----------------------------------------------------------------
    // Helper
    // ----------------------------------------------------------------

    private function format_date_db($date) {
        if (empty($date)) return null;
        $clean = str_replace('/', '-', $date);
        return date('Y-m-d', strtotime($clean));
    }
}
