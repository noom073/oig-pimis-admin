<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_evaluation_model extends CI_Model
{
    var $oracle;

    public function __construct()
    {
        $this->oracle = $this->load->database('oracle', true);
    }

    public function check_before_insert_evaluate($teamPlanID, $questionID)
    {
        $this->oracle->where('TEAMPLAN_ID', $teamPlanID);
        $this->oracle->where('QUESTION_ID', $questionID);
        $query = $this->oracle->get('PIMIS_USER_EVALUATE');
        return $query->num_rows() > 0 ? true : false;
    }

    public function insert_evaluate($array, $updater)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('TEAMPLAN_ID', $array['teamPlanID']);
        $this->oracle->set('INSPECTION_OPTION_ID', $array['inspectionOptionID']);
        $this->oracle->set('QUESTION_ID', $array['questionID']);
        $this->oracle->set('VALUE', $array['evalValue']);
        $this->oracle->set('STATUS', 'y');
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->set('USER_UPDATE', $updater);
        $query = $this->oracle->insert('PIMIS_USER_EVALUATE');
        return $query;
    }

    public function update_evaluate($array, $updater)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('VALUE', $array['evalValue']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->set('USER_UPDATE', $updater);
        $this->oracle->where('TEAMPLAN_ID', $array['teamPlanID']);
        $this->oracle->where('QUESTION_ID', $array['questionID']);
        $query = $this->oracle->update('PIMIS_USER_EVALUATE');
        return $query;
    }

    public function insert_file($array, $updater)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('FILE_NAME', $array['fileName']);
        $this->oracle->set('FILES_PATH', $array['nameInPath']);
        $this->oracle->set('TEAMPLAN_ID', $array['teamPlanID']);
        $this->oracle->set('STATUS', 'y');
        $this->oracle->set('QUESTION_ID', $array['questionID']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->set('USER_UPDATE', $updater);
        $query = $this->oracle->insert('PIMIS_USER_FILES');
        return $query;
    }

    public function get_evaluate($array)
    {
        $this->oracle->where('TEAMPLAN_ID', $array['teamPlanID']);
        $this->oracle->where('QUESTION_ID', $array['questionID']);
        $query = $this->oracle->get('PIMIS_USER_EVALUATE');
        return $query;
    }

    public function get_files_attath($array)
    {
        $this->oracle->where('TEAMPLAN_ID', $array['teamPlanID']);
        $this->oracle->where('QUESTION_ID', $array['questionID']);
        $query = $this->oracle->get('PIMIS_USER_FILES');
        return $query;
    }
}
