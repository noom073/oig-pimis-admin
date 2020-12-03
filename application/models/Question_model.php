<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Question_model extends CI_Model
{
    var $oracle;

    public function __construct()
    {
        $this->oracle = $this->load->database('oracle', true);
    }

    public function get_questions($subjectID)
    {
        $this->oracle->where('SUBJECT_ID', $subjectID);
        $this->oracle->where('STATUS', 'y');
        $this->oracle->order_by('Q_ORDER');
        $query = $this->oracle->get('PIMIS_QUESTION');
        return $query;
    }
    
    public function get_a_question($questionID)
    {
        $this->oracle->where('Q_ID', $questionID);
        $this->oracle->where('STATUS', 'y');
        $query = $this->oracle->get('PIMIS_QUESTION');
        return $query;
    }

    public function add_question($array)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('Q_NAME', $array['questionName']);
        $this->oracle->set('SUBJECT_ID', $array['subjectID']);
        $this->oracle->set('Q_ORDER', $array['questionOrder']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->set('USER_UPDATE', $this->session->email);
        $this->oracle->set('STATUS', 'y');
        $query = $this->oracle->insert('PIMIS_QUESTION');
        return $query;
    }

    public function update_question($array)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('Q_NAME', $array['questionName']);
        $this->oracle->set('Q_ORDER', $array['questionOrder']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->set('USER_UPDATE', $this->session->email);
        $this->oracle->where('Q_ID', $array['questionID']);
        $query = $this->oracle->update('PIMIS_QUESTION');
        return $query;
    }

    public function delete_question($questionID)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('STATUS', 'n');
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->set('USER_UPDATE', $this->session->email);
        $this->oracle->where('Q_ID', $questionID);
        $query = $this->oracle->update('PIMIS_QUESTION');
        return $query;
    }
}
