<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auditor extends CI_Controller
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
		$this->load->model('team_inspection_model');
		$this->load->model('plan_model');
		$this->load->model('inspection_option_model');
		$this->load->model('inspection_notes_model');

		$data['token'] = get_cookie('pimis-token');
		$this->load->library('user_data', $data);

		$this->userTypes = $this->user_data->get_user_types();
		$hasPermition = in_array('admin', $this->userTypes);
		if (!$hasPermition) redirect('welcome/forbidden');
	}

	public function index()
	{
		$sideBar['name'] 	= $this->session->nameth;
		$sideBar['userTypes'] 	= $this->userTypes;
		$script['custom'] = $this->load->view('auditor/index_content/script', '', true);
		$header['custom'] = '';

		$component['header'] 			= $this->load->view('auditor/component/header', $header, true);
		$component['navbar'] 			= $this->load->view('auditor/component/navbar', '', true);
		$component['mainSideBar'] 		= $this->load->view('sidebar/main-sidebar', $sideBar, true);
		$component['mainFooter'] 		= $this->load->view('auditor/component/footer_text', '', true);
		$component['controllerSidebar'] = $this->load->view('auditor/component/controller_sidebar', '', true);
		$component['contentWrapper'] 	= $this->load->view('auditor/index_content/content', '', true);
		$component['jsScript'] 			= $this->load->view('auditor/component/main_script', $script, true);

		$this->load->view('auditor/template', $component);
	}

	public function calendar()
	{
		$data['teams'] 		= $this->user_data->get_own_team();
		$sideBar['name'] 	= $this->session->nameth;
		$sideBar['userTypes'] 	= $this->userTypes;
		$script['custom'] = $this->load->view('auditor/calendar/script', $data, true);
		$header['custom'] = $this->load->view('auditor/calendar/custom_header', '', true);

		$component['header'] 			= $this->load->view('auditor/component/header', $header, true);
		$component['navbar'] 			= $this->load->view('auditor/component/navbar', '', true);
		$component['mainSideBar'] 		= $this->load->view('sidebar/main-sidebar', $sideBar, true);
		$component['mainFooter'] 		= $this->load->view('auditor/component/footer_text', '', true);
		$component['controllerSidebar'] = $this->load->view('auditor/component/controller_sidebar', '', true);
		$component['contentWrapper'] 	= $this->load->view('auditor/calendar/content', '', true);
		$component['jsScript'] 			= $this->load->view('auditor/component/main_script', $script, true);

		$this->load->view('auditor/template', $component);
	}

	public function inspection_list()
	{
		$teamPlanID = $this->input->get('team_plan_id', true);
		$email = $this->user_data->get_email();
		$check = $this->plan_model->check_team_plan_by_auditor($email, $teamPlanID);
		if ($check) {
			$data['teamPlan'] = $this->plan_model->get_a_team_plan($teamPlanID)->row_array();
			$data['planDetail'] = $this->plan_model->get_a_plan_by_id($data['teamPlan']['PLAN_ID'])->row_array();
			$sideBar['name'] 	= $this->session->nameth;
			$sideBar['userTypes'] 	= $this->userTypes;
			$dataForScript['planID'] = $teamPlanID;
			$script['custom'] = $this->load->view('auditor/inspection_list/script', $dataForScript, true);

			$component['header'] 			= $this->load->view('auditor/component/header', '', true);
			$component['navbar'] 			= $this->load->view('auditor/component/navbar', '', true);
			$component['mainSideBar'] 		= $this->load->view('sidebar/main-sidebar', $sideBar, true);
			$component['mainFooter'] 		= $this->load->view('auditor/component/footer_text', '', true);
			$component['controllerSidebar'] = $this->load->view('auditor/component/controller_sidebar', '', true);
			$component['contentWrapper'] 	= $this->load->view('auditor/inspection_list/content', $data, true);
			$component['jsScript'] 			= $this->load->view('auditor/component/main_script', $script, true);

			$this->load->view('auditor/template', $component);
		} else {
			redirect('auditor/calendar');
		}
	}

	public function inspect()
	{
		$teamPlanID = $this->input->get('team_plan_id', true);
		$email = $this->user_data->get_email();
		$check = $this->plan_model->check_team_plan_by_auditor($email, $teamPlanID);
		if ($check) {
			$data['inspections'] = $this->team_inspection_model->get_team_inspection_and_check_inspected($teamPlanID)->result_array();
			$data['teamPlan'] = $this->plan_model->get_a_team_plan($teamPlanID)->row_array();
			$data['planDetail'] = $this->plan_model->get_a_plan_by_id($data['teamPlan']['PLAN_ID'])->row_array();

			$sideBar['name'] 	= $this->session->nameth;
			$sideBar['userTypes'] 	= $this->userTypes;
			$script['custom'] = $this->load->view('auditor/inspect/script', $data, true);
			$header['custom'] = $this->load->view('auditor/inspect/custom_header', '', true);

			$component['header'] 			= $this->load->view('auditor/component/header', $header, true);
			$component['navbar'] 			= $this->load->view('auditor/component/navbar', '', true);
			$component['mainSideBar'] 		= $this->load->view('sidebar/main-sidebar', $sideBar, true);
			$component['mainFooter'] 		= $this->load->view('auditor/component/footer_text', '', true);
			$component['controllerSidebar'] = $this->load->view('auditor/component/controller_sidebar', '', true);
			$component['contentWrapper'] 	= $this->load->view('auditor/inspect/content', $data, true);
			$component['jsScript'] 			= $this->load->view('auditor/component/main_script', $script, true);

			$this->load->view('auditor/template', $component);
		} else {
			redirect('auditor/calendar');
		}
	}

	public function ajax_auditor_add_inpect_score()
	{
		$data['inspectionOptionID'] = $this->input->post('inspectionOptionID', true);
		$data['teamPlanID'] = $this->input->post('teamPlanID', true);
		$input = $this->input->post();
		unset($input['inspectionOptionID']); //clear inspectionID ในชุดข้อมูล ก่อนจะ loop array score
		unset($input['teamPlanID']); //clear planID ในชุดข้อมูล ก่อนจะ loop array score
		$data['scores'] = $input;
		$data['updator'] = $this->session->email;
		$result = $this->questionaire_model->insert_inspection_score($data);

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($result));
	}

	public function inspected()
	{
		$teamPlanID = $this->input->get('teamPlanID', true);
		$inspectionOptionID = $this->input->get('inspectionOptionID', true);
		$email = $this->user_data->get_email();
		$check = $this->plan_model->check_team_plan_by_auditor($email, $teamPlanID);
		if ($check) {
			$data['teamPlan'] = $this->plan_model->get_a_team_plan($teamPlanID)->row_array();
			$data['inspectionOption'] = $this->inspection_option_model->get_inspection_option($inspectionOptionID)->row_array();
			$data['planDetail'] = $this->plan_model->get_a_plan_by_id($data['teamPlan']['PLAN_ID'])->row_array();

			$sideBar['name'] 	= $this->session->nameth;
			$sideBar['userTypes'] 	= $this->userTypes;
			$script['custom'] = $this->load->view('auditor/inspected/script', $data, true);
			$header['custom'] = $this->load->view('auditor/inspected/custom_header', '', true);

			$component['header'] 			= $this->load->view('auditor/component/header', $header, true);
			$component['navbar'] 			= $this->load->view('auditor/component/navbar', '', true);
			$component['mainSideBar'] 		= $this->load->view('sidebar/main-sidebar', $sideBar, true);
			$component['mainFooter'] 		= $this->load->view('auditor/component/footer_text', '', true);
			$component['controllerSidebar'] = $this->load->view('auditor/component/controller_sidebar', '', true);
			$component['contentWrapper'] 	= $this->load->view('auditor/inspected/content', $data, true);
			$component['jsScript'] 			= $this->load->view('auditor/component/main_script', $script, true);

			$this->load->view('auditor/template', $component);
		} else {
			redirect('auditor/calendar');
		}
	}

	public function inspection_result()
	{
		$teamPlanID = $this->input->get('team_plan_id', true);
		$email = $this->user_data->get_email();
		$check = $this->plan_model->check_team_plan_by_auditor($email, $teamPlanID);
		if ($check) {
			$data['teamPlan'] = $this->plan_model->get_a_team_plan($teamPlanID)->row_array();
			$data['teamInspections'] = $this->team_inspection_model->get_team_inspection($teamPlanID)->result_array();
			$data['planDetail'] = $this->plan_model->get_a_plan_by_id($data['teamPlan']['PLAN_ID'])->row_array();
			$data['name'] 	= $this->session->nameth;

			$sideBar['name'] 	= $this->session->nameth;
			$sideBar['userTypes'] 	= $this->userTypes;
			$script['custom'] = $this->load->view('auditor/inspection_result/script', $data, true);
			$component['header'] 			= $this->load->view('auditor/component/header', '', true);
			$component['navbar'] 			= $this->load->view('auditor/component/navbar', '', true);
			$component['mainSideBar'] 		= $this->load->view('sidebar/main-sidebar', $sideBar, true);
			$component['mainFooter'] 		= $this->load->view('auditor/component/footer_text', '', true);
			$component['controllerSidebar'] = $this->load->view('auditor/component/controller_sidebar', '', true);
			$component['contentWrapper'] 	= $this->load->view('auditor/inspection_result/content', $data, true);
			$component['jsScript'] 			= $this->load->view('auditor/component/main_script', $script, true);

			$this->load->view('auditor/template', $component);
		} else {
			redirect('auditor/calendar');
		}
	}

	public function inspection_summary()
	{
		$teamPlanID = $this->input->get('team_plan_id', true);
		$email = $this->user_data->get_email();
		$check = $this->plan_model->check_team_plan_by_auditor($email, $teamPlanID);
		if ($check) {
			$data['teamPlan'] = $this->plan_model->get_a_team_plan($teamPlanID)->row_array();
			$data['teamInspections'] = $this->team_inspection_model->get_team_inspection($teamPlanID)->result_array();
			$data['planDetail'] = $this->plan_model->get_a_plan_by_id($data['teamPlan']['PLAN_ID'])->row_array();
			$sumScore = $this->questionaire_model->get_sum_form_score_by_planid($teamPlanID)->row_array();
			$data['sumScore'] = $sumScore;

			$sideBar['name'] 	= $this->session->nameth;
			$sideBar['userTypes'] 	= $this->userTypes;
			$script['custom'] = $this->load->view('auditor/inspection_summary/script', $data, true);


			$component['header'] 			= $this->load->view('auditor/component/header', '', true);
			$component['navbar'] 			= $this->load->view('auditor/component/navbar', '', true);
			$component['mainSideBar'] 		= $this->load->view('sidebar/main-sidebar', $sideBar, true);
			$component['mainFooter'] 		= $this->load->view('auditor/component/footer_text', '', true);
			$component['controllerSidebar'] = $this->load->view('auditor/component/controller_sidebar', '', true);
			$component['contentWrapper'] 	= $this->load->view('auditor/inspection_summary/content', $data, true);
			$component['jsScript'] 			= $this->load->view('auditor/component/main_script', $script, true);

			$this->load->view('auditor/template', $component);
		} else {
			redirect('auditor/calendar');
		}

		// $planID = $this->input->get('plan');
		// $inspectionID = $this->input->get('inspectionID');
		// $planData = $this->questionaire_model->get_plan($planID);
		// if ($planData->num_rows() == 1) {
		// 	//พบ planID 
		// 	$inspection = $this->questionaire_model->get_a_inspection($inspectionID)->row_array();
		// 	$sumScore = $this->questionaire_model->get_sum_form_score_by_planid($planID)->row_array();

		// 	$sideBar['name'] 	= $this->session->nameth;
		// 	$sideBar['userType'] 	= array('Administrator', 'Controller', 'Auditor', 'Viewer', 'User');
		// 	$dataForScript['planID'] = $planID;
		// 	$dataForScript['inspection'] = $inspection;
		// 	$script['custom'] = $this->load->view('auditor/inspection_summary/script', $dataForScript, true);

		// 	$data['inspection'] = $inspection;
		// 	$data['plan'] = $planData->row_array();
		// 	$data['sumScore'] = $sumScore;

		// 	$component['header'] 			= $this->load->view('auditor/component/header', '', true);
		// 	$component['navbar'] 			= $this->load->view('auditor/component/navbar', '', true);
		// 	$component['mainSideBar'] 		= $this->load->view('sidebar/main-sidebar', $sideBar, true);
		// 	$component['mainFooter'] 		= $this->load->view('auditor/component/footer_text', '', true);
		// 	$component['controllerSidebar'] = $this->load->view('auditor/component/controller_sidebar', '', true);
		// 	$component['contentWrapper'] 	= $this->load->view('auditor/inspection_summary/content', $data, true);
		// 	$component['jsScript'] 			= $this->load->view('auditor/component/main_script', $script, true);

		// 	$this->load->view('auditor/template', $component);
		// } else {
		// 	// ไม่พบ planID ให้กลับไปหน้า calendar
		// 	redirect('auditor/calendar');
		// }
	}

	public function ajax_add_summary()
	{
		$data['teamPlanID'] = $this->input->post('teamPlanID');
		$data['inspectionOptionID'] = $this->input->post('inspectionOptionID');
		$data['comment'] = $this->input->post('comment');
		$data['updator'] = $this->session->email;
		$insert = $this->summary_model->add_summary($data);
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

	public function ajax_get_summary()
	{
		$planID = $this->input->post('teamPlanID');
		$summaries = $this->summary_model->get_summaries($planID)->result_array();
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($summaries));
	}

	public function ajax_update_plan_score()
	{
		$data['teamPlanID'] = $this->input->post('teamPlanID');
		$data['policyScore'] = $this->input->post('policyScore');
		$data['prepareScore'] = $this->input->post('prepareScore');
		$data['updator'] = $this->session->email;
		$update = $this->questionaire_model->update_team_plan_score($data);
		if ($update) {
			$result['status'] = true;
			$result['text'] = 'บันทึกสำเร็จ';
			$result['data'] = $data;
		} else {
			$result['status'] = false;
			$result['text'] = 'บันทึกไม่สำเร็จ';
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($result));
	}

	public function ajax_get_summary_detail()
	{
		$summaryID = $this->input->post('summaryID');
		$getData = $this->summary_model->get_summary_detail($summaryID);
		if ($getData->num_rows()) {
			$data = $getData->row_array();
			$result['ROW_ID'] 		= $data['ROW_ID'];
			$result['TEAMPLAN_ID'] 	= $data['TEAMPLAN_ID'];
			$result['INSPECTION_OPTION_ID'] = $data['INSPECTION_OPTION_ID'];
			$result['USER_UPDATE'] 	= $data['USER_UPDATE'];
			$result['TIME_UPDATE'] 	= $data['TIME_UPDATE'];
			$result['COMMENTION'] 	= $data['COMMENTION']->load();
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($result));
	}

	public function ajax_update_summary()
	{
		$data['summaryID'] = $this->input->post('summaryID', true);
		$data['inspectionID'] = $this->input->post('inspectionID', true);
		$data['comment'] = $this->input->post('comment', true);
		$data['updator'] = $this->session->email;
		$insert = $this->summary_model->update_summary($data);
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

	public function ajax_delete_summary()
	{
		$summaryID = $this->input->post('summaryID');
		$updator = $this->session->email;
		$delete = $this->summary_model->delete_summary($summaryID, $updator);
		if ($delete) {
			$result['status'] = true;
			$result['text'] = 'ลบข้อมูลสำเร็จ';
		} else {
			$result['status'] = false;
			$result['text'] = 'ลบข้อมูลไม่สำเร็จ';
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($result));
	}

	public function ajax_update_inspect_score()
	{
		$teamPlanID = $this->input->post('teamPlanID', true);
		$scores = $this->input->post();
		$updator = $this->session->email;
		unset($scores['teamPlanID']);
		$result = array();
		foreach ($scores as $key => $val) {
			$questionID = explode('score-', $key)[1];
			$update = $this->questionaire_model->update_inspection_score($val, $teamPlanID, $questionID, $updator);
			if ($update) {
				$data['questionID'] = $questionID;
				$data['planID'] = $teamPlanID;
				$data['score'] = $val;
				$data['status'] = true;
			} else {
				$data['questionID'] = $questionID;
				$data['planID'] = $teamPlanID;
				$data['score'] = $val;
				$data['status'] = false;
			}
			$result[] = $data;
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($result));
	}

	public function ajax_add_note_inspection_result()
	{
		$input['teamPlanID'] 		= $this->input->post('teamPlanID', true);
		$input['inspectionOptionID'] = $this->input->post('inspectionOptionID', true);
		$input['commander'] 		= $this->input->post('commander', true);
		$input['dateTime'] 			= $this->input->post('dateTime', true);
		$input['auditee'] 			= $this->input->post('auditee', true);
		$input['auditeePosition']	= $this->input->post('auditeePosition', true);
		$input['canImprove'] 		= $this->input->post('canImprove', true);
		$input['failing'] 			= $this->input->post('failing', true);
		$input['importantFailing'] 	= $this->input->post('importantFailing', true);
		$input['commention'] 		= $this->input->post('commention', true);
		$input['inspectioScore'] 	= $this->input->post('inspectioScore', true);
		$input['workingScore'] 		= $this->input->post('workingScore', true);
		$input['updater'] 			= $this->session->email;
		$insert = $this->inspection_notes_model->insert_inspection_note_result($input);
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

	public function ajax_get_inspection_notes_list_by_team_plan_id()
	{
		$input = $this->input->post('teamPlanID', true);
		$data = $this->inspection_notes_model->get_inspection_notes_list_by_team_plan_id($input)->result_array();
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($data));
	}

	public function ajax_get_inspection_note_detail()
	{
		$input = $this->input->post('rowID', true);
		$getData = $this->inspection_notes_model->get_inspection_note_by_id($input);
		$result = [];
		if ($getData->num_rows()) {
			$data = $getData->row_array();
			$result['TEAMPLAN_ID'] 		= $data['TEAMPLAN_ID'];
			$result['INSPECTION_OPTION_ID'] = $data['INSPECTION_OPTION_ID'];
			$result['UNIT_COMMANDER'] 	= $data['UNIT_COMMANDER'];
			$result['AUDITEE_NAME'] 	= $data['AUDITEE_NAME'];
			$result['AUDITEE_POS'] 		= $data['AUDITEE_POS'];
			$result['AUDITOR_EMAIL'] 	= $data['AUDITOR_EMAIL'];
			$result['INSPECTION_SCORE'] = $data['INSPECTION_SCORE'];
			$result['WORKING_SCORE'] 	= $data['WORKING_SCORE'];
			$result['CAN_IMPROVE'] 		= $data['CAN_IMPROVE']->load();
			$result['FAILING'] 			= $data['FAILING']->load();
			$result['IMPORTANT_FAILING'] = $data['IMPORTANT_FAILING']->load();
			$result['COMMENTIONS'] 		= $data['COMMENTIONS']->load();
			$result['DATE_INSPECT'] 	= date('Y-m-d', strtotime($data['DATE_INSPECT']));
			$result['USER_UPDATE'] 		= $data['USER_UPDATE'];
			$result['TIME_UPDATE'] 		= $data['TIME_UPDATE'];
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($result));
	}

	public function ajax_update_inspection_note()
	{
		$input['rowID'] 			= $this->input->post('rowID', true);
		$input['inspectionOptionID'] = $this->input->post('inspectionOptionID', true);
		$input['commander'] 		= $this->input->post('commander', true);
		$input['dateTime'] 			= $this->input->post('dateTime', true);
		$input['auditee'] 			= $this->input->post('auditee', true);
		$input['auditeePosition']	= $this->input->post('auditeePosition', true);
		$input['canImprove'] 		= $this->input->post('canImprove', true);
		$input['failing'] 			= $this->input->post('failing', true);
		$input['importantFailing'] 	= $this->input->post('importantFailing', true);
		$input['commention'] 		= $this->input->post('commention', true);
		$input['inspectioScore'] 	= $this->input->post('inspectioScore', true);
		$input['workingScore'] 		= $this->input->post('workingScore', true);
		$input['updater'] 			= $this->session->email;
		$update = $this->inspection_notes_model->update_inspection_note($input);
		if ($update) {
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

	public function ajax_delete_inspection_note()
	{
		$input['rowID'] 	= $this->input->post('rowID', true);
		$input['updater']	= $this->session->email;
		$delete = $this->inspection_notes_model->delete_inspection_note($input);
		if ($delete) {
			$result['status'] = true;
			$result['text'] = 'ลบข้อมูลสำเร็จ';
		} else {
			$result['status'] = false;
			$result['text'] = 'ลบข้อมูลไม่สำเร็จ';
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($result));
	}

	public function ajax_get_inspection_note_by_team_plan_id_n_inspection_option_id()
	{
		$teamPlanID = $this->input->post('teamPlanID', true);
		$inspectionOptionID = $this->input->post('inspectionOptionID', true);
		$note = $this->inspection_notes_model
			->get_inspection_note_by_team_plan_id_n_inspection_option_id($teamPlanID, $inspectionOptionID);
		if ($note->num_rows()) {
			$data = $note->row_array();
			$rs['TEAMPLAN_ID'] 		= $data['TEAMPLAN_ID'];
			$rs['INSPECTION_OPTION_ID'] = $data['INSPECTION_OPTION_ID'];
			$rs['UNIT_COMMANDER'] 	= $data['UNIT_COMMANDER'];
			$rs['AUDITEE_NAME'] 	= $data['AUDITEE_NAME'];
			$rs['AUDITEE_POS'] 		= $data['AUDITEE_POS'];
			$rs['AUDITOR_EMAIL'] 	= $data['AUDITOR_EMAIL'];
			$rs['INSPECTION_SCORE'] = $data['INSPECTION_SCORE'];
			$rs['WORKING_SCORE'] 	= $data['WORKING_SCORE'];
			$rs['CAN_IMPROVE'] 		= $data['CAN_IMPROVE']->load();
			$rs['FAILING'] 			= $data['FAILING']->load();
			$rs['IMPORTANT_FAILING'] = $data['IMPORTANT_FAILING']->load();
			$rs['COMMENTIONS'] 		= $data['COMMENTIONS']->load();
			$rs['DATE_INSPECT'] 	= date('Y-m-d', strtotime($data['DATE_INSPECT']));
			$rs['USER_UPDATE'] 		= $data['USER_UPDATE'];
			$rs['TIME_UPDATE'] 		= $data['TIME_UPDATE'];
		} else {
			$rs = [];
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($rs));
	}
}
