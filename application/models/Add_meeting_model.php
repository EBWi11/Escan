<?php
class Add_meeting_model extends CI_Model
{

  public function __construct()
  {
    $this->load->database();
  }

  public function add_meeting($uid,$meeting_name,$class_no,$location,$start_time,$end_time,$total_people)
  {
    $Meeting_data = array(
        'user_ID' => $uid,
        'meeting_name' => $meeting_name,
        'class_no' => $class_no,
        'location' => $location,
        'start_time' => $start_time,
        'end_time' => $start_time,
        'total_people' => $total_people,
        'status' => '0',
    );
    if($uid && $meeting_name && $class_no && $location && $start_time && $end_time && $total_people)
    {
      return $this->db->insert('Meeting', $Meeting_data);
    }
  }

}
?>
