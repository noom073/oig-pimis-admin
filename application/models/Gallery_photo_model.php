<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Gallery_photo_model extends CI_Model
{
    var $oracle;

    public function __construct()
    {
        $this->oracle = $this->load->database('oracle', true);
    }

    public function get_photo_for_team_inspection($teamPlanID, $inspectionOptionID)
    {
        $this->oracle->where('TEAMPLAN_ID', $teamPlanID);
        $this->oracle->where('INSPECTION_OPTION_ID', $inspectionOptionID);
        $this->oracle->where('STATUS', 'y');
        $query = $this->oracle->get('PIMIS_GALLERY_PHOTO');
        return $query;
    }

}
