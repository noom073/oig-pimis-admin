<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auditor extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('session_services');

		$this->load->model('questionaire_model');
	}

	public function index()
	{
		$username = $this->session->nameth;
		$userType = $this->session_services->get_user_type_name($this->session->usertype);

		$sideBar['name'] = $username;
		$sideBar['userType'] = $userType;
		$script['custom'] = $this->load->view('auditor/index_content/script', '', true);
		$header['custom'] = '';

		$component['header'] 			= $this->load->view('auditor/component/header', $header, true);
		$component['navbar'] 			= $this->load->view('auditor/component/navbar', '', true);
		$component['mainSideBar'] 		= $this->load->view('auditor/component/sidebar', $sideBar, true);
		$component['mainFooter'] 		= $this->load->view('auditor/component/footer_text', '', true);
		$component['controllerSidebar'] = $this->load->view('auditor/component/controller_sidebar', '', true);
		$component['contentWrapper'] 	= $this->load->view('auditor/index_content/content', '', true);
		$component['jsScript'] 			= $this->load->view('auditor/component/main_script', $script, true);

		$this->load->view('auditor/template', $component);
	}

	public function calendar()
	{
		$username = $this->session->nameth;
		$userType = $this->session_services->get_user_type_name($this->session->usertype);

		$sideBar['name'] = $username;
		$sideBar['userType'] = $userType;
		$script['custom'] = $this->load->view('auditor/calendar/script', '', true);
		$header['custom'] = $this->load->view('auditor/calendar/custom_header', '', true);

		$component['header'] 			= $this->load->view('auditor/component/header', $header, true);
		$component['navbar'] 			= $this->load->view('auditor/component/navbar', '', true);
		$component['mainSideBar'] 		= $this->load->view('auditor/component/sidebar', $sideBar, true);
		$component['mainFooter'] 		= $this->load->view('auditor/component/footer_text', '', true);
		$component['controllerSidebar'] = $this->load->view('auditor/component/controller_sidebar', '', true);
		$component['contentWrapper'] 	= $this->load->view('auditor/calendar/content', '', true);
		$component['jsScript'] 			= $this->load->view('auditor/component/main_script', $script, true);

		$this->load->view('auditor/template', $component);
	}

	public function inspect()
	{
		$planID = $this->input->get('plan');
		$planData = $this->questionaire_model->get_plan($planID);
		if ($planData->num_rows() == 1) {
			$username = $this->session->nameth;
			$userType = $this->session_services->get_user_type_name($this->session->usertype);

			$sideBar['name'] = $username;
			$sideBar['userType'] = $userType;
			$script['custom'] = $this->load->view('auditor/inspect/script', '', true);
			$header['custom'] = $this->load->view('auditor/inspect/custom_header', '', true);;

			$inspections = $this->questionaire_model->get_inspections()->result_array();
			$inspectionsDivide = array('odd' => [], 'even' => []);
			foreach ($inspections as $index => $r) {
				if ($index % 2 == 1) {
					array_push($inspectionsDivide['even'], $r);
				} else {
					array_push($inspectionsDivide['odd'], $r);
				}
			}
			$data['inspections'] = $inspectionsDivide;

			$component['header'] 			= $this->load->view('auditor/component/header', $header, true);
			$component['navbar'] 			= $this->load->view('auditor/component/navbar', '', true);
			$component['mainSideBar'] 		= $this->load->view('auditor/component/sidebar', $sideBar, true);
			$component['mainFooter'] 		= $this->load->view('auditor/component/footer_text', '', true);
			$component['controllerSidebar'] = $this->load->view('auditor/component/controller_sidebar', '', true);
			$component['contentWrapper'] 	= $this->load->view('auditor/inspect/content', $data, true);
			$component['jsScript'] 			= $this->load->view('auditor/component/main_script', $script, true);

			$this->load->view('auditor/template', $component);
		} else {
			redirect('auditor/calendar');
		}
	}
	
}
