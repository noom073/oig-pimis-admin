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
        $this->load->model('plan_model');
        $this->load->model('team_inspection_model');
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
        $subjects = $this->subject_model->get_subject_by_inspection_option($inspectionOptionID)->result_array();
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
        $inspectionID = $this->input->post('inspectionOptionID');
        $subjects = $this->subject_model->get_subject_by_inspection_option($inspectionID)->result_array();
        $array = $this->data_model->make_array_tree($subjects);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($array));
    }

    public function ajax_inspection_data_calendar()
    {
        $data = $this->questionaire_model->get_inspections_date_data()->result_array();
        $result = array_map(function ($r) {
            $array['planID']    = $r['PLAN_ID'];
            $array['unitID']    = $r['INS_UNIT'];
            $array['dateStart'] = $r['INS_DATE'];
            $array['dateEnd']   = $r['FINISH_DATE'];
            $array['teamPlanID']= $r['TEAM_PLAN_ID'];
            $array['teamID']    = $r['TEAM_ID'];
            $array['teamName']  = $r['TEAM_NAME'];
            $array['teamYear']  = $r['TEAM_YEAR'];
            $array['color']     = $r['COLOR'];
            $array['unitAcm']   = $r['NPRT_ACM'];
            $array['unitName']  = $r['NPRT_NAME'];
            return $array;
        }, $data);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($result));
    }

    public function ajax_get_questions_and_score()
    {
        $inspectionOptionID = $this->input->post('inspectionOptionID');
        $teamPlanID = $this->input->post('teamPlanID');
        $subjects = $this->subject_model->get_subject_by_inspection_option($inspectionOptionID)->result_array();
        $array = $this->data_model->make_tree_with_score($subjects, $teamPlanID);
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
        if ($input['inspectionID']) {
            $data = $this->inspection_option_model->get_inspection_option_by_inspection_id($input)->result_array();
        } else {
            $data = $this->inspection_option_model->get_all_inspection_option()->result_array();
        }
        
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

    public function ajax_get_auditor_types()
    {
        $data = $this->auditor_model->get_auditor_types()->result_array();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function ajax_get_a_plan()
    {
        $input = $this->input->post('planID', true);
        $data = $this->plan_model->get_a_plan($input)->row_array();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function ajax_get_a_team_plan()
    {
        $input = $this->input->post('teamPlanID', true);
        $data = $this->plan_model->get_a_team_plan($input)->row_array();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }

    public function ajax_get_team_inspection()
    {
        $input = $this->input->post('teamPlanID', true);
        $data = $this->team_inspection_model->get_team_inspection($input)->result_array();
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($data));
    }
}
