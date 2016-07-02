<?php
class Login extends CI_Controller {

    public function index()
    {
        $this->load->helper('captcha');
        $this->load->view("login.html");
    }

    public function login_api()
    {
      $this->load->helper('cookie');
      $this->load->model('login_model');
      $phone_number = $this->input->post("phone_number");
      $password = $this->input->post("password");
      $captcha = $this->input->post("captcha");
      $data['login'] = $this->login_model->login($phone_number,$password,$captcha);
      $req = array('check' => $data['login']);
      if($data['login']=='1')
      {
        $cookie = array(
          'name'   => 'user_phone_number',
          'value'  => $phone_number,
          'expire' => '3600',
          //'domain' => '.localhost',
          //'path'   => '/',
          //'prefix' => 'myprefix_',
          //'secure' => TRUE
        );

        $this->input->set_cookie($cookie);
      }
      echo json_encode($req);
    }

    public function check_userinfo_api()
    {
      $type = $this->input->post('type');
      $phone_number = $this->input->post('phone_number');
      $department = $this->input->post('$department');
      $number = $this->input->post('number');
      $position = $this->input->post('position');
      $class_no = $this->input->post('class_no');

      if($type=="check")
      {
        $this->load->model('check_userinfo_model');
        $this->load->model('search_uid_model');

        $this->load->helper('cookie');
        $phone_number = $this->input->cookie('user_phone_number');

        $uid['uid'] = $this->search_uid_model->search_uid($phone_number);
        $data['check_userinfo'] = $this->check_userinfo_model->check_userinfo($uid['uid']);

        $req=array('check'=> $data['check_userinfo']);
        echo json_encode($req);
      }
      if($check=="insert")
      {
        $department = $this->input->post('department');
        $number = $this->input->post('number');
        $position  = $this->input->post('position');
        $class_num = $this->input->post('class_num');

        if($phone_number)
        {
          $this->load->model('add_userinfo_model');
          $add_result = $this->add_userinfo_model->add_userinfo($uid['uid'],$department,$number,$position,$class_num);
          $check="1";
  			  $req=array('check'=>$check);
  			  echo json_encode($req);
        }
        else {
          $check="error";
          $req=array('check'=>$check);
          echo json_encode($req);
        }
      }
    }

}
?>
