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
     * DataTables JSON source
     */
    public function table() {
        $rows = $this->M_sks->get_all();

        $draw = intval($this->input->get('draw'));
        $data = array();
        $i    = 1;

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
                '1'         => htmlspecialchars($row->patient_name),
                '2'         => $gender,
                '3'         => htmlspecialchars($row->company_name),
                '4'         => htmlspecialchars($row->diagnosa),
                '5'         => htmlspecialchars($row->docnumb),
                '6'         => !empty($row->docdate) ? date('d/m/Y', strtotime($row->docdate)) : '-',
                '7'         => !empty($row->datefrom) ? date('d/m/Y', strtotime($row->datefrom)) : '-',
                '8'         => !empty($row->dateto) ? date('d/m/Y', strtotime($row->dateto)) : '-',
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
     * Load form tambah SKS (via AJAX ke modal)
     */
    public function add() {
        $dokter = $this->M_sks->get_all_doctor();
        $data['dokter'] = $dokter->result();
        $data['kecamatan'] = $this->get_kecamatan(); 
        $data['kelurahan'] = $this->get_kelurahan_default(); 
        
        $this->load->view($this->dir_v.'add', $data);
    }

    /**
     * Load form edit SKS (via AJAX ke modal)
     */
    public function edit() {
        $id = intval($this->input->get('id'));
        $dokter = $this->M_sks->get_all_doctor();
        $data['dokter'] = $dokter->result();
        $data['kecamatan'] = $this->get_kecamatan(); 
        $data['kelurahan'] = $this->get_kelurahan_default(); 
        $data['row'] = $this->M_sks->get_by_id($id);
        if (!$data['row']) {
            show_404();
        }
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
        

        $data = array(
            'patient_name' => strtoupper(trim($this->input->post('patient_name'))),
            'company_name' => strtoupper(trim($this->input->post('company_name'))),
            'age'          => trim($this->input->post('age')),
            'gender'       => $this->input->post('gender'),
            'desa'         => strtoupper(trim($this->input->post('desa'))),
            'kecamatan_id' => $this->input->post('id_kecamatan'),
            'kecamatan'    => strtoupper(trim($this->input->post('kecamatan'))),
            'kelurahan_id' => $this->input->post('id_kelurahan'),
            'kelurahan'    => strtoupper(trim($this->input->post('kelurahan'))),
            'kabupaten'    => strtoupper(trim($this->input->post('kabupaten'))),
            'provinsi'     => strtoupper(trim($this->input->post('provinsi'))),
            'diagnosa'     => strtoupper(trim($this->input->post('diagnosa'))),
            'terapi'       => trim($this->input->post('terapi')),
            'docnumb'      => $this->M_sks->generate_docnumb(),
            'datefrom'     => $this->format_date_db($this->input->post('datefrom')),
            'dateto'       => $this->format_date_db($this->input->post('dateto')),
            'docdate'      => $this->format_date_db($this->input->post('docdate')),
            'doctby'       => strtoupper(trim($this->input->post('doctby'))),
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

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('status' => 1, 'notif' => validation_errors()));
            return;
        }

        $data = array(
            'patient_name' => strtoupper(trim($this->input->post('patient_name'))),
            'company_name' => strtoupper(trim($this->input->post('company_name'))),
            'age'          => trim($this->input->post('age')),
            'gender'       => $this->input->post('gender'),
            'desa'         => strtoupper(trim($this->input->post('desa'))),
            'kecamatan'    => strtoupper(trim($this->input->post('kecamatan'))),
            'kecamatan_id' => $this->input->post('id_kecamatan'),
            'kelurahan'    => strtoupper(trim($this->input->post('kelurahan'))),
            'kelurahan_id' => $this->input->post('id_kelurahan'),
            'kabupaten'    => strtoupper(trim($this->input->post('kabupaten'))),
            'provinsi'     => strtoupper(trim($this->input->post('provinsi'))),
            'diagnosa'     => strtoupper(trim($this->input->post('diagnosa'))),
            'terapi'       => trim($this->input->post('terapi')),
            'datefrom'     => $this->format_date_db($this->input->post('datefrom')),
            'dateto'       => $this->format_date_db($this->input->post('dateto')),
            'docdate'      => $this->format_date_db($this->input->post('docdate')),
            'doctby'       => strtoupper(trim($this->input->post('doctby'))),
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
            'notif'  => 'SKS ' . htmlspecialchars($row->patient_name) . ' berhasil dihapus!',
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

    function get_kecamatan()
    {
       $data                            = $this->M_sks->get_kecamatan();
       $option                          = "";
       foreach ($data as $d) {
            $selected                   = '';
            if($d->id_city == 3){
                $selected               = 'selected';
            }
            $option                     .= "<option value='".$d->id_city."'".$selected.">".$d->city_name."</option>";
       }
       return $option;
    }  

    function get_kelurahan_default()
    {
        $kecamatan_id                   = 3;
        $data                           = $this->M_sks->get_kelurahan_not_default($kecamatan_id);
        $option                         = "";
        $option                         .= "<option value=''>Pilih</option>";
        foreach ($data as $d) {
            $option                     .= "<option value='".$d->id_district."'>".$d->district_name."</option>";
        }
        return $option;
    }  


    function get_kelurahan_not_default()
    {
        $kecamatan_id                   = $this->input->get('kecamatan_id'); 
        $data                           = $this->M_sks->get_kelurahan_not_default($kecamatan_id);
        $option                         = '';
        $option                         .= '<option value="">Pilih</option>';
        foreach ($data as $d) {
            $option                     .= '<option value="'.$d->id_district.'">'.$d->district_name.'</option>';
        }
        $notif['html']                  = $option;
        echo json_encode($notif);
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
