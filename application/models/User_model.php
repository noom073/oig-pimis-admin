<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
    var $oracle;

    public function __construct()
    {
        $this->oracle = $this->load->database('oracle', true);
    }

    public function get_all_user()
    {
        $sql = "SELECT a.USER_ID, a.TITLE, a.FIRSTNAME, a.LASTNAME, b.TYPE_NAME, a.EMAIL, a.USER_ACTIVE, TO_CHAR(a.TIME_UPDATE, 'YYYY/MM/DD HH:MI:SS') as TIME_UPDATE
            FROM PIMIS_USER a 
            JOIN PIMIS_USER_TYPE b 
                ON a.USER_TYPE = b.TYPE_ID
            WHERE a.SYSTEM LIKE 'pimis'
            ORDER BY a.TIME_UPDATE DESC";
        $result = $this->oracle->query($sql);
        return $result;
    }

    public function list_user_type()
    {
        $this->oracle->select('TYPE_ID, TYPE_NAME');
        $query = $this->oracle->get('PIMIS_USER_TYPE');
        return $query;
    }

    public function chk_user_duplicate($email)
    {
        $this->oracle->where('EMAIL', $email);
        $query = $this->oracle->get('PIMIS_USER');
        return $query;
    }

    public function insert_user($array)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('TITLE', $array['title']);
        $this->oracle->set('FIRSTNAME', $array['firstname']);
        $this->oracle->set('LASTNAME', $array['lastname']);
        $this->oracle->set('EMAIL', $array['email']);
        $this->oracle->set('USER_TYPE', $array['userType']);
        $this->oracle->set('USER_ACTIVE', $array['activation']);
        $this->oracle->set('USER_UPDATE', $array['updater']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $insert = $this->oracle->insert('PIMIS_USER');
        return $insert;
    }

    public function delete_user($array)
    {
        $this->oracle->where('USER_ID', $array['userID']);
        $query = $this->oracle->delete('PIMIS_USER');
        return $query;
    }

    public function get_user_detail($array)
    {
        $this->oracle->where('USER_ID', $array['userID']);
        $query = $this->oracle->get('PIMIS_USER');
        return $query;
    }

    public function update_user($array)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('TITLE', $array['title']);
        $this->oracle->set('FIRSTNAME', $array['firstname']);
        $this->oracle->set('LASTNAME', $array['lastname']);
        $this->oracle->set('EMAIL', $array['email']);
        $this->oracle->set('USER_TYPE', $array['userType']);
        $this->oracle->set('USER_ACTIVE', $array['activation']);
        $this->oracle->set('USER_UPDATE', $array['updater']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->where('USER_ID', $array['userID']);
        $insert = $this->oracle->update('PIMIS_USER');
        return $insert;
    }

    public function chk_user_for_update($array)
    {
        $this->oracle->where('EMAIL', $array['email']);
        $this->oracle->where('USER_ID', $array['userID']);
        $query = $this->oracle->get('PIMIS_USER');
        return $query;
    }
}
