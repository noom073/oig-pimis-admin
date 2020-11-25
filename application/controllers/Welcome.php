<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Welcome extends CI_Controller
{

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');

		$this->load->library('session');
		$this->load->library('authentication');
	}
	public function index()
	{
		$data['header'] 			= $this->load->view('welcome/component/header', '', true);
		$data['navbar'] 			= $this->load->view('welcome/component/navbar', '', true);
		$data['mainSideBar'] 		= $this->load->view('welcome/component/sidebar', '', true);
		$data['mainFooter'] 		= $this->load->view('welcome/component/footer_text', '', true);
		$data['controllerSidebar'] 	= $this->load->view('welcome/component/controller_sidebar', '', true);
		$data['contentWrapper'] 	= $this->load->view('welcome/index_content/content', '', true);
		$data['jsScript'] 			= $this->load->view('welcome/index_content/script', '', true);

		$this->load->view('welcome/template', $data);
	}

	public function login()
	{
		$this->load->view('welcome/login_content/content');
	}

	public function ajax_adlogin_process()
	{
		$this->load->model('auth_model');

		// echo json_encode($this->input->post());
		$rtarfMail = $this->input->post('email');
		$password = $this->input->post('password');

		$checkADReturn = $this->authentication->check_ad($rtarfMail, $password);
		$loginProcess = $this->authentication->process_login($checkADReturn);	

		echo json_encode($loginProcess);
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('login/index');
	}
}
