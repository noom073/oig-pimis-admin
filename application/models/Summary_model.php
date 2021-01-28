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
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->set('USER_UPDATE', $this->session->email);
        $query = $this->oracle->insert('PIMIS_INSPECTION_SUMMARY');
        return $query;
    }

    public function get_summaries($teamPlanID)
    {
        $sql = "SELECT a.TEAMPLAN_ID, a.INSPECTION_OPTION_ID ,
        sum(b.SCORE) AS SCORE, 
        c.ROW_ID, TO_CHAR(c.TIME_UPDATE, 'YYYY/MM/DD HH24:MI:SS') AS TIME_UPDATE,
        d.INSPECTION_NAME
        FROM PIMIS_TEAM_INSPECTION a
        LEFT JOIN PIMIS_INSPECTION_SCORE_AUDITOR b 
            ON a.INSPECTION_OPTION_ID = b.INSPECTION_OPTION_ID 
        	AND b.TEAMPLAN_ID = ?
        LEFT JOIN PIMIS_INSPECTION_SUMMARY c
            ON a.TEAMPLAN_ID = c.TEAMPLAN_ID 
            AND a.INSPECTION_OPTION_ID  = c.INSPECTION_OPTION_ID 
        INNER JOIN PIMIS_INSPECTION_OPTION d 
            ON a.INSPECTION_OPTION_ID = d.ROW_ID 
        WHERE a.TEAMPLAN_ID = ?
        GROUP BY a.TEAMPLAN_ID, a.INSPECTION_OPTION_ID, c.ROW_ID, c.TIME_UPDATE, d.INSPECTION_NAME
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
        $this->oracle->set('INSPECTION_OPTION_ID', $array['inspectionID']);
        if ($array['comment'] == '') {
            $this->oracle->set('COMMENTION','EMPTY_CLOB()', false);
        } else {
            $this->oracle->set('COMMENTION', $array['comment']);
        }        
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->set('USER_UPDATE', $this->session->email);
        $this->oracle->where('ROW_ID', $array['summaryID']);
        $query = $this->oracle->update('PIMIS_INSPECTION_SUMMARY');
        return $query;
    }

    public function delete_summary($summaryID)
    {
        $this->oracle->where('ROW_ID', $summaryID);
        $query = $this->oracle->delete('PIMIS_INSPECTION_SUMMARY');
        return $query;
    }
}
