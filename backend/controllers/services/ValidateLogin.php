<?php

  class ValidateLogin{   

    function validarLogin(){
    	
    	  if(!isset($_SESSION)){
          session_start();
        }

        if(isset($_SESSION['userlogin'])){
          $output['respuesta'] = '1';
          $output['logo_empresa'] = $_SESSION['logo_empresa'];
          $output['nombre_asesor'] = $_SESSION['nombre_asesor'];
          $output['foto_asesor'] = $_SESSION['foto_asesor'];
          $output['nombre_inmobiliaria'] = $_SESSION['nombre_inmobiliaria'];
          $output['fecha_acceso'] = $_SESSION['fecha_acceso'];
          //echo '1';
          echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
        }
        else{
          $output['respuesta'] = '0';
          echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
        }

    }  


  }  
  

?>