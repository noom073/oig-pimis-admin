<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Nprt_model extends CI_Model
{

    public function __construct()
    {
        $this->oracle = $this->load->database('oracle', true);
    }

    public function get_nprt_units()
    {
        $this->oracle->where("SUBSTR(NPRT_UNIT, 8, 3) = '000'");
        $query = $this->oracle->get('PER_NPRT_TAB');
        return $query;
    }
}
