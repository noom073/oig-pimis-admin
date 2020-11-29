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

    public function get_inspections_date_data()
    {
        $this->oracle->select("A.ID, A.SET, TO_CHAR(A.INS_DATE, 'YYYY-MM-DD') as INS_DATE,
            TO_CHAR(A.FINISH_DATE, 'YYYY-MM-DD') as FINISH_DATE,
            B.DEPARTMENT_NAME, B.STANDFOR ");
        $this->oracle->join('PITS_UNIT B', 'A.INS_UNIT = B.ID');
        $result = $this->oracle->get('PITS_PLAN A');
        return $result;
    }

    // public function get_a_subject($subjectID)
    // {
    //     $this->oracle->select('SUBJECT_ID, SUBJECT_NAME, SUBJECT_PARENT_ID, INSPECTION_ID, SUBJECT_ORDER');
    //     $this->oracle->where('SUBJECT_ID', $subjectID);
    //     $result = $this->oracle->get('PIMIS_SUBJECT');
    //     return $result;
    // }
}
