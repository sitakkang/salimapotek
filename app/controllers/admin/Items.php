<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Items extends CI_Controller {

    public $dir_v = 'admin/items/';
	public $dir_m = 'admin/';
	public $dir_l = 'admin/';

    public function __construct(){
        parent::__construct();
        $this->m_auth->check_login();
        $this->load->model($this->dir_m.'M_items');
        $this->load->library($this->dir_l.'l_admin');
    }

    function index()
    {
        $data['css'] = array(
            'lib/datatables/dataTables.bootstrap.min.css'
        );
        $data['js'] = array(
            'lib/datatables/datatables.min.js',
            'lib/datatables/dataTables.bootstrap.min.js',
            'src/js/admin/items.js'
        );
        $data['panel'] = '<i class="fa fa-cube"></i> &nbsp;<b>Data Master Items</b>';
        $this->l_skin->config($this->dir_v.'view', $data);
    }

    function table()
    {
        $get_all = $this->db->query('SELECT id, kodeitem, namaitem, hargasatuan, deskripsi, itemimage FROM items ORDER BY id DESC');

        // Datatables Variables
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $data = array();
        $i = 1;
        foreach($get_all->result() as $id) {
            $data[] = array(
                "DT_RowId" => $id->id,
                "0" => $i++,
                "1" => $id->kodeitem,
                "2" => $id->namaitem,
                "3" => 'Rp ' . number_format($id->hargasatuan, 0, ',', '.'),
                "4" => substr($id->deskripsi, 0, 50) . '...',
            );
         }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $get_all->num_rows(),
            "recordsFiltered" => $get_all->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }

    function add()
    {
        $this->load->view($this->dir_v.'add');
    }

    function edit()
    {
        $data_id = $this->input->get('id');
        $result_id = $this->db->query('SELECT id, kodeitem, namaitem, hargasatuan, deskripsi, itemimage FROM items WHERE id='.$data_id.' LIMIT 1');
        $data['id'] = $result_id->row();
        $this->load->view($this->dir_v.'edit', $data);
    }

    // ACTION POST
    function act_add()
    {
        $this->form_validation->set_rules('kodeitem', 'Kode Item', 'trim|required|min_length[3]|max_length[10]|is_unique[items.kodeitem]');
        $this->form_validation->set_rules('namaitem', 'Nama Item', 'trim|required|min_length[3]|max_length[50]');
        $this->form_validation->set_rules('hargasatuan', 'Harga Satuan', 'trim|required|numeric');
        
        if ($this->form_validation->run() == FALSE){
            $notif['notif'] = validation_errors();
            $notif['status'] = 1;
            echo json_encode($notif);
        }else{
            // Upload Image
            $itemimage = '';
            if (!empty($_FILES['itemimage']['name'])) {
                $config['upload_path'] = './uploads/items/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['max_size'] = 2048; // 2MB
                $config['file_name'] = 'item_' . time() . '_' . rand(1000, 9999);
                
                // Create directory if not exists
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, true);
                }
                
                $this->load->library('upload', $config);
                
                if ($this->upload->do_upload('itemimage')) {
                    $upload_data = $this->upload->data();
                    $itemimage = 'uploads/items/' . $upload_data['file_name'];
                }
            }

            $data = array(
                'kodeitem' => $this->input->post('kodeitem'),
                'namaitem' => $this->input->post('namaitem'),
                'hargasatuan' => $this->input->post('hargasatuan'),
                'deskripsi' => $this->input->post('deskripsi'),
                'itemimage' => $itemimage,
                'createby' => $this->session->userdata('sess_id'),
                'createdt' => date('Y-m-d H:i:s')
            );
            
            $this->db->insert('items', $data);
            $notif['lastid'] = $this->db->insert_id();
            $notif['notif'] = 'Data Item '.$this->input->post('namaitem').' berhasil disimpan !';
            $notif['status'] = 2;
            echo json_encode($notif);
        }
    }

    function act_edit()
    {
        $id = $this->input->post('id');
        
        $this->form_validation->set_rules('kodeitem', 'Kode Item', 'trim|required|min_length[3]|max_length[10]');
        $this->form_validation->set_rules('namaitem', 'Nama Item', 'trim|required|min_length[3]|max_length[50]');
        $this->form_validation->set_rules('hargasatuan', 'Harga Satuan', 'trim|required|numeric');
        
        if ($this->form_validation->run() == FALSE){
            $notif['notif'] = validation_errors();
            $notif['status'] = 1;
            echo json_encode($notif);
        }else{
            // Get old image
            $old_data = $this->db->get_where('items', array('id' => $id))->row();
            $itemimage = $old_data->itemimage;
            
            // Upload new image if exists
            if (!empty($_FILES['itemimage']['name'])) {
                $config['upload_path'] = './uploads/items/';
                $config['allowed_types'] = 'jpg|jpeg|png|gif';
                $config['max_size'] = 2048; // 2MB
                $config['file_name'] = 'item_' . time() . '_' . rand(1000, 9999);
                
                // Create directory if not exists
                if (!is_dir($config['upload_path'])) {
                    mkdir($config['upload_path'], 0777, true);
                }
                
                $this->load->library('upload', $config);
                
                if ($this->upload->do_upload('itemimage')) {
                    // Delete old image
                    if (!empty($old_data->itemimage) && file_exists($old_data->itemimage)) {
                        unlink($old_data->itemimage);
                    }
                    
                    $upload_data = $this->upload->data();
                    $itemimage = 'uploads/items/' . $upload_data['file_name'];
                }
            }

            $data = array(
                'kodeitem' => $this->input->post('kodeitem'),
                'namaitem' => $this->input->post('namaitem'),
                'hargasatuan' => $this->input->post('hargasatuan'),
                'deskripsi' => $this->input->post('deskripsi'),
                'itemimage' => $itemimage,
                'updateby' => $this->session->userdata('sess_id'),
                'updatedt' => date('Y-m-d H:i:s')
            );
            
            $this->db->where('id', $id);
            $this->db->update('items', $data);
            $notif['notif'] = 'Data Item '.$this->input->post('namaitem').' berhasil diubah !';
            $notif['status'] = 2;
            echo json_encode($notif);
        }
    }

    function act_del()
    {
        $id = $this->input->post('id');
        
        // Get image path before delete
        $item = $this->db->get_where('items', array('id' => $id))->row();
        
        // Delete image file if exists
        if (!empty($item->itemimage) && file_exists($item->itemimage)) {
            unlink($item->itemimage);
        }
        
        // Delete from database
        $this->db->where('id', $id);
        $this->db->delete('items');
        
        $notif['notif'] = 'Data item '.$this->input->post('namaitem').' berhasil dihapus !';
        $notif['status'] = 2;
        echo json_encode($notif);
    }

}