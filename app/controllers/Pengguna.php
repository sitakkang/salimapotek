<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengguna extends CI_Controller {

    public $dir_v = 'pengguna/';

    public function __construct() {
        parent::__construct();
        $this->m_auth->check_login();
        $this->m_auth->check_akses();
        $this->load->model('M_pengguna');
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
            'src/js/admin/pengguna.js',
        );
        $data['panel'] = '<i class="fa fa-users"></i> &nbsp;<b>Manajemen Pengguna</b>';
        $this->l_skin->main($this->dir_v.'view', $data);
    }

    /**
     * DataTables JSON source
     */
    public function table() {
        $rows = $this->M_pengguna->get_all();
        $level = $this->session->userdata('sess_level');

        $draw = intval($this->input->get('draw'));
        $data = array();
        $i    = 1;

        foreach ($rows->result() as $row) {
            // Skip level 1 (superadmin) for non-superadmin
            if ($level != 1 && $row->level == 1) continue;

            $status_badge = $row->status == 1
                ? '<span class="ds-badge ds-badge-green">Aktif</span>'
                : '<span class="ds-badge ds-badge-pink">Nonaktif</span>';

            $data[] = array(
                'DT_RowId'  => $row->id_user,
                '0'         => $i++,
                '1'         => htmlspecialchars($row->fullname),
                '2'         => htmlspecialchars($row->username),
                '3'         => htmlspecialchars($row->nip ?: '-'),
                '4'         => htmlspecialchars($row->name),
                '5'         => $row->last_login ? date('d/m/Y H:i', strtotime($row->last_login)) : '-',
                '6'         => $status_badge,
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
     * Load form tambah Pengguna (via AJAX ke modal)
     */
    public function add() {
        $data['level'] = $this->M_pengguna->get_level();
        $this->load->view($this->dir_v.'add', $data);
    }

    /**
     * Load form edit Pengguna (via AJAX ke modal)
     */
    public function edit() {
        $id = intval($this->input->get('id'));
        $data['row'] = $this->M_pengguna->get_by_id($id);
        $data['level'] = $this->M_pengguna->get_level();
        if (!$data['row']) {
            show_404();
        }
        $this->load->view($this->dir_v.'edit', $data);
    }

    /**
     * Load form reset password (via AJAX ke modal)
     */
    public function reset() {
        $id = intval($this->input->get('id'));
        $data['row'] = $this->M_pengguna->get_by_id($id);
        if (!$data['row']) {
            show_404();
        }
        $this->load->view($this->dir_v.'reset', $data);
    }

    /**
     * Proses simpan Pengguna baru
     */
    public function act_add() {
        $this->form_validation->set_rules('fullname', 'Nama Lengkap', 'trim|required|min_length[3]');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[3]|is_unique[conf_users.username]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|matches[passconf]');
        $this->form_validation->set_rules('passconf', 'Konfirmasi Password', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('status' => 1, 'notif' => validation_errors()));
            return;
        }

        $this->load->library('admin/l_admin');
        $password = $this->input->post('password');
        $salt = $this->l_admin->rand_str(8, TRUE);
        $encrypt = $this->l_admin->encrypt_pass($password, $salt);

        $data = array(
            'fullname' => trim($this->input->post('fullname')),
            'username' => trim($this->input->post('username')),
            'nip'      => trim($this->input->post('nip')),
            'password' => $encrypt,
            'salt'     => $salt,
            'level'    => intval($this->input->post('level')),
            'status'   => intval($this->input->post('status')),
        );

        $this->M_pengguna->insert($data);
        $lastid = $this->db->insert_id();

        // Debug: verify nip was saved
        $saved = $this->M_pengguna->get_by_id($lastid);
        $nip_saved = !empty($saved->nip) ? $saved->nip : '(empty)';

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'Pengguna ' . $data['fullname'] . ' berhasil ditambahkan! (NIP: ' . $nip_saved . ')',
            'lastid' => $lastid,
        ));
    }

    /**
     * Proses update Pengguna
     */
    public function act_edit() {
        $id = intval($this->input->post('id'));

        $this->form_validation->set_rules('fullname', 'Nama Lengkap', 'trim|required|min_length[3]');

        $username_new = trim($this->input->post('username'));
        $username_old = trim($this->input->post('username_old'));

        if ($username_new !== $username_old) {
            $this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[3]|is_unique[conf_users.username]');
        }

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('status' => 1, 'notif' => validation_errors()));
            return;
        }

        $data = array(
            'fullname' => trim($this->input->post('fullname')),
            'username' => $username_new,
            'nip'      => trim($this->input->post('nip')),
            'level'    => intval($this->input->post('level')),
            'status'   => intval($this->input->post('status')),
        );

        $this->M_pengguna->update($id, $data);
        $saved = $this->M_pengguna->get_by_id($id);

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'Pengguna ' . $data['fullname'] . ' berhasil diperbarui! (NIP: ' . (!empty($saved->nip) ? $saved->nip : '(empty)') . ')',
        ));
    }

    /**
     * Proses nonaktifkan Pengguna (soft delete — update status ke 2)
     */
    public function act_del() {
        $id  = intval($this->input->post('id'));
        $row = $this->M_pengguna->get_by_id($id);

        if (!$row) {
            echo json_encode(array('status' => 1, 'notif' => 'Data pengguna tidak ditemukan!'));
            return;
        }

        $this->M_pengguna->update($id, array('status' => 2));

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'Pengguna ' . htmlspecialchars($row->fullname) . ' berhasil dinonaktifkan!',
        ));
    }

    /**
     * Proses reset password
     */
    public function act_reset() {
        $id = intval($this->input->post('id'));

        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|matches[passconf]');
        $this->form_validation->set_rules('passconf', 'Konfirmasi Password', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('status' => 1, 'notif' => validation_errors()));
            return;
        }

        $this->load->library('admin/l_admin');
        $password = $this->input->post('password');
        $salt = $this->l_admin->rand_str(8, TRUE);
        $encrypt = $this->l_admin->encrypt_pass($password, $salt);

        $data = array(
            'password' => $encrypt,
            'salt'     => $salt,
        );

        $this->M_pengguna->update($id, $data);
        $row = $this->M_pengguna->get_by_id($id);

        echo json_encode(array(
            'status' => 2,
            'notif'  => 'Password ' . htmlspecialchars($row->fullname) . ' berhasil direset!',
        ));
    }
}
