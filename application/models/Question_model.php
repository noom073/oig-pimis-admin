<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Question_model extends CI_Model
{
    var $oracle;

    public function __construct()
    {
        $this->oracle = $this->load->database('oracle', true);
    }

    public function get_all_question($subjectID)
    {
        $this->oracle->where('SUBJECT_ID', $subjectID);
        $query = $this->oracle->get('PIMIS_QUESTION');
        return $query;
    }
}
