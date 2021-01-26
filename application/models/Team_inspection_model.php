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

    public function update_team_inspection($array)
    {
        $oldTeamInspection = $this->get_team_inspection($array['teamPlanID'])->result_array();
        $oldTeamInspectionID = array_map(function ($r) {
            return $r['ROW_ID'];
        }, $oldTeamInspection);
        return $oldTeamInspectionID;
    }
}
