<?php

  class ValidateLogin{   

    function validarLogin(){
    	
    	  if(!isset($_SESSION)){
          session_start();
        }

        if(isset($_SESSION['userlogin'])){
          echo '1';
        }
        else{
          echo '0';
        }

    }  


  }  
  

?>