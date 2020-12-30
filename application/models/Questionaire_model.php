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

    public function get_inspections()
    {
        $query = $this->oracle->get('PIMIS_INSPECTIONS');
        return $query;
    }

    public function get_a_inspection($inspectionID)
    {
        $this->oracle->where('INSPE_ID', $inspectionID);
        $query = $this->oracle->get('PIMIS_INSPECTIONS');
        return $query;
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

    public function get_plan($planID)
    {
        $sql = 'SELECT a.ID, a."SET", a.INS_UNIT, a.INS_DATE, a.FINISH_DATE, a.POLICY_SCORE, a.PREPARE_SCORE,
        b.DEPARTMENT_NAME, b.STANDFOR 
        FROM PITS_PLAN a
        INNER JOIN PITS_UNIT b
            ON a.INS_UNIT = b.ID
        WHERE a.ID = ?';
        $query = $this->oracle->query($sql, array($planID));
        return $query;
    }

    public function insert_inspection_score($array)
    {
        $this->oracle->trans_begin();
        $data['planID'] = $array['planID'];
        $data['inspectionID'] = $array['inspectionID'];

        $response = array();
        foreach ($array['scores'] as $key => $val) {
            $mixData = explode('-', $key);
            $data['questionID'] = $mixData[1];
            $data['score'] = $val;

            $date = date("Y-m-d H:i:s");
            $this->oracle->set("PLAN_ID", $data['planID']);
            $this->oracle->set("INSPECTION_ID", $data['inspectionID']);
            $this->oracle->set("QUESTION_ID", $data['questionID']);
            $this->oracle->set("SCORE", $data['score']);
            $this->oracle->set("TIME_UPDATE", "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
            $this->oracle->set("USER_UPDATE", $this->session->email);
            $insert = $this->oracle->insert('PIMIS_INSPECTION_SCORE_AUDITOR');
            if ($insert) {
                $result['status'] = true;
                $result['text'] = $array;
            } else {
                $result['status'] = false;
                $result['text'] = $array;
            }
            array_push($response, $result);
        }

        if ($this->oracle->trans_status() === FALSE) {
            $this->oracle->trans_rollback();
            $objects['status'] = false;
            $objects['data'] = '';
        } else {
            $this->oracle->trans_commit();
            $objects['status'] = true;
            $objects['data'] = $response;
        }
        return $objects;
    }

    public function get_inspections_with_inpected_check($planID)
    {
        $sql = "SELECT a.INSPE_ID, a.INSPE_NAME, b.INSPECTION_ID 
        FROM PIMIS_INSPECTIONS a
        LEFT JOIN PIMIS_INSPECTION_SCORE_AUDITOR b  
            ON a.INSPE_ID = b.INSPECTION_ID 
            AND b.PLAN_ID = ?
        GROUP BY a.INSPE_ID, a.INSPE_NAME, b.INSPECTION_ID
        ORDER BY a.INSPE_ID";
        $query = $this->oracle->query($sql, array($planID));
        return $query;
    }

    public function update_plan_score($array)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('POLICY_SCORE', $array['policyScore']);
        $this->oracle->set('PREPARE_SCORE', $array['prepareScore']);
        $this->oracle->where('ID', $array['planID']);
        $update = $this->oracle->update('PITS_PLAN');
        return $update;
    }

    public function get_sum_form_score_by_planid($planID)
    {
        $sql = "SELECT SUM(SCORE) as SCORE
            FROM PIMIS_INSPECTION_SCORE_AUDITOR
            WHERE PLAN_ID = ?";
        $query = $this->oracle->query($sql, array($planID));
        return $query;
    }

    public function update_inspection_score($score, $planID, $questionID)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('SCORE', $score);
        $this->oracle->set("TIME_UPDATE", "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->set("USER_UPDATE", $this->session->email);
        $this->oracle->where('PLAN_ID', $planID);
        $this->oracle->where('QUESTION_ID', $questionID);
        $query = $this->oracle->update('PIMIS_INSPECTION_SCORE_AUDITOR');
        return $query;
    }
}
