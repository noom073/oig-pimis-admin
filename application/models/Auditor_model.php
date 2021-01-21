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
        $this->oracle->where('ADT_TEAM', $rowID);
        $query = $this->oracle->get('PIMIS_AUDITOR');
        return $query;
    }

}
