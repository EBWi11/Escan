<?php
class Change_password_model extends CI_Model
{

  public function __construct()
  {
    $this->load->database();
  }

  public function change_password($uid,$old_pwd,$new_pwd)
  {
    $query = $this->db->query("select password from Users where user_ID = '".$uid."';");
    foreach ($query->result_array(1) as $row)
    {
      if($row['password']==md5($old_pwd))
      {
        $this->db->set('password', md5($new_pwd));
        $this->db->where('user_ID', $uid);
        $this->db->update('Users');
        return 1;
      }
      else {
        return 3;
      }
    }
  }
}
?>
