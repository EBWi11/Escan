<?php
class Check_user_phone_model extends CI_Model
{

  public function __construct()
  {
    $this->load->database();
  }

  public function check_user_phone($phone_id,$uid)
  {
    $query = $this->db->query("select phone_id from Users where user_ID = '".$uid."';");
    foreach ($query->result_array() as $row)
    {
      if($row['phone_id'] == $phone_id)
      {
        return 1;
      }
      else {
        return 0;
      }
    }
  }
}
?>
