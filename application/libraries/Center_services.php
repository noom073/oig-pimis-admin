<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Center_services
{

    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        // $this->CI->load->library('session');
    }

    public function convert_th_num_to_arabic($text)
    {
        $search = array('๐','๑','๒','๓','๔','๕','๖','๗','๘','๙');
        $replace = array('0','1','2','3','4','5','6','7','8','9');
        return str_replace($search, $replace, $text);
    }
}
