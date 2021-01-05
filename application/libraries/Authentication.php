<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Authentication
{

    protected $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->helper('string');
        $this->CI->load->helper('cookie');
        $this->CI->load->library('session');
        $this->CI->load->library('session_services');
        $this->CI->load->model('auth_model');
    }

    private function check_ad($rtarfMail, $password)
    {
        $url = "https://itdev.rtarf.mi.th/welfare/index.php/authentication_2";
        $curlAD = curl_init();
        curl_setopt($curlAD, CURLOPT_URL, $url);
        curl_setopt($curlAD, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlAD, CURLOPT_POST, true);
        curl_setopt($curlAD, CURLOPT_POSTFIELDS, "username={$rtarfMail}&password={$password}");
        curl_setopt($curlAD, CURLOPT_CAINFO, FCPATH . "assets/ca/cacert.pem");
        $output     = curl_exec($curlAD);
        $curlErr    = curl_error($curlAD);
        if ($curlErr) {
            $data['status']  = false;
            $data['errno']   = curl_errno($curlAD);
            $data['error']   = curl_error($curlAD);
        } else {
            $data['status']     = true;
            $data['http_code']  = curl_getinfo($curlAD, CURLINFO_HTTP_CODE);
            $data['response']   = $output;
        }
        curl_close($curlAD);
        return $data;
    }

    private function check_token($token)
    {
        $url = "https://itdev.rtarf.mi.th/welfare/index.php/profile?token={$token}";
        $curlAD = curl_init();
        curl_setopt($curlAD, CURLOPT_URL, $url);
        curl_setopt($curlAD, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlAD, CURLOPT_CAINFO, FCPATH . "assets/ca/cacert.pem");
        $output = curl_exec($curlAD);
        $curlErr = curl_error($curlAD);
        if ($curlErr) {
            $data['status']  = false;
            $data['errno']   = curl_errno($curlAD);
            $data['error']   = curl_error($curlAD);
        } else {
            $data['status']     = true;
            $data['http_code']  = curl_getinfo($curlAD, CURLINFO_HTTP_CODE);
            $data['response']   = $output;
        }
        curl_close($curlAD);
        return $data;
    }

    public function check_login()
    {
        $isLogged = $this->CI->session->isLogged;
        return $isLogged;
    }

    public function check_type_user($userType)
    {
        $isLogged = $this->check_login();
        if ($isLogged) {
            $result = ($userType == $this->CI->session->usertype) ? true : false;
        } else {
            $result = false;
        }
        return $result;
    }

    public function go_home()
    {
        $userType =  $this->CI->session->usertype;
        switch ($userType) {
            case 'admin':
                redirect('admin/index');
                break;
            case 'user':
                redirect('user/index');
                break;

            default:
                redirect('main/index');
                break;
        }
        return;
    }

    public function process_login($rtarfMail, $password)
    {
        $checkADReturn = $this->check_ad($rtarfMail, $password); // CHECK USER PASSWORD AD
        if ($checkADReturn['status'] === true && $checkADReturn['http_code'] == 200) {
            $ADToken = json_decode($checkADReturn['response']);
            $checkTokenReturn = $this->check_token($ADToken->TOKEN);

            if ($checkTokenReturn['status'] === true && $checkTokenReturn['http_code'] == 200) { // CHECK TOKEN
                $ADData = json_decode($checkTokenReturn['response']);
                $userID = $this->CI->auth_model->get_user($ADData->EMAIL)->row_array()['USER_ID'];
                $userType = $this->CI->auth_model->get_user_type($userID);
                if ($userType->num_rows() > 0) { // CHECK PRIVILEGES
                    $token = random_string('alnum', 32);
                    $checkToken = $this->CI->auth_model->check_token($token);
                    if ($checkToken->num_rows() == 0) { // CHECK TOKEN DUPLICATE
                        $insertToken = $this->CI->auth_model->insert_token($token, $userID);
                        if ($insertToken) { // CHECK INSERT TOKEN
                            set_cookie('pimis-token', $token, 0);
                            $sesData['usertype']    = array_map(function ($r) {
                                $data['TYPE_NAME'] = $r['TYPE_NAME'];
                                $data['TYPE_NAME_FULL'] = $this->CI->session_services->get_user_type_name($r['TYPE_NAME']);
                                return $data;
                            }, $userType->result_array());
                            $sesData['nameth']      = $ADData->BIOG_NAME;
                            $sesData['nameen']      = $ADData->BIOG_NAME_ENG;
                            $sesData['email']       = $ADData->EMAIL;
                            $sesData['mid']         = $ADData->BIOG_ID;
                            $sesData['cid']         = $ADData->REG_CID;
                            $sesData['isLogged']    = true;
                            $this->CI->session->set_userdata($sesData);
                            $result['status']   = true;
                            $result['data']     = $sesData;
                        } else {
                            $result['status']   = false;
                            $result['text']     = 'เพิ่ม TOKEN ไม่สำเร็จ';
                            $result['http_code'] = 403;
                        }
                    } else {
                        $result['status']   = false;
                        $result['text']     = 'สร้าง TOKEN ไม่สำเร็จ';
                        $result['http_code'] = 403;
                    }
                } else {
                    $result['status']   = false;
                    $result['text']     = 'ไม่พบสิทธิผู้ใช้นี้';
                    $result['http_code'] = 403;
                }
            } else {
                $result['status']   = false;
                $result['text']     = 'TOKEN นี้ ไม่พบข้อมูลผู้ใช้';
                $result['http_code'] = $checkTokenReturn['http_code'];
            }
        } else {
            $result['status']   = false;
            $result['text']     = 'Email หรือ Password ไม่ถูกต้อง';
            $result['curldata'] = $checkADReturn; // return http code and curl data
        }
        return $result;
    }
}
