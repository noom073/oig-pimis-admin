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
        $sql = "SELECT a.USER_ID, a.TITLE, a.FIRSTNAME, a.LASTNAME, a.EMAIL, a.USER_ACTIVE,
            TO_CHAR(a.TIME_UPDATE, 'YYYY/MM/DD HH:MI:SS') as TIME_UPDATE
            FROM PIMIS_USER a 
            WHERE a.SYSTEM LIKE 'pimis'
            AND a.STATUS = 'y'
            ORDER BY a.TIME_UPDATE DESC";
        $result = $this->oracle->query($sql);
        return $result;
    }

    public function list_user_type()
    {
        $this->oracle->select('TYPE_ID, TYPE_NAME');
        $this->oracle->order_by('ORDER_NUMBER');
        $query = $this->oracle->get('PIMIS_USER_TYPE');
        return $query;
    }

    public function chk_user_duplicate($email)
    {
        $this->oracle->where('EMAIL', $email);
        $this->oracle->where('SYSTEM', 'pimis');
        $this->oracle->where('STATUS', 'y');
        $query = $this->oracle->get('PIMIS_USER');
        return $query;
    }

    public function insert_user($array)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('TITLE', $array['title']);
        $this->oracle->set('FIRSTNAME', $array['firstname']);
        $this->oracle->set('LASTNAME', $array['lastname']);
        $this->oracle->set('IDP', $array['idp']);
        $this->oracle->set('EMAIL', $array['email']);
        $this->oracle->set('USER_ACTIVE', $array['activation']);
        $this->oracle->set('SYSTEM', 'pimis');
        $this->oracle->set('STATUS', 'y');
        $this->oracle->set('UNIT_ID', $array['unitID']);
        $this->oracle->set('USER_UPDATE', $array['updater']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $insert = $this->oracle->insert('PIMIS_USER');
        if ($insert) {
            $this->oracle->select('USER_ID');
            $this->oracle->where('EMAIL', $array['email']);
            $this->oracle->where('SYSTEM', 'pimis');
            $this->oracle->where('STATUS', 'y');
            $getUserID = $this->oracle->get('PIMIS_USER')->row_array();
            $result['status']   = $insert;
            $result['insertID'] = $getUserID['USER_ID'];
        } else {
            $result['status']   = false;
            $result['insertID'] = '';
        }
        return $result;
    }

    public function insert_privileges($arrayUserPrivileges, $userID, $updater)
    {
        foreach ($arrayUserPrivileges as $r) {
            $date = date("Y-m-d H:i:s");
            $this->oracle->set('USER_ID', $userID);
            $this->oracle->set('TYPE_ID', $r);
            $this->oracle->set('STATUS', 'y');
            $this->oracle->set('USER_UPDATE', $updater);
            $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
            $insert = $this->oracle->insert('PIMIS_USER_PRIVILEGES');
            $data['typeID'] = $r;
            $data['userID'] = $userID;
            $data['status'] = $insert;
            $result[] = $data;
        }
        return $result;
    }

    public function delete_user($array)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('STATUS', 'n');
        $this->oracle->set('USER_UPDATE', $array['updater']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->where('USER_ID', $array['userID']);
        $query = $this->oracle->update('PIMIS_USER');
        return $query;
    }

    public function delete_privileges($array)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('STATUS', 'n');
        $this->oracle->set('USER_UPDATE', $array['updater']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->where('USER_ID', $array['userID']);
        $query = $this->oracle->update('PIMIS_USER_PRIVILEGES');
        return $query;
    }

    public function get_user_detail($userID)
    {
        $this->oracle->where('USER_ID', $userID);
        $query = $this->oracle->get('PIMIS_USER');
        return $query;
    }

    public function update_user($array)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('TITLE', $array['title']);
        $this->oracle->set('FIRSTNAME', $array['firstname']);
        $this->oracle->set('LASTNAME', $array['lastname']);
        $this->oracle->set('IDP', $array['idp']);
        $this->oracle->set('EMAIL', $array['email']);
        $this->oracle->set('USER_ACTIVE', $array['activation']);
        $this->oracle->set('UNIT_ID', $array['unitID']);
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
        $this->oracle->where('STATUS', 'y');
        $query = $this->oracle->get('PIMIS_USER');
        return $query;
    }

    public function get_privileges_per_user($userID)
    {
        $this->oracle->select('a.USER_ID, b.TYPE_ID, b.TYPE_NAME');
        $this->oracle->join('PIMIS_USER_TYPE b', "a.TYPE_ID = b.TYPE_ID");
        $this->oracle->where('a.USER_ID', $userID);
        $this->oracle->where('a.STATUS', 'y');
        $query = $this->oracle->get('PIMIS_USER_PRIVILEGES a');
        return $query;
    }

    public function get_user_by_email($rtarfMail)
    {
        $this->oracle->where('EMAIL', $rtarfMail);
        $query = $this->oracle->get('PIMIS_USER');
        return $query;
    }
}
