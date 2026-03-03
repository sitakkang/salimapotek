<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tables extends CI_Controller {

    public $dir_v = 'tables/';

    public function __construct() {
        parent::__construct();
        $this->m_auth->check_login();
    }

    public function index() {
        $data['css'] = array(
            'lib/datatables/dataTables.bootstrap.min.css'
        );
        $data['js'] = array(
            'lib/datatables/datatables.min.js',
            'lib/datatables/dataTables.bootstrap.min.js',
            'lib/sweetalert/sweetalert2.all.min.js',
            'src/js/admin/tables.js'
        );
        $data['panel'] = '<i class="fa fa-th"></i> &nbsp;<b>Manajemen Meja</b>';
        $this->l_skin->main($this->dir_v.'view', $data);
    }

    public function table() {
        $rows = $this->db->query(
            'SELECT id_table, no_meja, kapasitas, status FROM pos_tables ORDER BY no_meja ASC'
        );

        $draw = intval($this->input->get('draw'));
        $data = array();
        $i    = 1;

        $status_label = array(
            'AVAILABLE' => '<span class="badge badge-success">Tersedia</span>',
            'OCCUPIED'  => '<span class="badge badge-danger">Terpakai</span>',
            'RESERVED'  => '<span class="badge badge-warning">Dipesan</span>',
        );

        foreach ($rows->result() as $row) {
            $label = isset($status_label[$row->status]) ? $status_label[$row->status] : $row->status;
            $data[] = array(
                'DT_RowId' => $row->id_table,
                '0' => $i++,
                '1' => $row->no_meja,
                '2' => $row->kapasitas . ' orang',
                '3' => $label,
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
        $data['row'] = $this->db->get_where('pos_tables', array('id_table' => $id))->row();
        $this->load->view($this->dir_v.'edit', $data);
    }

    public function act_add() {
        $this->form_validation->set_rules('no_meja',   'Nomor Meja', 'trim|required|min_length[1]|max_length[10]|is_unique[pos_tables.no_meja]');
        $this->form_validation->set_rules('kapasitas', 'Kapasitas',  'trim|required|is_natural_no_zero');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('status' => 1, 'notif' => validation_errors()));
            return;
        }

        $data = array(
            'no_meja'    => strtoupper(trim($this->input->post('no_meja'))),
            'kapasitas'  => intval($this->input->post('kapasitas')),
            'status'     => 'AVAILABLE',
            'created_at' => date('Y-m-d H:i:s'),
        );

        $this->db->insert('pos_tables', $data);
        $id = $this->db->insert_id();

        echo json_encode(array(
            'status' => 2,
            'lastid' => $id,
            'notif'  => 'Meja ' . $data['no_meja'] . ' berhasil ditambahkan!',
        ));
    }

    public function act_edit() {
        $id = intval($this->input->post('id'));

        $this->form_validation->set_rules('no_meja',   'Nomor Meja', 'trim|required|min_length[1]|max_length[10]');
        $this->form_validation->set_rules('kapasitas', 'Kapasitas',  'trim|required|is_natural_no_zero');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('status' => 1, 'notif' => validation_errors()));
            return;
        }

        $check = $this->db->query(
            "SELECT id_table FROM pos_tables WHERE no_meja = ? AND id_table != ?",
            array(strtoupper(trim($this->input->post('no_meja'))), $id)
        );
        if ($check->num_rows() > 0) {
            echo json_encode(array('status' => 1, 'notif' => 'Nomor meja sudah digunakan!'));
            return;
        }

        $data = array(
            'no_meja'   => strtoupper(trim($this->input->post('no_meja'))),
            'kapasitas' => intval($this->input->post('kapasitas')),
            'status'    => $this->input->post('status'),
        );

        $this->db->where('id_table', $id);
        $this->db->update('pos_tables', $data);

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'Meja ' . $data['no_meja'] . ' berhasil diperbarui!',
        ));
    }

    public function act_del() {
        $id  = intval($this->input->post('id'));
        $row = $this->db->get_where('pos_tables', array('id_table' => $id))->row();

        if (!$row) {
            echo json_encode(array('status' => 1, 'notif' => 'Meja tidak ditemukan!'));
            return;
        }
        if ($row->status === 'OCCUPIED') {
            echo json_encode(array('status' => 1, 'notif' => 'Meja sedang digunakan, tidak dapat dihapus!'));
            return;
        }

        $this->db->where('id_table', $id);
        $this->db->delete('pos_tables');

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'Meja ' . $row->no_meja . ' berhasil dihapus!',
        ));
    }
}
