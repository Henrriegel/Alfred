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

  function sesion_get () {
    $this->load->model('DAO');

    $idSesion = $this->input->get('id_sesion');

    $dataQuery = $this->DAO->read('tb_sesion', "id_sesion", $idSesion, FALSE);

    if($dataQuery['errorMessage'] == NULL){
      $putData = array(
        "errorMessage" => "",
        "data" => $dataQuery['data']
      );
    } else {
      $putData = array(
        "errorMessage" => "Error while updating",
        "data" => $dataQuery['data']
      );
    }

    $this->response($putData, 200);
  }

  function sesion_post () {

    $this->load->model('DAO');

    $this->form_validation->set_data($this->post());
    $this->form_validation->set_rules('fk_persona', 'User', 'required');
    $this->form_validation->set_rules('origen', 'Origin', 'required');
    $this->form_validation->set_rules('estado', 'Status', 'required');

    if ( $this->form_validation->run() ) {

      $data = array(
        "id_sesion" => null,
        "fk_persona" => $this->post('fk_persona'),
        "origen" => $this->post('origen'),
        "estado" => $this->post('estado')
      );

      $dataQuery = $this->DAO->create('tb_sesion', $data);

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

  function sesion_put () {
    $this->load->model('DAO');

    $idSesion = $this->input->get('id_sesion');

    $data = array(
      'estado' => $this->put('estado')
    );

    if(!empty($idSesion)){
      $dataQuery = $this->DAO->update('tb_sesion', $idSesion, $data, "id_sesion");

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

  function sesion_delete () {
    $this->load->model('DAO');

    $idSesion = $this->input->get('id_sesion');

    if(!empty($idSesion)){
      $dataQuery = $this->DAO->delete('tb_sesion', "id_sesion", $idSesion);

      if($dataQuery['errorMessage'] == NULL){
        $deleteData = array(
          "errorMessage" => "",
          "data" => "Session deleted"
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
