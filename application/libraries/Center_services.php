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
        $search = array('๐', '๑', '๒', '๓', '๔', '๕', '๖', '๗', '๘', '๙');
        $replace = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        return str_replace($search, $replace, $text);
    }

    public function conv_date_to_thai($YYYYMMDD, $type = 'l') // param $YYYYMMDD = 'YYYY-MM-DD'
    {
        if (trim($YYYYMMDD) == '') return 'Invalid input';
        $mixDate = explode('-', $YYYYMMDD);
        if ($type == 's') {
            $dd = ($mixDate[2] < 10) ? substr($mixDate[2], 1) : $mixDate[2];
            $month = $this->short_thai_month($mixDate[1]);
            $year = substr($mixDate[0] + 543, 2, 2);
            return "{$dd} {$month}{$year}";
        } else {
            $dd = ($mixDate[2] < 10) ? substr($mixDate[2], 1) : $mixDate[2];
            $month = $this->long_thai_month($mixDate[1]);
            $year = $mixDate[0] + 543;
            return "{$dd} {$month} {$year}";
        }
    }


    public function short_thai_month($MM)
    {
        switch ($MM) {
            case '01':
                return 'ม.ค.';
                break;
            case '02':
                return 'ก.พ.';
                break;
            case '03':
                return 'มี.ค.';
                break;
            case '04':
                return 'เม.ย.';
                break;
            case '05':
                return 'พ.ค.';
                break;
            case '06':
                return 'มิ.ย.';
                break;
            case '07':
                return 'ก.ค.';
                break;
            case '08':
                return 'ส.ค.';
                break;
            case '09':
                return 'ก.ย.';
                break;
            case '10':
                return 'ต.ค.';
                break;
            case '11':
                return 'พ.ย.';
                break;
            case '12':
                return 'ธ.ค.';
                break;

            default:
                return 'Invalid number';
                break;
        }
    }

    public function long_thai_month($MM)
    {
        switch ($MM) {
            case '01':
                return 'มกราคม';
                break;
            case '02':
                return 'กุมภาพันธ์';
                break;
            case '03':
                return 'มีนาคม';
                break;
            case '04':
                return 'เมษายน';
                break;
            case '05':
                return 'พฤษภาคม';
                break;
            case '06':
                return 'มิถุนายน';
                break;
            case '07':
                return 'กรกฎาคม';
                break;
            case '08':
                return 'สิงหาคม';
                break;
            case '09':
                return 'กันยายน';
                break;
            case '10':
                return 'ตุลาคม';
                break;
            case '11':
                return 'พฤศจิกายน';
                break;
            case '12':
                return 'ธันวาคม';
                break;

            default:
                return 'Invalid number';
                break;
        }
    }
}
