<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Inspection_notes_model extends CI_Model
{
    var $oracle;

    public function __construct()
    {
        $this->oracle = $this->load->database('oracle', true);
    }

    public function insert_inspection_note_result($array)
    {
        if ($array['canImprove'] == '') $this->oracle->set('CAN_IMPROVE', 'EMPTY_CLOB()', false);
        else $this->oracle->set('CAN_IMPROVE', $array['canImprove']);

        if ($array['failing'] == '') $this->oracle->set('FAILING', 'EMPTY_CLOB()', false);
        else $this->oracle->set('FAILING', $array['failing']);

        if ($array['importantFailing'] == '') $this->oracle->set('IMPORTANT_FAILING', 'EMPTY_CLOB()', false);
        else $this->oracle->set('IMPORTANT_FAILING', $array['importantFailing']);

        if ($array['commention'] == '') $this->oracle->set('COMMENTIONS', 'EMPTY_CLOB()', false);
        else $this->oracle->set('COMMENTIONS', $array['commention']);

        $date = date("Y-m-d H:i:s");
        $this->oracle->set('TEAMPLAN_ID', $array['teamPlanID']);
        $this->oracle->set('INSPECTION_OPTION_ID', $array['inspectionOptionID']);
        $this->oracle->set('UNIT_COMMANDER', $array['commander']);
        $this->oracle->set('AUDITEE_NAME', $array['auditee']);
        $this->oracle->set('AUDITEE_POS', $array['auditeePosition']);
        $this->oracle->set('AUDITOR_EMAIL', $array['updater']);
        $this->oracle->set('INSPECTION_SCORE', $array['inspectioScore']);
        $this->oracle->set('WORKING_SCORE', $array['workingScore']);
        $this->oracle->set('DATE_INSPECT', "TO_DATE('{$array['dateTime']}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->set('STATUS', 'y');
        $this->oracle->set('USER_UPDATE', $array['updater']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $query = $this->oracle->insert('PIMIS_INSPECTION_NOTES');
        return $query;
    }

    public function get_inspection_notes_list_by_team_plan_id($id)
    {
        $sql = "SELECT a.ROW_ID, TO_CHAR(a.TIME_UPDATE,'YYYY-MM-DD HH24:MI:SS') as TIME_UPDATE,
            b.INSPECTION_NAME
            FROM PIMIS_INSPECTION_NOTES a
            INNER JOIN PIMIS_INSPECTION_OPTION b
                ON a.INSPECTION_OPTION_ID = b.ROW_ID 
            WHERE a.TEAMPLAN_ID = ?
            AND a.STATUS = 'y'";
        $query = $this->oracle->query($sql, array($id));
        return $query;
    }

    public function get_inspection_note_by_id($id)
    {
        $this->oracle->like('ROW_ID', $id);
        $query = $this->oracle->get('PIMIS_INSPECTION_NOTES');
        return $query;
    }

    public function get_inspection_note_by_team_plan_id_n_inspection_option_id($teamPlanID, $inspectionOptionID)
    {
        $this->oracle->where('TEAMPLAN_ID', $teamPlanID);
        $this->oracle->where('INSPECTION_OPTION_ID', $inspectionOptionID);
        $this->oracle->where('STATUS', 'y');
        $query = $this->oracle->get('PIMIS_INSPECTION_NOTES');
        return $query;
    }

    public function update_inspection_note($array)
    {
        if ($array['canImprove'] == '') $this->oracle->set('CAN_IMPROVE', 'EMPTY_CLOB()', false);
        else $this->oracle->set('CAN_IMPROVE', $array['canImprove']);

        if ($array['failing'] == '') $this->oracle->set('FAILING', 'EMPTY_CLOB()', false);
        else $this->oracle->set('FAILING', $array['failing']);

        if ($array['importantFailing'] == '') $this->oracle->set('IMPORTANT_FAILING', 'EMPTY_CLOB()', false);
        else $this->oracle->set('IMPORTANT_FAILING', $array['importantFailing']);

        if ($array['commention'] == '') $this->oracle->set('COMMENTIONS', 'EMPTY_CLOB()', false);
        else $this->oracle->set('COMMENTIONS', $array['commention']);

        $date = date("Y-m-d H:i:s");
        $this->oracle->set('INSPECTION_OPTION_ID', $array['inspectionOptionID']);
        $this->oracle->set('UNIT_COMMANDER', $array['commander']);
        $this->oracle->set('AUDITEE_NAME', $array['auditee']);
        $this->oracle->set('AUDITEE_POS', $array['auditeePosition']);
        $this->oracle->set('INSPECTION_SCORE', $array['inspectioScore']);
        $this->oracle->set('WORKING_SCORE', $array['workingScore']);
        $this->oracle->set('DATE_INSPECT', "TO_DATE('{$array['dateTime']}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->set('USER_UPDATE', $array['updater']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->where('ROW_ID', $array['rowID']);
        $query = $this->oracle->update('PIMIS_INSPECTION_NOTES');
        return $query;
    }

    public function delete_inspection_note($array)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('STATUS', 'n');
        $this->oracle->set('USER_UPDATE', $array['updater']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->where('ROW_ID', $array['rowID']);
        $query = $this->oracle->update('PIMIS_INSPECTION_NOTES');
        return $query;
    }

    public function get_inspection_note_report($id)
    {
        $sql = "SELECT a.AUDITEE_NAME, a.AUDITEE_POS, a.CAN_IMPROVE, a.COMMENTIONS, a.FAILING, 
                a.IMPORTANT_FAILING, a.INSPECTION_SCORE, a.UNIT_COMMANDER, a.WORKING_SCORE,
                TO_CHAR(a.DATE_INSPECT, 'YYYY-MM-DD') as DATE_INSPECT,
                b.TITLE ||' '|| b.FIRSTNAME ||'  '|| b.LASTNAME AS INSPECTOR, 
                d.INSPE_NAME,
                g.NPRT_NAME
            FROM PIMIS_INSPECTION_NOTES a
            INNER JOIN PIMIS_USER b
                ON a.AUDITOR_EMAIL = b.EMAIL 
            INNER JOIN PIMIS_INSPECTION_OPTION c
                ON a.INSPECTION_OPTION_ID = c.ROW_ID 
            INNER JOIN PIMIS_INSPECTIONS d 
                ON c.INSPECTION_ID = d.INSPE_ID 
            INNER JOIN PIMIS_AUDITOR_TEAM_IN_PLAN e 
                ON a.TEAMPLAN_ID = e.ROW_ID 
            INNER JOIN PITS_PLAN f 
                ON e.PLAN_ID = f.ID 
            INNER JOIN PER_NPRT_TAB g 
                ON f.INS_UNIT = g.NPRT_UNIT
            WHERE a.ROW_ID = ?";
        $query = $this->oracle->query($sql, array($id));        
        return $query;
    }
    
    public function note_summary_score($noteID)
    {
        $sql = "SELECT SUM(b.SCORE) as SCORE
            FROM PIMIS_INSPECTION_NOTES a
            LEFT JOIN PIMIS_INSPECTION_SCORE_AUDITOR b
                ON a.INSPECTION_OPTION_ID = b.INSPECTION_OPTION_ID 
                AND a.TEAMPLAN_ID = b.TEAMPLAN_ID 
            WHERE a.ROW_ID = ?";
        $query = $this->oracle->query($sql, array($noteID))->row_array();
        return $query;
    }
}
