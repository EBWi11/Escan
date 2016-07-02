<?php
class Verify_user_model extends CI_Model
{

  public function __construct()
  {
    $this->load->database();
  }

  public function verify_user($uid,$phone_id)
  {
    $query = $this->db->query("select phone_id from Users where user_ID = '".$uid."';");

    $row = $query->row();
    foreach ($query->result_array(1) as $row)
    {
      if($row['phone_id'] == $phone_id)
      {
        return '1';
      }
      else
      {
        return 0;
      }
    }
  }
}
?>
