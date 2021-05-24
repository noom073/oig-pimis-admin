<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inspection_option_model extends CI_Model
{
    var $oracle;

    public function __construct()
    {
        $this->oracle = $this->load->database('oracle', true);
    }

    public function get_inspection_option_by_inspection_id($array) // old name get_inspection_options
    {
        $this->oracle->where('INSPECTION_ID', $array['inspectionID']);
        $this->oracle->where('STATUS', 'y');
        $query = $this->oracle->get('PIMIS_INSPECTION_OPTION');
        return $query;
    }

    public function get_all_inspection_option()
    {
        $this->oracle->where('STATUS', 'y');
        $query = $this->oracle->get('PIMIS_INSPECTION_OPTION');
        return $query;
    }

    public function add_inspection_option($array)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('INSPECTION_NAME', $array['name']);
        $this->oracle->set('INSPECTION_ID', $array['inspectionID']);
        $this->oracle->set('OPTION_YEAR', $array['year']);
        $this->oracle->set('USER_UPDATE', $array['updater']);
        $this->oracle->set('STATUS', 'y');
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $query = $this->oracle->insert('PIMIS_INSPECTION_OPTION');
        return $query;
    }

    public function get_inspection_option($id)
    {
        $this->oracle->where('ROW_ID', $id);
        $query = $this->oracle->get('PIMIS_INSPECTION_OPTION');
        return $query;
    }

    public function check_inspection_option_in_subject($inspectionOptionID)
    {
        $this->oracle->where('INSPECTION_OPTION_ID', $inspectionOptionID);
        $this->oracle->where('SUBJECT_STATUS', 'y');
        $query = $this->oracle->get('PIMIS_SUBJECT');
        return $query;
    }

    public function check_inspection_option_in_auditor_score($inspectionOptionID)
    {
        $this->oracle->where('INSPECTION_OPTION_ID', $inspectionOptionID);
        $query = $this->oracle->get('PIMIS_INSPECTION_SCORE_AUDITOR');
        return $query;
    }

    public function delete_inspection_option($inspectionOptionID, $updater)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('STATUS', 'n');
        $this->oracle->set('USER_UPDATE', $updater);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->where('ROW_ID', $inspectionOptionID);
        $query = $this->oracle->update('PIMIS_INSPECTION_OPTION');
        return $query;
    }

    public function get_a_inspection_option($inspectionOptionID)
    {
        $this->oracle->where('ROW_ID', $inspectionOptionID);
        $query = $this->oracle->get('PIMIS_INSPECTION_OPTION');
        return $query;
    }
    
}
