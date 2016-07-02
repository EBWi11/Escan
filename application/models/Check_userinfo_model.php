<?php
class Check_userinfo_model extends CI_Model
{

  public function __construct()
  {
    $this->load->database();
  }

  public function check_userinfo($uid)
  {
    $query = $this->db->query("select number from UsersInfo where user_ID = '".$uid."';");
    $row = $query->row();
    foreach ($query->result_array(1) as $row)
    {
      /*if($row['user_ID'])
      {
        return '1';
      }
      else
      {
        return '0';
      }*/
      return $row['number'];
    }

  }

}
?>
