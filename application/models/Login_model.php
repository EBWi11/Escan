<?php
class Login_model extends CI_Model
{

  public function __construct()
  {
    $this->load->database();
  }

  public function login($phone_number,$password,$captcha)
  {
      $query = $this->db->query("select password from Users where phone_number = '".$phone_number."';");

      $row = $query->row();
      foreach ($query->result_array(1) as $row)
      {
        if($row['password']==md5($password))
        {
          return 1;
        }
        else
        {
          return 2;
        }
      }

  }

}
?>
