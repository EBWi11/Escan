<?php
class GPS_precision_calculate_model extends CI_Model
{

  public function __construct()
  {
    $this->load->database();
  }

  public function precision_calculate($mid,$time,$s)
  {
    if($s == 1)
    {
      $time = "now()";
        $query = $this->db->query("select user_ID,scan_GPS_X,scan_GPS_Y from Scan where  meeting_ID = '".$mid."' and to_days(scan_time) = to_days(".$time.");");
    }
    else
    {
        $query = $this->db->query("select user_ID,scan_GPS_X,scan_GPS_Y from Scan where  meeting_ID = '".$mid."' and to_days(scan_time) = to_days('".$time."');");
    }

    $i = "a";
    $GPS_data = array();
    foreach ($query->result_array() as $row)
    {
      $row['user_ID'];
      $row['scan_GPS_X'];
      $row['scan_GPS_Y'];
      $$i = array($row['user_ID'],$row['scan_GPS_X'],$row['scan_GPS_Y']);
      $n = ord($i);
      array_push($GPS_data,$$i);
      $i = chr($n+1);
    }

    $array_length = count($GPS_data)-1;
    if($array_length>5)
    {
      $x_data = array();
      $y_data = array();

    for($i = 0;$i<=$array_length;$i++)
    {
      array_push($x_data,$GPS_data[$i][1]);
      array_push($y_data,$GPS_data[$i][2]);
    }

    sort($x_data,1);
    sort($y_data,1);

    $x_min = $x_data[0];
    $x_max = $x_data[$array_length];

    $y_min = $y_data[0];
    $y_max = $y_data[$array_length];

    $coordinate_x = array();
    $coordinate_y = array();

    for($i = $x_min;$i<$x_max+0.001;$i = $i+0.001)
    {
      array_push($coordinate_x,$i);
    }

    for($i = $y_min;$i<$y_max+0.001;$i = $i+0.001)
    {
      array_push($coordinate_y,$i);
    }

    $x_count = count($coordinate_x);
    $y_count = count($coordinate_y);

    $xy_info = array();

    for($y = $coordinate_y[0];$y<$coordinate_y[$y_count-1];$y = $y+0.001)
    {
          for($x = $coordinate_x[0];$x<$coordinate_x[$x_count-1];$x = $x+0.001)
          {
              $users_point = self::check_xy($x,$y,$x+0.001,$y+0.001,$GPS_data);
              if($users_point[0][0])
              {
                  array_push($xy_info,$users_point);
              }
          }
    }

    $xy_info_count = count($xy_info);

    for($i = 1;$i<$xy_info_count;$i++)
    {
      for($j = $xy_info_count-1;$j>=$i;$j--)
      {
        if($xy_info[$j][0]<$xy_info[$j-1][0])
        {
          $x = $xy_info[$j];
          $xy_info[$j][0] = $xy_info[$j-1][0];
          $xy_info[$j-1][0] = $x;
        }
      }
    }

    for($i = 0;$i<$xy_info_count;$i++)
    {
      if($i == $xy_info_count-1)
      {
        foreach ($xy_info[$i][1] as $uid) {
          $this->db->set('GPS_static',1);
          $this->db->where('user_ID', $uid);
          $this->db->where('meeting_ID', $mid);
          $this->db->update('Scan');
        }
      }
      else
      {
        foreach ($xy_info[$i][1] as $uid)
        {
            $this->db->set('GPS_static',0);
            $this->db->where('user_ID', $uid);
            $this->db->where('meeting_ID', $mid);
            $this->db->update('Scan');
        }
      }
    }
  }
  else {
    foreach ($query->result_array() as $row)
    {
      $row['user_ID'];
      $this->db->set('GPS_static',2);
      $this->db->where('user_ID', $row['user_ID']);
      $this->db->where('meeting_ID', $mid);
      $this->db->update('Scan');
    }
  }
  }

  public function check_xy($x,$y,$x_down,$y_down,$GPS_data)
  {
    $xy_count = 0;
    $data_length = count($GPS_data);

    $users = array();

    for($i = 0;$i<$data_length;$i++)
    {
        if($x<=$GPS_data[$i][1] && $GPS_data[$i][1]<=$x_down && $y<=$GPS_data[$i][2] && $GPS_data[$i][2]<=$y_down)
        {
            $xy_count++;
            array_push($users,$GPS_data[$i][0]);
        }
    }

    $count = array($xy_count);
    $users_point = array($count,$users);
    return $users_point;
  }
}
?>
