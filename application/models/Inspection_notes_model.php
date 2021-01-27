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
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('TEAMPLAN_ID', $array['teamPlanID']);
        $this->oracle->set('INSPECTION_OPTION_ID', $array['inspectionOptionID']);
        $this->oracle->set('UNIT_COMMANDER', $array['commander']);
        $this->oracle->set('AUDITEE_NAME', $array['auditee']);
        $this->oracle->set('AUDITEE_POS', $array['auditeePosition']);
        $this->oracle->set('AUDITOR_EMAIL', $array['updater']);
        $this->oracle->set('INSPECTION_SCORE', $array['inspectioScore']);
        $this->oracle->set('WORKING_SCORE', $array['workingScore']);
        $this->oracle->set('CAN_IMPROVE', $array['canImprove']);
        $this->oracle->set('FAILING', $array['failing']);
        $this->oracle->set('IMPORTANT_FAILING', $array['importantFailing']);
        $this->oracle->set('COMMENTIONS', $array['commention']);
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
        $this->oracle->where('ROW_ID', $id);
        $query = $this->oracle->get('PIMIS_INSPECTION_NOTES');
        return $query;
    }

    public function update_inspection_note($array)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('INSPECTION_OPTION_ID', $array['inspectionOptionID']);
        $this->oracle->set('UNIT_COMMANDER', $array['commander']);
        $this->oracle->set('AUDITEE_NAME', $array['auditee']);
        $this->oracle->set('AUDITEE_POS', $array['auditeePosition']);
        $this->oracle->set('INSPECTION_SCORE', $array['inspectioScore']);
        $this->oracle->set('WORKING_SCORE', $array['workingScore']);
        $this->oracle->set('CAN_IMPROVE', $array['canImprove']);
        $this->oracle->set('FAILING', $array['failing']);
        $this->oracle->set('IMPORTANT_FAILING', $array['importantFailing']);
        $this->oracle->set('COMMENTIONS', $array['commention']);
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
}
