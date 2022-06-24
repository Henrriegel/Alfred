<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DAO extends CI_Model {

  //Función para registrar datos en una tabla
  function create($dbTable, $rowData = array()){

    $this->db->insert($dbTable, $rowData);
    
    if($this->db->error()['message']!=""){
      //Si algo sale mal arroja un mensaje de error
      return array(
        "errorMessage" => $this->db->error()['message'],
        "data" => $this->db->error()['code']
      );
    } else {
      //Si se registró bien devuelve el id del registro
      return array(
        "errorMessage" => NULL,
        "data" => $this->db->insert_id()
      );
    }
  }

  //Función para obtener uno o más datos de una tabla
  function read($dbTable, $idName, $idFilter, $oneResult = FALSE){
    
    //Si queremos leer solo un registro
    if($idFilter){
      $this->db->where($idName, $idFilter);
    }
    $queryData = $this->db->get($dbTable);

    if($this->db->error()['message']!=""){
      //Si algo sale mal arroja un mensaje de error
      return array(
        "errorMessage" => $this->db->error()['message'],
        "data" => $this->db->error()['code']
      );
    } else {
      //Si se leyó bien devuelve el/los registros leidos
      return array(
        "errorMessage" => NULL,
        "data" => $oneResult ?  $queryData->row() : $queryData->result()
      );
    }
  }

  function readEnrolls($table, $filter = array()){
    $this->db->where($filter);
    $query = $this->db->get($table);
    if($this->db->error()['message']==''){
      return array(
        "errorMessage" => NULL,
        "data" => $query->row()
      );
    } else {
      return array(
        "errorMessage" => "Tas mal",
        "data" => NULL
      );
    }
  }

  //Función para actualizar datos de un registro
  function update($dbTable, $idFilter, $rowData, $idName){

    $this->db->where($idName, $idFilter);
    $this->db->update($dbTable, $rowData);
    
    if($this->db->error()['message']!=""){
      //Si algo sale mal arroja un mensaje de error
      return array(
        "errorMessage" => $this->db->error()['message'],
        "data" => $this->db->error()['code']
      );
    } else {
      return array(
        "errorMessage" => NULL,
        "data" => $rowData
      );
    }
  }

  //Función para borrar datos de un registro
  function delete($dbTable, $idName = "", $idFilter = null){

    if($idFilter OR $idName != ""){
      
      $this->db->where($idName, $idFilter);
      $this->db->delete($dbTable);

      if($this->db->error()['message']!=""){
        //Si algo sale mal arroja un mensaje de error
        return array(
          "errorMessage" => $this->db->error()['message'],
          "data" => $this->db->error()['code']
        );
      } else {
        return array(
          "errorMessage" => NULL,
          "data" => "Data deleted"
        );
      }
    
    } else {
      return array(
        "errorMessage" => "You must send an Id with its field name",
        "data" => $this->db->error()['code']
      );
    }
  }

}
