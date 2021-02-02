<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{
	private $userTypes;
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		$this->load->helper('cookie');
		$this->load->library('session');
		$this->load->library('session_services');
		$this->load->model('user_model');
		$this->load->model('privilege_model');

		$data['token'] = get_cookie('pimis-token');
		$this->load->library('user_data', $data);

		$this->userTypes = $this->user_data->get_user_types();
		$hasPermition = in_array('admin', $this->userTypes);
		if (!$hasPermition) redirect('welcome/forbidden');
	}

	public function index()
	{
		$data['name'] 		= $this->session->nameth;
		$data['userType'] 	= $this->session_services->get_user_type_name($this->session->usertype);
		$sideBar['name'] 	= $this->session->nameth;
		$sideBar['userTypes'] 	= $this->userTypes;
		$script['customScript'] = $this->load->view('admin/index_content/script', '', true);

		$component['header'] 			= $this->load->view('admin/component/header', '', true);
		$component['navbar'] 			= $this->load->view('admin/component/navbar', '', true);
		$component['mainSideBar'] 		= $this->load->view('sidebar/main-sidebar', $sideBar, true);
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
		$sideBar['name'] 		= $this->session->nameth;
		$sideBar['userTypes'] 	= $this->userTypes;
		$script['customScript'] = $this->load->view('admin/list_user/script', '', true);

		$component['header'] 			= $this->load->view('admin/component/header', '', true);
		$component['navbar'] 			= $this->load->view('admin/component/navbar', '', true);
		$component['mainSideBar'] 		= $this->load->view('sidebar/main-sidebar', $sideBar, true);
		$component['mainFooter'] 		= $this->load->view('admin/component/footer_text', '', true);
		$component['controllerSidebar'] = $this->load->view('admin/component/controller_sidebar', '', true);
		$component['contentWrapper'] 	= $this->load->view('admin/list_user/content', $data, true);
		$component['jsScript'] 			= $this->load->view('admin/component/main_script', $script, true);

		$this->load->view('admin/template', $component);
	}

	public function list_authorize()
	{
		$allUserTypes 		= $this->user_model->list_user_type()->result_array();
		$data['name'] 		= $this->session->nameth;
		$data['userType'] 	= $this->session_services->get_user_type_name($this->session->usertype);
		$data['allUserTypes'] = array_map(function ($r) {
			$r['TYPE_NAME'] = $this->session_services->get_user_type_name($r['TYPE_NAME']);
			return $r;
		}, $allUserTypes);
		$sideBar['name'] 		= $this->session->nameth;
		$sideBar['userTypes'] 	= $this->userTypes;
		$script['customScript'] = $this->load->view('admin/list_authorize/script', '', true);
		$header['custom'] 		= $this->load->view('admin/list_authorize/header', '', true);

		$component['header'] 			= $this->load->view('admin/component/header', $header, true);
		$component['navbar'] 			= $this->load->view('admin/component/navbar', '', true);
		$component['mainSideBar'] 		= $this->load->view('sidebar/main-sidebar', $sideBar, true);
		$component['mainFooter'] 		= $this->load->view('admin/component/footer_text', '', true);
		$component['controllerSidebar'] = $this->load->view('admin/component/controller_sidebar', '', true);
		$component['contentWrapper'] 	= $this->load->view('admin/list_authorize/content', $data, true);
		$component['jsScript'] 			= $this->load->view('admin/component/main_script', $script, true);

		$this->load->view('admin/template', $component);
	}

	public function user_authorize()
	{
		$userID = $this->input->get('userID', true);
		$data['userDetail'] 	= $this->user_model->get_user_detail($userID)->row_array();
		$sideBar['name'] 		= $this->session->nameth;
		$sideBar['userTypes'] 	= $this->userTypes;
		$scriptData['userPrivileges'] = $this->user_model->get_privileges_per_user($userID)->result_array();
		$script['customScript'] = $this->load->view('admin/user_authorize/script', $scriptData, true);
		$header['custom'] 		= $this->load->view('admin/user_authorize/header', '', true);

		$component['header'] 			= $this->load->view('admin/component/header', $header, true);
		$component['navbar'] 			= $this->load->view('admin/component/navbar', '', true);
		$component['mainSideBar'] 		= $this->load->view('sidebar/main-sidebar', $sideBar, true);
		$component['mainFooter'] 		= $this->load->view('admin/component/footer_text', '', true);
		$component['controllerSidebar'] = $this->load->view('admin/component/controller_sidebar', '', true);
		$component['contentWrapper'] 	= $this->load->view('admin/user_authorize/content', $data, true);
		$component['jsScript'] 			= $this->load->view('admin/component/main_script', $script, true);

		$this->load->view('admin/template', $component);
	}

	public function ajax_get_user_all()
	{
		$users = $this->user_model->get_all_user()->result_array();
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($users));
	}

	public function ajax_list_user_and_privileges()
	{
		$users = $this->user_model->get_all_user()->result_array();
		$result = array_map(function ($r) {
			$privilegesData = $this->user_model->get_privileges_per_user($r['USER_ID'])->result_array();
			$privileges = array();
			foreach ($privilegesData as $data) {
				$data['TYPE_NAME_FULL'] = $this->session_services->get_user_type_name($data['TYPE_NAME']);
				$privileges[] = $data;
			}
			$r['PRIVILEGES'] = $privileges;
			return $r;
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
		$data['idp'] 		= $this->input->post('idp');
		$email 				= explode('@', $this->input->post('email'));
		$data['email'] 		= $email[0] . '@rtarf.mi.th';
		$data['userType']	= array('2');
		$data['activation'] = $this->input->post('activation');
		$data['unitID'] = $this->input->post('unitID');
		$data['updater'] 	= $this->session->email;

		$userDuplicate = $this->user_model->chk_user_duplicate($data['email'])->num_rows();
		if ($userDuplicate == 0) {
			$insert = $this->user_model->insert_user($data);
			if ($insert['status']) {
				$insertPrivilege = $this->user_model->insert_privileges($data['userType'], $insert['insertID'], $data['updater']);
				$result['status'] = true;
				$result['text'] = 'บันทึกสำเร็จ';
				$result['insertPrivileges'] = $insertPrivilege;
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
		$data['updater'] = $this->session->email;
		$deletePrivileges = $this->user_model->delete_privileges($data);
		$deleteUser = $this->user_model->delete_user($data);

		if ($deleteUser) { // CHECK DELETE USER STATUS
			$result['user']['status'] = true;
			$result['user']['text'] = 'ลบผู้ใช้งานสำเร็จ';
		} else {
			$result['user']['status'] = false;
			$result['user']['text'] = 'ลบผู้ใช้งานไม่สำเร็จ';
		}
		if ($deletePrivileges) { // CHECK DELETE PRIVILEGES USER STATUS
			$result['privilege']['status'] = true;
			$result['privilege']['text'] = 'ลบสิทธิผู้ใช้งานสำเร็จ';
		} else {
			$result['privilege']['status'] = false;
			$result['privilege']['text'] = 'ลบสิทธิผู้ใช้งานไม่สำเร็จ';
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($result));
	}

	public function ajax_get_user_detail()
	{
		$userID = $this->input->post('userID');
		$userDetail = $this->user_model->get_user_detail($userID)->row_array();
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($userDetail));
	}

	public function ajax_update_user_detail()
	{
		$data['title'] 		= $this->input->post('title');
		$data['firstname'] 	= $this->input->post('fname');
		$data['lastname'] 	= $this->input->post('lname');
		$data['idp'] 	= $this->input->post('idp');
		$email 				= explode('@', $this->input->post('email'));
		$data['email'] 		= $email[0] . '@rtarf.mi.th';
		$data['activation'] = $this->input->post('activation');
		$data['userID'] 	= $this->input->post('userID');
		$data['unitID'] 	= $this->input->post('unitID');
		$data['updater'] 	= $this->session->email;

		$userDuplicate = $this->user_model->chk_user_for_update($data)->num_rows();
		if ($userDuplicate <= 1) {
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

	public function ajax_edit_privilage()
	{
		$userID 	= $this->input->post('userID', true);
		$updater	= $this->session->email;
		$newPrivileges 	= is_array($this->input->post('privileges', true)) ? $this->input->post('privileges', true) : array();
		$privileges 	= $this->user_model->get_privileges_per_user($userID)->result_array();
		$oldPrivileges 	= array_map(function ($r) {
			return $r['TYPE_ID'];
		}, $privileges);
		$toClearPrivileges 	= array_merge(array_diff($oldPrivileges, $newPrivileges), array());
		$toAddPrivileges 	= array_merge(array_diff($newPrivileges, $oldPrivileges), array());
		$result = array();
		foreach ($toClearPrivileges as $r) { // REMOVE OTHER PRIVRILEGE
			$data['privilege'] = $r;
			$data['userID'] = $userID;
			$data['result'] = $this->privilege_model->remove_privilege($userID, $r, $updater);
			$result['remove'][] = $data;
		}
		foreach ($toAddPrivileges as $r) { // ADD NEW PRIVRILEGE
			$data['privilege'] = $r;
			$data['userID'] = $userID;
			$data['result'] = $this->privilege_model->add_privilege($userID, $r, $updater);
			$result['add'][] = $data;
		}
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($result));
	}
}
