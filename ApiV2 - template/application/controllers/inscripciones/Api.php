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

  function inscripcion_get () {
    $this->load->model('DAO');

    //Filtrar entre alumno y cursos

    
    $idSesion = $this->input->get('id_inscripcion');
    $dataQuery = $this->DAO->read('tb_inscripcion', "id_sesion", $idSesion, False);

    if($dataQuery['errorMessage'] == ""){
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

  function isRegistered_get () {
    $this->load->model('DAO');

    $idCurso = $this->input->get('fk_curso');
    $idAlumno = $this->input->get('fk_alumno');
    $dataQuery = $this->DAO->readEnrolls('tb_inscripcion', array(
      "fk_curso" => $idCurso,
      "fk_alumno" => $idAlumno
    ));

    if($dataQuery['errorMessage'] == ""){
      $getData = array(
        "errorMessage" => "",
        "data" => $dataQuery['data']
      );
    } else {
      $getData = array(
        "errorMessage" => "Error while getting",
        "data" => ""
      );
    }

    $this->response($getData, 200);
  }

  function inscripcion_post () {

    $this->load->model('DAO');

    $this->form_validation->set_data($this->post());
    $this->form_validation->set_rules('fk_curso', 'Course', 'required');
    $this->form_validation->set_rules('fk_alumno', 'User', 'required');
    $this->form_validation->set_rules('nombre_curso', 'Nombre', 'required');
    $this->form_validation->set_rules('descripcion_curso', 'Descripcion', 'required');

    if ( $this->form_validation->run() ) {

      $data = array(
        "fk_curso" => $this->post('fk_curso'),
        "fk_alumno" => $this->post('fk_alumno'),
        "nombre_curso" => $this->post('nombre_curso'),
        "descripcion_curso" => $this->post('descripcion_curso')
      );

      $dataQuery = $this->DAO->create('tb_inscripcion', $data);

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

  function inscripcion_put () {
    $this->load->model('DAO');

    $idInscripcion = $this->input->get('id_inscripcion');

    $data = array(
      'validado' => $this->put('validado')
    );

    if(!empty($idInscripcion)){
      $dataQuery = $this->DAO->update('tb_inscripcion', $idInscripcion, $data, "id_inscripcion");

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

  function inscripcion_delete () {
    $this->load->model('DAO');

    $idInscripcion = $this->input->get('id_inscripcion');

    if(!empty($idInscripcion)){
      $dataQuery = $this->DAO->delete('tb_inscripcion', "id_inscripcion", $idInscripcion);

      if($dataQuery['errorMessage'] == NULL){
        $deleteData = array(
          "errorMessage" => "",
          "data" => "Data deleted"
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
