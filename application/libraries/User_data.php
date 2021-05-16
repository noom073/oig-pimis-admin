<?php
defined('BASEPATH') or exit('No direct script access allowed');
class User_data
{
    private $CI;
    private $token;
    public function __construct($param)
    {
        $this->CI = &get_instance();
        $this->CI->load->model('auth_model');
        $this->token = $param['token'];
    }

    public function get_token()
    {
        return $this->token;
    }

    public function get_user_id()
    {
        $data = $this->CI->auth_model->get_user_id($this->token)->row_array();
        return $data['USER_ID'];
    }

    public function get_email()
    {
        $userID = $this->get_user_id();
        $data = $this->CI->auth_model->get_email($userID)->row_array();
        return $data['EMAIL'];
    }

    public function get_own_team()
    {
        $email = $this->get_email();
        $data = $this->CI->auth_model->get_own_team($email);
        return $data;
    }

    public function get_user_types()
    {
        $userID = $this->get_user_id();
        $data = $this->CI->auth_model->get_user_type($userID)->result_array();
        $result = array_map(function ($r) {
            return $r['TYPE_NAME'];
        }, $data);
        $types = array_merge($result);
        return $types;
    }

    public function get_unit_id_user()
    {
        $userID = $this->get_user_id();
        $data = $this->CI->auth_model->get_unit_user($userID);
        return $data;
    }

    public function get_name()
    {
        $userID = $this->get_user_id();
        $data = $this->CI->auth_model->get_user_by_id($userID);
        return $data;
    }

    public function get_user_inspection_type($teamID)
    {
        $userEmail = $this->get_email();
        $inspections = $this->CI->auth_model
            ->get_user_inspection_type_in_teamplan($teamID, $userEmail)
            ->result_array();

        $data = array_map(function ($r) {
            return $r['INSPECTION_ID'];
        }, $inspections);

        return $data;
    }
}
