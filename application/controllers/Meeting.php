<?php
class Meeting extends CI_Controller {

  public function __construct()
  {
    parent::__construct();
    $this->load->helper('url');
  }

  public function check_meeting($mid)
  {
      $this->load->helper('cookie');
      $phone_number = $this->input->cookie('user_phone_number');

      $this->load->model('GPS_precision_calculate_model');
      $this->GPS_precision_calculate_model->precision_calculate($mid,'','1');

      if($phone_number)
      {
        $this->load->model('my_meetings_model');
        $this->my_meetings_model->check_meeting($mid,'',1);

        $this->load->model('escan_model');
        $status = $this->escan_model->check_status($mid);
        if($status == 1)
        {
          $html_status= "close";
        }
        else
        {
          $html_status = "open";
        }
        $data = array(
          'mid' => $mid,
          'status' => $html_status,
        );
        $this->load->view("my_meeting.html",$data);
      }

      else {
          redirect('login');
      }
  }

  public function status()
  {
    $this->load->helper('cookie');
    $phone_number = $this->input->cookie('user_phone_number');

    $mid = $this->input->post('mid');
    $status = $this->input->post('status');

    if($phone_number)
    {
      $this->load->model('my_meetings_model');
      echo $this->my_meetings_model->status($mid,$status);
    }
    else {
        redirect('login');
    }
  }

  public function revise_remark()
  {
    $this->load->helper('cookie');
    $phone_number = $this->input->cookie('user_phone_number');

    $mid = $this->input->post('mid');
    $uid = $this->input->post('uid');
    $time = $this->input->post('time');
    $remark = $this->input->post('remark');

    if($phone_number)
    {
      $this->load->model('my_meetings_model');
      echo $this->my_meetings_model->revise_remark($mid,$uid,$time,$remark);
    }
    else {
        redirect('login');
    }
  }

  public function escan()
  {

    $phone_number  = $this->input->post("phone_number");
    $mid  = $this->input->post("mid");
    $scan_GPS_X  = $this->input->post("scan_GPS_X");
    $scan_GPS_Y  = $this->input->post("scan_GPS_Y");
    $x = (string)$scan_GPS_X;
    $y = (string)$scan_GPS_Y;
    $phone_id  = $this->input->post("phone_id");

    $this->load->model('search_uid_model');
    $uid['uid'] = $this->search_uid_model->search_uid($phone_number);

    $this->load->model('verify_user_model');
    $verify_user = $this->verify_user_model->verify_user($uid['uid'],$phone_id);

    $this->load->model('escan_model');

    if($verify_user)
    {
        $check = $this->escan_model->scan($uid['uid'],$mid,$x,$y);
        $req = array('check' => $check);
        echo json_encode($req);
    }
    else
    {
      $req = array('check' => "error!");
      echo json_encode($req);
    }
  }


  public function check_history_view($mid)
  {
    $this->load->helper('cookie');
    $phone_number = $this->input->cookie('user_phone_number');

    if($phone_number)
    {
      $data = array(
        'mid' => $mid
      );
      $this->load->view('check_history.html',$data);
    }
    else {
        redirect('login');
    }
  }

  public function check_history($mid,$date)
  {
    $this->load->helper('cookie');
    $phone_number = $this->input->cookie('user_phone_number');
    if($phone_number)
    {
      $this->load->model('GPS_precision_calculate_model');
      $this->GPS_precision_calculate_model->precision_calculate($mid,$date,2);

      $this->load->view('detail.html');
      $this->load->model('my_meetings_model');

      $this->my_meetings_model->check_meeting($mid,$date,2);

      /*
      $result = $this->my_meetings_model->check_history($mid,$date);
      echo "<h2>签到情况</h2>";
      echo "<table border='2'>
      <tr>
      <th>班级&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
      <th>学号&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
      <th>姓名&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
      <th>性别&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
      <th>组织&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
      <th>电话&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
      <th>签到状态&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
      </tr>";
      foreach ($result->result_array() as $row) {
        if($row['scan_result'])
        {
          $static = "已签到";
        }
        else
        {

        }
        echo"<tr>";
        echo"<td>" . $row['class_no'] . "</td>";
        echo"<td>" . $row['number'] . "</td>";
        echo"<td>" . $row['name'] . "</td>";
        echo"<td>" . $row['sex'] . "</td>";
        echo"<td>" . $row['phone_number'] . "</td>";
        echo"<td>" . $row['scan_time'] . "</td>";
        echo"<td>" . $static . "</td>";
        echo "</tr>";
        echo "</table>";
      }*/
    }
  }

  public function user_scan_api()
  {
    $phone_number = $this->input->post("phone_number");
    $this->load->model('search_uid_model');
    $uid['uid'] = $this->search_uid_model->search_uid($phone_number);
    $this->load->model('escan_model');
    $data = $this->escan_model->user_scan($uid['uid']);
    $req = array('data' => $data);
    echo json_encode($req);
  }
}
?>
