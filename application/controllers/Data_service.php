<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Data_service extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session_services');
        $this->load->model('user_model');
        $this->load->model('questionaire_model');
        $this->load->model('subject_model');
        $this->load->model('question_model');
        $this->load->model('inspection_model');
        $this->load->model('inspection_option_model');
        $this->load->model('data_model');
        $this->load->model('nprt_model');
        $this->load->model('auditor_model');
    }

    public function ajax_get_type_user()
    {
        $userTypes = $this->user_model->list_user_type()->result_array();
        $result = array_map(function ($r) {
            $data = $r;
            $data['TYPE_NAME'] = $this->session_services->get_user_type_name($r['TYPE_NAME']);
            return $data;
        }, $userTypes);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }

    public function ajax_get_inspection()
    {
        $inspection = $this->questionaire_model->get_inspection()->result_array();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($inspection));
    }

    public function ajax_get_subjects()
    {
        $inspectionOptionID = $this->input->post('inspectionOptionID');
        $subjects = $this->subject_model->get_subject_by_inspection($inspectionOptionID)->result_array();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($subjects));
    }

    public function ajax_get_a_subject()
    {
        $subjectID = $this->input->post('subjectID');
        $subject = $this->subject_model->get_a_subject($subjectID)->row_array();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($subject));
    }

    public function ajax_get_questions()
    {
        $subjectID = $this->input->post('subjectID');
        $questions = $this->question_model->get_questions($subjectID)->result_array();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($questions));
    }

    public function ajax_get_question()
    {
        $questionID = $this->input->post('questionID');
        $question  = $this->question_model->get_a_question($questionID)->row_array();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($question));
    }

    public function ajax_get_questions_by_inspection()
    {
        $inspectionID = $this->input->post('inspectionID');
        $subjects = $this->subject_model->get_subject_by_inspection($inspectionID)->result_array();
        $array = $this->data_model->make_array_tree($subjects);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($array));
    }

    public function ajax_inspection_data_calendar()
    {
        $data = $this->questionaire_model->get_inspections_date_data()->result_array();
        $result = array_map(function ($r) {
            $array['planID']    = $r['ID'];
            $array['depName']   = $r['DEPARTMENT_NAME'];
            $array['unitAcm']   = $r['STANDFOR'];
            $array['dateStart'] = $r['INS_DATE'];
            $array['dateEnd']   = $r['FINISH_DATE'];
            $array['squad']     = $r['SET'];
            return $array;
        }, $data);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }

    public function ajax_get_questions_and_score()
    {
        $inspectionID = $this->input->post('inspectionID');
        $planID = $this->input->post('plan');
        $subjects = $this->subject_model->get_subject_by_inspection($inspectionID)->result_array();
        $array = $this->data_model->make_tree_with_score($subjects, $planID);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($array));
    }

    public function ajax_get_all_user_types()
    {
        $allUserTypes = $this->user_model->list_user_type()->result_array();
        $data = array_map(function ($r) {
            $r['TYPE_NAME_FULL'] = $this->session_services->get_user_type_name($r['TYPE_NAME']);
            return $r;
        }, $allUserTypes);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function ajax_get_a_inspection()
    {
        $inspectionID = $this->input->post('inspectionID', true);
        $data = $this->inspection_model->get_a_inspection($inspectionID)->row_array();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function ajax_get_inspection_options()
    {
        $input['inspectionID'] = $this->input->post('inspectionID', true);
        $data = $this->inspection_option_model->get_inspection_options($input)->result_array();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function ajax_get_nprt_units()
    {
        $units = $this->nprt_model->get_nprt_units()->result_array();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($units));
    }

}
