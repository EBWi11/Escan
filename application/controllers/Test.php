<?php
class Test extends CI_Controller {
  public function index()
  {
      $this->load->model('GPS_precision_calculate_model');
      $this->GPS_precision_calculate_model->precision_calculate("1","",1);
  }
}
