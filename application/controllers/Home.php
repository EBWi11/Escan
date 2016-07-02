<?php
class Home extends CI_Controller {

    public function __construct()
    {
      parent::__construct();
      $this->load->helper('url');
    }

    public function index()
    {

      $this->load->model('check_userinfo_model');
      $this->load->model('search_uid_model');

      $this->load->helper('cookie');
      $phone_number = $this->input->cookie('user_phone_number');

      $uid['uid'] = $this->search_uid_model->search_uid($phone_number);
      $data['check_userinfo'] = $this->check_userinfo_model->check_userinfo($uid['uid']);


      if($phone_number && $data['check_userinfo'])
      {
        $this->load->view("home.html");
      }

      else if($phone_number && empty($data['check_userinfo']))
      {
        $this->load->view("add_userinfo.html");
      }

      else if(!isset($phone_number))
      {
        redirect('login');
      }
      else{
        echo "1";
      }

    }


    public function add_userinfo()
    {
      $this->load->model('search_uid_model');
      $uid['uid'] = $this->search_uid_model->search_uid($phone_number);
      if($phone_number)
      {
        $this->load->view('add_userinfo.html');
      }
    }

    public function add_userinfo_android_api()
    {
      $phone_id = $this->input->post('phone_id');
      $phone_number = $this->input->post('phone_number');
      $department = $this->input->post('department');
      $number = $this->input->post('number');
      $position  = $this->input->post('position');
      $class_num = $this->input->post('class_num');

      $this->load->model('search_uid_model');
      $uid['uid'] = $this->search_uid_model->search_uid($phone_number);

      $this->load->model('add_userinfo_model');
      $this->load->model('check_user_phone_model');

      $check = $this->check_user_phone_model->check_user_phone($phone_id,$uid['uid']);
      if($check)
      {
        $add_result = $this->add_userinfo_model->add_userinfo($uid['uid'],$department,$number,$position,$class_num);
        $check="1";
        $req=array('check'=>$check);
        echo json_encode($req);
      }
      else {
        $check="0";
        $req=array('check'=>$check);
        echo json_encode($req);
      }
    }

    public function check_userinfo_api()
    {
      $phone_id = $this->input->post('phone_id');

      $phone_number = $this->input->post('phone_number');
      $this->load->model('search_uid_model');
      $uid['uid'] = $this->search_uid_model->search_uid($phone_number);

      $this->load->model('add_userinfo_model');
      $this->load->model('check_user_phone_model');
      $check = $this->check_user_phone_model->check_user_phone($phone_id,$uid['uid']);
      if($check)
      {
        $data = $this->add_userinfo_model->return_userinfo_api($uid['uid']);
        $req = array('data' => $data);
        echo json_encode($req);
      }
      else {
        $req = array('data' => 0);
        echo json_encode($req);
      }
    }

    public function add_userinfo_api()
    {

      $this->load->helper('cookie');
      $phone_number = $this->input->cookie('user_phone_number');

      $this->load->model('search_uid_model');
      $uid['uid'] = $this->search_uid_model->search_uid($phone_number);

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
        redirect('login');
      }

    }

    public function add_meeting()
    {
      $this->load->helper('cookie');
      $phone_number = $this->input->cookie('user_phone_number');

      $this->load->model('search_uid_model');
      $uid['uid'] = $this->search_uid_model->search_uid($phone_number);

      $this->load->model('check_userinfo_model');
      $data['check_userinfo'] = $this->check_userinfo_model->check_userinfo($uid['uid']);


      if($phone_number && $data['check_userinfo'])
      {
        $this->load->view("add_meeting.html");
      }
      else {
        redirect('login');
      }
    }

    public function add_meeting_api()
    {
      $this->load->helper('cookie');
      $phone_number = $this->input->cookie('user_phone_number');

      $this->load->model('search_uid_model');
      $uid['uid'] = $this->search_uid_model->search_uid($phone_number);

      $meeting_name = $this->input->post('meeting_name');
      $class_no = $this->input->post('class_no');
      $location = $this->input->post('location');
      $start_time = $this->input->post('start_time');
      $end_time = $this->input->post('end_time');
      $total_people = $this->input->post('total_people');

      if($phone_number)
      {
          $this->load->model('add_meeting_model');
          $req = $this->add_meeting_model->add_meeting($uid['uid'],$meeting_name,$class_no,$location,$start_time,$end_time,$total_people);

      }
      else {
        redirect('login');
      }
    }

      public function my_meetings()
      {
        $this->load->helper('cookie');
        $phone_number = $this->input->cookie('user_phone_number');

        $this->load->model('search_uid_model');
        $uid['uid'] = $this->search_uid_model->search_uid($phone_number);

        if($phone_number)
        {
          $this->load->view("my_meetings.html");

          $this->load->model('my_meetings_model');
          $this->my_meetings_model->my_meetings($uid['uid']);
        }
        else
        {
          redirect('login');
        }

      }

      public function check_meeting()
      {
        $this->load->helper('cookie');
        $phone_number = $this->input->cookie('user_phone_number');
        if(empty($phone_number))
        {
          $p = $this->input->post("phone_number");
          if(empty($p))
          {
            $check="error";
            $req=array('check'=>$check);
            echo json_encode($req);
          }
          else {
            $phone_number = $p;
          }
        }
        else
        {
          $this->load->model('escan_model');
          $this->load->model('search_uid_model');
          $uid['uid'] = $this->search_uid_model->search_uid($phone_number);

          $this->escan_model->user_check_meetings($uid['uid']);
        }
      }

      public function return_userinfo()
      {
        $this->load->helper('cookie');
        $phone_number = $this->input->cookie('user_phone_number');

        if($phone_number)
        {
          $this->load->model('add_userinfo_model');
          $this->load->model('search_uid_model');
          $uid['uid'] = $this->search_uid_model->search_uid($phone_number);

          $this->add_userinfo_model->return_userinfo($uid['uid']);

        }
      }

      public function change_password_view()
      {
        $this->load->helper('cookie');
        $phone_number = $this->input->cookie('user_phone_number');
        if($phone_number)
        {
          $this->load->view("change_password.html");
        }
        else {
          redirect('login');
        }
      }

      public function change_password()
      {
        $this->load->helper('cookie');
        $phone_number = $this->input->cookie('user_phone_number');
        $this->load->model('search_uid_model');
        $uid['uid'] = $this->search_uid_model->search_uid($phone_number);
        $old_pwd = $this->input->post("old_pwd");
        $new_pwd = $this->input->post("new_pwd");
        if($uid['uid'])
        {
          $this->load->model("change_password_model");
          if(strlen($new_pwd)<8)
          {
            $check="2";
            $req=array('check'=>$check);
            echo json_encode($req);
          }
          else
          {
            $req = $this->change_password_model->change_password($uid['uid'],$old_pwd,$new_pwd);
            if($req==1)
            {
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
        else{
          redirect('login');
        }

      }

      public function cancel_login()
      {
        $this->load->helper('cookie');
        delete_cookie('phone_number');
        redirect('');
      }

    }

  ?>
