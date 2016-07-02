<?php
class Register_model extends CI_Model
{

  public function __construct()
  {
    $this->load->database();
  }

  public function signal_check($type,$str1,$str2)
  {
      if(isset($type))
      {
        switch($type)
        {

          case 'phone_number':
          {
              if($str1!=null)
              {
                if(strlen($str1)==11&&preg_match("/^1[34578]\d{9}$/",$str1))
                {
                  if($query = $this->db->query("select user_ID from Users where phone_number= '" . $str1 ."';"))
                  {
                    return "phone_number is reply";
                    break;
                  }
                  else {
                    return "right";
                    break;
                  }
                }
              else
              {
                return "please input right phone_number";
                break;
              }

            }
            else
            {
              return "phone_number is null";
              break;
            }
          }

          case 'email':
          {
            if($str1!=null)
            {
                if(preg_match("/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/",$str1))
                {

                  if($query = $this->db->query("select user_ID from Users where email= '" . $str1 ."';"))
                  {
                    return "email is reply";
                    break;
                  }
                  else {
                    return "right";
                    break;
                  }
                }
                else
                {
                  return "please input reight email";
                  break;
                }
            }
            else
            {
              return "email is null";
              break;
            }
          }

          case 'name':
          {
            if($str1!=null)
            {
              if(preg_match("/^[\x{4e00}-\x{9fa5}]+$/u",$str1))
              {
                return "right";
                break;
              }
              else
              {
                return "please your real name";
                break;
              }
            }
            else
            {
              return "name is null";
              break;
            }
          }

          case 'sex':
          {
            if($str1!=null)
            {
              return "right";
              break;
            }
            else
            {
              return "sex is null";
              break;
            }
          }

          case 'password':
          {
            if($str1!=null)
            {
                if(strlen($str1)<8)
                {
                  return "pleas input a stronger password";
                  break;
                }
                else
                {
                  return "right";
                  break;
                }
            }
            else
            {
              return "password is null";
              break;
            }
          }

          case 'password_rep':
          {
            if($str1!=null&&$str2!=null)
            {
                if($str1==$str2)
                {
                  return "right";
                  break;
                }
                else
                {
                  return "twice password was not equal";
                  break;
                }
            }
            else
            {
              return "password or password_rep is null";
              break;
            }
          }

          case 'phone_id':
          {
            if(isset($str1))
            {
              if($query = $this->db->query("select user_ID from Users where phone_id= '" . $str1 ."';"))
              {
                return "phone_id is reply";
                break;
              }
              else {
                return "right";
                break;
              }
            }
            else
            {
              return "phone_id is null";
              break;
            }
          }

        }
      }
  }

  public function register($phone_number, $email, $name, $sex, $password,$password_rep, $phone_id)
  {
    $user_data = array(

        'phone_number' => $phone_number,
        'email' => $email,
        'name' => $name,
        'sex' => $sex,
        'password' => $password,
        'phone_id' => $phone_id,
    );

    if(self::signal_check("phone_number",$phone_number,0)=="right" && self::signal_check("email",$email,0)=="right" && self::signal_check("name",$name,0)=="right" && self::signal_check("sex",$sex,0)=="right" && self::signal_check("password",$password,0)=="right" && self::signal_check("password_rep",$password,$password_rep)=="right") //&& self::signal_check("phone_id",$phone_id,0)=="right")
    {
      return $this->db->insert('Users', $user_data);
    }
    else
    {
      return "error!";
    }
  }
}
?>
