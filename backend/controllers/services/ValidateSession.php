<?php

    require_once("../clases/ValidateSessionSQL.php");    

    class ValidateSession{

      private $validateSession;

      function ValidateSession(){
        $this->validateSession = new ValidateSessionSQL();
      } 

      function validarSession($user,$password){        

        /*$autAPI   = new AutenticaAPI();
        $datosAPI = $autAPI->retornarDatosAPI('ip-api','datos_remoto');

        $data = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"]), true );
        $data['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];  

        $datosAPI = $autAPI->retornarDatosAPI('ip-api-dns','dns_remoto');
        $data1 = json_decode(file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"]), true );              
        $data['dns'] = $data1['dns'];

        $datos_acceso = json_encode($data, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);   */      
        
        $output = $this->validateSession->validarSession($user,$password); 
        echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);        

      }

      function resetPassword($password_act,$password_new){                 

        $output = $this->validateSession->ResetPassword($password_act,$password_new); 
        echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);        

      }

    }
	      	

?>
