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
        $this->load->model('data_model');
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
        $inspectionID = $this->input->post('inspectionID');
        $subjects = $this->subject_model->get_subject_by_inspection($inspectionID)->result_array();
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
        $inspectionID = $this->input->post('inspectionID');;
        $subjects = $this->subject_model->get_subject_by_inspection($inspectionID)->result_array();
        $array = $this->data_model->make_array_tree($subjects);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($array));
    }

    public function ajax_inspection_data_calendar()
    {
        $data = $this->questionaire_model->get_inspections_date_data()->result_array();
        $result = array_map(function($r) {
            $array['depName'] = $r['DEPARTMENT_NAME'];
            $array['title'] = $r['STANDFOR'];
            $array['start'] = $r['INS_DATE'];
            $array['end']   = $r['FINISH_DATE'];
            return $array;
        }, $data);

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }
}
