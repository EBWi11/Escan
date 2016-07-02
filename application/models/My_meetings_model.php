<?php
include 'qrcode.php';
class My_meetings_model extends CI_Model
{

  public function __construct()
  {
    $this->load->database();
    $this->load->helper('date');
  }

  public function my_meetings($uid)
  {
    $query = $this->db->query("select meeting_ID,meeting_name,class_no,location,start_time,end_time,total_people from Meeting where user_ID='".$uid."';");
    echo "<table border='2'>
    <tr>
    <th>meeting_ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>课程名称&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>班级&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>地点&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>起始时间&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>结束时间&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>参与人数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    </tr>";

    foreach ($query->result_array() as $row)
    {

      echo"<tr>";
      echo"<td>" . $row['meeting_ID'] . "</td>";
      echo"<td>" . $row['meeting_name'] . "</td>";
      echo"<td>" . $row['class_no'] . "</td>";
      echo"<td>" . $row['location'] . "</td>";
      echo"<td>" . $row['start_time'] . "</td>";
      echo"<td>" . $row['end_time'] . "</td>";
      echo"<td>" . $row['total_people'] . "</td>";
      echo "<td>".'<button name="'. $row['meeting_ID'] .'"  onclick="check(this.name)">查看</button>'." </td>";
      echo "<td>".'<button name="'. $row['meeting_ID'] .'"  onclick="check_history(this.name)">查看历史签到情况</button>'." </td>";
      echo"</tr>";
    }
    echo "</table>";


    }

    public function check_meeting($mid,$time,$s)
    {
      if($s==1)
      {
        $time = 'now()';
      }

      $query = $this->db->query("select meeting_ID,meeting_name,class_no,location,start_time,end_time,total_people from Meeting where meeting_ID='".$mid."';");
      echo"<h2>课程详情</h2>";
      echo "<table border='2'>
      <tr>
      <th>meeting_ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
      <th>课程名称&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
      <th>班级&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
      <th>地点&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
      <th>起始时间&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
      <th>结束时间&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
      <th>参与人数&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
      </tr>";

      foreach ($query->result_array() as $row)
      {

        echo"<tr>";
        echo"<td>" . $row['meeting_ID'] . "</td>";
        echo"<td>" . $row['meeting_name'] . "</td>";
        echo"<td>" . $row['class_no'] . "</td>";
        echo"<td>" . $row['location'] . "</td>";
        echo"<td>" . $row['start_time'] . "</td>";
        echo"<td>" . $row['end_time'] . "</td>";
        echo"<td>" . $row['total_people'] . "</td>";
        echo"</tr>";
      }
      echo "</table>";

      $post_data['mid'] = $mid;
      $postdata = http_build_query($post_data);
      $options = array(
      'http' => array(
      'method' => 'POST',
      'header' => 'Content-type:application/x-www-form-urlencoded',
      'content' => $postdata,
      'timeout' => 15 * 60 // 超时时间（单位:s）
      )
    );

      $context = stream_context_create($options);

      $result = file_get_contents('http://localhost/Escan/QRcode/QR.php', false, $context);
      echo '<div align="center"><img src = "http://localhost/Escan/QRcode/'.$mid.'.png"></div>';

        $user_info = $this->db->query("select Users.user_ID,class_no,number,name,sex,department,phone_number from (UsersInfo left join Users on UsersInfo.user_ID = Users.user_ID) where class_no = (select class_no from Meeting where meeting_ID = '".$mid."');");
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
        <th>位置信息（参考）&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
        <th>备注&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
        </tr>";

        foreach ($user_info->result_array() as $urow) {
          if(self::scan_check($mid,$urow['user_ID'],$time))
          {
            $scan_check="已签到";
          }
          else
          {
            $scan_check="未签到";
          }

          $gps_static = self::gps_check($mid,$urow['user_ID'],$time);
          $g_static = '';
          if($gps_static == 1)
          {
            $g_s = '在签到现场';
          }
          else if($gps_static == 2)
          {
            $g_s = '人数过少无法判断';
          }
          else
          {
            $g_s = '不在签到现场';
          }

          $remark = self::get_remark($mid,$urow['user_ID'],$time);
          if($time=="now()")
          {
            date_default_timezone_set('PRC');
            $time = date("Y-m-d");
          }
          echo"<tr>";
          echo"<td>" . $urow['class_no'] . "</td>";
          echo"<td>" . $urow['number'] . "</td>";
          echo"<td>" . $urow['name'] . "</td>";
          echo"<td>" . $urow['sex'] . "</td>";
          echo"<td>" . $urow['department'] . "</td>";
          echo"<td>" . $urow['phone_number'] . "</td>";
          echo"<td>".$scan_check."</td>";
          echo"<td>" . $g_s. "</td>";
          echo "<td>".'<input type = "text" id="'. $urow['user_ID'] .'"  name = "'.$time.'" onblur = "revise_remark('.$mid.','.$urow['user_ID'].',this.name)"></input>'." </td>";
          echo"</tr>";
        }
          echo "</table>";
    }

    public function scan_check($mid,$uid,$time)
    {
      $user_info_scan = $this->db->query("select scan_result from Scan where user_ID = '".$uid."' and to_days(scan_time) = to_days('".$time."') and meeting_ID='".$mid."';");
      foreach ($user_info_scan->result_array() as $row)
      {
        if($row['scan_result'] == 1)
          return 1;
        else
          return 0;
      }
    }

    public function gps_check($mid,$uid,$time)
    {
      $gps_static = $this->db->query("select GPS_static from Scan where user_ID = '".$uid."' and to_days(scan_time) = to_days('".$time."') and meeting_ID='".$mid."';");
      foreach ($gps_static->result_array(1) as $row)
      {
        return $row["GPS_static"];
      }
    }

    public function check_history($mid,$date)
    {
      $result = $this->db->query("select * from ((Scan left join UsersInfo on Scan.user_ID = UsersInfo.user_ID) left join Users on Scan.user_ID = Users.user_ID) where meeting_ID='".$mid."' and to_days(scan_time) = to_days('".$date."');");
      /*foreach ($result->result_array() as $row) {
        echo $row['meeting_ID'];
      }*/
      return $result;
    }

    public function get_remark($mid,$uid,$time)
    {
      $result = $this->db->query("select Remark from Scan where user_ID = '".$uid."' and to_days(scan_time) = to_days('".$time."') and meeting_ID='".$mid."';");
      foreach ($result->result_array(1) as $row)
      {
        return $row["Remark"];
      }
    }

    public function status($mid,$status)
    {
        if($mid && $status)
        {
          $this->db->set('status', $status);
          $this->db->where('meeting_ID', $mid);
          return $this->db->update('Meeting');
        }
        else {
          return "error!";
        }
    }

    public function revise_remark($mid,$uid,$time,$remark)
    {
      /*$this->db->set('Remark', $remark);
      $this->db->where('meeting_ID', $mid);
      $this->db->where('user_ID',$uid);
      $this->db->where('to_days(scan_time)','to_days('.$time.')');
      return $this->db->update('Scan');*/
      return $this->db->query("update Scan set Remark = '".$remark."' where meeting_ID = '".$mid."' and user_ID = '".$uid."' and to_days(scan_time) = to_days('".$time."');");
    }

  }
?>
