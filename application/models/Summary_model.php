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
        $sql = "SELECT a.ROW_ID, a.COMMENTION, TO_CHAR(a.TIME_UPDATE, 'YYYY/MM/DD HH24:MI:SS') AS TIME_UPDATE, b.INSPE_NAME, sum(c.SCORE) as SCORE
            FROM PIMIS_INSPECTION_SUMMARY a
            LEFT JOIN PIMIS_INSPECTIONS b
                ON a.INSPECTION_ID = b.INSPE_ID 
            LEFT JOIN PIMIS_INSPECTION_SCORE_AUDITOR c
                ON a.INSPECTION_ID = c.INSPECTION_ID 
                AND c.PLAN_ID = ?
            WHERE a.PLAN_ID = ?
            GROUP BY a.ROW_ID, a.COMMENTION, a.TIME_UPDATE, b.INSPE_NAME
            ORDER BY a.ROW_ID";
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
}
