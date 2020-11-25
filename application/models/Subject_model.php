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
        $this->oracle->set('INSPECTION_ID', $array['inspectionID']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->set('USER_UPDATE', $this->session->email);
        $this->oracle->set('SUBJECT_ORDER', $array['subjectOrder']);
        $this->oracle->set('SUBJECT_LEVEL', '1');
        $query = $this->oracle->insert('PIMIS_SUBJECT');
        return $query;
    }

    public function update_subject($array)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('SUBJECT_NAME', $array['subjectName']);
        $this->oracle->set('SUBJECT_PARENT_ID', $array['subjectParent']);
        $this->oracle->set('INSPECTION_ID', $array['inspectionID']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->set('USER_UPDATE', $this->session->email);
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
        $this->oracle->set('INSPECTION_ID', $array['inspectionID']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->set('USER_UPDATE', $this->session->email);
        $this->oracle->set('SUBJECT_ORDER', $array['subjectOrder']);
        $this->oracle->set('SUBJECT_LEVEL', $array['subjectLevel']);
        $query = $this->oracle->insert('PIMIS_SUBJECT');
        return $query;
    }
}
