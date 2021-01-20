<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auditor_team_model extends CI_Model
{
    var $oracle;

    public function __construct()
    {
        $this->oracle = $this->load->database('oracle', true);
    }

    public function get_auditor_teams()
    {
        $query = $this->oracle->get('PIMIS_AUDITOR_TEAM');
        return $query;
    }
}
