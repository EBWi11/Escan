<?php
class Escan_model extends CI_Model
{

  public function __construct()
  {
    $this->load->database();
  }

  public function scan($uid,$mid,$scan_GPS_X,$scan_GPS_Y)
  {
    if(self::check_status($mid))
    {
      date_default_timezone_set('PRC');
      $time=date('Y-m-d H:i:s',time());
      $scan_data = array(
          'user_ID' => $uid,
          'meeting_ID' => $mid,
          'scan_GPS_X' => $scan_GPS_X,
          'scan_GPS_Y' => $scan_GPS_Y,
          'scan_time' => $time,
          'scan_result' =>'1',
      );

      $check = $this->db->insert('Scan',$scan_data);
      if($check)
      {
        return $check;
      }
      else {
        return "insert error";
      }
    }
    else{
      return "error!";
    }
  }

  public function check_status($mid)
  {
    $query = $this->db->query("select status from Meeting where meeting_ID = '".$mid."';");

    $row = $query->row();
    foreach ($query->result_array(1) as $row)
    {
      if($row['status']=="1")
      {
        return 1;
      }
      else
      {
        return 0;
      }
    }

  }


  public function user_check_meetings($uid)
  {
      $query = $this->db->query("select number,name,meeting_name,scan_time from ((Scan left join (UsersInfo left join Users on Users.user_ID = UsersInfo.user_ID) on Scan.user_ID = UsersInfo.user_ID) left join Meeting  on Scan.meeting_ID=Meeting.meeting_ID) where UsersInfo.user_ID = '".$uid."';");
      echo"<h2>签到详情</h2>";
      echo "<table border='2'>
      <tr>
      <th>学号&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
      <th>姓名&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
      <th>课程名称&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
      <th>签到时间&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
      </tr>";

      foreach ($query->result_array() as $row)
      {
        echo"<tr>";
        echo"<td>" . $row['number'] . "</td>";
        echo"<td>" . $row['name'] . "</td>";
        echo"<td>" . $row['meeting_name'] . "</td>";
        echo"<td>" . $row['scan_time'] . "</td>";
        echo"</tr>";
      }

  }

  public function user_scan($uid)
  {
    $query = $this->db->query("select number,name,meeting_name,scan_time from ((Scan left join (UsersInfo left join Users on Users.user_ID = UsersInfo.user_ID) on Scan.user_ID = UsersInfo.user_ID) left join Meeting  on Scan.meeting_ID=Meeting.meeting_ID) where UsersInfo.user_ID = '".$uid."';");
    $i = "a";
    $n = 0;
    $data = array();
    foreach ($query->result_array() as $row)
    {
      $row['number'];
      $row['meeting_name'];
      $row['scan_time'];
      $$i = array($row['number'],$row['meeting_name'],$row['scan_time']);
      $n = ord($i);
      array_push($data,$$i);
      $i = chr($n+1);
    }
    return $data; 
  }

}
?>
