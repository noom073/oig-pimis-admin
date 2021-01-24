<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Plan_model extends CI_Model
{
    var $oracle;

    public function __construct()
    {
        $this->oracle = $this->load->database('oracle', true);
    }

    public function add_new_plan($array)
    {
        $addPlan = $this->add_plan($array);
        if ($addPlan) {
            $planID = $this->get_id_plan($array);
            $addTeamResult = array();
            foreach ($array['auditorTeam'] as $teamID) {
                $tpData['planID'] = $planID;
                $tpData['teamID'] = $teamID;
                $tpData['updater'] = $array['updater'];
                $addTeamToPlan = $this->add_team_to_plan($tpData);
                $addTeamResult = $tpData;
                $addTeamResult['result'] = $addTeamToPlan;
                $result['status'] = true;
                $result['addTeam'][] = $addTeamResult;
                $result['text'] = 'เพิ่มหน่วยที่ตรวจแล้ว';
            }
        } else {
            $result['status'] = false;
            $result['data'] = '';
            $result['text'] = 'เพิ่มหน่วยที่ตรวจไม่สำเร็จ';
        }
        return $result;
    }

    public function add_plan($array)
    {
        $this->oracle->set('INS_UNIT', $array['unitID']);
        $this->oracle->set('INS_DATE', "TO_DATE('{$array['startDate']}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->set('FINISH_DATE', "TO_DATE('{$array['endDate']}','YYYY/MM/DD HH24:MI:SS')", false);
        $query = $this->oracle->insert('PITS_PLAN');
        return $query;
    }

    public function get_id_plan($array)
    {
        $this->oracle->select('ID');
        $this->oracle->where('INS_UNIT', $array['unitID']);
        $this->oracle->where('INS_DATE', "TO_DATE('{$array['startDate']}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->where('FINISH_DATE', "TO_DATE('{$array['endDate']}','YYYY/MM/DD HH24:MI:SS')", false);
        $query = $this->oracle->get('PITS_PLAN')->row_array();
        return $query['ID'];
    }

    public function add_team_to_plan($array)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('PLAN_ID', $array['planID']);
        $this->oracle->set('TEAM_ID', $array['teamID']);
        $this->oracle->set('USER_UPDATE', $array['updater']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $query = $this->oracle->insert('PIMIS_AUDITOR_TEAM_IN_PLAN');
        return $query;
    }
}
