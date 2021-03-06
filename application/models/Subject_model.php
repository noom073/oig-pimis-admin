<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Subject_model extends CI_Model
{
    var $oracle;

    public function __construct()
    {
        $this->oracle = $this->load->database('oracle', true);
    }

    public function add_subject($array)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('SUBJECT_NAME', $array['subjectName']);
        $this->oracle->set('SUBJECT_PARENT_ID', '0');
        $this->oracle->set('INSPECTION_OPTION_ID', $array['inspectionID']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->set('USER_UPDATE', $array['updater']);
        $this->oracle->set('SUBJECT_ORDER', $array['subjectOrder']);
        $this->oracle->set('SUBJECT_LEVEL', '1');
        $this->oracle->set('SUBJECT_STATUS', 'y');
        $query = $this->oracle->insert('PIMIS_SUBJECT');
        return $query;
    }

    public function update_subject($array)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('SUBJECT_NAME', $array['subjectName']);
        $this->oracle->set('SUBJECT_PARENT_ID', $array['subjectParent']);
        $this->oracle->set('INSPECTION_OPTION_ID', $array['inspectionID']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->set('USER_UPDATE', $array['updater']);
        $this->oracle->set('SUBJECT_ORDER', $array['subjectOrder']);
        $this->oracle->where('SUBJECT_ID', $array['subjectID']);
        $query = $this->oracle->update('PIMIS_SUBJECT');
        return $query;
    }

    public function add_sub_subject($array)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('SUBJECT_NAME', $array['subjectName']);
        $this->oracle->set('SUBJECT_PARENT_ID', $array['subjectParent']);
        $this->oracle->set('INSPECTION_OPTION_ID', $array['inspectionID']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->set('USER_UPDATE', $array['updater']);
        $this->oracle->set('SUBJECT_ORDER', $array['subjectOrder']);
        $this->oracle->set('SUBJECT_LEVEL', $array['subjectLevel']);
        $this->oracle->set('SUBJECT_STATUS', 'y');
        $query = $this->oracle->insert('PIMIS_SUBJECT');
        return $query;
    }

    public function has_subject_exist($subjectID)
    {
        $this->oracle->where('SUBJECT_ID', $subjectID);
        $query = $this->oracle->get('PIMIS_SUBJECT');
        $hasSubjectExist = $query->num_rows() > 0 ? true : false;
        return $hasSubjectExist;
    }

    public function has_child_subject($subjectID)
    {
        $this->oracle->where('SUBJECT_PARENT_ID', $subjectID);
        $query = $this->oracle->get('PIMIS_SUBJECT');
        $hasSubjectExist = $query->num_rows() > 0 ? true : false;
        return $hasSubjectExist;
    }

    public function get_a_subject($subjectID)
    {
        $this->oracle->select('SUBJECT_ID, SUBJECT_NAME, SUBJECT_PARENT_ID, INSPECTION_OPTION_ID, SUBJECT_ORDER');
        $this->oracle->where('SUBJECT_ID', $subjectID);
        $result = $this->oracle->get('PIMIS_SUBJECT');
        return $result;
    }

    public function get_subject_by_inspection_option($inspectionOptionID) // old name get_subject_by_inspection
    {
        $this->oracle->select('SUBJECT_ID, SUBJECT_NAME, SUBJECT_PARENT_ID, INSPECTION_OPTION_ID, 
            SUBJECT_ORDER, SUBJECT_LEVEL');
        $this->oracle->where('INSPECTION_OPTION_ID', $inspectionOptionID);
        $this->oracle->where('SUBJECT_STATUS', 'y');
        $this->oracle->order_by('SUBJECT_ORDER');
        $result = $this->oracle->get('PIMIS_SUBJECT');
        return $result;
    }

    public function delete_subject($subjectID, $updater)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('SUBJECT_STATUS', 'n');
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->set('USER_UPDATE', $updater);
        $this->oracle->where('SUBJECT_ID', $subjectID);
        $query = $this->oracle->update('PIMIS_SUBJECT');
        return $query;
    }

    public function get_subject_in_question($subjectID)
    {
        $this->oracle->where('SUBJECT_ID', $subjectID);
        $this->oracle->where('STATUS', 'y');
        $query = $this->oracle->get('PIMIS_QUESTION');
        return $query;
    }

    public function check_parent_subject($subjectID)
    {
        $this->oracle->where('SUBJECT_PARENT_ID', $subjectID);
        $this->oracle->where('SUBJECT_STATUS', 'y');
        $query = $this->oracle->get('PIMIS_SUBJECT');
        return $query;
    }
}
