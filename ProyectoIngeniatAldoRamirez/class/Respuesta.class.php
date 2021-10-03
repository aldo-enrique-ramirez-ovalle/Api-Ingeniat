<?php
namespace Respuesta;
use ConexionBaseDeDatos\ConexionBaseDeDatos;
     class Respuesta extends ConexionBaseDeDatos{
          private $Codigo =1, $Respuesta, $Registros, $mensaje = '';
          public function __construct(){
               parent::__construct();
          }
          function setRespuesta($Respuesta) {  
               $this->Respuesta = $Respuesta; 
          } 
          function getRespuesta() { 
                return $this->Respuesta; 
          }    
          function setCodigo($Codigo)
          {
               $this->Codigo = $Codigo;
          } 
          function getCodigo()
          {
               return $this->Codigo;
          }
          function setRegistros($Registros) {  
               $this->Registros = $Registros; 
          }         
          function getRegistros() { 
                    return $this->Registros; 
          }      
          function setSMessage($mensaje) {  
               $this->mensaje = $mensaje; 
          }
          function getSMessage() { 
               return $this->mensaje; 
          }
     }
?>