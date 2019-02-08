<?php

  class ValidateLogin{   

    function validarLogin(){
    	
    	  if(!isset($_SESSION)){
          session_start();
        }

        if(isset($_SESSION['userlogin'])){
          $output['respuesta'] = '1';
          $output['logo_empresa'] = $_SESSION['logo_empresa'];
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