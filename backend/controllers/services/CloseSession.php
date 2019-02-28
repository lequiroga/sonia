<?php

  require_once("../clases/ValidateSessionSQL.php");

  class CloseSession{   

    private $validateSession;

    function CloseSession(){

    	if(!isset($_SESSION)){
          session_start();
        }        
    	
    	$this->validateSession = new ValidateSessionSQL();
    	$this->validateSession->CloseSession($_SESSION['id_user'],$_SESSION['fecha_acceso']);    	

        session_destroy();

    }  


  }  
  

?>