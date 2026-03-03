<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('l_auth');
    }

    public function index()
    {
        if($this->session->userdata('sess_log') == TRUE){
            redirect(base_url().'home');
        }else{
            redirect(base_url().'login');
        }
    }

    public function login()
	{
		if(!empty($this->input->post('username'))){
            $login_array = array(
                $this->l_auth->filter_input($this->input->post('username')),
                $this->l_auth->filter_input($this->input->post('password')),
                $this->input->post('datatoken')
            );
            if($this->act_login($login_array)){
				$this->index();
            }else{
            	$notif = '<div class="alert alert-danger text-center">Username atau Password anda salah !</div>';
            	$this->session->set_tempdata('notif_login', $notif, 15);
        		$data['token'] = $this->gen_token();
                $this->l_skin->login('auth/form_login', $data);
            }
        }else{
        	if($this->session->userdata('sess_log') == TRUE){
				$this->index();
			}else{
        		$data['token'] = $this->gen_token();
				$this->l_skin->login('auth/form_login', $data);
			}
        }
	}

	private function act_login($data_login)
	{
		if(!isset($data_login) OR count($data_login) != 3) return FALSE;
		$username = $data_login[0];
		$password = $data_login[1];
		$token = $data_login[2];
		if($this->check_token($token)){
			$query = $this->db->query('SELECT * FROM conf_users WHERE username = "'.$username.'" AND status=1 LIMIT 1');
			if ($query->num_rows() > 0){
				$row = $query->row();
				$sess_data = array(
					'sess_id'  		=> $row->id_user,
					'sess_name' 	=> $row->fullname,
					'sess_level' 	=> $row->level,
					'sess_avatar' 	=> $row->avatar,
					'sess_log' 		=> 'TRUE'
				);
				$user_pass 		= $row->password;
				$user_salt 		= $row->salt;
				$user_status 	= $row->status;
				if($this->l_auth->encrypt_pass($password, $user_salt) === $user_pass){ 
					$this->session->set_userdata($sess_data);
					$track = array(
						'last_login' 	=> $this->l_auth->date_time_now(),
						'ip_address' 	=> $this->input->ip_address()
					);
					$this->db->where('id_user', $row->id_user);
					$this->db->update('conf_users', $track);
					$set_log = array(
						'id_user' 		=> $row->id_user,
						'nama_user' 	=> $row->fullname,
						'ip_address' 	=> $this->input->ip_address(),
						'tanggal' 		=> $this->l_auth->date_time_now()
					);
					$this->db->insert('temp_login', $set_log);
					return TRUE;
				}
				return FALSE;
			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}

    function gen_token()
    {
    	$token = $this->l_auth->rand_str1(5, FALSE);
    	$this->session->set_tempdata('datatoken', $token, 120);
    	return '<input type="hidden" name="datatoken" value="'.base64_encode($token).'">';
    }

    function check_token($token)
    {
    	$data_token = base64_decode($token);
    	$sess_token = $this->session->tempdata('datatoken');
    	if($data_token === $sess_token){
    		return TRUE;
    	}else{
    		return FALSE;
    	}
    	$this->session->unset_tempdata('datatoken');
    }

    public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url('login'));
	}
}