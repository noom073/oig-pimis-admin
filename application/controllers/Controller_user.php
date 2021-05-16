<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Controller_user extends CI_Controller
{
    private $userTypes;
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('cookie');
        $this->load->library('session');
        $this->load->library('session_services');
        $this->load->library('center_services');

        $this->load->model('subject_model');
        $this->load->model('question_model');
        $this->load->model('questionaire_model');
        $this->load->model('inspection_model');
        $this->load->model('inspection_option_model');
        $this->load->model('auditor_model');

        $data['token'] = get_cookie('pimis-token');
        $this->load->library('user_data', $data);

        $this->userTypes = $this->user_data->get_user_types();
        $hasPermition = in_array('control', $this->userTypes);
        if (!$hasPermition) redirect('welcome/forbidden');
    }

    public function index()
    {
        $data['name']       = $this->user_data->get_name();
        $data['userType']   = $this->session_services->get_user_type_name($this->session->usertype);
        $sideBar['name']     = $this->user_data->get_name();
        $sideBar['userTypes']     = $this->userTypes;

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
        $data['name']       = $this->user_data->get_name();
        $data['userType']   = $this->session_services->get_user_type_name($this->session->usertype);
        $sideBar['name']     = $this->user_data->get_name();
        $sideBar['userTypes']     = $this->userTypes;
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

    // public function subject()
    // {
    //     $data['name']       = $this->session->nameth;
    //     $data['userType']   = $this->session_services->get_user_type_name($this->session->usertype);
    //     $sideBar['name']     = $this->session->nameth;
    //     $sideBar['userType']     = array('Administrator', 'Controller', 'Auditor', 'Viewer', 'User');
    //     $script['customScript'] = $this->load->view('controller_user/subject/script', '', true);

    //     $component['header']            = $this->load->view('controller_user/component/header', '', true);
    //     $component['navbar']            = $this->load->view('controller_user/component/navbar', '', true);
    //     $component['mainSideBar']         = $this->load->view('sidebar/main-sidebar', $sideBar, true);
    //     $component['mainFooter']        = $this->load->view('controller_user/component/footer_text', '', true);
    //     $component['controllerSidebar'] = $this->load->view('controller_user/component/controller_sidebar', '', true);
    //     $component['contentWrapper']    = $this->load->view('controller_user/subject/content', $data, true);
    //     $component['jsScript']          = $this->load->view('controller_user/component/main_script', $script, true);

    //     $this->load->view('controller_user/template', $component);
    // }

    public function subject()
    {
        $inspectionoption = $this->input->get('inspectionoption', true);
        $insOpt = $this->inspection_option_model->get_inspection_option($inspectionoption);
        if ($insOpt->num_rows()) {

            $data['insOpt']         = $insOpt->row_array();
            $sideBar['name']        = $this->user_data->get_name();
            $sideBar['userTypes']     = $this->userTypes;
            $scriptData['insOpt']   = $insOpt->row_array();
            $script['customScript'] = $this->load->view('controller_user/subject/script', $scriptData, true);

            $component['header']            = $this->load->view('controller_user/component/header', '', true);
            $component['navbar']            = $this->load->view('controller_user/component/navbar', '', true);
            $component['mainSideBar']       = $this->load->view('sidebar/main-sidebar', $sideBar, true);
            $component['mainFooter']        = $this->load->view('controller_user/component/footer_text', '', true);
            $component['controllerSidebar'] = $this->load->view('controller_user/component/controller_sidebar', '', true);
            $component['contentWrapper']    = $this->load->view('controller_user/subject/content', $data, true);
            $component['jsScript']          = $this->load->view('controller_user/component/main_script', $script, true);

            $this->load->view('controller_user/template', $component);
        } else {
            redirect('controller_user/question_manage');
        }
    }

    public function ajax_add_subject()
    {
        $subjectName = $this->center_services->convert_th_num_to_arabic($this->input->post('subject_name'));
        $data['subjectName']    = $subjectName;
        $data['subjectOrder']   = $this->input->post('subject_order');
        $data['inspectionID']   = $this->input->post('inspectionID');
        $data['updater']        = $this->user_data->get_email();

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
        $data['updater']   = $this->user_data->get_email();
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
        $data['updater']        = $this->user_data->get_email();

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
        $data['questionLimitScore']  = $this->input->post('questionLimitScore');
        $data['questionOrder']  = $this->input->post('questionOrder');
        $data['subjectID']      = $this->input->post('subjectID');
        $data['updater']        = $this->user_data->get_email();

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
            $data['name']       = $this->user_data->get_name();
            $data['userType']   = $this->session_services->get_user_type_name($this->session->usertype);
            $data['subject']    = $this->subject_model->get_a_subject($subjectID)->row_array();
            // $data['questions']  = $this->question_model->get_all_question($subjectID)->result_array();
            $sideBar['name']     = $this->user_data->get_name();
            $sideBar['userTypes']     = $this->userTypes;
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
        $data['questionLimitScore']  = $this->input->post('questionLimitScore');
        $data['questionOrder']  = $this->input->post('questionOrder');
        $data['questionID']     = $this->input->post('questionID');
        $data['updater']        = $this->user_data->get_email();
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
        $checkInAuditorScore = $this->question_model->check_question_id_auditor_score($questionID)->num_rows();
        $checkInUserEvaluate = $this->question_model->check_question_id_user_evaulate($questionID)->num_rows();
        if ($checkInAuditorScore == 0 && $checkInUserEvaluate == 0) {
            $updater = $this->user_data->get_email();
            $delete = $this->question_model->delete_question($questionID, $updater);
            if ($delete) {
                $result['status']   = true;
                $result['text']     = 'ลบข้อมูลสำเร็จ';
            } else {
                $result['status']   = false;
                $result['text']     = 'ลบข้อมูลไม่สำเร็จ';
            }
        } else {
            $result['status']   = false;
            $result['text']     = 'ลบข้อมูลไม่สำเร็จ มีการใช้ข้อคำถามนี้ ในตารางคะแนนการตรวจแล้ว';
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }

    public function ajax_delete_subject()
    {
        $subjectID = $this->input->post('subjectID');
        $hasInSubjectTable = $this->subject_model->check_parent_subject($subjectID)->num_rows() > 0 ? true : false;
        $hasInQuestiontTable = $this->subject_model->get_subject_in_question($subjectID)->num_rows() > 0 ? true : false;
        if ($hasInSubjectTable) {
            $inSubjectTable['status'] = true;
            $inSubjectTable['text'] = 'มีการใช้ข้อมูลนี้ในตารางหัวข้อคำถาม';
        } else {
            $inSubjectTable['status'] = false;
            $inSubjectTable['text'] = 'ไม่พบการใช้ข้อมูลนี้ในตารางหัวข้อคำถาม';
        }

        if ($hasInQuestiontTable) {
            $InQuestiontTable['status'] = true;
            $InQuestiontTable['text'] = 'มีการใช้ข้อมูลนี้ในตารางคำถาม';
        } else {
            $InQuestiontTable['status'] = false;
            $InQuestiontTable['text'] = 'ไม่พบการใช้ข้อมูลนี้ในตารางคำถาม';
        }

        if ($inSubjectTable['status'] == false && $InQuestiontTable['status'] == false) {
            $updater = $this->user_data->get_email();
            $delete = $this->subject_model->delete_subject($subjectID, $updater);
            if ($delete) {
                $result['status']   = true;
                $result['text']     = 'ลบข้อมูลสำเร็จ';
            } else {
                $result['status']   = false;
                $result['text']     = 'ลบข้อมูลไม่สำเร็จ';
            }
        } else {
            $result['status']   = false;
            $result['text']     = 'ลบข้อมูลไม่สำเร็จ';
            $result['inSubjectTable'] = $inSubjectTable;
            $result['InQuestiontTable'] = $InQuestiontTable;
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }

    public function inspection()
    {
        $data['name']       = $this->user_data->get_name();
        $data['userType']   = $this->session_services->get_user_type_name($this->session->usertype);
        $sideBar['name']     = $this->user_data->get_name();
        $sideBar['userTypes']     = $this->userTypes;
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
        $data['updater']        = $this->user_data->get_email();
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
        $input['updater']           = $this->user_data->get_email();
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

    public function question_manage()
    {
        $data['name']       = $this->user_data->get_name();
        $data['userType']   = $this->session_services->get_user_type_name($this->session->usertype);
        $sideBar['name']     = $this->user_data->get_name();
        $sideBar['userTypes']     = $this->userTypes;
        $script['customScript'] = $this->load->view('controller_user/question_manage/script', '', true);

        $component['header']            = $this->load->view('controller_user/component/header', '', true);
        $component['navbar']            = $this->load->view('controller_user/component/navbar', '', true);
        $component['mainSideBar']         = $this->load->view('sidebar/main-sidebar', $sideBar, true);
        $component['mainFooter']        = $this->load->view('controller_user/component/footer_text', '', true);
        $component['controllerSidebar'] = $this->load->view('controller_user/component/controller_sidebar', '', true);
        $component['contentWrapper']    = $this->load->view('controller_user/question_manage/content', $data, true);
        $component['jsScript']          = $this->load->view('controller_user/component/main_script', $script, true);

        $this->load->view('controller_user/template', $component);
    }

    public function ajax_add_inspection_option()
    {
        $input['name']          = $this->input->post('inspectionOptionName', true);
        $input['year']          = $this->input->post('optionYear', true);
        $input['inspectionID']  = $this->input->post('inspectionID', true);
        $input['updater']       = $this->user_data->get_email();
        $insert = $this->inspection_option_model->add_inspection_option($input);
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

    public function ajax_delete_inspection_option()
    {
        $inspectionOptionID = $this->input->post('inspectionOptionID', true);
        $checkInspOptInSubj = $this->inspection_option_model->check_inspection_option_in_subject($inspectionOptionID)->num_rows();
        $checkInspOptInAudScore = $this->inspection_option_model->check_inspection_option_in_auditor_score($inspectionOptionID)->num_rows();
        if ($checkInspOptInSubj == 0) { // CHECK INSPECTION OPTION USED IN SUBJECT TABLE
            $inSubject['status'] = true;
            $inSubject['text'] = 'ไม่พบการใช้ชุดคำถามนี้ในตาราง ชุดคำถามประเมิน';
        } else {
            $inSubject['status'] = false;
            $inSubject['text'] = 'พบการใช้ชุดคำถามนี้ในตาราง ชุดคำถามประเมิน';
        }
        if ($checkInspOptInAudScore == 0) { // CHECK INSPECTION OPTION USED IN AUDITOR SCORE TABLE
            $inAudScore['status'] = true;
            $inAudScore['text'] = 'ไม่พบการใช้ชุดคำถามนี้ในตาราง คะแนนฟอร์มการตรวจราชการ';
        } else {
            $inAudScore['status'] = false;
            $inAudScore['text'] = 'พบการใช้ชุดคำถามนี้ในตาราง คะแนนฟอร์มการตรวจราชการ';
        }

        if ($inSubject['status'] && $inAudScore['status']) { // CHECK TRUE IN BOTH
            $updater = $this->user_data->get_email();
            $result['status'] = $this->inspection_option_model->delete_inspection_option($inspectionOptionID, $updater);
            $result['checkInSubject'] = $inSubject;
            $result['checkInAuditorScore'] = $inAudScore;
        } else {
            $result['status'] = false;
            $result['checkInSubject'] = $inSubject;
            $result['checkInAuditorScore'] = $inAudScore;
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }

    public function auditor_type()
    {
        $data['name']       = $this->user_data->get_name();
        $data['userType']   = $this->session_services->get_user_type_name($this->session->usertype);
        $sideBar['name']     = $this->user_data->get_name();
        $sideBar['userTypes']     = $this->userTypes;
        $script['customScript'] = $this->load->view('controller_user/auditor_type/script', '', true);

        $component['header']            = $this->load->view('controller_user/component/header', '', true);
        $component['navbar']            = $this->load->view('controller_user/component/navbar', '', true);
        $component['mainSideBar']         = $this->load->view('sidebar/main-sidebar', $sideBar, true);
        $component['mainFooter']        = $this->load->view('controller_user/component/footer_text', '', true);
        $component['controllerSidebar'] = $this->load->view('controller_user/component/controller_sidebar', '', true);
        $component['contentWrapper']    = $this->load->view('controller_user/auditor_type/content', $data, true);
        $component['jsScript']          = $this->load->view('controller_user/component/main_script', $script, true);

        $this->load->view('controller_user/template', $component);
    }

    public function ajax_get_auditor_type_detail()
    {
        $input['rowID'] = $this->input->post('auditorTypeID', true);
        $data = $this->auditor_model->get_auditor_type_detail($input)->row_array();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function ajax_update_auditor_type_detail()
    {
        $input['inspectionName'] = $this->input->post('inspectionName', true);
        $input['insoectionType'] = $this->input->post('insoectionType', true);
        $input['auditorTypeID'] = $this->input->post('auditorTypeID', true);
        $input['updater'] = $this->user_data->get_email();
        $update = $this->auditor_model->update_auditor_type($input);
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

    public function ajax_add_auditor_type()
    {
        $input['inspectionName'] = $this->input->post('inspectionName', true);
        $input['insoectionType'] = $this->input->post('insoectionType', true);
        $input['updater'] = $this->user_data->get_email();
        $update = $this->auditor_model->add_auditor_type($input);
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

    public function ajax_delete_auditor_type()
    {
        $input['rowID'] = $this->input->post('auditorTypeID', true);
        $checkIsUsed = $this->auditor_model->check_auditor_type_in_auditor_table($input);
        if ($checkIsUsed) {
            $result['status'] = false;
            $result['text'] = 'มีการใช้ข้อมูลอยู่';
        } else {
            $delete = $this->auditor_model->delete_auditor_type($input);
            if ($delete) {
                $result['status'] = true;
                $result['text'] = 'ลบข้อมูลสำเร็จ';
            } else {
                $result['status'] = false;
                $result['text'] = 'ลบข้อมูลไม่สำเร็จ';
            }
        }
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }
}
