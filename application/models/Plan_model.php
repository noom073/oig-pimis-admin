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
        $this->oracle->select('MAX(ID) AS ID');
        $this->oracle->where('INS_UNIT', $array['unitID']);
        $this->oracle->where('INS_DATE', "TO_DATE('{$array['startDate']}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->where('FINISH_DATE', "TO_DATE('{$array['endDate']}','YYYY/MM/DD HH24:MI:SS')", false);
        $query = $this->oracle->get('PITS_PLAN')->row_array();
        return $query['ID'];
    }

    private function add_team_to_plan($array)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('PLAN_ID', $array['planID']);
        $this->oracle->set('TEAM_ID', $array['teamID']);
        $this->oracle->set('USER_UPDATE', $array['updater']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $query = $this->oracle->insert('PIMIS_AUDITOR_TEAM_IN_PLAN');
        return $query;
    }

    public function get_event_detail($array)
    {
        $result['plan'] = $this->get_a_plan_by_id($array['planID'])->row_array();
        $result['teamPlan'] = $this->get_team_plan_detail_by_plan_id($array['planID'])->result_array();
        return $result;
    }

    public function get_team_plan_detail_by_plan_id($planID)
    {
        $sql = "SELECT ROW_ID AS TEAM_PLAN_ID, TEAM_ID
            FROM PIMIS_AUDITOR_TEAM_IN_PLAN
            WHERE PLAN_ID = ?";
        $query = $this->oracle->query($sql, array($planID));
        return $query;
    }

    public function get_a_plan_by_id($planID)
    {
        $sql = "SELECT ID AS PLAN_ID, INS_UNIT, 
            TO_CHAR(INS_DATE, 'YYYY-MM-DD') AS INS_DATE,
            TO_CHAR(FINISH_DATE, 'YYYY-MM-DD') AS FINISH_DATE
            FROM PITS_PLAN
            WHERE ID = ?";
        $query = $this->oracle->query($sql, array($planID));
        return $query;
    }

    public function get_a_plan($id)
    {
        $sql = "SELECT a.ID, a.INS_UNIT, 
            TO_CHAR(a.INS_DATE, 'YYYY-MM-DD') AS INS_DATE,
            TO_CHAR(a.FINISH_DATE, 'YYYY-MM-DD') AS FINISH_DATE
            FROM PITS_PLAN a
            WHERE ID = ?";
        $result = $this->oracle->query($sql, array($id));
        return $result;
    }

    public function get_team_plan_by_plan_id($id)
    {
        $this->oracle->where('PLAN_ID', $id);
        $query = $this->oracle->get('PIMIS_AUDITOR_TEAM_IN_PLAN');
        return $query;
    }

    public function update_team_to_plan($planID, $teams, $updator)
    {
        $eventDetail = $this->plan_model->get_team_plan_by_plan_id($planID)->result_array();
        $oldTeams = array_map(function ($r) {
            return $r['TEAM_ID'];
        }, $eventDetail);

        $teamsToAdd = array_merge(array(), array_diff($teams, $oldTeams));
        $teamsToRemove = array_merge(array(), array_diff($oldTeams, $teams));
        $result['addTeam'] = $this->update_team_to_plan_add($planID, $teamsToAdd, $updator);
        $result['removeTeam'] = $this->update_team_to_plan_remove($planID, $teamsToRemove);
        return $result;
    }

    private function update_team_to_plan_add($planID, $teamArray, $updator)
    {
        $result = array();
        foreach ($teamArray as $r) {
            $tpData['planID'] = $planID;
            $tpData['teamID'] = $r;
            $tpData['updater'] = $updator;
            $insert = $this->add_team_to_plan($tpData);
            if ($insert) {
                $data['planID'] = $planID;
                $data['teamID'] = $r;
                $data['status'] = true;
            } else {
                $data['planID'] = $planID;
                $data['teamID'] = $r;
                $data['status'] = false;
            }
            $result[] = $data;
        }
        return $result;
    }

    private function update_team_to_plan_remove($planID, $teamArray)
    {
        $result = array();
        foreach ($teamArray as $r) {
            $this->oracle->where('PLAN_ID', $planID);
            $this->oracle->where('TEAM_ID', $r);
            $delete = $this->oracle->delete('PIMIS_AUDITOR_TEAM_IN_PLAN');
            if ($delete) {
                $data['planID'] = $planID;
                $data['teamID'] = $r;
                $data['status'] = true;
            } else {
                $data['planID'] = $planID;
                $data['teamID'] = $r;
                $data['status'] = false;
            }
            $result[] = $data;
        }
        return $result;
    }

    public function update_plan($array)
    {
        $this->oracle->set('INS_UNIT', $array['unitID']);
        $this->oracle->set('INS_DATE', "TO_DATE('{$array['startDate']}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->set('FINISH_DATE', "TO_DATE('{$array['endDate']}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->where('ID', $array['planID']);
        $query = $this->oracle->update('PITS_PLAN');
        return $query;
    }

    public function delete_plan($id)
    {
        $this->oracle->where('ID', $id);
        $query = $this->oracle->delete('PITS_PLAN');
        return $query;
    }

    public function get_a_team_plan($id)
    {
        $this->oracle->where('ROW_ID', $id);
        $query = $this->oracle->get('PIMIS_AUDITOR_TEAM_IN_PLAN');
        return $query;
    }
}
