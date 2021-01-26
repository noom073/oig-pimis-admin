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
        $this->oracle->where('TEAMPLAN_ID', $teamPlanID);
        $query = $this->oracle->get('PIMIS_TEAM_INSPECTION');
        return $query;
    }

    public function get_inspection_option_for_inspect_by_team_plan_id($teamPlanID)
    {
        $sql ="SELECT a.ROW_ID, a.TEAMPLAN_ID, 
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
            $delete = $this->remove_team_inspection($r, $array['teamPlanID']);
            $data['status'] = $delete;
            $data['teamPlanID'] = $array['teamPlanID'];
            $data['teamInspection'] = $r;
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
        $this->oracle->set('USER_UPDATE', $updator);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $query = $this->oracle->insert('PIMIS_TEAM_INSPECTION');
        return $query;
    }

    public function remove_team_inspection($inspectionOptionID, $teamPlanID)
    {
        $this->oracle->where('TEAMPLAN_ID', $teamPlanID);
        $this->oracle->where('INSPECTION_OPTION_ID', $inspectionOptionID);
        $query = $this->oracle->delete('PIMIS_TEAM_INSPECTION');
        return $query;
    }
}
