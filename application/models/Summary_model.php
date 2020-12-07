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
        $this->oracle->set('PLAN_ID', $array['planID']);
        $this->oracle->set('INSPECTION_ID', $array['inspectionID']);
        $this->oracle->set('COMMENTION', $array['comment']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->set('USER_UPDATE', $this->session->email);
        $query = $this->oracle->insert('PIMIS_INSPECTION_SUMMARY');
        return $query;
    }

    public function get_summaries($planID)
    {
        $sql = "SELECT a.INSPE_ID,a.INSPE_NAME, SUM(b.SCORE) AS SCORE, c.ROW_ID, c.COMMENTION, TO_CHAR(c.TIME_UPDATE, 'YYYY/MM/DD HH24:MI:SS') AS TIME_UPDATE
        FROM PIMIS_INSPECTIONS a
        LEFT JOIN PIMIS_INSPECTION_SCORE_AUDITOR b 
            ON a.INSPE_ID = b.INSPECTION_ID 
            AND b.PLAN_ID = ?
        LEFT JOIN PIMIS_INSPECTION_SUMMARY c
            ON a.INSPE_ID = c.INSPECTION_ID 
            AND c.PLAN_ID = ?
        GROUP BY a.INSPE_ID, a.INSPE_NAME, c.ROW_ID, c.COMMENTION, c.TIME_UPDATE
        ORDER BY a.INSPE_ID ";
        $query = $this->oracle->query($sql, array($planID, $planID));
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
        $this->oracle->set('INSPECTION_ID', $array['inspectionID']);
        $this->oracle->set('COMMENTION', $array['comment']);
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
