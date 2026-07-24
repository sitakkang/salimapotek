<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Diagnosa extends CI_Controller {

    public $dir_v = 'diagnosa/';

    public function __construct() {
        parent::__construct();
        $this->m_auth->check_login();
        $this->m_auth->check_akses();
        $this->load->model('M_diagnosa');
    }

    public function index() {
        $data['css'] = array(
            'lib/datatables/dataTables.bootstrap.min.css',
            'lib/datatables/fixedColumns.bootstrap.min.css',
        );
        $data['js'] = array(
            'lib/datatables/datatables.min.js',
            'lib/datatables/dataTables.bootstrap.min.js',
            'lib/datatables/dataTables.fixedColumns.min.js',
            'lib/sweetalert/sweetalert2.all.min.js',
            'src/js/admin/diagnosa.js',
        );
        $data['panel'] = '<i class="fa fa-stethoscope"></i> &nbsp;<b>Manajemen Diagnosa</b>';
        $this->l_skin->main($this->dir_v.'view', $data);
    }

    public function table() {
        $rows = $this->M_diagnosa->get_all();

        $draw = intval($this->input->get('draw'));
        $data = array();
        $i    = 1;

        foreach ($rows->result() as $row) {
            $status = $row->dgn_status == 1
                ? '<span class="badge badge-success">Aktif</span>'
                : '<span class="badge badge-secondary">Nonaktif</span>';

            $data[] = array(
                'DT_RowId'  => $row->id_diagnosa,
                '0'         => $i++,
                '1'         => htmlspecialchars($row->dgn_cat ?: '-'),
                '2'         => htmlspecialchars($row->dgn_name),
                '3'         => $status,
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

    public function add() {
        $this->load->view($this->dir_v.'add');
    }

    public function edit() {
        $id = intval($this->input->get('id'));
        $data['row'] = $this->M_diagnosa->get_by_id($id);
        if (!$data['row']) {
            show_404();
        }
        $this->load->view($this->dir_v.'edit', $data);
    }

    public function act_add() {
        $data = array(
            'dgn_name'     => strtoupper(trim($this->input->post('dgn_name'))),
            'dgn_cat'      => strtoupper(trim($this->input->post('dgn_cat'))),
            'dgn_status'   => 1,
            'dgn_insert_dt' => date('Y-m-d H:i:s'),
            'dgn_insert_by' => $this->session->userdata('sess_id'),
        );

        $this->M_diagnosa->insert($data);

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'Diagnosa ' . $data['dgn_name'] . ' berhasil ditambahkan!',
        ));
    }

    public function act_edit() {
        $id = intval($this->input->post('id'));

        $data = array(
            'dgn_name' => strtoupper(trim($this->input->post('dgn_name'))),
            'dgn_cat'  => strtoupper(trim($this->input->post('dgn_cat'))),
        );

        $this->M_diagnosa->update($id, $data);

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'Diagnosa ' . $data['dgn_name'] . ' berhasil diperbarui!',
        ));
    }

    public function act_del() {
        $id  = intval($this->input->post('id'));
        $row = $this->M_diagnosa->get_by_id($id);

        if (!$row) {
            echo json_encode(array('status' => 1, 'notif' => 'Data diagnosa tidak ditemukan!'));
            return;
        }

        $this->M_diagnosa->delete($id);

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'Diagnosa ' . htmlspecialchars($row->dgn_name) . ' berhasil dihapus!',
        ));
    }
}
