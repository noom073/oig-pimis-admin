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

    public function insert_token($token, $userID)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('TOKEN', $token);
        $this->oracle->set('ACTIVE', 'y');
        $this->oracle->set('USER_ID', $userID);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
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
        $this->oracle->where('ACTIVE', 'y');
        $query = $this->oracle->get('PIMIS_USER');
        return $query;
    }
}
