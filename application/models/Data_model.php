<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_model extends CI_Model {

    public function __construct() {
        // $this->oracle = $this->load->database('oracle', true);
        $this->load->model('question_model');
    }

	public function make_array_tree($dataArray) {
        return $this->draw_array_tree($dataArray);
    }
    
    private function draw_array_tree($dataArray, $parentID = 0) {
        $array = array_filter($dataArray, function($r) use ($parentID) {
            return $r['SUBJECT_PARENT_ID'] == $parentID;
        });

        $result = array_map(function($r) use ($dataArray) {
            $child = $this->draw_array_tree($dataArray, $r['SUBJECT_ID']);  
            if ($child) {
                $r['child'] = $child;
            } else {
                $r['questions'] = $this->question_model->get_questions($r['SUBJECT_ID'])->result_array();
            }     
            return $r;
        }, $array);
        return $result;
    }
}
