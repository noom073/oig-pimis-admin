<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth_model extends CI_Model {
    var $oracle;

    public function __construct() {
        $this->oracle = $this->load->database('oracle', true);
    }

	public function get_user_type($email) {
        $this->oracle->select('a.USER_TYPE, b.TYPE_NAME');
        $this->oracle->join('PIMIS_USER_TYPE b', 'a.USER_TYPE = b.TYPE_ID');
        $this->oracle->where('a.EMAIL', $email);
        $result = $this->oracle->get('PIMIS_USER a');

        return $result;
	}
}
