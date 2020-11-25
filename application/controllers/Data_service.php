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

    public function ajax_get_subject()
    {
        $inspectionID = $this->input->post('inspectionID');
        $subject = $this->questionaire_model->get_subject_by_inspection($inspectionID)->result_array();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($subject));
    }

    public function ajax_get_subject_one()
    {
        $subjectID = $this->input->post('subjectID');
        $subject = $this->questionaire_model->get_subject_one($subjectID)->row_array();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($subject));
    }
}
