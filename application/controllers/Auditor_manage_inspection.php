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
		$this->load->model('auditor_model');
	}

	public function index()
	{
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

	public function ajax_add_auditor_name()
	{
		$input['teamName'] 	= $this->input->post('teamName', true);
		$input['teamYear'] 	= $this->input->post('teamYear', true);
		$input['updater'] 	= $this->session->email;
		$insert = $this->auditor_team_model->insert_auditor_team($input);
		if ($insert) {
			$result['status'] = true;
			$result['text'] = 'บันทึกสำเร็จ';
		} else {
			$result['status'] = false;
			$result['text'] = 'บันทึกไม่สำเร็จ';
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($result));
	}

	public function ajax_get_a_auditor_name()
	{
		$rowID = $this->input->post('rowID', true);
		$data = $this->auditor_team_model->get_a_team_name($rowID)->row_array();
		$this->output
			->set_content_type('apllication/json')
			->set_output(json_encode($data));
	}

	public function ajax_update_auditor_name()
	{
		$input['teamName'] 	= $this->input->post('teamName', true);
		$input['teamYear'] 	= $this->input->post('teamYear', true);
		$input['rowID'] 	= $this->input->post('rowID', true);
		$input['updater'] 	= $this->session->email;
		$update = $this->auditor_team_model->update_team_name($input);
		if ($update) {
			$result['status'] = true;
			$result['text'] = 'บันทึกสำเร็จ';
		} else {
			$result['status'] = false;
			$result['text'] = 'บันทึกไม่สำเร็จ';
		}
		$this->output
			->set_content_type('apllication/json')
			->set_output(json_encode($result));
	}

	public function auditor_team_member()
	{
		$teamID = $this->input->get('team', true);
		$team = $this->auditor_team_model->validate_team_row_id($teamID);
		if ($team->num_rows() !== 0) {
			$data['team'] = $team->row_array();
			$sideBar['name'] 	= $this->session->nameth;
			$script['custom'] = $this->load->view('auditor_manage_inspection/auditor_team_member/script', $data, true);

			$component['header'] 			= $this->load->view('auditor_manage_inspection/component/header', '', true);
			$component['navbar'] 			= $this->load->view('auditor_manage_inspection/component/navbar', '', true);
			$component['mainSideBar'] 		= $this->load->view('sidebar/main-sidebar', $sideBar, true);
			$component['mainFooter'] 		= $this->load->view('auditor_manage_inspection/component/footer_text', '', true);
			$component['controllerSidebar'] = $this->load->view('auditor_manage_inspection/component/controller_sidebar', '', true);
			$component['contentWrapper'] 	= $this->load->view('auditor_manage_inspection/auditor_team_member/content', $data, true);
			$component['jsScript'] 			= $this->load->view('auditor_manage_inspection/component/main_script', $script, true);

			$this->load->view('auditor_manage_inspection/template', $component);
		} else {
			redirect('auditor_manage_inspection/auditor_topic');
		}
	}

	public function ajax_get_team_member()
	{
		$teamID = $this->input->post('teamID', true);
		$data = $this->auditor_model->get_team_members($teamID)->result_array();
		$this->output
			->set_content_type('apllication/json')
			->set_output(json_encode($data));
	}
}
