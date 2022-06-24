<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use RestServer\RestController;
require APPPATH . '/libraries/RestController.php';
require APPPATH . '/libraries/Format.php';


class Home extends RestController {
  function __construct(){
    parent::__construct();
  }
  public function index_get(){
    $this->response(
      array(
        "status" => 1,
        "mensaje" => "Consulta de servicios sw17"
      ),
      200
    );
  }


}
