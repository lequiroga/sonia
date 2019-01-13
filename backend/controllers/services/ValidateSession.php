<?php

    require_once("../clases/ValidateSessionSQL.php");

    class ValidateSession{

      private $validateSession;

      function ValidateSession(){
        $this->validateSession = new ValidateSessionSQL();
      } 

      function validarSession($user,$password){                 

        $output = $this->validateSession->validarSession($user,$password); 
        echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);        

      }

      function resetPassword($password_act,$password_new){                 

        $output = $this->validateSession->ResetPassword($password_act,$password_new); 
        echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);        

      }

    }
	      	

?>
