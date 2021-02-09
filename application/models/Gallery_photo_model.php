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

    public function upload_gallery_multiple($files, $idData)
    {
        $this->oracle->trans_begin(); //START TRANSACTIONS
        $filesUplaod = [];
        for ($i = 0; $i < count($files['photo']['name']); $i++) {
            $_FILES['images[]']['name']     = $files['photo']['name'][$i];
            $_FILES['images[]']['type']     = $files['photo']['type'][$i];
            $_FILES['images[]']['tmp_name'] = $files['photo']['tmp_name'][$i];
            $_FILES['images[]']['error']    = $files['photo']['error'][$i];
            $_FILES['images[]']['size']     = $files['photo']['size'][$i];

            $config['upload_path']          = './assets/filesUpload/';
            $config['allowed_types']        = 'gif|jpg|jpeg|png';
            $config['max_size']             = 2048;
            $config['file_name']            = random_string('alnum', 64);

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('images[]')) {
                $uploadFail['status'] = false;
                $uploadFail['file_name'] = $_FILES['images[]']['name'];
                $uploadFail['error'] = $this->upload->display_errors();
                $this->oracle->trans_rollback(); //ROLLBACK TRANSACTIONS
                return $uploadFail; //EXIT ALL
            } else {
                $imageData = $this->upload->data();
                $fileInput['fileName']          = $imageData['client_name'];
                $fileInput['filePath']          = $imageData['file_name'];
                $fileInput['teamPlanID']        = $idData['teamPlanID'];
                $fileInput['inspectionOptionID'] = $idData['inspectionOptionID'];
                $fileInput['updater']           = $this->session->email;
                $upload['status']       = $this->insert_gallery($fileInput);
                $upload['fileupload']   = $imageData;
                $upload['idData']       = $idData;
            }
            $filesUplaod[] = $upload;
        }
        $this->oracle->trans_commit(); //COMMIT TRANSACTIONS
        $result['status'] = true; 
        $result['data'] = $filesUplaod; 
        return $result;
    }

    public function insert_gallery($fileInput)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('TEAMPLAN_ID', $fileInput['teamPlanID']);
        $this->oracle->set('INSPECTION_OPTION_ID', $fileInput['inspectionOptionID']);
        $this->oracle->set('PIC_NAME', $fileInput['fileName']);
        $this->oracle->set('PIC_PATH', $fileInput['filePath']);
        $this->oracle->set('STATUS', 'y');
        $this->oracle->set('USER_UPDATE', $fileInput['updater']);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $query = $this->oracle->insert('PIMIS_GALLERY_PHOTO');
        return $query;
    }

    public function delete_photo($photoID, $updater)
    {
        $date = date("Y-m-d H:i:s");
        $this->oracle->set('STATUS', 'n');
        $this->oracle->set('USER_UPDATE', $updater);
        $this->oracle->set('TIME_UPDATE', "TO_DATE('{$date}','YYYY/MM/DD HH24:MI:SS')", false);
        $this->oracle->where('ROW_ID', $photoID);
        $query = $this->oracle->update('PIMIS_GALLERY_PHOTO');
        return $query;
    }
}
