<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Summary_model extends CI_Model
{
    var $oracle;

    public function __construct()
    {
        $this->oracle = $this->load->database('oracle', true);
    }

    public function add_summary($array)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('TEAMPLAN_ID', $array['teamPlanID']);
        $this->oracle->set('INSPECTION_OPTION_ID', $array['inspectionOptionID']);
        $this->oracle->set('COMMENTION', $array['comment']);
        $this->oracle->set('STATUS', 'y');
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->set('USER_UPDATE', $array['updator']);
        $query = $this->oracle->insert('PIMIS_INSPECTION_SUMMARY');
        return $query;
    }

    public function get_summaries($teamPlanID)
    {
        $sql = "SELECT a.TEAMPLAN_ID, a.INSPECTION_OPTION_ID ,
        sum(b.SCORE) AS SCORE, 
        c.ROW_ID, TO_CHAR(c.TIME_UPDATE, 'YYYY/MM/DD HH24:MI:SS') AS TIME_UPDATE,
        d.INSPECTION_NAME, d.INSPECTION_ID
        FROM PIMIS_TEAM_INSPECTION a
        LEFT JOIN PIMIS_INSPECTION_SCORE_AUDITOR b 
            ON a.INSPECTION_OPTION_ID = b.INSPECTION_OPTION_ID 
        	AND b.TEAMPLAN_ID = ?
        LEFT JOIN PIMIS_INSPECTION_SUMMARY c
            ON a.TEAMPLAN_ID = c.TEAMPLAN_ID 
            AND a.INSPECTION_OPTION_ID  = c.INSPECTION_OPTION_ID 
            And c.STATUS = 'y'
        INNER JOIN PIMIS_INSPECTION_OPTION d 
            ON a.INSPECTION_OPTION_ID = d.ROW_ID 
        WHERE a.TEAMPLAN_ID = ?
        AND a.STATUS = 'y'
        GROUP BY a.TEAMPLAN_ID, a.INSPECTION_OPTION_ID, c.ROW_ID, c.TIME_UPDATE, d.INSPECTION_NAME, 
            d.INSPECTION_ID
        ORDER BY a.INSPECTION_OPTION_ID";
        $query = $this->oracle->query($sql, array($teamPlanID, $teamPlanID));
        return $query;
    }

    public function get_summary_detail($summaryID)
    {
        $this->oracle->where('ROW_ID', $summaryID);
        $query = $this->oracle->get('PIMIS_INSPECTION_SUMMARY');
        return $query;
    }

    public function update_summary($array)
    {
        $date = date("Y-m-d H:i:s");
        if ($array['comment'] == '') {
            $this->oracle->set('COMMENTION','EMPTY_CLOB()', false);
        } else {
            $this->oracle->set('COMMENTION', $array['comment']);
        }        
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->set('USER_UPDATE', $array['updator']);
        $this->oracle->where('ROW_ID', $array['summaryID']);
        $query = $this->oracle->update('PIMIS_INSPECTION_SUMMARY');
        return $query;
    }

    public function delete_summary($summaryID, $updator)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('STATUS', 'n');
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->set('USER_UPDATE', $updator);
        $this->oracle->where('ROW_ID', $summaryID);
        $query = $this->oracle->update('PIMIS_INSPECTION_SUMMARY');
        return $query;
    }

    public function get_all_summary_comment_by_teamplan($teamPlan)
    {
        $sql = "SELECT a.ROW_ID, a.TEAMPLAN_ID,
            b.INSPECTION_NAME, b.INSPECTION_ID, c.INSPE_NAME,
            d.COMMENTION
            FROM PIMIS_TEAM_INSPECTION a 
            INNER JOIN PIMIS_INSPECTION_OPTION b 
                ON a.INSPECTION_OPTION_ID = b.ROW_ID 
            INNER JOIN PIMIS_INSPECTIONS c 
                ON b.INSPECTION_ID = c.INSPE_ID 
            LEFT JOIN PIMIS_INSPECTION_SUMMARY d 
                ON a.TEAMPLAN_ID = d.TEAMPLAN_ID 
                AND a.INSPECTION_OPTION_ID = d.INSPECTION_OPTION_ID 
            WHERE a.TEAMPLAN_ID = ?
            ORDER BY c.INSPE_ID";
        $query = $this->oracle->query($sql, array($teamPlan));
        return $query;
    }
    
    public function get_header_summary($teamPlan)
    {
        $sql = "SELECT a.ROW_ID, a.PLAN_ID, a.TEAM_ID,
        b.INS_UNIT, TO_CHAR(b.INS_DATE, 'YYYY-MM-DD') AS INS_DATE, 
        TO_CHAR(b.FINISH_DATE, 'YYYY-MM-DD') AS FINISH_DATE, 
        a.POLICY_SCORE, a.PREPARE_SCORE ,
        c.nprt_name
        FROM PIMIS_AUDITOR_TEAM_IN_PLAN a 
        INNER JOIN PITS_PLAN b 
            ON a.PLAN_ID = b.ID 
        INNER JOIN PER_NPRT_TAB c 
            ON b.INS_UNIT = c.nprt_unit
        WHERE a.ROW_ID = ?";
        $query = $this->oracle->query($sql, array($teamPlan));
        return $query;
    }

    public function summary_score($teamPlanID)
    {
       $this->oracle->select_sum('SCORE');
       $this->oracle->where('TEAMPLAN_ID', $teamPlanID);
       $query = $this->oracle->get('PIMIS_INSPECTION_SCORE_AUDITOR');
       return $query->row_array();
    }
}
