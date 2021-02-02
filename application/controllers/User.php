<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends CI_Controller
{
    private $userTypes;
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('cookie');
        $this->load->library('session');
        $this->load->library('session_services');

        $data['token'] = get_cookie('pimis-token');
        $this->load->library('user_data', $data);

        $this->userTypes = $this->user_data->get_user_types();
        $hasPermition = in_array('user', $this->userTypes);
        if (!$hasPermition) redirect('welcome/forbidden');
    }

    public function index()
    {
        $sideBar['name']        = $this->session->nameth;
        $sideBar['userTypes']   = $this->userTypes;
        $script['custom']       = $this->load->view('user/index_content/script', '', true);
        $header['custom']       = '';

        $component['header']            = $this->load->view('user/component/header', $header, true);
        $component['navbar']            = $this->load->view('user/component/navbar', '', true);
        $component['mainSideBar']       = $this->load->view('sidebar/main-sidebar', $sideBar, true);
        $component['mainFooter']        = $this->load->view('user/component/footer_text', '', true);
        $component['controllerSidebar'] = $this->load->view('user/component/controller_sidebar', '', true);
        $component['contentWrapper']    = $this->load->view('user/index_content/content', '', true);
        $component['jsScript']          = $this->load->view('user/component/main_script', $script, true);

        $this->load->view('user/template', $component);
    }

    public function calendar()
    {
        $data['unitID']          = $this->user_data->get_unit_id_user();
        $sideBar['name']        = $this->session->nameth;
        $sideBar['userTypes']   = $this->userTypes;
        $script['custom']       = $this->load->view('user/calendar/script', $data, true);
        $header['custom']       = $this->load->view('user/calendar/custom_header', '', true);

        $component['header']            = $this->load->view('user/component/header', $header, true);
        $component['navbar']            = $this->load->view('user/component/navbar', '', true);
        $component['mainSideBar']       = $this->load->view('sidebar/main-sidebar', $sideBar, true);
        $component['mainFooter']        = $this->load->view('user/component/footer_text', '', true);
        $component['controllerSidebar'] = $this->load->view('user/component/controller_sidebar', '', true);
        $component['contentWrapper']    = $this->load->view('user/calendar/content', '', true);
        $component['jsScript']          = $this->load->view('user/component/main_script', $script, true);

        $this->load->view('user/template', $component);
    }

    public function inspect()
	{
        $this->load->model('team_inspection_model');
        $this->load->model('plan_model');
		$teamPlanID = $this->input->get('team_plan_id', true);
		$data['inspections'] = $this->team_inspection_model->get_team_inspection_and_check_inspected($teamPlanID)->result_array();
		$data['teamPlan'] = $this->plan_model->get_a_team_plan($teamPlanID)->row_array();
		$data['planDetail'] = $this->plan_model->get_a_plan_by_id($data['teamPlan']['PLAN_ID'])->row_array();

		$sideBar['name'] 	= $this->session->nameth;
		$sideBar['userTypes'] 	= $this->userTypes;
		$script['custom'] = $this->load->view('user/inspect/script', $data, true);
		$header['custom'] = $this->load->view('user/inspect/custom_header', '', true);

		$component['header'] 			= $this->load->view('user/component/header', $header, true);
		$component['navbar'] 			= $this->load->view('user/component/navbar', '', true);
		$component['mainSideBar'] 		= $this->load->view('sidebar/main-sidebar', $sideBar, true);
		$component['mainFooter'] 		= $this->load->view('user/component/footer_text', '', true);
		$component['controllerSidebar'] = $this->load->view('user/component/controller_sidebar', '', true);
		$component['contentWrapper'] 	= $this->load->view('user/inspect/content', $data, true);
		$component['jsScript'] 			= $this->load->view('user/component/main_script', $script, true);

		$this->load->view('user/template', $component);
    }
    
    public function inspected()
	{
		$teamPlanID = $this->input->get('teamPlanID', true);
		$inspectionOptionID = $this->input->get('inspectionOptionID', true);
		$data['teamPlan'] = $this->plan_model->get_a_team_plan($teamPlanID)->row_array();
		$data['inspectionOption'] = $this->inspection_option_model->get_inspection_option($inspectionOptionID)->row_array();
		$data['planDetail'] = $this->plan_model->get_a_plan_by_id($data['teamPlan']['PLAN_ID'])->row_array();

		$sideBar['name'] 	= $this->session->nameth;
		$sideBar['userTypes'] 	= $this->userTypes;
		$script['custom'] = $this->load->view('user/inspected/script', $data, true);
		$header['custom'] = $this->load->view('user/inspected/custom_header', '', true);

		$component['header'] 			= $this->load->view('user/component/header', $header, true);
		$component['navbar'] 			= $this->load->view('user/component/navbar', '', true);
		$component['mainSideBar'] 		= $this->load->view('sidebar/main-sidebar', $sideBar, true);
		$component['mainFooter'] 		= $this->load->view('user/component/footer_text', '', true);
		$component['controllerSidebar'] = $this->load->view('user/component/controller_sidebar', '', true);
		$component['contentWrapper'] 	= $this->load->view('user/inspected/content', $data, true);
		$component['jsScript'] 			= $this->load->view('user/component/main_script', $script, true);

		$this->load->view('user/template', $component);
	}
}
