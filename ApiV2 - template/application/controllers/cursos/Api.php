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

  function curso_get () {
    $this->load->model('DAO');

    $idCurso = $this->input->get('id_curso');

    $dataQuery = $this->DAO->read('tb_curso', "id_curso", $idCurso, FALSE);

    if($dataQuery['errorMessage'] == NULL){
      $getData = array(
        "errorMessage" => "",
        "data" => $dataQuery['data']
      );
    } else {
      $getData = array(
        "errorMessage" => "Error while updating",
        "data" => $dataQuery['data']
      );
    }

    $this->response($getData, 200);
  }

  function curso_post () {

    $this->load->model('DAO');

    $this->form_validation->set_data($this->post());
    $this->form_validation->set_rules('fk_profesor', 'Professor', 'required');
    $this->form_validation->set_rules('fk_carrera', 'Career', 'required');
    $this->form_validation->set_rules('descripcion_curso', 'Description', 'required');
    $this->form_validation->set_rules('nombre_curso', 'Name', 'required');

    if ( $this->form_validation->run() ) {

      $data = array(
        "id_curso" => null,
        "nombre_curso" => $this->post('nombre_curso'),
        "fk_profesor" => $this->post('fk_profesor'),
        "fk_carrera" => $this->post('fk_carrera'),
        "descripcion_curso" => $this->post('descripcion_curso')
      );

      $dataQuery = $this->DAO->create('tb_curso', $data);

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

  function curso_put () {
    $this->load->model('DAO');

    $idCurso = $this->input->get('id_curso');

    $data = array(
      'nombre_curso' => $this->put('nombre_curso'),
      'fk_carrera' => $this->put('fk_carrera'),
      'descripcion_curso' => $this->put('descripcion_curso')
    );

    if(!empty($idCurso)){
      $dataQuery = $this->DAO->update('tb_curso', $idCurso, $data, "id_curso");

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

  function curso_delete () {
    $this->load->model('DAO');

    $idCurso = $this->input->get('id_curso');

    if(!empty($idCurso)){
      $dataQuery = $this->DAO->delete('tb_curso', "id_curso", $idCurso);

      if($dataQuery['errorMessage'] == NULL){
        $deleteData = array(
          "errorMessage" => "",
          "data" => "Course deleted"
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
