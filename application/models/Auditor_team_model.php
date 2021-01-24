<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auditor_team_model extends CI_Model
{
    var $oracle;

    public function __construct()
    {
        $this->oracle = $this->load->database('oracle', true);
    }

    public function get_auditor_teams()
    {
        $query = $this->oracle->get('PIMIS_AUDITOR_TEAM');
        return $query;
    }

    public function insert_auditor_team($array)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('TEAM_NAME', $array['teamName']);
        $this->oracle->set('TEAM_YEAR', $array['teamYear']);
        $this->oracle->set('COLOR', $array['color']);
        $this->oracle->set('USER_UPDATE', $array['updater']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $query = $this->oracle->insert('PIMIS_AUDITOR_TEAM');
        return $query;
    }

    public function get_a_team_name($rowID)
    {
        $this->oracle->where('ROW_ID', $rowID);
        $query = $this->oracle->get('PIMIS_AUDITOR_TEAM');
        return $query;
    }

    public function update_team_name($array)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('TEAM_NAME', $array['teamName']);
        $this->oracle->set('TEAM_YEAR', $array['teamYear']);
        $this->oracle->set('COLOR', $array['color']);
        $this->oracle->set('USER_UPDATE', $array['updater']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->where('ROW_ID', $array['rowID']);
        $query = $this->oracle->update('PIMIS_AUDITOR_TEAM');
        return $query;
    }

    public function validate_team_row_id($rowID)
    {
        $this->oracle->where('ROW_ID', $rowID);
        $query = $this->oracle->get('PIMIS_AUDITOR_TEAM');
        return $query;
    }

    public function is_auditor_team_in_plan($array)
    {
        $this->oracle->where('TEAM_ID', $array['rowID']);
        $query = $this->oracle->get('PIMIS_AUDITOR_TEAM_IN_PLAN')->num_rows();
        return $query == 0 ? false : true;
    }

    public function delete_auditor_team($array)
    {
        $this->oracle->where('ROW_ID', $array['rowID']);
        $query = $this->oracle->delete('PIMIS_AUDITOR_TEAM');
        return $query;
    }
}
