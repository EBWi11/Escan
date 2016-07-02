<?php
class Search_uid_model extends CI_Model
{

  public function __construct()
  {
    $this->load->database();
  }

  public function search_uid($phone_number)
  {
    $query = $this->db->query("select user_ID from Users where phone_number = '".$phone_number."';");

    $row = $query->row();
    foreach ($query->result_array(1) as $row)
    {
      return $row['user_ID'];
    }

  }

}
