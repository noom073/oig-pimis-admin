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
        $this->load->helper('string');
        $this->load->library('session');
        $this->load->library('session_services');

        $this->load->model('user_evaluation_model');
        $this->load->model('team_inspection_model');
        $this->load->model('plan_model');

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
        $teamPlanID = $this->input->get('team_plan_id', true);
        $unitID = $this->user_data->get_unit_id_user();
        $checkTeamPlan = $this->plan_model->check_team_plan($teamPlanID, $unitID);
        var_dump($checkTeamPlan);
        if ($checkTeamPlan) {
            $data['inspections'] = $this->team_inspection_model->get_team_inspection_and_check_inspected_user($teamPlanID)->result_array();
            $data['teamPlan'] = $this->plan_model->get_a_team_plan($teamPlanID)->row_array();
            $data['planDetail'] = $this->plan_model->get_a_plan_by_id($data['teamPlan']['PLAN_ID'])->row_array();
    
            $sideBar['name']     = $this->session->nameth;
            $sideBar['userTypes']     = $this->userTypes;
            $script['custom'] = $this->load->view('user/inspect/script', $data, true);
            $header['custom'] = $this->load->view('user/inspect/custom_header', '', true);
    
            $component['header']             = $this->load->view('user/component/header', $header, true);
            $component['navbar']             = $this->load->view('user/component/navbar', '', true);
            $component['mainSideBar']         = $this->load->view('sidebar/main-sidebar', $sideBar, true);
            $component['mainFooter']         = $this->load->view('user/component/footer_text', '', true);
            $component['controllerSidebar'] = $this->load->view('user/component/controller_sidebar', '', true);
            $component['contentWrapper']     = $this->load->view('user/inspect/content', $data, true);
            $component['jsScript']             = $this->load->view('user/component/main_script', $script, true);
    
            $this->load->view('user/template', $component);
        } else {
            redirect('user/calendar');
        }
        
    }

    // public function inspected()
    // {
    // 	$teamPlanID = $this->input->get('teamPlanID', true);
    // 	$inspectionOptionID = $this->input->get('inspectionOptionID', true);
    // 	$data['teamPlan'] = $this->plan_model->get_a_team_plan($teamPlanID)->row_array();
    // 	$data['inspectionOption'] = $this->inspection_option_model->get_inspection_option($inspectionOptionID)->row_array();
    // 	$data['planDetail'] = $this->plan_model->get_a_plan_by_id($data['teamPlan']['PLAN_ID'])->row_array();

    // 	$sideBar['name'] 	= $this->session->nameth;
    // 	$sideBar['userTypes'] 	= $this->userTypes;
    // 	$script['custom'] = $this->load->view('user/inspected/script', $data, true);
    // 	$header['custom'] = $this->load->view('user/inspected/custom_header', '', true);

    // 	$component['header'] 			= $this->load->view('user/component/header', $header, true);
    // 	$component['navbar'] 			= $this->load->view('user/component/navbar', '', true);
    // 	$component['mainSideBar'] 		= $this->load->view('sidebar/main-sidebar', $sideBar, true);
    // 	$component['mainFooter'] 		= $this->load->view('user/component/footer_text', '', true);
    // 	$component['controllerSidebar'] = $this->load->view('user/component/controller_sidebar', '', true);
    // 	$component['contentWrapper'] 	= $this->load->view('user/inspected/content', $data, true);
    // 	$component['jsScript'] 			= $this->load->view('user/component/main_script', $script, true);

    // 	$this->load->view('user/template', $component);
    // }

    public function ajax_set_evaluate()
    {
        $input['evalValue'] = $this->input->post('evalValue', true);
        $input['questionID'] = $this->input->post('questionID', true);
        $input['teamPlanID'] = $this->input->post('teamPlanID', true);
        $input['inspectionOptionID'] = $this->input->post('inspectionOptionID', true);
        $updater = $this->session->email;

        $config['upload_path']          = './assets/filesUpload/';
        $config['allowed_types']        = 'gif|jpg|jpeg|png|rar|zip|tar|tar.gz|doc|docx|ppt|pptx|xls|xlsx';
        $config['max_size']             = 2048;
        $config['file_name']            = random_string('alnum', 64);

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('evalFile')) {
            $result['fileUpload']['error'] = $this->upload->display_errors();
        } else {
            $data = $this->upload->data();
            $fileInput['fileName'] = $data['client_name'];
            $fileInput['nameInPath'] = $data['file_name'];
            $fileInput['teamPlanID'] = $input['teamPlanID'];
            $fileInput['questionID'] = $input['questionID'];
            $result['fileUpload']['status'] = $this->user_evaluation_model->insert_file($fileInput, $updater);
            $result['fileUpload']['data'] = $data;
        }

        $isEvaluated = $this->user_evaluation_model
            ->check_before_insert_evaluate($input['teamPlanID'], $input['questionID']);
        if ($isEvaluated) {
            // UPDATE
            $update = $this->user_evaluation_model->update_evaluate($input, $updater);
            if ($update) {
                $result['update']['status'] = true;
                $result['update']['text'] = 'แก้ไขสำเร็จ';
            } else {
                $result['update']['status'] = false;
                $result['update']['text'] = 'แก้ไขไม่สำเร็จ';
            }
        } else {
            // INSERT NEW 
            $updater = $this->session->email;
            $insert = $this->user_evaluation_model->insert_evaluate($input, $updater);
            if ($insert) {
                $result['update']['status'] = true;
                $result['update']['text'] = 'บันทึกสำเร็จ';
            } else {
                $result['update']['status'] = false;
                $result['update']['text'] = 'บันทึกไม่สำเร็จ';
            }
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }

    public function ajax_get_evaluate()
    {
        $input['questionID'] = $this->input->post('questionID', true);
        $input['teamPlanID'] = $this->input->post('teamPlanID', true);
        $data = $this->user_evaluation_model->get_evaluate($input)->row_array();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function ajax_get_files_attath()
    {
        $input['questionID'] = $this->input->post('questionID', true);
        $input['teamPlanID'] = $this->input->post('teamPlanID', true);
        $data = $this->user_evaluation_model->get_files_attath($input)->result_array();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}
