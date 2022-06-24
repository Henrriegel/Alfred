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

  function persona_get () {
    $this->load->model('DAO');

    $idPersona = $this->input->get('id_persona');

    if($idPersona){
      $dataQuery = $this->DAO->read('tb_persona', "id_persona", $idPersona, TRUE);
    } else {
      $dataQuery = $this->DAO->read('tb_persona', "id_persona", $idPersona, FALSE);
    }

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

  function persona_post () {

    $this->load->model('DAO');

    $this->form_validation->set_data($this->post());
    $this->form_validation->set_rules('correo_persona', 'Email', 'required|is_unique[tb_persona.correo_persona]');
    $this->form_validation->set_rules('nombre_persona', 'Name', 'required');
    $this->form_validation->set_rules('contrasenia_persona', 'Password', 'required');
    $this->form_validation->set_rules('telefono_persona', 'Phone', 'required');
    $this->form_validation->set_rules('rol_persona', 'Rol', 'required');

    if ( $this->form_validation->run() ) {

      $data = array(
        "id_persona" => null,
        "correo_persona" => $this->post('correo_persona'),
        "nombre_persona" => $this->post('nombre_persona'),
        "contrasenia_persona" => $this->post('contrasenia_persona'),
        "telefono_persona" => $this->post('telefono_persona'),
        "rol_persona" => $this->post('rol_persona')
      );

      $dataQuery = $this->DAO->create('tb_persona', $data);

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

  function persona_put () {
    $this->load->model('DAO');

    $idPersona = $this->input->get('id_persona');

    $data = array(
      'correo_persona' => $this->put('correo_persona'),
      'nombre_persona' => $this->put('nombre_persona'),
      'contrasenia_persona' => $this->put('contrasenia_persona'),
      'telefono_persona' => $this->put('telefono_persona'),
      'rol_persona' => $this->put('rol_persona')
    );

    if(!empty($idPersona)){
      $dataQuery = $this->DAO->update('tb_persona', $idPersona, $data, "id_persona");

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

  function persona_delete () {
    $this->load->model('DAO');

    $idPersona = $this->input->get('id_persona');

    if(!empty($idPersona)){
      $dataQuery = $this->DAO->delete('tb_persona', "id_persona", $idPersona);

      if($dataQuery['errorMessage'] == NULL){
        $deleteData = array(
          "errorMessage" => "",
          "data" => "User deleted"
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

  function personaCursos_delete () {
    $this->load->model('DAO');

    $idPersona = $this->input->get('id_persona');

    if(!empty($idPersona)){
      $dataQuery = $this->DAO->delete('tb_curso', "fk_profesor", $idPersona);

      if($dataQuery['errorMessage'] == NULL){
        $deleteData = array(
          "errorMessage" => "",
          "data" => "User deleted"
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

  function login_post(){
    //Se crea un form validation para asegurarse de que el usuario mande los datos correctos al iniciar sesión
    $this->form_validation->set_data( $this->post() ? $this->post() : array());
    $this->form_validation->set_rules( "correo_persona", "Email", "required");
    $this->form_validation->set_rules( "contrasenia_persona", "Password", "required");

    if($this->form_validation->run()){
      $filter = $this->post('correo_persona');

      //Se realiza un read para verificar qque el usuario esté registrado en la base de datos
      $user_exists = $this->DAO->read( 'tb_persona', "correo_persona", $filter, TRUE);

      if($user_exists['errorMessage'] == NULL){
        if($this->post('contrasenia_persona') == $user_exists['data']->contrasenia_persona){
          
          $sessionData = array(
            "id_persona" => $user_exists['data']->id_persona,
            "nombre_persona" => $user_exists['data']->nombre_persona,
            "correo_persona" => $user_exists['data']->correo_persona,
            "rol_persona" => $user_exists['data']->rol_persona
          );

          $loginData = array(
            "errorMessage" => array(),
            "data" => $sessionData
          );

        }else{
          $loginData = array(
            "errorMessage" => "Wrong password",
            "data" => array()
          );
        }
      }else{
        $loginData = array(
          "errorMessage" => "Wrong email",
          "data" => array()
        );
      }
    }else{
      $respuesta = array(
        "data" => "Incomplete formulary",
        "errorMessage" =>$this->form_validation->error_array()
      );
    }
    $this->response($loginData,200);
  }

}
