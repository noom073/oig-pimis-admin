<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auditor_manage_inspection extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('session_services');

		$this->load->model('questionaire_model');
		$this->load->model('summary_model');
		$this->load->model('auditor_team_model');
	}

	public function index()
	{
		$username = $this->session->nameth;
		$userType = $this->session_services->get_user_type_name($this->session->usertype);

		$sideBar['name'] 	= $this->session->nameth;
		$script['custom'] = $this->load->view('auditor_manage_inspection/index_content/script', '', true);
		$header['custom'] = '';

		$component['header'] 			= $this->load->view('auditor_manage_inspection/component/header', $header, true);
		$component['navbar'] 			= $this->load->view('auditor_manage_inspection/component/navbar', '', true);
		$component['mainSideBar'] 		= $this->load->view('sidebar/main-sidebar', $sideBar, true);
		$component['mainFooter'] 		= $this->load->view('auditor_manage_inspection/component/footer_text', '', true);
		$component['controllerSidebar'] = $this->load->view('auditor_manage_inspection/component/controller_sidebar', '', true);
		$component['contentWrapper'] 	= $this->load->view('auditor_manage_inspection/index_content/content', '', true);
		$component['jsScript'] 			= $this->load->view('auditor_manage_inspection/component/main_script', $script, true);

		$this->load->view('auditor_manage_inspection/template', $component);
	}

	public function set_plan()
	{
		$username = $this->session->nameth;
		$userType = $this->session_services->get_user_type_name($this->session->usertype);

		$sideBar['name'] 	= $this->session->nameth;
		$script['custom'] = $this->load->view('auditor_manage_inspection/set_plan/script', '', true);
		$header['custom'] = $this->load->view('auditor_manage_inspection/set_plan/custom_header', '', true);;

		$component['header'] 			= $this->load->view('auditor_manage_inspection/component/header', $header, true);
		$component['navbar'] 			= $this->load->view('auditor_manage_inspection/component/navbar', '', true);
		$component['mainSideBar'] 		= $this->load->view('sidebar/main-sidebar', $sideBar, true);
		$component['mainFooter'] 		= $this->load->view('auditor_manage_inspection/component/footer_text', '', true);
		$component['controllerSidebar'] = $this->load->view('auditor_manage_inspection/component/controller_sidebar', '', true);
		$component['contentWrapper'] 	= $this->load->view('auditor_manage_inspection/set_plan/content', '', true);
		$component['jsScript'] 			= $this->load->view('auditor_manage_inspection/component/main_script', $script, true);

		$this->load->view('auditor_manage_inspection/template', $component);
	}

	public function auditor_topic()
	{
		$sideBar['name'] 	= $this->session->nameth;
		$script['custom'] = $this->load->view('auditor_manage_inspection/auditor_topic/script', '', true);

		$component['header'] 			= $this->load->view('auditor_manage_inspection/component/header', '', true);
		$component['navbar'] 			= $this->load->view('auditor_manage_inspection/component/navbar', '', true);
		$component['mainSideBar'] 		= $this->load->view('sidebar/main-sidebar', $sideBar, true);
		$component['mainFooter'] 		= $this->load->view('auditor_manage_inspection/component/footer_text', '', true);
		$component['controllerSidebar'] = $this->load->view('auditor_manage_inspection/component/controller_sidebar', '', true);
		$component['contentWrapper'] 	= $this->load->view('auditor_manage_inspection/auditor_topic/content', '', true);
		$component['jsScript'] 			= $this->load->view('auditor_manage_inspection/component/main_script', $script, true);

		$this->load->view('auditor_manage_inspection/template', $component);
	}

	public function ajax_get_auditor_team()
	{
		$data = $this->auditor_team_model->get_auditor_teams()->result_array();
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
	}
}
