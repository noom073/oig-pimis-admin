<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Questionaire_model extends CI_Model
{
    var $oracle;

    public function __construct()
    {
        $this->oracle = $this->load->database('oracle', true);
    }

    public function get_inspection($inspectionID = 0)
    {
        if ($inspectionID) {
            $this->oracle->where('INSPE_ID', $inspectionID);
        }
        $this->oracle->select('INSPE_ID, INSPE_NAME');
        $this->oracle->order_by('INSPE_ID');
        $result = $this->oracle->get('PIMIS_INSPECTIONS');
        return $result;
    }

    public function get_subject_by_inspection($inspectionID)
    {
        $this->oracle->select('SUBJECT_ID, SUBJECT_NAME, SUBJECT_PARENT_ID, INSPECTION_ID, SUBJECT_ORDER, SUBJECT_LEVEL');
        $this->oracle->where('INSPECTION_ID', $inspectionID);
        $result = $this->oracle->get('PIMIS_SUBJECT');
        return $result;
    }

    public function get_subject_one($subjectID)
    {
        $this->oracle->select('SUBJECT_ID, SUBJECT_NAME, SUBJECT_PARENT_ID, INSPECTION_ID, SUBJECT_ORDER');
        $this->oracle->where('SUBJECT_ID', $subjectID);
        $result = $this->oracle->get('PIMIS_SUBJECT');
        return $result;
    }
   
}
