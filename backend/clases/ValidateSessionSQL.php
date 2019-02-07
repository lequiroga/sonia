<?php

  // Conexión a la base de datos
  require_once('ConectaBD.php');

  class ValidateSessionSQL{       

  	private $conn;
  	private $dbConn;

  	function ValidateSessionSQL(){
      $conn   = new ConectaBD();
	  $dbConn = $conn->conectarBD();   
	  unset($this->conn);
  	}

    function validarSession($user,$password){         

        if(!isset($_SESSION)){
          session_start();
        }              			

		$output = array();

        $query = "SELECT                     
                    id_user AS id_user
                  FROM 
                    session.tb_users_app
                  WHERE 
                    UPPER(userlogin)=UPPER('$user')
                    AND password=MD5('$password')
                    AND estado=1
                  ";

        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        $row = pg_fetch_array($result, null);

        if(isset($row['id_user'])){
          $output['respuesta']='1';
          $_SESSION['userlogin']=$user;
          $_SESSION['id_user']=$row['id_user'];
          //echo '1'; 
        }
        else{
          $output['respuesta']='0'; 
          unset($_SESSION['userlogin']);
          unset($_SESSION['id_user']);
          //echo '0'; 
        }

        pg_free_result($result);        

		return $output;       

    }   

    function ResetPassword($password_act,$password_new){        

        if(!isset($_SESSION)){
          session_start();
        }

        $user=$_SESSION['userlogin'];        

        $output = array();

        $query = "SELECT 
                    COUNT(*) AS cant_users
                  FROM 
                    session.tb_users_app
                  WHERE 
                    UPPER(userlogin)=UPPER('$user')
                    AND password=MD5('$password_act')
                    AND estado=1";

        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        $row = pg_fetch_array($result, null);

        if($row['cant_users']==1){
          $output['respuesta']='1';   

          $query = "UPDATE
                      session.tb_users_app
                    SET 
                      password=MD5('$password_new')
                    WHERE
                      UPPER(userlogin)=UPPER('$user')
                      AND password=MD5('$password_act')
                      AND estado=1";

          pg_query($query) or die('La consulta fallo: ' . pg_last_error());
          //echo '1'; 
        }
        else{
          $output['respuesta']='0';           
          //echo '0'; 
        }

        session_destroy();

        pg_free_result($result);

        return $output;               

    } 

  }

?>