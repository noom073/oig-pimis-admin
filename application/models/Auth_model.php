<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    var $oracle;

    public function __construct()
    {
        $this->oracle = $this->load->database('oracle', true);
    }

    public function get_user_type($userID)
    {
        $sql = "SELECT B.TYPE_ID, B.TYPE_NAME 
            FROM PIMIS_USER_PRIVILEGES A
            INNER JOIN PIMIS_USER_TYPE B 
                ON A.TYPE_ID = B.TYPE_ID 
            WHERE A.USER_ID = ?
            ORDER BY B.ORDER_NUMBER";
        $query = $this->oracle->query($sql, array($userID));
        return $query;
    }

    public function get_user($rtarfMail)
    {
        $sql = "SELECT *
            FROM PIMIS_USER
            WHERE EMAIL = ?
            AND SYSTEM = 'pimis'";
        $query = $this->oracle->query($sql, array($rtarfMail));
        return $query;
    }
}
