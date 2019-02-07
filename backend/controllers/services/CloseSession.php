<?php

  class CloseSession{   

    function CloseSession(){
    	
    	  if(!isset($_SESSION)){
          session_start();
        }

        session_destroy();

    }  


  }  
  

?>