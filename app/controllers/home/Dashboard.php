<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->m_auth->check_login();
	}

	function index()
	{
		// $data['head_title'] = '';
		// $data['head_subtitle'] = '';
		
		$data['icon_data'] = array(
			'Recruitment' 					=> array(
													'link' => site_url().'recruitment',
													'icon' => 'img/noimage.png'
												),
			'Onboarding'					=> array(
													'link' => '#',
													'icon' => 'img/noimage.png'
												),
			'Performance Management'		=> array(
													'link' => '#',
													'icon' => 'img/noimage.png'
												),
			'Benefits Administration'		=> array(
													'link' => '#',
													'icon' => 'img/noimage.png'
												),
			'Workforce Management'			=> array(
													'link' => '#',
													'icon' => 'img/noimage.png'
												),
			'Time And Attendance'			=> array(
													'link' => '#',
													'icon' => 'img/noimage.png'
												),
			'Absence And Leave Management'	=> array(
													'link' => '#',
													'icon' => 'img/noimage.png'
												),
			'Learning And Development'		=> array(
													'link' => '#',
													'icon' => 'img/noimage.png'
												),
			'Talent Management'				=> array(
													'link' => '#',
													'icon' => 'img/noimage.png'
												),
			'HR Analytics'					=> array(
													'link' => '#',
													'icon' => 'img/noimage.png'
												)
		);

		$data['sidebar_data'] = array(
			'Documentation' 			=> array(
											'link' => '#',
											'icon' => ''
										),
			'About HRIS'				=> array(
											'link' => '#',
											'icon' => ''
										),
			'Request New Users'			=> array(
											'link' => '#',
											'icon' => ''
										),
			'Request New Feature'		=> array(
											'link' => '#',
											'icon' => ''
										),
			'Request Reset Password'	=> array(
											'link' => '#',
											'icon' => ''
										),
			'Report Error/Bugs'			=> array(
											'link' => '#',
											'icon' => ''
										),
			'Contact Us'				=> array(
											'link' => '#',
											'icon' => ''
										)
		);

		// SKS & Pharmacy counters
		$data['sks_hari_ini'] = $this->db->query(
			"SELECT COUNT(*) AS jml FROM sks WHERE DATE(insertdt) = CURDATE()"
		)->row()->jml;

		$data['sks_bulan_ini'] = $this->db->query(
			"SELECT COUNT(*) AS jml FROM sks WHERE MONTH(insertdt) = MONTH(CURDATE()) AND YEAR(insertdt) = YEAR(CURDATE())"
		)->row()->jml;

		$data['total_pasien'] = $this->db->query(
			"SELECT COUNT(*) AS jml FROM ms_patient WHERE patient_status = 1"
		)->row()->jml;

		$data['total_doctor'] = $this->db->query(
			"SELECT COUNT(*) AS jml FROM conf_users WHERE level = 3"
		)->row()->jml;

		$this->l_skin->main('home/dashboard', $data);
	}

}