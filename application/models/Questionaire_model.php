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
        $this->oracle->order_by('ORDER_NUM');
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
        $sql = "SELECT a.ID AS PLAN_ID, a.INS_UNIT, 
            TO_CHAR(a.INS_DATE, 'YYYY-MM-DD HH24:MI:SS') AS INS_DATE,
            TO_CHAR(a.FINISH_DATE, 'YYYY-MM-DD HH24:MI:SS') AS FINISH_DATE,
            b.ROW_ID AS TEAM_PLAN_ID, b.TEAM_ID,
            c.TEAM_NAME, c.TEAM_YEAR, c.COLOR,
            d.NPRT_NAME, d.NPRT_ACM
            FROM PITS_PLAN a 
            RIGHT JOIN PIMIS_AUDITOR_TEAM_IN_PLAN b 
                ON a.ID = b.PLAN_ID 
            INNER JOIN PIMIS_AUDITOR_TEAM c 
                ON b.TEAM_ID = c.ROW_ID 
            INNER JOIN PER_NPRT_TAB d
                ON d.NPRT_UNIT = a.INS_UNIT";
        $result = $this->oracle->query($sql);
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
        $data['teamPlanID'] = $array['teamPlanID'];
        $data['inspectionOptionID'] = $array['inspectionOptionID'];

        $response = array();
        foreach ($array['scores'] as $key => $val) {
            $mixData = explode('-', $key);
            $data['questionID'] = $mixData[1];
            $data['score'] = $val;

            $date = date("Y-m-d H:i:s");
            $this->oracle->set("TEAMPLAN_ID", $data['teamPlanID']);
            $this->oracle->set("INSPECTION_OPTION_ID", $data['inspectionOptionID']);
            $this->oracle->set("QUESTION_ID", $data['questionID']);
            $this->oracle->set("SCORE", $data['score']);
            $this->oracle->set("TIME_UPDATE", "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
            $this->oracle->set("USER_UPDATE", $this->session->email);
            $insert = $this->oracle->insert('PIMIS_INSPECTION_SCORE_AUDITOR');
            if ($insert) {
                $result['status'] = true;
                $result['result'] = $data;
            } else {
                $result['status'] = false;
                $result['result'] = $data;
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

    public function update_team_plan_score($array) // old nameupdate_plan_score
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('POLICY_SCORE', $array['policyScore']);
        $this->oracle->set('PREPARE_SCORE', $array['prepareScore']);
        $this->oracle->where('ROW_ID', $array['teamPlanID']);
        $update = $this->oracle->update('PIMIS_AUDITOR_TEAM_IN_PLAN');
        return $update;
    }

    public function get_sum_form_score_by_planid($teamPlanID)
    {
        $sql = "SELECT SUM(SCORE) as SCORE
            FROM PIMIS_INSPECTION_SCORE_AUDITOR
            WHERE TEAMPLAN_ID = ?";
        $query = $this->oracle->query($sql, array($teamPlanID));
        return $query;
    }

    public function update_inspection_score($score, $teamPlanID, $questionID)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('SCORE', $score);
        $this->oracle->set("TIME_UPDATE", "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->set("USER_UPDATE", $this->session->email);
        $this->oracle->where('TEAMPLAN_ID', $teamPlanID);
        $this->oracle->where('QUESTION_ID', $questionID);
        $query = $this->oracle->update('PIMIS_INSPECTION_SCORE_AUDITOR');
        return $query;
    }
}
