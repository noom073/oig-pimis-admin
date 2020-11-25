<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
	}

	public function index()
	{
		$data['header'] 			= $this->load->view('main/component/header', '', true);
		$data['navbar'] 			= $this->load->view('main/component/navbar', '', true);
		$data['mainSideBar'] 		= $this->load->view('main/component/sidebar', '', true);
		$data['mainFooter'] 		= $this->load->view('main/component/footer_text', '', true);
		$data['controllerSidebar'] 	= $this->load->view('main/component/controller_sidebar', '', true);
		$data['contentWrapper'] 	= $this->load->view('main/index_content/content', '', true);
		$data['jsScript'] 			= $this->load->view('main/index_content/script', '', true);

		$this->load->view('main/template', $data);
	}

	public function login()
	{
		$data['header'] 			= $this->load->view('main/component/header', '', true);
		$data['navbar'] 			= $this->load->view('main/component/navbar', '', true);
		$data['mainSideBar'] 		= $this->load->view('main/component/sidebar', '', true);
		$data['mainFooter'] 		= $this->load->view('main/component/footer_text', '', true);
		$data['controllerSidebar'] 	= $this->load->view('main/component/controller_sidebar', '', true);
		$data['contentWrapper'] 	= $this->load->view('main/index_content/content', '', true);
		$data['jsScript'] 			= $this->load->view('main/component/script', '', true);

		$this->load->view('main/template', $data);
	}
}
