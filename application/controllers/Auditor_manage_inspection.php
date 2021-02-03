<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auditor_manage_inspection extends CI_Controller
{
	private $userTypes;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('cookie');
		$this->load->library('session');
		$this->load->library('session_services');

		$this->load->model('questionaire_model');
		$this->load->model('summary_model');
		$this->load->model('auditor_team_model');
		$this->load->model('auditor_model');
		$this->load->model('plan_model');
		$this->load->model('team_inspection_model');

		$data['token'] = get_cookie('pimis-token');
		$this->load->library('user_data', $data);

		$this->userTypes = $this->user_data->get_user_types();
		$hasPermition = in_array('admin', $this->userTypes);
		if (!$hasPermition) redirect('welcome/forbidden');
	}

	public function index()
	{
		$sideBar['name'] 	= $this->user_data->get_name();
		$sideBar['userTypes'] 	= $this->userTypes;
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
		$sideBar['name'] 	= $this->user_data->get_name();
		$sideBar['userTypes'] 	= $this->userTypes;
		$script['custom'] 	= $this->load->view('auditor_manage_inspection/set_plan/script', '', true);
		$header['custom'] 	= $this->load->view('auditor_manage_inspection/set_plan/custom_header', '', true);

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
		$sideBar['name'] 	= $this->user_data->get_name();
		$sideBar['userTypes'] 	= $this->userTypes;
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
		$input['color'] 	= $this->input->post('color', true);
		$input['updater'] 	= $this->user_data->get_email();
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
		$input['color'] 	= $this->input->post('color', true);
		$input['rowID'] 	= $this->input->post('rowID', true);
		$input['updater'] 	= $this->user_data->get_email();
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
			$data['team'] 		= $team->row_array();
			$data['auditorTypes'] = $this->auditor_model->get_auditor_types()->result_array();
			$sideBar['name'] 	= $this->user_data->get_name();
			$sideBar['userTypes'] 	= $this->userTypes;
			$script['custom'] 	= $this->load->view('auditor_manage_inspection/auditor_team_member/script', $data, true);

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

	public function ajax_add_auditor_member()
	{
		$input['title'] 		= $this->input->post('title', true);
		$input['firstName'] 	= $this->input->post('firstName', true);
		$input['lastName'] 		= $this->input->post('lastName', true);
		$input['position'] 		= $this->input->post('position', true);
		$email = explode('@', $this->input->post('email', true));
		$input['email'] 		= $email[0];
		$input['auditorTeam']	= $this->input->post('auditorTeam', true);
		$input['auditorType'] 	= $this->input->post('auditorType', true);
		$input['updater'] 		= $this->user_data->get_email();
		$insert = $this->auditor_model->insert_auditor_member($input);
		if ($insert) {
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

	public function ajax_get_auditor_detail()
	{
		$input['rowID'] = $this->input->post('auditorID', true);
		$data = $this->auditor_model->get_a_auditor($input)->row_array();
		$data['ADT_EMAIL'] = explode('@', $data['ADT_EMAIL'])[0];
		$this->output
			->set_content_type('apllication/json')
			->set_output(json_encode($data));
	}

	public function ajax_update_auditor_detail()
	{
		$input['title'] 		= $this->input->post('title', true);
		$input['firstName'] 	= $this->input->post('firstName', true);
		$input['lastName'] 		= $this->input->post('lastName', true);
		$input['position'] 		= $this->input->post('position', true);
		$email = explode('@', $this->input->post('email', true));
		$input['email'] 		= $email[0];
		$input['auditorTeam']	= $this->input->post('auditorTeam', true);
		$input['auditorType'] 	= $this->input->post('auditorType', true);
		$input['auditorID'] 	= $this->input->post('auditorID', true);
		$input['updater'] 		= $this->user_data->get_email();
		$update = $this->auditor_model->update_auditor_detail($input);
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

	public function ajax_delete_auditor()
	{
		$input['auditorID'] = $this->input->post('auditorID', true);
		$input['updater'] 	= $this->user_data->get_email();
		$delete = $this->auditor_model->delete_auditor($input);
		if ($delete) {
			$result['status'] = true;
			$result['text'] = 'ลบข้อมูลสำเร็จ';
		} else {
			$result['status'] = false;
			$result['text'] = 'ลบข้อมูลไม่สำเร็จ';
		}
		$this->output
			->set_content_type('apllication/json')
			->set_output(json_encode($result));
	}

	public function ajax_add_plan()
	{
		$input['unitID'] 		= $this->input->post('unitID', true);
		$input['startDate'] 	= $this->input->post('startDate', true);
		$input['endDate'] 		= $this->input->post('endDate', true);
		$input['auditorTeam']	= $this->input->post('auditorTeam', true);
		$input['updater'] 		= $this->user_data->get_email();
		$insert = $this->plan_model->add_new_plan($input);
		if ($insert['status']) {
			$result['status'] = true;
			$result['text'] = 'บันทึกสำเร็จ';
			$result['data'] = $insert;
		} else {
			$result['status'] = false;
			$result['text'] = 'บันทึกไม่สำเร็จ';
		}
		$this->output
			->set_content_type('apllication/json')
			->set_output(json_encode($result));
	}

	public function ajax_delete_auditor_team()
	{
		$input['rowID'] = $this->input->post('auditorTeamID', true);
		$isteamInUsed = $this->auditor_team_model->is_auditor_team_in_plan($input);
		if (!$isteamInUsed) {
			$data['updater'] = $this->user_data->get_email();
			$data['rowID'] = $input['rowID'];
			$delete = $this->auditor_team_model->delete_auditor_team($data);
			if ($delete) {
				$result['status'] = true;
				$result['text'] = 'ลบข้อมูลสำเร็จ';
			} else {
				$result['status'] = false;
				$result['text'] = 'ลบข้อมูลไม่สำเร็จ';
			}
		} else {
			$result['status'] = false;
			$result['text'] = 'มีการใช้ข้อมูลอยู่';
		}
		$this->output
			->set_content_type('apllication/json')
			->set_output(json_encode($result));
	}

	public function ajax_get_event_detail()
	{
		$input['planID'] = $this->input->post('groupID', true);
		$data = $this->plan_model->get_event_detail($input);
		$this->output
			->set_content_type('apllication/json')
			->set_output(json_encode($data));
	}

	public function ajax_update_plan()
	{
		$input['unitID'] 		= $this->input->post('unitID', true);
		$input['startDate'] 	= $this->input->post('startDate', true);
		$input['endDate'] 		= $this->input->post('endDate', true);
		$input['auditorTeam'] 	= is_array($this->input->post('auditorTeam', true)) ? $this->input->post('auditorTeam', true) : array();
		$input['planID'] 		= $this->input->post('planID', true);
		$input['updater'] 		= $this->user_data->get_email();
		$result['updatePlan'] = $this->plan_model->update_plan($input);
		$result['updateTeamToPlan'] = $this->plan_model->update_team_to_plan($input['planID'], $input['auditorTeam'], $input['updater']);

		$planToRemove = $this->plan_model->get_team_plan_detail_by_plan_id($input['planID'])->num_rows();
		if ($planToRemove == 0) {
			$deleteData['id'] 		= $input['planID'];
			$deleteData['updater'] 	= $this->user_data->get_email();
			$result['removePlan'] 	= $this->plan_model->delete_plan($deleteData);
		} else {
			$result['removePlan'] = false;
		}

		$this->output
			->set_content_type('apllication/json')
			->set_output(json_encode($result));
	}

	public function set_inspection()
	{
		$sideBar['name'] 	= $this->user_data->get_name();
		$sideBar['userTypes'] 	= $this->userTypes;
		$script['custom'] = $this->load->view('auditor_manage_inspection/set_inspection/script', '', true);
		$header['custom'] = $this->load->view('auditor_manage_inspection/set_inspection/custom_header', '', true);

		$component['header'] 			= $this->load->view('auditor_manage_inspection/component/header', $header, true);
		$component['navbar'] 			= $this->load->view('auditor_manage_inspection/component/navbar', '', true);
		$component['mainSideBar'] 		= $this->load->view('sidebar/main-sidebar', $sideBar, true);
		$component['mainFooter'] 		= $this->load->view('auditor_manage_inspection/component/footer_text', '', true);
		$component['controllerSidebar'] = $this->load->view('auditor_manage_inspection/component/controller_sidebar', '', true);
		$component['contentWrapper'] 	= $this->load->view('auditor_manage_inspection/set_inspection/content', '', true);
		$component['jsScript'] 			= $this->load->view('auditor_manage_inspection/component/main_script', $script, true);

		$this->load->view('auditor_manage_inspection/template', $component);
	}

	public function ajax_update_team_inspection()
	{
		$input['teamInspection'] = is_array($this->input->post('teamInspection', true)) ? $this->input->post('teamInspection', true) : [];
		$input['teamPlanID'] 	= $this->input->post('teamPlanID', true);
		$input['updater'] 		= $this->user_data->get_email();

		$update = $this->team_inspection_model->update_team_inspection($input);
		$this->output
			->set_content_type('apllication/json')
			->set_output(json_encode($update));
	}
}
