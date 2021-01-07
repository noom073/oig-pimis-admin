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
        $this->load->model('question_model');
        $this->load->model('questionaire_model');
        $this->load->model('inspection_model');
    }

    public function index()
    {
        $data['name']       = $this->session->nameth;
        $data['userType']   = $this->session_services->get_user_type_name($this->session->usertype);
        $sideBar['name']     = $this->session->nameth;
        $sideBar['userType']     = array('Administrator', 'Controller', 'Auditor', 'Viewer', 'User');

        $script['customScript'] = $this->load->view('controller_user/index_content/script', '', true);

        $component['header']            = $this->load->view('controller_user/component/header', '', true);
        $component['navbar']            = $this->load->view('controller_user/component/navbar', '', true);
        $component['mainSideBar']         = $this->load->view('sidebar/main-sidebar', $sideBar, true);
        $component['mainFooter']        = $this->load->view('controller_user/component/footer_text', '', true);
        $component['controllerSidebar'] = $this->load->view('controller_user/component/controller_sidebar', '', true);
        $component['contentWrapper']    = $this->load->view('controller_user/index_content/content', $data, true);
        $component['jsScript']          = $this->load->view('controller_user/component/main_script', $script, true);

        $this->load->view('controller_user/template', $component);
    }

    public function inspection_list()
    {
        $data['name']       = $this->session->nameth;
        $data['userType']   = $this->session_services->get_user_type_name($this->session->usertype);
        $sideBar['name']     = $this->session->nameth;
        $sideBar['userType']     = array('Administrator', 'Controller', 'Auditor', 'Viewer', 'User');
        $script['customScript'] = $this->load->view('controller_user/inspection_list/script', '', true);

        $component['header']            = $this->load->view('controller_user/component/header', '', true);
        $component['navbar']            = $this->load->view('controller_user/component/navbar', '', true);
        $component['mainSideBar']         = $this->load->view('sidebar/main-sidebar', $sideBar, true);
        $component['mainFooter']        = $this->load->view('controller_user/component/footer_text', '', true);
        $component['controllerSidebar'] = $this->load->view('controller_user/component/controller_sidebar', '', true);
        $component['contentWrapper']    = $this->load->view('controller_user/inspection_list/content', $data, true);
        $component['jsScript']          = $this->load->view('controller_user/component/main_script', $script, true);

        $this->load->view('controller_user/template', $component);
    }

    public function subject()
    {
        $data['name']       = $this->session->nameth;
        $data['userType']   = $this->session_services->get_user_type_name($this->session->usertype);
        $sideBar['name']     = $this->session->nameth;
        $sideBar['userType']     = array('Administrator', 'Controller', 'Auditor', 'Viewer', 'User');
        $script['customScript'] = $this->load->view('controller_user/subject/script', '', true);

        $component['header']            = $this->load->view('controller_user/component/header', '', true);
        $component['navbar']            = $this->load->view('controller_user/component/navbar', '', true);
        $component['mainSideBar']         = $this->load->view('sidebar/main-sidebar', $sideBar, true);
        $component['mainFooter']        = $this->load->view('controller_user/component/footer_text', '', true);
        $component['controllerSidebar'] = $this->load->view('controller_user/component/controller_sidebar', '', true);
        $component['contentWrapper']    = $this->load->view('controller_user/subject/content', $data, true);
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

    public function ajax_add_question()
    {
        $data['questionName']   = $this->center_services->convert_th_num_to_arabic($this->input->post('questionName'));
        $data['questionOrder']  = $this->input->post('questionOrder');
        $data['subjectID']      = $this->input->post('subjectID');

        $insert = $this->question_model->add_question($data);
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

    public function questions()
    {
        $subjectID = $this->input->get('subject_id');
        // $hasChildSubject = $this->subject_model->has_child_subject($subjectID);
        $hasSubjectExist = $this->subject_model->has_subject_exist($subjectID);
        // if (!$hasSubjectExist || $hasChildSubject) {
        if (!$hasSubjectExist) {
            redirect('controller_user/subject');
        } else {
            $data['name']       = $this->session->nameth;
            $data['userType']   = $this->session_services->get_user_type_name($this->session->usertype);
            $data['subject']    = $this->subject_model->get_a_subject($subjectID)->row_array();
            // $data['questions']  = $this->question_model->get_all_question($subjectID)->result_array();
            $sideBar['name']     = $this->session->nameth;
            $sideBar['userType']     = array('Administrator', 'Controller', 'Auditor', 'Viewer', 'User');
            $script['customScript'] = $this->load->view('controller_user/questions/script', $data, true);

            $component['header']            = $this->load->view('controller_user/component/header', '', true);
            $component['navbar']            = $this->load->view('controller_user/component/navbar', '', true);
            $component['mainSideBar']       = $this->load->view('sidebar/main-sidebar', $sideBar, true);
            $component['mainFooter']        = $this->load->view('controller_user/component/footer_text', '', true);
            $component['controllerSidebar'] = $this->load->view('controller_user/component/controller_sidebar', '', true);
            $component['contentWrapper']    = $this->load->view('controller_user/questions/content', $data, true);
            $component['jsScript']          = $this->load->view('controller_user/component/main_script', $script, true);

            $this->load->view('controller_user/template', $component);
        }
    }

    public function ajax_edit_question()
    {
        $data['questionName']   = $this->center_services->convert_th_num_to_arabic($this->input->post('questionName'));
        $data['questionOrder']  = $this->input->post('questionOrder');
        $data['questionID']     = $this->input->post('questionID');
        $update = $this->question_model->update_question($data);
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

    public function ajax_delete_question()
    {
        $questionID = $this->input->post('questionID');
        $delete = $this->question_model->delete_question($questionID);
        if ($delete) {
            $result['status']   = true;
            $result['text']     = 'ลบข้อมูลสำเร็จ';
        } else {
            $result['status']   = false;
            $result['text']     = 'ลบข้อมูลไม่สำเร็จ';
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }

    public function ajax_delete_subject()
    {
        $subjectID = $this->input->post('subjectID');
        $delete = $this->subject_model->delete_subject($subjectID);
        if ($delete) {
            $result['status']   = true;
            $result['text']     = 'ลบข้อมูลสำเร็จ';
        } else {
            $result['status']   = false;
            $result['text']     = 'ลบข้อมูลไม่สำเร็จ';
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }

    public function inspection()
    {
        $data['name']       = $this->session->nameth;
        $data['userType']   = $this->session_services->get_user_type_name($this->session->usertype);
        $sideBar['name']     = $this->session->nameth;
        $sideBar['userType']     = array('Administrator', 'Controller', 'Auditor', 'Viewer', 'User');
        $script['customScript'] = $this->load->view('controller_user/index_content/script', '', true);

        $component['header']            = $this->load->view('controller_user/component/header', '', true);
        $component['navbar']            = $this->load->view('controller_user/component/navbar', '', true);
        $component['mainSideBar']       = $this->load->view('sidebar/main-sidebar', $sideBar, true);
        $component['mainFooter']        = $this->load->view('controller_user/component/footer_text', '', true);
        $component['controllerSidebar'] = $this->load->view('controller_user/component/controller_sidebar', '', true);
        $component['contentWrapper']    = $this->load->view('controller_user/index_content/content', $data, true);
        $component['jsScript']          = $this->load->view('controller_user/component/main_script', $script, true);

        $this->load->view('controller_user/template', $component);
    }

    public function add_inspection()
    {
        $data['inspectionName'] = $this->input->post('inspectionName', true);
        $data['inspectionOrder'] = $this->input->post('inspectionOrder', true);
        $data['updater']        = $this->session->email;
        $insert = $this->inspection_model->add_inspection($data);
        if ($insert) {
            $result['status']   = true;
            $result['text']     = 'บันทึกข้อมูลสำเร็จ';
        } else {
            $result['status']   = false;
            $result['text']     = 'บันทึกข้อมูลไม่สำเร็จ';
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }

    public function ajax_update_inspection()
    {
        $input['inspectionName']    = $this->input->post('inspectionName', true);
        $input['inspectionOrder']   = $this->input->post('inspectionOrder', true);
        $input['inspectionID']      = $this->input->post('inspectionID', true);
        $input['updater']           = $this->session->email;
        $update = $this->inspection_model->update_inspection($input);
        if ($update) {
            $result['status']   = true;
            $result['text']     = 'บันทึกข้อมูลสำเร็จ';
        } else {
            $result['status']   = false;
            $result['text']     = 'บันทึกข้อมูลไม่สำเร็จ';
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }

    public function ajax_delete_inspection()
    {
        $inspectionID = $this->input->post('inspectionID', true);
        $checkInpspection = $this->inspection_model->check_inspection_in_subject($inspectionID);
        if ($checkInpspection->num_rows() == 0) {
            $delete = $this->inspection_model->delete_inspection($inspectionID);
            if ($delete) {
                $result['status']   = true;
                $result['text']     = 'ลบข้อมูลสำเร็จ';
            } else {
                $result['status']   = false;
                $result['text']     = 'ลบไม่ข้อมูลสำเร็จ';
            }
            
        } else {
            $result['status']   = false;
            $result['text']     = 'ลบไม่สำเร็จ สายการตรวจนี้มีการใช้งานอยู่';
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }
}
