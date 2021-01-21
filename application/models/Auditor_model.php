<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auditor_model extends CI_Model
{
    var $oracle;

    public function __construct()
    {
        $this->oracle = $this->load->database('oracle', true);
    }

    public function get_team_members($rowID)
    {
        $sql = "SELECT a.ADT_ID, a.ADT_TITLE, a.ADT_FIRSTNAME, a.ADT_LASTNAME, a.POSITION, b.AUDITOR_POSITION 
            FROM PIMIS_AUDITOR a
            INNER JOIN PIMIS_AUDITOR_TYPE b 
                ON a.ADT_TYPE = b.ADT_T_ID 
            WHERE a.ADT_TEAM = ?";
        $query = $this->oracle->query($sql, array($rowID));
        return $query;
    }

    public function get_auditor_types()
    {
        $query = $this->oracle->get('PIMIS_AUDITOR_TYPE');
        return $query;
    }

    public function insert_auditor_member($array)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('ADT_TITLE', $array['title']);
        $this->oracle->set('ADT_FIRSTNAME', $array['firstName']);
        $this->oracle->set('ADT_LASTNAME', $array['lastName']);
        $this->oracle->set('POSITION', $array['position']);
        $this->oracle->set('ADT_IDP', $array['idp']);
        $this->oracle->set('ADT_TEAM', $array['auditorTeam']);
        $this->oracle->set('ADT_TYPE', $array['auditorType']);
        $this->oracle->set('USER_UPDATE', $array['updater']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $query = $this->oracle->insert('PIMIS_AUDITOR');
        return $query;
    }
}
