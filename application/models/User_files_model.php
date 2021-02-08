<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_files_model extends CI_Model
{
    var $oracle;

    public function __construct()
    {
        $this->oracle = $this->load->database('oracle', true);
    }

    public function delete_file_attach($rowID, $updater)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('STATUS', 'n');
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->set('USER_UPDATE', $updater);
        $this->oracle->where('ROW_ID', $rowID);
        $query = $this->oracle->update('PIMIS_USER_FILES');
        return $query;
    }

}
