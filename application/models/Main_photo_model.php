<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Main_photo_model extends CI_Model
{
    var $oracle;

    public function __construct()
    {
        $this->oracle = $this->load->database('oracle', true);
    }

    public function get_main_photo_for_team_paln($teamPlanID)
    {        
        $this->oracle->where('TEAMPLAN_ID', $teamPlanID);
        $this->oracle->where('STATUS', 'y');
        $this->oracle->order_by('ROW_ID', 'DESC');
        $query = $this->oracle->get('PIMIS_MAIN_PHOTO');
        return $query;
    }

    public function insert_photo($array)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('TEAMPLAN_ID', $array['teamPlanID']);
        $this->oracle->set('PIC_NAME', $array['fileName']);
        $this->oracle->set('PIC_PATH', $array['filePath']);
        $this->oracle->set('STATUS', 'y');
        $this->oracle->set('USER_UPDATE', $array['updater']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $query = $this->oracle->insert('PIMIS_MAIN_PHOTO');
        return $query;
    }

    public function delete_photo($photoID, $updater)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('STATUS', 'n');
        $this->oracle->set('USER_UPDATE', $updater);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->where('ROW_ID', $photoID);
        $query = $this->oracle->update('PIMIS_MAIN_PHOTO');
        return $query;
    }

}
