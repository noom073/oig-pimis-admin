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
        $this->oracle->set('STATUS', 'y');
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
            WHERE PLAN_ID = ?
            AND STATUS = 'y'";
        $query = $this->oracle->query($sql, array($planID));
        return $query;
    }

    public function get_a_plan_by_id($planID)
    {
        $sql = "SELECT a.ID AS PLAN_ID, a.INS_UNIT, 
            TO_CHAR(a.INS_DATE, 'YYYY-MM-DD') AS INS_DATE,
            TO_CHAR(a.FINISH_DATE, 'YYYY-MM-DD') AS FINISH_DATE,
            b.NPRT_ACM, b.NPRT_NAME
            FROM PITS_PLAN a
            INNER JOIN PER_NPRT_TAB B 
                ON a.INS_UNIT = b.NPRT_UNIT
            WHERE a.ID = ?";
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
        $this->oracle->where('STATUS', 'y');
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
            // check TEAMPLAN_ID in  PIMIS_INSPECTION_SCORE_AUDITOR, PIMIS_INSPECTION_NOTES, PIMIS_INSPECTION_SUMMARY
            $teamPlanID = $this->get_team_plan_id_by_plan_n_team_id($planID, $r);
            $isTeamPlanInScoreAuditor = $this->check_team_plan_in_score_auditor($teamPlanID);
            $isTeamPlanInInspectionNotes = $this->check_team_plan_in_inspection_notes($teamPlanID);
            $isTeamPlanInInspectionSummary = $this->check_team_plan_in_inspection_summary($teamPlanID);
            if (!$isTeamPlanInScoreAuditor && !$isTeamPlanInInspectionNotes && !$isTeamPlanInInspectionSummary) {
                $this->oracle->set('STATUS', 'n');
                $this->oracle->where('PLAN_ID', $planID);
                $this->oracle->where('TEAM_ID', $r);
                $delete = $this->oracle->update('PIMIS_AUDITOR_TEAM_IN_PLAN');
                if ($delete) {
                    $data['status'] = true;
                } else {
                    $data['status'] = false;
                }
                $data['text']   = '';
                $data['planID'] = $planID;
                $data['teamID'] = $r;
                $data['teamPlanID'] = $teamPlanID;
            } else {
                $text = '';
                $text .= $isTeamPlanInScoreAuditor == true ? 'มีการใช้ TEAMPLAN_ID ใน ScoreAuditor ' : '';
                $text .= $isTeamPlanInInspectionNotes == true ? 'มีการใช้ TEAMPLAN_ID ใน InspectionNotes ' : '';
                $text .= $isTeamPlanInInspectionSummary == true ? 'มีการใช้ TEAMPLAN_ID ใน InspectionSummary ' : '';
                $data['text']   = $text;
                $data['planID'] = $planID;
                $data['teamID'] = $r;
                $data['teamPlanID'] = $teamPlanID;
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

    public function delete_plan($array)
    {
        $this->oracle->set('STATUS', 'n');
        $this->oracle->set('USER_UPDATE', $array['updater']);
        $this->oracle->set('TIME_UPDATE', 'SYSDATE', false);
        $this->oracle->where('ID', $array['id']);
        $query = $this->oracle->update('PITS_PLAN');
        return $query;
    }

    public function get_a_team_plan($id)
    {
        $sql = "SELECT a.ROW_ID, a.PLAN_ID, a.TEAM_ID, a.POLICY_SCORE, a.PREPARE_SCORE, a.STATUS,
            b.TEAM_NAME, b.TEAM_YEAR 
            FROM PIMIS_AUDITOR_TEAM_IN_PLAN a
            INNER JOIN PIMIS_AUDITOR_TEAM b 
                ON a.TEAM_ID = b.ROW_ID
            WHERE a.ROW_ID = ?";
        $query = $this->oracle->query($sql, array($id));
        return $query;
    }

    public function get_team_plan_id_by_plan_n_team_id($planID, $teamID)
    {
        $this->oracle->select('ROW_ID');
        $this->oracle->where('TEAM_ID', $teamID);
        $this->oracle->where('PLAN_ID', $planID);
        $query = $this->oracle->get('PIMIS_AUDITOR_TEAM_IN_PLAN')->row_array();
        return $query['ROW_ID'];
    }

    public function check_team_plan_in_score_auditor($planID)
    {
        $this->oracle->where('TEAMPLAN_ID', $planID);
        $this->oracle->where('STATUS', 'y');
        $query = $this->oracle->get('PIMIS_INSPECTION_SCORE_AUDITOR');
        return $query->num_rows() == 0 ? false : true;
    }

    public function check_team_plan_in_inspection_notes($planID)
    {
        $this->oracle->where('TEAMPLAN_ID', $planID);
        $this->oracle->where('STATUS', 'y');
        $query = $this->oracle->get('PIMIS_INSPECTION_NOTES');
        return $query->num_rows() == 0 ? false : true;
    }

    public function check_team_plan_in_inspection_summary($planID)
    {
        $this->oracle->where('TEAMPLAN_ID', $planID);
        $this->oracle->where('STATUS', 'y');
        $query = $this->oracle->get('PIMIS_INSPECTION_SUMMARY');
        return $query->num_rows() == 0 ? false : true;
    }

    public function check_team_plan($teamPlanID, $unitID)
    {
        $sql = "SELECT *
            FROM PIMIS_AUDITOR_TEAM_IN_PLAN a
            INNER JOIN PITS_PLAN b 
                ON a.PLAN_ID = b.ID 
                AND b.INS_UNIT = ?
            WHERE a.ROW_ID = ?";
        $query = $this->oracle->query($sql, array($unitID, $teamPlanID));
        return $query->num_rows() ? true : false;
    }

    public function check_team_plan_by_auditor($email, $teamPlanID)
    {
        $sql = "SELECT *
            FROM PIMIS_AUDITOR_TEAM_IN_PLAN a
            INNER JOIN PIMIS_AUDITOR_TEAM b 
                ON a.TEAM_ID = b.ROW_ID 
            INNER JOIN PIMIS_AUDITOR c 
                ON c.ADT_TEAM = b.ROW_ID 
                AND c.ADT_EMAIL = ?
                AND c.ADT_STATUS = 'y'
            WHERE a.ROW_ID = ?";
        $query = $this->oracle->query($sql, array($email, $teamPlanID));
        return $query->num_rows() ? true : false;
    }
}
