<?php
class Register extends CI_Controller {
//defined('BASEPATH') OR exit('No direct script access allowed');

    public function test()
    {
      $this->load->view('register.html');
    }

    public function register_api()
    {
      $json = file_get_contents('php://input');
        $obj=json_decode($json);

        $type = $obj->{'type'};
        //$str1 = $obj->{'str1'};
        //$str2 = $obj->{'str2'};

        $phone_number = $obj->{'phone_number'};
        $email = $obj->{'email'};
        $name = $obj->{'name'};
        $sex = $obj->{'sex'};
        $password = $obj->{'password'};
        $password_rep = $obj->{'password_rep'};
        $phone_id = $obj->{'phone_id'};

      $this->load->model('register_model');
      if($type=="register")
      {
        if($password==$password_rep)
        {
          $data['register']=$this->register_model->register($phone_number,$email,$name,$sex,md5($password),md5($password_rep),$phone_id);
          //echo $date['register'];
          $req = array('check' => $data['register']);
          echo json_encode($req);
        }
      }
      else
      {
        $data['register']=$this->register_model->signal_check($type,$str1,$str2);
        echo $data['check'];
      }

    }

}
