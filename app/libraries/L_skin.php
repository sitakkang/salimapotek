<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class L_skin {

	protected $skin;

	function __construct()
	{
		$this->skin = &get_instance();
	}

	function apps_config($key)
    {
		$data = array(
			'title' 		=> 'Warung Gratcio',
			'logo' 			=> 'img/gratcio_lebar.jpeg',
			'login' 		=> 'img/dashboard.jpeg',
			'favicon' 		=> 'img/favicon.ico',
			'noimage' 		=> 'img/noimage.png',
			'loading' 		=> 'img/loading.gif',
			'email' 		=> 'info@imip.co.id',
			'footer' 		=> 'PT Indonesia Morowali Industrial Park - Copyright @2019 Allright Reserved',
			'meta_desc' 	=> 'IMIP Application',
			'meta_key' 		=> 'PT Indonesia Morowali Industrial Park',
			'head_title' 	=> 'PT Indonesia Morowali Industrial Park',
			'head_subtitle' => 'A Human Resources Information System (HRIS) is a software or online solution that is used for data entry, data tracking and the data information requirements of an organizations human resources (HR) management, payroll and bookkeeping operations'
		);
    	return $data[$key];
    }
	
	function main($template, $data=NULL)
	{
		if(!$this->is_ajax()){
			$data['header'] = $this->skin->load->view('skin/header', $data, TRUE);
			$data['sidebar'] = $this->skin->load->view('skin/sidebar', $data, TRUE);
			$data['content'] = $this->skin->load->view($template, $data, TRUE);
			$data['modal'] = $this->skin->load->view('skin/modal', $data, TRUE);
			$data['footer'] = $this->skin->load->view('skin/footer', $data, TRUE);
			$this->skin->load->view('skin/master', $data);
		}else{
			$this->skin->load->view('$template', $data);
		}
	}

	function config($template, $data=NULL)
	{
		if(!$this->is_ajax()){
			$data['header'] = $this->skin->load->view('skin/header', $data, TRUE);
			$data['sidebar'] = $this->skin->load->view('skin/sidebar', $data, TRUE);
			$data['menu'] = $this->skin->load->view('skin/menu', $data, TRUE);
			$data['content'] = $this->skin->load->view($template, $data, TRUE);
			$data['modal'] = $this->skin->load->view('skin/modal', $data, TRUE);
			$data['footer'] = $this->skin->load->view('skin/footer', $data, TRUE);
			$this->skin->load->view('skin/master_config', $data);
		}else{
			$this->skin->load->view('$template', $data);
		}
	}

	function login($template, $data=NULL)
	{
		if(!$this->is_ajax()){
			$data['content'] = $this->skin->load->view($template, $data, TRUE);
			$this->skin->load->view('skin/master_login', $data);
		}else{
			$this->skin->load->view('$template', $data);
		}
	}

	function is_ajax()
	{
		return ($this->skin->input->server('HTTP_X_REQUESTED_WITH')&&($this->skin->input->server('HTTP_X_REQUESTED_WITH')=='XMLHttpRequest'));
	}

	function css_load($data)
	{
		if( ! is_array($data) OR count($data) === 0 ){
			echo '<link rel="stylesheet" href="'.base_url($data).'">';
		}else{
			foreach($data as $val) {
				if(stripos($val, 'https') !== false) {
					echo '<link rel="stylesheet" href="'.$val.'">';
            		echo "\n";
				}else{
					echo '<link rel="stylesheet" href="'.base_url($val).'">';
	            	echo "\n";
	            }
			}
		}
	}

	function js_load($data)
	{
		if( ! is_array($data) OR count($data) === 0 ){
			echo '<script src="'.base_url($data).'"></script>';
		}else{
			foreach($data as $val) {
				if(stripos($val, 'https') !== false) {
					echo '<script src="'.$val.'"></script>';
            		echo "\n";
				}else{
					echo '<script src="'.base_url($val).'"></script>';
            		echo "\n";
				}
				
			}
		}
	}

	function breadcrumb()
	{
		$real 	= uri_string();
		$url 	= explode("/",$real);
		$batas 	= count($url);
		$akhir	= $batas - 1;

		$link = '';
		echo '<ol class="breadcrumb">';
		echo '<li><a href="'.site_url().'">HRIS</a></li>';
		for ($x = 0; $x < $batas; $x++) {
		    $title = ucfirst($url[$x]);
		    $link .= $url[$x].'/';
		    echo ($x == $akhir) ? '<li class="active">'.$title.'</li>' : '<li><a href="'.site_url($link).'">'.$title.'</a></li>';
		}
		echo '</ol>';
	}

    function menu_sidebar()
    {
        $LevelUser = $_SESSION['sess_level'];
        $file_json_menu_main	= file_get_contents('src/json/main_menu.json');
        $file_json_menu_sub		= file_get_contents('src/json/sub_menu.json');
        $data_main_menu		= json_decode($file_json_menu_main,true);
        $data_sub_menu		= json_decode($file_json_menu_sub,true);

        if($data_main_menu OR $data_sub_menu){
	        foreach ($data_main_menu as $key => $val) {
	        	if($data_main_menu[$key]['sub'] == 2){
	        		echo '<li><a href="javascript:;" data-sidenav-dropdown-toggle><span class="sidenav-link-icon"><i class="fa '.$data_main_menu[$key]['icon'].'"></i></span><span class="sidenav-link-title">'.$data_main_menu[$key]['name'].'</span><span class="sidenav-dropdown-icon show" data-sidenav-dropdown-icon><i class="fa fa-angle-down"></i></span><span class="sidenav-dropdown-icon" data-sidenav-dropdown-icon><i class="fa fa-angle-up"></i></span></a>'."\n";
	        		echo '<ul class="sidenav-dropdown" data-sidenav-dropdown>';
	        		foreach ($data_sub_menu as $key2 => $val2)
	                {
	                    if($data_main_menu[$key]['id_menu'] === $data_sub_menu[$key2]['id_menu'])
	                    {
	                    	$array_sub = $data_sub_menu[$key2]['level'];
	                    	$level_sub = strrpos($array_sub,$LevelUser);
	                    	if(!empty($level_sub)){
	                        	echo '<li><a href="'.site_url().$data_sub_menu[$key2]['link'].'"><i class="fa '.$data_sub_menu[$key2]['icon'].'"></i>&nbsp;&nbsp;'.$data_sub_menu[$key2]['name'].'</a></li>'."\n";
	                        }
	                    }
	                }
	                echo '</ul></li>';
	        	}else{
	        		echo '<li><a href="'.site_url().$data_main_menu[$key]['link'].'"><span class="sidenav-link-icon"><i class="fa '.$data_main_menu[$key]['icon'].'"></i></span><span class="sidenav-link-title">'.$data_main_menu[$key]['name'].'</span></a></li>';
	        	}
	        }
	    }
    }
}