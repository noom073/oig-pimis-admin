<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Privilege_model extends CI_Model
{
    var $oracle;

    public function __construct()
    {
        $this->oracle = $this->load->database('oracle', true);
    }

    public function remove_privilege($userID, $typeID)
    {
        $this->oracle->where('USER_ID', $userID);
        $this->oracle->where('TYPE_ID', $typeID);
        $query = $this->oracle->delete('PIMIS_USER_PRIVILEGES');
        return $query;
    }

    public function add_privilege($userID, $typeID, $updater)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('USER_ID', $userID);
        $this->oracle->set('TYPE_ID', $typeID);
        $this->oracle->set('USER_UPDATE', $updater);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $query = $this->oracle->insert('PIMIS_USER_PRIVILEGES');
        return $query;
    }
}
