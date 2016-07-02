<?php
class Add_userinfo_model extends CI_Model
{

  public function __construct()
  {
    $this->load->database();
  }

  public function add_userinfo($uid,$department,$number,$position,$class_num)
  {
    $userinfo_data = array(
        'user_ID' => $uid,
        'department' => $department,
        'number' => $number,
        'position' => $position,
        'class_no' => $class_num,
    );

    if($uid && $department && $number && $position && $class_num)
    {
      return $this->db->insert('UsersInfo', $userinfo_data);
    }
    else {
      return '0';
    }
  }

  public function return_userinfo($uid)
  {
    $query = $this->db->query("select department,number,position,class_no from UsersInfo where user_ID='".$uid."';");
    echo"<h2>身份</h2>";
    echo "<table border='2'>
    <tr>
    <th>单位&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>学号&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>地址&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    <th>班级&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
    </tr>";

    foreach ($query->result_array() as $row)
    {
      echo"<tr>";
      echo"<td>" . $row['department'] . "</td>";
      echo"<td>" . $row['number'] . "</td>";
      echo"<td>" . $row['position'] . "</td>";
      echo"<td>" . $row['class_no'] . "</td>";
      echo"</tr>";
    }
  }

  public function return_userinfo_api($uid)
  {
    $query = $this->db->query("select department,number,position,class_no from UsersInfo where user_ID='".$uid."';");
    $i = "a";
    $n = 0;
    $data = array();
    foreach ($query->result_array() as $row)
    {
      $row['department'];
      $row['number'];
      $row['position'];
      $row['class_no'];
      $$i = array($row['department'],$row['number'],$row['position'],$row['class_no']);
      $n = ord($i);
      array_push($data,$$i);
      $i = chr($n+1);
    }
    return $data;
  }
}
?>
