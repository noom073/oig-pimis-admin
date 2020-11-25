<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Session_services
{

    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        // $this->CI->load->library('session');
    }

    public function get_user_type_name($userType)
    {
        switch ($userType) {
            case 'admin':
                $name = 'Administrator';
                break;
            case 'control':
                $name = 'Controller';
                break;
            case 'user':
                $name = 'User';
                break;
            case 'auditor':
                $name = 'Auditor';
                break;
            case 'viewer':
                $name = 'Viewer';
                break;

            default:
                $name = '--';
                break;
        }
        
        return $name;
    }
}
