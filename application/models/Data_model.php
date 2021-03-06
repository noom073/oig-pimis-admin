<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Data_model extends CI_Model
{

    public function __construct()
    {
        // $this->oracle = $this->load->database('oracle', true);
        $this->load->model('question_model');
    }

    public function make_array_tree($dataArray)
    {
        return $this->draw_array_tree($dataArray);
    }

    private function draw_array_tree($dataArray, $parentID = 0)
    {
        $array = array_filter($dataArray, function ($r) use ($parentID) {
            return $r['SUBJECT_PARENT_ID'] == $parentID;
        });

        $data = array_map(function ($r) use ($dataArray) {
            $child = $this->draw_array_tree($dataArray, $r['SUBJECT_ID']);
            if ($child) $r['child'] = array_merge(array(), $child);
            $r['questions'] = $this->question_model->get_questions($r['SUBJECT_ID'])->result_array();
            return $r;
        }, $array);
        $result = array_merge(array(), $data);

        return $result;
    }

    public function make_tree_with_score($subjects, $teamPlanID)
    {
        return $this->get_questions_and_score($subjects, $teamPlanID);
    }

    private function get_questions_and_score($dataArray, $teamPlanID, $parentID = 0)
    {
        $array = array_filter($dataArray, function ($r) use ($parentID) {
            return $r['SUBJECT_PARENT_ID'] == $parentID;
        });

        $data = array_map(function ($r) use ($dataArray, $teamPlanID) {
            $child = $this->get_questions_and_score($dataArray, $teamPlanID, $r['SUBJECT_ID']);
            if ($child) $r['child'] = array_merge(array(), $child);
            $r['questions'] = $this->question_model->get_question_and_score($teamPlanID, $r['SUBJECT_ID'])->result_array();
            return $r;
        }, $array);
        $result = array_merge(array(), $data);

        return $result;
    }

    public function make_tree_with_score_user($subjects, $teamPlanID)
    {
        return $this->get_questions_and_score_user($subjects, $teamPlanID);
    }

    private function get_questions_and_score_user($dataArray, $teamPlanID, $parentID = 0)
    {
        $array = array_filter($dataArray, function ($r) use ($parentID) {
            return $r['SUBJECT_PARENT_ID'] == $parentID;
        });

        $data = array_map(function ($r) use ($dataArray, $teamPlanID) {
            $child = $this->get_questions_and_score_user($dataArray, $teamPlanID, $r['SUBJECT_ID']);
            if ($child) $r['child'] = array_merge(array(), $child);
            $r['questions'] = $this->question_model->get_question_and_score_user($teamPlanID, $r['SUBJECT_ID'])->result_array();
            return $r;
        }, $array);
        $result = array_merge(array(), $data);

        return $result;
    }    
}
