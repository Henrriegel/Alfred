<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use RestServer\RestController;
require APPPATH . '/libraries/RestController.php';
require APPPATH . '/libraries/Format.php';


class Api extends RestController {
  function __construct(){
    parent::__construct();
    $this->load->model('DAO');
  }

  function carrera_get () {
    $this->load->model('DAO');

    $idCarrera = $this->input->get('id_carrera');

    $dataQuery = $this->DAO->read('tb_carrera', "id_carrera", $idCarrera, FALSE);

    if($dataQuery['errorMessage'] == NULL){
      $getData = array(
        "errorMessage" => "",
        "data" => $dataQuery['data']
      );
    } else {
      $getData = array(
        "errorMessage" => "Error reading the data",
        "data" => $dataQuery['data']
      );
    }

    $this->response($getData, 200);
  }

  function carrera_post () {

    $this->load->model('DAO');

    $this->form_validation->set_data($this->post());
    $this->form_validation->set_rules('nombre_carrera', 'Name', 'required');

    if ( $this->form_validation->run() ) {

      $data = array(
        "id_carrera" => null,
        "nombre_carrera" => $this->post('nombre_carrera')
      );

      $dataQuery = $this->DAO->create('tb_carrera', $data);

      if($dataQuery['errorMessage'] == NULL){
        $postData = array(
          "errorMessage" => "",
          //Guarda el id insertado
          "data" => $dataQuery['data']
        );
      } else {
        $postData = array(
          "errorMessage" => "Error while registering",
          "data" => array()
        );
      }

    } else {

      $postData = array(
        "errorMessage" => $this->form_validation->error_array(),
        "data" => array()
      );
    }

    $this->response($postData, 200);
  }

  function carrera_put () {
    $this->load->model('DAO');

    $idCarrera = $this->input->get('id_carrera');

    $data = array(
      'nombre_carrera' => $this->put('nombre_carrera')
    );

    if(!empty($idCarrera)){
      $dataQuery = $this->DAO->update('tb_carrera', $idCarrera, $data, "id_carrera");

      if($dataQuery['errorMessage'] == NULL){
        $putData = array(
          "errorMessage" => "",
          "data" => "Update Completed"
        );
      } else {
        $putData = array(
          "errorMessage" => "Error while updating",
          "data" => $dataQuery['data']
        );
      }
    } else {
      $putData = array(
        "errorMessage" => "Id needed",
        "data" => ""
      );
    }

    $this->response($putData, 200);
  }

  function carrera_delete () {
    $this->load->model('DAO');

    $idCarrera = $this->input->get('id_carrera');

    if(!empty($idCarrera)){
      $dataQuery = $this->DAO->delete('tb_carrera', "id_carrera", $idCarrera);

      if($dataQuery['errorMessage'] == NULL){
        $deleteData = array(
          "errorMessage" => "",
          "data" => "Career deleted"
        );
      } else {
        $deleteData = array(
          "errorMessage" => "Error while deleting",
          "data" => $dataQuery['data']
        );
      }
    } else {
      $deleteData = array(
        "errorMessage" => "Id needed",
        "data" => ""
      );
    }

    $this->response($deleteData, 200);
  }

}
