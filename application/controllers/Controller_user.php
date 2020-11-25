<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Controller_user extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('session_services');
        $this->load->library('center_services');

        $this->load->model('subject_model');
    }

    public function index()
    {
        echo date("Y-m-d H:i:s e");
        $data['name']       = $this->session->nameth;
        $data['userType']   = $this->session_services->get_user_type_name($this->session->usertype);

        $script['customScript'] = $this->load->view('controller_user/index_content/script', '', true);

        $component['header']            = $this->load->view('controller_user/component/header', '', true);
        $component['navbar']            = $this->load->view('controller_user/component/navbar', '', true);
        $component['mainSideBar']       = $this->load->view('controller_user/component/sidebar', $data, true);
        $component['mainFooter']        = $this->load->view('controller_user/component/footer_text', '', true);
        $component['controllerSidebar'] = $this->load->view('controller_user/component/controller_sidebar', '', true);
        $component['contentWrapper']    = $this->load->view('controller_user/index_content/content', $data, true);
        $component['jsScript']          = $this->load->view('controller_user/component/main_script', $script, true);

        $this->load->view('controller_user/template', $component);
    }

    public function subject()
    {
        $data['name']       = $this->session->nameth;
        $data['userType']   = $this->session_services->get_user_type_name($this->session->usertype);

        $script['customScript'] = $this->load->view('controller_user/inspection/script', '', true);

        $component['header']            = $this->load->view('controller_user/component/header', '', true);
        $component['navbar']            = $this->load->view('controller_user/component/navbar', '', true);
        $component['mainSideBar']       = $this->load->view('controller_user/component/sidebar', $data, true);
        $component['mainFooter']        = $this->load->view('controller_user/component/footer_text', '', true);
        $component['controllerSidebar'] = $this->load->view('controller_user/component/controller_sidebar', '', true);
        $component['contentWrapper']    = $this->load->view('controller_user/inspection/content', $data, true);
        $component['jsScript']          = $this->load->view('controller_user/component/main_script', $script, true);

        $this->load->view('controller_user/template', $component);
    }

    public function ajax_add_subject()
    {
        $subjectName = $this->center_services->convert_th_num_to_arabic($this->input->post('subject_name'));
        $data['subjectName']    = $subjectName;
        $data['subjectOrder']   = $this->input->post('subject_order');
        $data['inspectionID']   = $this->input->post('inspectionID');

        $insert = $this->subject_model->add_subject($data);
        if ($insert) {
            $result['status']   = true;
            $result['text']     = 'บันทึกสำเร็จ';
        } else {
            $result['status']   = false;
            $result['text']     = 'บันทึกไม่สำเร็จ';
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }

    public function ajax_update_subject()
    {
        $subjectName = $this->center_services->convert_th_num_to_arabic($this->input->post('subject_name'));
        $data['subjectID']      = $this->input->post('subjectId');
        $data['subjectName']    = $subjectName;
        $data['subjectParent']  = $this->input->post('subject_parent');
        $data['subjectOrder']   = $this->input->post('subject_order');
        $data['inspectionID']   = $this->input->post('inspectionID');
        $update = $this->subject_model->update_subject($data);
        if ($update) {
            $result['status']   = true;
            $result['text']     = 'บันทึกสำเร็จ';
        } else {
            $result['status']   = false;
            $result['text']     = 'บันทึกไม่สำเร็จ';
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }

    public function inspection()
    {
        $data['name']       = $this->session->nameth;
        $data['userType']   = $this->session_services->get_user_type_name($this->session->usertype);

        $script['customScript'] = $this->load->view('controller_user/index_content/script', '', true);

        $component['header']            = $this->load->view('controller_user/component/header', '', true);
        $component['navbar']            = $this->load->view('controller_user/component/navbar', '', true);
        $component['mainSideBar']       = $this->load->view('controller_user/component/sidebar', $data, true);
        $component['mainFooter']        = $this->load->view('controller_user/component/footer_text', '', true);
        $component['controllerSidebar'] = $this->load->view('controller_user/component/controller_sidebar', '', true);
        $component['contentWrapper']    = $this->load->view('controller_user/index_content/content', $data, true);
        $component['jsScript']          = $this->load->view('controller_user/component/main_script', $script, true);

        $this->load->view('controller_user/template', $component);
    }

    public function ajax_add_sub_subject()
    {
        $subjectName = $this->center_services->convert_th_num_to_arabic($this->input->post('subjectName'));
        $data['subjectName']    = $subjectName;
        $data['subjectParent']  = $this->input->post('subjectParent');
        $data['inspectionID']   = $this->input->post('inspectionID');
        $data['subjectOrder']   = $this->input->post('subjectOrder');
        $data['subjectLevel']   = $this->input->post('subjectLevel');

        $insert = $this->subject_model->add_sub_subject($data);

        if ($insert) {
            $result['status']   = true;
            $result['text']     = 'บันทึกสำเร็จ';
        } else {
            $result['status']   = false;
            $result['text']     = 'บันทึกไม่สำเร็จ';
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }
}
