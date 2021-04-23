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
        $this->oracle->set('LIMIT_SCORE', $array['questionLimitScore']);
        $this->oracle->set('Q_ORDER', $array['questionOrder']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->set('USER_UPDATE', $array['updater']);
        $this->oracle->set('STATUS', 'y');
        $query = $this->oracle->insert('PIMIS_QUESTION');
        return $query;
    }

    public function update_question($array)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('Q_NAME', $array['questionName']);
        $this->oracle->set('LIMIT_SCORE', $array['questionLimitScore']);
        $this->oracle->set('Q_ORDER', $array['questionOrder']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->set('USER_UPDATE', $array['updater']);
        $this->oracle->where('Q_ID', $array['questionID']);
        $query = $this->oracle->update('PIMIS_QUESTION');
        return $query;
    }

    public function delete_question($questionID, $updater)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('STATUS', 'n');
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->set('USER_UPDATE', $updater);
        $this->oracle->where('Q_ID', $questionID);
        $query = $this->oracle->update('PIMIS_QUESTION');
        return $query;
    }

    public function get_question_and_score($teamPlanID, $subjectID)
    {
        $sql = "SELECT a.Q_ID, a.Q_NAME, a.LIMIT_SCORE,
        TO_CHAR(b.SCORE) AS SCORE
        FROM PIMIS_QUESTION a
        INNER JOIN PIMIS_INSPECTION_SCORE_AUDITOR b 
            ON a.Q_ID = b.QUESTION_ID 
            AND  b.TEAMPLAN_ID = ?
        WHERE a.SUBJECT_ID = ?
        AND a.STATUS = 'y'";

        $query = $this->oracle->query($sql, array($teamPlanID, $subjectID));
        return $query;
    }

    public function check_question_id_auditor_score($questionID)
    {
        $this->oracle->where('QUESTION_ID', $questionID);
        $query = $this->oracle->get('PIMIS_INSPECTION_SCORE_AUDITOR');
        return $query;
    }

    public function get_question_and_score_user($teamPlanID, $subjectID)
    {
        $sql = "SELECT a.Q_ID, a.Q_NAME, a.LIMIT_SCORE,
        TO_CHAR(b.VALUE) AS SCORE
        FROM PIMIS_QUESTION a
        LEFT JOIN PIMIS_USER_EVALUATE b 
            ON a.Q_ID = b.QUESTION_ID 
            AND  b.TEAMPLAN_ID = ?
        WHERE a.SUBJECT_ID = ?
        AND a.STATUS = 'y'";

        $query = $this->oracle->query($sql, array($teamPlanID, $subjectID));
        return $query;
    }
}
