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
        $data = $this->CI->auth_model->get_email($userID)->row_aray();
        return $data['EMAIL'];
    }
}
