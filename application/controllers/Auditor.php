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
		$this->load->model('summary_model');
	}

	public function index()
	{
		$username = $this->session->nameth;
		$userType = $this->session_services->get_user_type_name($this->session->usertype);

		$sideBar['name'] 	= $this->session->nameth;
		$sideBar['userType'] 	= array('Administrator', 'Controller', 'Auditor', 'Viewer', 'User');
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
		$username = $this->session->nameth;
		$userType = $this->session_services->get_user_type_name($this->session->usertype);

		$sideBar['name'] 	= $this->session->nameth;
		$sideBar['userType'] 	= array('Administrator', 'Controller', 'Auditor', 'Viewer', 'User');
		$script['custom'] = $this->load->view('auditor/calendar/script', '', true);
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
		$planID = $this->input->get('plan');
		$planData = $this->questionaire_model->get_plan($planID);
		if ($planData->num_rows() == 1) {
			//พบ planID 
			$username = $this->session->nameth;
			$userType = $this->session_services->get_user_type_name($this->session->usertype);

			$sideBar['name'] 	= $this->session->nameth;
			$sideBar['userType'] 	= array('Administrator', 'Controller', 'Auditor', 'Viewer', 'User');
			$dataForScript['planID'] = $planID;
			$script['custom'] = $this->load->view('auditor/inspection_list/script', $dataForScript, true);

			$data['plan'] = $planData->row_array();

			$component['header'] 			= $this->load->view('auditor/component/header', '', true);
			$component['navbar'] 			= $this->load->view('auditor/component/navbar', '', true);
			$component['mainSideBar'] 		= $this->load->view('sidebar/main-sidebar', $sideBar, true);
			$component['mainFooter'] 		= $this->load->view('auditor/component/footer_text', '', true);
			$component['controllerSidebar'] = $this->load->view('auditor/component/controller_sidebar', '', true);
			$component['contentWrapper'] 	= $this->load->view('auditor/inspection_list/content', $data, true);
			$component['jsScript'] 			= $this->load->view('auditor/component/main_script', $script, true);

			$this->load->view('auditor/template', $component);
		} else {
			// ไม่พบ planID ให้กลับไปหน้า calendar
			redirect('auditor/calendar');
		}
	}

	public function inspect()
	{
		$planID = $this->input->get('plan');
		$planData = $this->questionaire_model->get_plan($planID);
		if ($planData->num_rows() == 1) {
			//พบ planID 
			$username = $this->session->nameth;
			$userType = $this->session_services->get_user_type_name($this->session->usertype);

			$sideBar['name'] 	= $this->session->nameth;
			$sideBar['userType'] 	= array('Administrator', 'Controller', 'Auditor', 'Viewer', 'User');
			$dataForScript['planID'] = $planID;
			$script['custom'] = $this->load->view('auditor/inspect/script', $dataForScript, true);
			$header['custom'] = $this->load->view('auditor/inspect/custom_header', '', true);

			$inspections = $this->questionaire_model
				->get_inspections_with_inpected_check($planID)
				->result_array();
			$inspectionsDivide = array('odd' => array(), 'even' => array());
			foreach ($inspections as $index => $r) {
				if ($index % 2 == 1) {
					array_push($inspectionsDivide['even'], $r);
				} else {
					array_push($inspectionsDivide['odd'], $r);
				}
			}
			$data['inspections'] = $inspectionsDivide;
			$data['plan'] = $planData->row_array();

			$component['header'] 			= $this->load->view('auditor/component/header', $header, true);
			$component['navbar'] 			= $this->load->view('auditor/component/navbar', '', true);
			$component['mainSideBar'] 		= $this->load->view('sidebar/main-sidebar', $sideBar, true);
			$component['mainFooter'] 		= $this->load->view('auditor/component/footer_text', '', true);
			$component['controllerSidebar'] = $this->load->view('auditor/component/controller_sidebar', '', true);
			$component['contentWrapper'] 	= $this->load->view('auditor/inspect/content', $data, true);
			$component['jsScript'] 			= $this->load->view('auditor/component/main_script', $script, true);

			$this->load->view('auditor/template', $component);
		} else {
			// ไม่พบ planID ให้กลับไปหน้า calendar
			redirect('auditor/calendar');
		}
	}

	public function ajax_auditor_add_inpect_score()
	{
		$input = $this->input->post();
		$data['inspectionID'] = $input['inspectionID'];
		$data['planID'] = $input['planID'];
		unset($input['inspectionID']); //clear inspectionID ในชุดข้อมูล ก่อนจะ loop array score
		unset($input['planID']); //clear planID ในชุดข้อมูล ก่อนจะ loop array score
		$data['scores'] = $input;
		$result = $this->questionaire_model->insert_inspection_score($data);

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($result));
	}

	public function inspected()
	{
		$planID = $this->input->get('plan');
		$inspectionID = $this->input->get('inspectionID');
		$planData = $this->questionaire_model->get_plan($planID);
		if ($planData->num_rows() == 1) {
			//พบ planID 
			$username = $this->session->nameth;
			$userType = $this->session_services->get_user_type_name($this->session->usertype);
			$inspection = $this->questionaire_model->get_a_inspection($inspectionID)->row_array();

			$sideBar['name'] 	= $this->session->nameth;
			$sideBar['userType'] 	= array('Administrator', 'Controller', 'Auditor', 'Viewer', 'User');
			$dataForScript['planID'] = $planID;
			$dataForScript['inspection'] = $inspection;
			$script['custom'] = $this->load->view('auditor/inspected/script', $dataForScript, true);
			$header['custom'] = $this->load->view('auditor/inspected/custom_header', '', true);

			$data['inspection'] = $inspection;
			$data['plan'] = $planData->row_array();

			$component['header'] 			= $this->load->view('auditor/component/header', $header, true);
			$component['navbar'] 			= $this->load->view('auditor/component/navbar', '', true);
			$component['mainSideBar'] 		= $this->load->view('sidebar/main-sidebar', $sideBar, true);
			$component['mainFooter'] 		= $this->load->view('auditor/component/footer_text', '', true);
			$component['controllerSidebar'] = $this->load->view('auditor/component/controller_sidebar', '', true);
			$component['contentWrapper'] 	= $this->load->view('auditor/inspected/content', $data, true);
			$component['jsScript'] 			= $this->load->view('auditor/component/main_script', $script, true);

			$this->load->view('auditor/template', $component);
		} else {
			// ไม่พบ planID ให้กลับไปหน้า calendar
			redirect('auditor/calendar');
		}
	}

	public function inspection_result()
	{
		$planID = $this->input->get('plan');
		$inspectionID = $this->input->get('inspectionID');
		$planData = $this->questionaire_model->get_plan($planID);
		if ($planData->num_rows() == 1) {
			//พบ planID 
			$username = $this->session->nameth;
			$userType = $this->session_services->get_user_type_name($this->session->usertype);
			$inspection = $this->questionaire_model->get_a_inspection($inspectionID)->row_array();

			$sideBar['name'] 	= $this->session->nameth;
			$sideBar['userType'] 	= array('Administrator', 'Controller', 'Auditor', 'Viewer', 'User');
			$dataForScript['planID'] = $planID;
			$dataForScript['inspection'] = $inspection;
			$script['custom'] = $this->load->view('auditor/inspection_result/script', $dataForScript, true);

			$data['inspection'] = $inspection;
			$data['plan'] = $planData->row_array();

			$component['header'] 			= $this->load->view('auditor/component/header', '', true);
			$component['navbar'] 			= $this->load->view('auditor/component/navbar', '', true);
			$component['mainSideBar'] 		= $this->load->view('sidebar/main-sidebar', $sideBar, true);
			$component['mainFooter'] 		= $this->load->view('auditor/component/footer_text', '', true);
			$component['controllerSidebar'] = $this->load->view('auditor/component/controller_sidebar', '', true);
			$component['contentWrapper'] 	= $this->load->view('auditor/inspection_result/content', $data, true);
			$component['jsScript'] 			= $this->load->view('auditor/component/main_script', $script, true);

			$this->load->view('auditor/template', $component);
		} else {
			// ไม่พบ planID ให้กลับไปหน้า calendar
			redirect('auditor/calendar');
		}
	}

	public function inspection_summary()
	{
		$planID = $this->input->get('plan');
		$inspectionID = $this->input->get('inspectionID');
		$planData = $this->questionaire_model->get_plan($planID);
		if ($planData->num_rows() == 1) {
			//พบ planID 
			$username = $this->session->nameth;
			$userType = $this->session_services->get_user_type_name($this->session->usertype);
			$inspection = $this->questionaire_model->get_a_inspection($inspectionID)->row_array();
			$sumScore = $this->questionaire_model->get_sum_form_score_by_planid($planID)->row_array();

			$sideBar['name'] 	= $this->session->nameth;
			$sideBar['userType'] 	= array('Administrator', 'Controller', 'Auditor', 'Viewer', 'User');
			$dataForScript['planID'] = $planID;
			$dataForScript['inspection'] = $inspection;
			$script['custom'] = $this->load->view('auditor/inspection_summary/script', $dataForScript, true);

			$data['inspection'] = $inspection;
			$data['plan'] = $planData->row_array();
			$data['sumScore'] = $sumScore;

			$component['header'] 			= $this->load->view('auditor/component/header', '', true);
			$component['navbar'] 			= $this->load->view('auditor/component/navbar', '', true);
			$component['mainSideBar'] 		= $this->load->view('sidebar/main-sidebar', $sideBar, true);
			$component['mainFooter'] 		= $this->load->view('auditor/component/footer_text', '', true);
			$component['controllerSidebar'] = $this->load->view('auditor/component/controller_sidebar', '', true);
			$component['contentWrapper'] 	= $this->load->view('auditor/inspection_summary/content', $data, true);
			$component['jsScript'] 			= $this->load->view('auditor/component/main_script', $script, true);

			$this->load->view('auditor/template', $component);
		} else {
			// ไม่พบ planID ให้กลับไปหน้า calendar
			redirect('auditor/calendar');
		}
	}

	public function ajax_add_summary()
	{
		$data['planID'] = $this->input->post('planID');
		$data['inspectionID'] = $this->input->post('inspectionID');
		$data['comment'] = $this->input->post('comment');
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
		$planID = $this->input->post('planID');
		$summaries = $this->summary_model->get_summaries($planID)->result_array();
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($summaries));
	}

	public function ajax_update_plan_score()
	{
		$data['planID'] = $this->input->post('plan');
		$data['policyScore'] = $this->input->post('policyScore');
		$data['prepareScore'] = $this->input->post('prepareScore');
		$update = $this->questionaire_model->update_plan_score($data);
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
		$detail = $this->summary_model->get_summary_detail($summaryID)->row_array();
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($detail));
	}

	public function ajax_update_summary()
	{
		$data['summaryID'] = $this->input->post('summaryID');
		$data['inspectionID'] = $this->input->post('inspectionID');
		$data['comment'] = $this->input->post('comment');
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
		$delete = $this->summary_model->delete_summary($summaryID);
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
}
