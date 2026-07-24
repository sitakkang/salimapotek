<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Obat extends CI_Controller {

    public $dir_v = 'obat/';

    public function __construct() {
        parent::__construct();
        $this->m_auth->check_login();
        $this->m_auth->check_akses();
        $this->load->model('M_obat');
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
            'lib/mask/jquery.mask.min.js',
            'src/js/admin/obat.js',
        );
        $data['panel'] = '<i class="fa fa-pills"></i> &nbsp;<b>Manajemen Obat</b>';
        $this->l_skin->main($this->dir_v.'view', $data);
    }

    public function table() {
        $rows = $this->M_obat->get_all();

        $draw = intval($this->input->get('draw'));
        $data = array();
        $i    = 1;

        foreach ($rows->result() as $row) {
            $status = $row->obat_status == 1
                ? '<span class="badge badge-success">Aktif</span>'
                : '<span class="badge badge-secondary">Nonaktif</span>';

            $data[] = array(
                'DT_RowId'  => $row->id_obat,
                '0'         => $i++,
                '1'         => htmlspecialchars($row->obat_name),
                '2'         => htmlspecialchars($row->obat_satuan),
                '3'         => 'Rp ' . number_format($row->obat_price, 0, ',', '.'),
                '4'         => $status,
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
        $data['row'] = $this->M_obat->get_by_id($id);
        if (!$data['row']) {
            show_404();
        }
        $this->load->view($this->dir_v.'edit', $data);
    }

    public function act_add() {
        $data = array(
            'obat_name'   => strtoupper(trim($this->input->post('obat_name'))),
            'obat_satuan' => strtoupper(trim($this->input->post('obat_satuan'))),
            'obat_price'  => str_replace(array(',', '.'), '', trim($this->input->post('obat_price'))),
            'obat_status' => 1,
        );

        $this->M_obat->insert($data);

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'Obat ' . $data['obat_name'] . ' berhasil ditambahkan!',
        ));
    }

    public function act_edit() {
        $id = intval($this->input->post('id'));

        $data = array(
            'obat_name'   => strtoupper(trim($this->input->post('obat_name'))),
            'obat_satuan' => strtoupper(trim($this->input->post('obat_satuan'))),
            'obat_price'  => str_replace(array(',', '.'), '', trim($this->input->post('obat_price'))),
        );

        $this->M_obat->update($id, $data);

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'Obat ' . $data['obat_name'] . ' berhasil diperbarui!',
        ));
    }

    public function act_del() {
        $id  = intval($this->input->post('id'));
        $row = $this->M_obat->get_by_id($id);

        if (!$row) {
            echo json_encode(array('status' => 1, 'notif' => 'Data obat tidak ditemukan!'));
            return;
        }

        $this->M_obat->delete($id);

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'Obat ' . htmlspecialchars($row->obat_name) . ' berhasil dihapus!',
        ));
    }
}
