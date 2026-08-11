<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gen_modul extends CI_Controller {

    public $dir_v = 'admin/gen_modul/';

    public function __construct(){
        parent::__construct();
        $this->m_auth->check_superadmin();
    }

    function index()
    {
        $data['js'] = array('src/js/admin/gen_modul.js');
        $data['dropdown_dir'] = $this->list_dir_ctrl();
        $this->l_skin->config($this->dir_v.'view', $data);
    }

    function filter_text_number($data)
    {
    	$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data ?? '');
        return preg_replace("/[^a-zA-Z0-9_]/", "", $data);
    }

    function list_dir_ctrl()
    {
        $rootDir = 'app/controllers/';
        $list_dir = array_filter(glob($rootDir.'*'), 'is_dir');
        $directory = array();
        foreach ($list_dir as $d){
            $directory[] = str_replace($rootDir,'',$d);
        }
        return $directory;
    }

    public function check_dir_folder($data='')
    {
        $rootDir = 'app/controllers/';

        $list_dir = array_filter(glob($rootDir.'*'), 'is_dir');

        $result = NULL;
        foreach ($list_dir as $d){
            $directory = str_replace($rootDir,'',$d);
            if($directory == $data){
                $result .= $directory;
            }
        }
        return (is_null($result)) ? TRUE : FALSE;

    }

    function act_main_modul()
    {
        $this->form_validation->set_rules('folder_name1', 'Main Folder', 'trim|required|min_length[4]');
        $this->form_validation->set_rules('file_name1', 'Main File', 'trim|required|min_length[4]');
        if ($this->form_validation->run() == FALSE){
			$notif['notif']  = validation_errors();
			$notif['status'] = 1;
			echo json_encode($notif);
		}else{

            $folder_name = strtolower(
                $this->filter_text_number(
                    $this->input->post('folder_name1')
                )
            );
            $file_name = strtolower(
                $this->filter_text_number(
                    $this->input->post('file_name1')
                )
            );

            if($this->check_dir_folder($folder_name)){
                
                $dir_tpl = 'app/views/admin/gen_modul/';
                $path_c = 'app/controllers/'.$folder_name;
                $path_m = 'app/models/'.$folder_name;
                $path_l = 'app/libraries/'.$folder_name;
                $path_v = 'app/views/'.$folder_name;
                $path_j = 'src/js/'.$folder_name;
                
                if(!is_dir($path_c)){
                    mkdir($path_c, 0777, true);
                }
                
                if(!is_dir($path_m)){
                    mkdir($path_m, 0777, true);
                }
                
                if(!is_dir($path_l)){
                    mkdir($path_l, 0777, true);
                }
                
                if(!is_dir($path_v)){
                    mkdir($path_v, 0777, true);
                }
                
                if(!is_dir($path_j)){
                    mkdir($path_j, 0777, true);
                }

                if(!is_dir($path_v.'/'.$file_name)){
                    mkdir($path_v.'/'.$file_name, 0777, true);
                }

                $tpl_controllers = $dir_tpl.'tpl_controllers.php';
                $new_c           = $path_c.'/'.ucwords($file_name).'.php';
                copy($tpl_controllers, $new_c);

                $tpl_models = $dir_tpl.'tpl_models.php';
                $new_m      = $path_m.'/M_'.$file_name.'.php';
                copy($tpl_models, $new_m);

                $tpl_libraries = $dir_tpl.'tpl_libraries.php';
                $new_l         = $path_l.'/L_'.$file_name.'.php';
                copy($tpl_libraries, $new_l);

                $tpl_views = $dir_tpl.'tpl_views.php';
                $new_v     = $path_v.'/'.$file_name.'/view.php';
                copy($tpl_views, $new_v);

                
                $tpl_javascript = $dir_tpl.'tpl_javascript.js';
                $new_j          = $path_j.'/'.$file_name.'.js';
                copy($tpl_javascript, $new_j);

                $notif['notif']  = 'Main Modul dan Sub Modul baru berhasil dibuat !';
                $notif['status'] = 2;
            }else{
                $notif['notif']  = 'Nama folder sudah terdaftar !';
                $notif['status'] = 1;
            }
            echo json_encode($notif);
            
        }
    }

    function act_sub_modul()
    {
        $this->form_validation->set_rules('file_name2', 'Sub File', 'trim|required|min_length[4]');
        if ($this->form_validation->run() == FALSE){
			$notif['notif']  = validation_errors();
			$notif['status'] = 1;
			echo json_encode($notif);
		}else{
            $folder_name = strtolower(
                $this->filter_text_number(
                    $this->input->post('folder_name2')
                )
            );
            $file_name   = strtolower(
                $this->filter_text_number(
                    $this->input->post('file_name2')
                )
            );

            $dir_tpl = 'app/views/admin/gen_modul/';
            $path_c = 'app/controllers/'.$folder_name;
            $path_m = 'app/models/'.$folder_name;
            $path_l = 'app/libraries/'.$folder_name;
            $path_v = 'app/views/'.$folder_name;
            $path_j = 'src/js/'.$folder_name;

            if(!is_dir($path_c)){
                mkdir($path_c, 0777, true);
            }
            
            if(!is_dir($path_m)){
                mkdir($path_m, 0777, true);
            }
            
            if(!is_dir($path_l)){
                mkdir($path_l, 0777, true);
            }
            
            if(!is_dir($path_v)){
                mkdir($path_v, 0777, true);
            }
            
            if(!is_dir($path_j)){
                mkdir($path_j, 0777, true);
            }

            if(!is_dir($path_v.'/'.$file_name)){
                mkdir($path_v.'/'.$file_name, 0777, true);
            }

            if($this->input->post('check_ctrl') == 1){
                $tpl_controllers = $dir_tpl.'tpl_controllers.php';
                $new_c           = $path_c.'/'.ucwords($file_name).'.php';
                copy($tpl_controllers, $new_c);
            }

            if($this->input->post('check_mdl') == 1){
                $tpl_models = $dir_tpl.'tpl_models.php';
                $new_m      = $path_m.'/M_'.$file_name.'.php';
                copy($tpl_models, $new_m);
            }

            if($this->input->post('check_lib') == 1){
                $tpl_libraries = $dir_tpl.'tpl_libraries.php';
                $new_l         = $path_l.'/L_'.$file_name.'.php';
                copy($tpl_libraries, $new_l);
            }

            if($this->input->post('check_view') == 1){
                $tpl_views = $dir_tpl.'tpl_views.php';
                $new_v     = $path_v.'/'.$file_name.'/view.php';
                copy($tpl_views, $new_v);
            }

            if($this->input->post('check_js') == 1){
                $tpl_javascript = $dir_tpl.'tpl_javascript.js';
                $new_j          = $path_j.'/'.$file_name.'.js';
                copy($tpl_javascript, $new_j);
            }

            $notif['notif']  = 'Sub Modul baru berhasil dibuat !';
            $notif['status'] = 2;
            echo json_encode($notif);
        }
    }
    
}