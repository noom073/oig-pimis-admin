<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
		$this->load->library('session_services');

		$this->load->model('user_model');
	}

	public function index()
	{
		$data['name'] 		= $this->session->nameth;
		$data['userType'] 	= $this->session_services->get_user_type_name($this->session->usertype);

		$script['customScript'] = $this->load->view('admin/index_content/script', '', true);

		$component['header'] 			= $this->load->view('admin/component/header', '', true);
		$component['navbar'] 			= $this->load->view('admin/component/navbar', '', true);
		$component['mainSideBar'] 		= $this->load->view('admin/component/sidebar', $data, true);
		$component['mainFooter'] 		= $this->load->view('admin/component/footer_text', '', true);
		$component['controllerSidebar'] = $this->load->view('admin/component/controller_sidebar', '', true);
		$component['contentWrapper'] 	= $this->load->view('admin/index_content/content', $data, true);
		$component['jsScript'] 			= $this->load->view('admin/component/main_script', $script, true);

		$this->load->view('admin/template', $component);
	}

	public function list_user()
	{
		$data['name'] 		= $this->session->nameth;
		$data['userType'] 	= $this->session_services->get_user_type_name($this->session->usertype);

		$script['customScript'] 		= $this->load->view('admin/list_user/script', '', true);

		$component['header'] 			= $this->load->view('admin/component/header', '', true);
		$component['navbar'] 			= $this->load->view('admin/component/navbar', '', true);
		$component['mainSideBar'] 		= $this->load->view('admin/component/sidebar', $data, true);
		$component['mainFooter'] 		= $this->load->view('admin/component/footer_text', '', true);
		$component['controllerSidebar'] = $this->load->view('admin/component/controller_sidebar', '', true);
		$component['contentWrapper'] 	= $this->load->view('admin/list_user/content', '', true);
		$component['jsScript'] 			= $this->load->view('admin/component/main_script', $script, true);

		$this->load->view('admin/template', $component);
	}

	public function ajax_get_user_all()
	{
		$users = $this->user_model->get_all_user()->result_array();
		$result = array_map(function ($r) {
			$data = $r;
			$data['TYPE_NAME'] = $this->session_services->get_user_type_name($r['TYPE_NAME']);
			return $data;
		}, $users);
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($result));
	}

	public function ajax_add_user()
	{
		$data['title'] 		= $this->input->post('title');
		$data['firstname'] 	= $this->input->post('fname');
		$data['lastname'] 	= $this->input->post('lname');
		$email 				= explode('@', $this->input->post('email'));
		$data['email'] 		= $email[0] . '@rtarf.mi.th';
		$data['userType'] 	= $this->input->post('userType');
		$data['activation'] = $this->input->post('activation');
		$data['updater'] 	= $this->session->email;

		$userDuplicate = $this->user_model->chk_user_duplicate($data['email'])->num_rows();
		if ($userDuplicate == 0) {
			$insert = $this->user_model->insert_user($data);
			if ($insert) {
				$result['status'] = true;
				$result['text'] = 'บันทึกสำเร็จ';
			} else {
				$result['status'] = false;
				$result['text'] = 'บันทึกไม่สำเร็จ';
			}
		} else {
			$result['status'] = false;
			$result['text'] = 'มี Email ซ้ำ';
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($result));
	}

	public function ajax_delete_user()
	{
		$data['userID'] = $this->input->post('userID');
		$delete = $this->user_model->delete_user($data);
		if ($delete) {
			$result['status'] = true;
			$result['text'] = 'ลบสำเร็จ';
		} else {
			$result['status'] = false;
			$result['text'] = 'ลบไม่สำเร็จ';
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($result));
	}

	public function ajax_get_user_detail()
	{
		$data['userID'] = $this->input->post('userID');
		$userDetail = $this->user_model->get_user_detail($data)->row_array();
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($userDetail));
	}

	public function ajax_update_user_detail()
	{
		$data['title'] 		= $this->input->post('title');
		$data['firstname'] 	= $this->input->post('fname');
		$data['lastname'] 	= $this->input->post('lname');
		$email 				= explode('@', $this->input->post('email'));
		$data['email'] 		= $email[0] . '@rtarf.mi.th';
		$data['userType'] 	= $this->input->post('userType');
		$data['activation'] = $this->input->post('activation');
		$data['userID'] 	= $this->input->post('userID');
		$data['updater'] 	= $this->session->email;

		$userDuplicate = $this->user_model->chk_user_for_update($data)->num_rows();
		if ($userDuplicate == 1) {
			$insert = $this->user_model->update_user($data);
			if ($insert) {
				$result['status'] = true;
				$result['text'] = 'บันทึกสำเร็จ';
			} else {
				$result['status'] = false;
				$result['text'] = 'บันทึกไม่สำเร็จ';
			}
		} else {
			$result['status'] = false;
			$result['text'] = 'มี Email ซ้ำ';
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($result));
	}
}
