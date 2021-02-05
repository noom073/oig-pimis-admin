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
            WHERE a.ADT_TEAM = ?
                AND a.ADT_STATUS = 'y'
            ORDER BY b.ADT_T_ID";
        $query = $this->oracle->query($sql, array($rowID));
        return $query;
    }

    public function get_auditor_types()
    {
        $this->oracle->join('PIMIS_INSPECTIONS b', 'a.INSPECTION_ID = b.INSPE_ID', 'left');
        $this->oracle->order_by('a.ADT_T_ID');
        $query = $this->oracle->get('PIMIS_AUDITOR_TYPE a');
        return $query;
    }

    public function insert_auditor_member($array)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('ADT_TITLE', $array['title']);
        $this->oracle->set('ADT_FIRSTNAME', $array['firstName']);
        $this->oracle->set('ADT_LASTNAME', $array['lastName']);
        $this->oracle->set('POSITION', $array['position']);
        $this->oracle->set('ADT_EMAIL', $array['email'] . '@rtarf.mi.th');
        $this->oracle->set('ADT_TEAM', $array['auditorTeam']);
        $this->oracle->set('ADT_TYPE', $array['auditorType']);
        $this->oracle->set('ADT_STATUS', 'y');
        $this->oracle->set('USER_UPDATE', $array['updater']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $query = $this->oracle->insert('PIMIS_AUDITOR');
        return $query;
    }

    public function get_a_auditor($array)
    {
        $this->oracle->where('ADT_ID', $array['rowID']);
        $query = $this->oracle->get('PIMIS_AUDITOR');
        return $query;
    }

    public function update_auditor_detail($array)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('ADT_TITLE', $array['title']);
        $this->oracle->set('ADT_FIRSTNAME', $array['firstName']);
        $this->oracle->set('ADT_LASTNAME', $array['lastName']);
        $this->oracle->set('POSITION', $array['position']);
        $this->oracle->set('ADT_EMAIL', $array['email'] . '@rtarf.mi.th');
        $this->oracle->set('ADT_TEAM', $array['auditorTeam']);
        $this->oracle->set('ADT_TYPE', $array['auditorType']);
        $this->oracle->set('USER_UPDATE', $array['updater']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->where('ADT_ID', $array['auditorID']);
        $query = $this->oracle->update('PIMIS_AUDITOR');
        return $query;
    }

    public function delete_auditor($array)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('ADT_STATUS', 'n');
        $this->oracle->set('USER_UPDATE', $array['updater']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->where('ADT_ID', $array['auditorID']);
        $query = $this->oracle->update('PIMIS_AUDITOR');
        return $query;
    }

    public function get_auditor_type_detail($array)
    {
        $this->oracle->where('ADT_T_ID', $array['rowID']);
        $query = $this->oracle->get('PIMIS_AUDITOR_TYPE');
        return $query;
    }

    public function update_auditor_type($array)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('AUDITOR_POSITION', $array['inspectionName']);
        $this->oracle->set('INSPECTION_ID', $array['insoectionType']);
        $this->oracle->set('USER_UPDATE', $array['updater']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->where('ADT_T_ID', $array['auditorTypeID']);
        $query = $this->oracle->update('PIMIS_AUDITOR_TYPE');
        return $query;
    }

    public function add_auditor_type($array)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('AUDITOR_POSITION', $array['inspectionName']);
        $this->oracle->set('INSPECTION_ID', $array['insoectionType']);
        $this->oracle->set('USER_UPDATE', $array['updater']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $query = $this->oracle->insert('PIMIS_AUDITOR_TYPE');
        return $query;
    }

    public function check_auditor_type_in_auditor_table($array)
    {
        $this->oracle->where('ADT_TYPE', $array['rowID']);
        $query = $this->oracle->get('PIMIS_AUDITOR');
        return $query->num_rows() == 0 ? false : true;
    }

    public function delete_auditor_type($array)
    {
        $this->oracle->where('ADT_T_ID', $array['rowID']);
        $query = $this->oracle->delete('PIMIS_AUDITOR_TYPE');
        return $query;
    }
}
