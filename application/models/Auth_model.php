<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    var $oracle;

    public function __construct()
    {
        $this->oracle = $this->load->database('oracle', true);
    }

    public function get_user_type($userID)
    {
        $sql = "SELECT B.TYPE_ID, B.TYPE_NAME 
            FROM PIMIS_USER_PRIVILEGES A
            INNER JOIN PIMIS_USER_TYPE B 
                ON A.TYPE_ID = B.TYPE_ID 
            WHERE A.USER_ID = ?
            AND A.STATUS = 'y' 
            ORDER BY B.ORDER_NUMBER";
        $query = $this->oracle->query($sql, array($userID));
        return $query;
    }

    public function get_user($rtarfMail)
    {
        $sql = "SELECT *
            FROM PIMIS_USER
            WHERE EMAIL = ?
            AND SYSTEM = 'pimis'";
        $query = $this->oracle->query($sql, array($rtarfMail));
        return $query;
    }

    public function check_token($token)
    {
        $this->oracle->where('TOKEN', $token);
        $query = $this->oracle->get('PIMIS_TOKEN_DATA');
        return $query;
    }

    public function insert_token($token, $userID, $ADToken)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('TOKEN', $token);
        $this->oracle->set('ACTIVE', 'y');
        $this->oracle->set('USER_ID', $userID);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->set('AD_TOKEN', $ADToken);
        $query = $this->oracle->insert('PIMIS_TOKEN_DATA');
        return $query;
    }

    public function update_token($token)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('ACTIVE', 'n');
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->where('TOKEN', $token);
        $query = $this->oracle->update('PIMIS_TOKEN_DATA');
        return $query;
    }

    public function get_user_id($token)
    {
        $this->oracle->select('USER_ID');
        $this->oracle->where('TOKEN', $token);
        $this->oracle->where('ACTIVE', 'y');
        $query = $this->oracle->get('PIMIS_TOKEN_DATA');
        return $query;
    }

    public function get_email($userID)
    {
        $this->oracle->select('EMAIL');
        $this->oracle->where('USER_ID', $userID);
        $this->oracle->where('STATUS', 'y');
        $query = $this->oracle->get('PIMIS_USER');
        return $query;
    }

    public function get_own_team($email)
    {
        $sql = "SELECT ADT_TEAM
            FROM PIMIS_AUDITOR
            WHERE ADT_EMAIL = 'pichet.v@rtarf.mi.th'
            AND ADT_STATUS = 'y'";
        $query = $this->oracle->query($sql, array($email))->result_array();
        $data = array_map(function ($r) {
            return $r['ADT_TEAM'];
        }, $query);
        $teams = array_merge(array(), $data);
        return $teams;
    }

    public function get_unit_user($userID)
    {
        $sql = "SELECT UNIT_ID 
            FROM PIMIS_USER 
            WHERE USER_ID = ?";
        $query = $this->oracle->query($sql, array($userID))->row_array();
        return $query['UNIT_ID'];
    }

    public function get_user_by_id($userID)
    {
        $sql = "SELECT *
            FROM PIMIS_USER 
            WHERE USER_ID = ?";
        $query = $this->oracle->query($sql, array($userID))->row_array();
        return "{$query['TITLE']} {$query['FIRSTNAME']}  {$query['LASTNAME']}";
    }

    public function get_user_inspection_type_in_teamplan($teamID, $userEmail)
    {
        $sql = "SELECT b.INSPECTION_ID 
            FROM PIMIS_AUDITOR a
            INNER JOIN PIMIS_AUDITOR_TYPE b
                ON a.ADT_TYPE = b.ADT_T_ID 
            WHERE a.ADT_EMAIL = ?
            AND a.ADT_TEAM = ?";
        $query = $this->oracle->query($sql, array($userEmail, $teamID));
        return $query;
    }
}
