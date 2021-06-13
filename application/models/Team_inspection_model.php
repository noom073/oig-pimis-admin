<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Team_inspection_model extends CI_Model
{
    var $oracle;

    public function __construct()
    {
        $this->oracle = $this->load->database('oracle', true);
    }

    public function get_team_inspection($teamPlanID)
    {
        $sql = "SELECT a.ROW_ID AS TEAM_INSPECTION_ID, A.TEAMPLAN_ID,
            b.ROW_ID AS INSPECTION_OPTION_ID, b.INSPECTION_NAME , b.INSPECTION_ID 
            FROM PIMIS_TEAM_INSPECTION a
            INNER JOIN PIMIS_INSPECTION_OPTION b
                ON a.INSPECTION_OPTION_ID = b.ROW_ID
            WHERE a.TEAMPLAN_ID = ? 
            AND a.STATUS = 'y'";
        $query = $this->oracle->query($sql, array($teamPlanID));
        return $query;
    }

    public function get_inspection_option_for_inspect_by_team_plan_id($teamPlanID)
    {
        $sql = "SELECT a.ROW_ID, a.TEAMPLAN_ID, 
        b.INSPECTION_NAME as INSPECTION_OPTION_NAME, b.ROW_ID,
        c.INSPE_NAME AS INSPECTION_NAME
        FROM PIMIS_TEAM_INSPECTION a
        INNER JOIN PIMIS_INSPECTION_OPTION b 
            ON a.INSPECTION_OPTION_ID = b.ROW_ID 
        INNER JOIN PIMIS_INSPECTIONS c 
            ON b.INSPECTION_ID = c.INSPE_ID 
        WHERE a.TEAMPLAN_ID = ?";
        $query = $this->oracle->query($sql, array($teamPlanID));
        return $query;
    }

    public function update_team_inspection($array)
    {
        $oldTeamInspection = $this->get_team_inspection($array['teamPlanID'])->result_array();
        $oldTeamInspectionID = array_map(function ($r) {
            return $r['INSPECTION_OPTION_ID'];
        }, $oldTeamInspection);
        $teamInspection['toAdd'] = array_diff($array['teamInspection'], $oldTeamInspectionID);
        $teamInspection['toRemove'] = array_diff($oldTeamInspectionID, $array['teamInspection']);

        $toAdd = array();
        foreach ($teamInspection['toAdd'] as $r) {
            $insert = $this->add_team_inspection($r, $array['teamPlanID'], $array['updater']);
            $data['status'] = $insert;
            $data['teamPlanID'] = $array['teamPlanID'];
            $data['teamInspection'] = $r;
            $toAdd[] = $data;
        }

        $toRemove = array();
        foreach ($teamInspection['toRemove'] as $r) {
            // CHECK IN PIMIS_INSPECTION_SCORE_AUDITOR, PIMIS_INSPECTION_NOTES, PIMIS_INSPECTION_SUMMARY
            $inScoreAuditor = $this->check_team_plan_in_score_auditor($array['teamPlanID'], $r);
            $inNotes = $this->check_team_plan_in_inspection_notes($array['teamPlanID'], $r);
            $inSummary = $this->check_team_plan_in_inspection_summary($array['teamPlanID'], $r);
            if (!$inScoreAuditor && !$inNotes && !$inSummary) {
                $delete = $this->remove_team_inspection($r, $array['teamPlanID'], $array['updater']);
                $data['status'] = $delete;
                $data['teamPlanID'] = $array['teamPlanID'];
                $data['teamInspection'] = $r;
                $data['text'] = 'สามารถลบได้ ไม่พบข้อมูลใน Table อื่น';
            } else {
                $data['status'] = false;
                $data['teamPlanID'] = $array['teamPlanID'];
                $data['teamInspection'] = $r;
                $text = '';
                $text .= $inScoreAuditor ? 'พบการใช้ใน PIMIS_INSPECTION_SCORE_AUDITOR ' : '';
                $text .= $inNotes ? 'พบการใช้ใน PIMIS_INSPECTION_NOTES ' : '';
                $text .= $inSummary ? 'พบการใช้ใน PIMIS_INSPECTION_SUMMARY ' : '';
                $data['text'] = $text;
            }

            $toRemove[] = $data;
        }

        $result['toAdd'] = $toAdd;
        $result['toRemove'] = $toRemove;
        return $result;
    }

    public function add_team_inspection($inspectionOptionID, $teamPlanID, $updator)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('TEAMPLAN_ID', $teamPlanID);
        $this->oracle->set('INSPECTION_OPTION_ID', $inspectionOptionID);
        $this->oracle->set('STATUS', 'y');
        $this->oracle->set('USER_UPDATE', $updator);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $query = $this->oracle->insert('PIMIS_TEAM_INSPECTION');
        return $query;
    }

    public function remove_team_inspection($inspectionOptionID, $teamPlanID, $updator)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('STATUS', 'n');
        $this->oracle->set('USER_UPDATE', $updator);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->where('TEAMPLAN_ID', $teamPlanID);
        $this->oracle->where('INSPECTION_OPTION_ID', $inspectionOptionID);
        $query = $this->oracle->update('PIMIS_TEAM_INSPECTION');
        return $query;
    }

    public function get_team_inspection_and_check_inspected($teamPlanID)
    {
        $sql = "SELECT a.TEAMPLAN_ID, a.INSPECTION_OPTION_ID,
            b.INSPECTION_NAME, 
            c.INSPECTION_OPTION_ID  AS INSPECTED 
            FROM PIMIS_TEAM_INSPECTION a
            INNER JOIN PIMIS_INSPECTION_OPTION b 
                ON a.INSPECTION_OPTION_ID = b.ROW_ID 
            LEFT JOIN PIMIS_INSPECTION_SCORE_AUDITOR c 
                ON a.TEAMPLAN_ID = c.TEAMPLAN_ID 
                AND a.INSPECTION_OPTION_ID = c.INSPECTION_OPTION_ID 
            WHERE a.TEAMPLAN_ID = ?
            AND a.STATUS = 'y'
            GROUP BY a.TEAMPLAN_ID, a.INSPECTION_OPTION_ID, b.INSPECTION_NAME, c.INSPECTION_OPTION_ID
            ORDER BY a.INSPECTION_OPTION_ID";
        $query = $this->oracle->query($sql, array($teamPlanID));
        return $query;
    }

    public function check_team_plan_in_score_auditor($teamPlanID, $inspectionOptionID)
    {
        $this->oracle->where('TEAMPLAN_ID', $teamPlanID);
        $this->oracle->where('INSPECTION_OPTION_ID', $inspectionOptionID);
        $this->oracle->where('STATUS', 'y');
        $query = $this->oracle->get('PIMIS_INSPECTION_SCORE_AUDITOR');
        return $query->num_rows() == 0 ? false : true;
    }

    public function check_team_plan_in_inspection_notes($teamPlanID, $inspectionOptionID)
    {
        $this->oracle->where('TEAMPLAN_ID', $teamPlanID);
        $this->oracle->where('INSPECTION_OPTION_ID', $inspectionOptionID);
        $this->oracle->where('STATUS', 'y');
        $query = $this->oracle->get('PIMIS_INSPECTION_NOTES');
        return $query->num_rows() == 0 ? false : true;
    }

    public function check_team_plan_in_inspection_summary($teamPlanID, $inspectionOptionID)
    {
        $this->oracle->where('TEAMPLAN_ID', $teamPlanID);
        $this->oracle->where('INSPECTION_OPTION_ID', $inspectionOptionID);
        $this->oracle->where('STATUS', 'y');
        $query = $this->oracle->get('PIMIS_INSPECTION_SUMMARY');
        return $query->num_rows() == 0 ? false : true;
    }

    public function get_team_inspection_and_check_inspected_user($teamPlanID)
    {
        $sql = "SELECT a.TEAMPLAN_ID, a.INSPECTION_OPTION_ID,
            b.INSPECTION_NAME, 
            c.INSPECTION_OPTION_ID  AS INSPECTED 
            FROM PIMIS_TEAM_INSPECTION a
            INNER JOIN PIMIS_INSPECTION_OPTION b 
                ON a.INSPECTION_OPTION_ID = b.ROW_ID 
            LEFT JOIN PIMIS_USER_EVALUATE c 
                ON a.TEAMPLAN_ID = c.TEAMPLAN_ID 
                AND a.INSPECTION_OPTION_ID = c.INSPECTION_OPTION_ID 
            WHERE a.TEAMPLAN_ID = ?
            AND a.STATUS = 'y'
            GROUP BY a.TEAMPLAN_ID, a.INSPECTION_OPTION_ID, b.INSPECTION_NAME, c.INSPECTION_OPTION_ID
            ORDER BY a.INSPECTION_OPTION_ID";
        $query = $this->oracle->query($sql, array($teamPlanID));
        return $query;
    }

    public function get_leader_of_team($teamPlan)
    {
        $sql = "SELECT a.COMMANDER, b.ADT_TITLE||'  '||b.ADT_FIRSTNAME||'  '||b.ADT_LASTNAME AS LEADER , b.POSITION
            FROM PIMIS_AUDITOR_TEAM_IN_PLAN a 
            INNER JOIN PIMIS_AUDITOR b
                ON a.TEAM_ID = b.ADT_TEAM 
                AND b.ADT_TYPE = 1
            WHERE a.ROW_ID = ?";
        $query = $this->oracle->query($sql, array($teamPlan));
        return $query;
    }

    public function is_exist_teamplan($teamPlanID)
    {
        $query = $this->oracle->get_where('PIMIS_AUDITOR_TEAM_IN_PLAN', array('ROW_ID' => $teamPlanID));
        return $query->num_rows() == 0 ? false : true;
    }
}
