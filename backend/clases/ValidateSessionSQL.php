<?php

  // Conexión a la base de datos
  require_once("ConectaBD.php");
  require_once("AutenticaAPI.php");

  class ValidateSessionSQL{       

  	private $conn;
  	private $dbConn;

  	function ValidateSessionSQL(){
      $conn   = new ConectaBD();
	    $dbConn = $conn->conectarBD();   
	    unset($this->conn);
  	}

    function insertarDatosSession($id_user_app,$fecha_acceso,$datos_host_remoto){

      $datos_remoto = json_encode($datos_host_remoto); 

      if(!isset($_SESSION)){
          session_start();
      }      
      //print_r($datos_remoto1);exit;
      
      $query = "INSERT INTO 
                  session.tb_users_access
                  (
                    id_user_app,
                    fecha_acceso,
                    datos_host_remoto
                  )
                VALUES
                  (
                    $id_user_app,
                    '$fecha_acceso',
                    '$datos_remoto'
                  )  
               ";
       //print_r($query);exit;    
      $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());         

    }

    function validarSession($user,$password){         

        if(!isset($_SESSION)){
          session_start();
        }              			

		    $output = array();

        $query = "SELECT                     
                    a.id_user AS id_user,
                    aa.id_inmobiliaria AS id_inmobiliaria,
                    b.imagen_logo AS logo_empresa,
                    a.id_user_app_externo AS id_user_app_externo,
                    b.nombre_razon_social AS nombre_inmobiliaria,
                    aa.nombres||' '||aa.apellidos AS nombre_asesor,
                    aa.photo AS foto_asesor
                  FROM 
                    session.tb_users_app a
                    INNER JOIN rrhh.tb_personal aa ON aa.id_personal = a.id_personal
                    LEFT JOIN rrhh.tb_inmobiliaria b ON aa.id_inmobiliaria = b.id_inmobiliaria 
                  WHERE 
                    UPPER(a.userlogin)=UPPER('$user')
                    AND a.password=MD5('$password')
                    AND a.estado=1
                  ";


        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        $row = pg_fetch_array($result, null);

        if(isset($row['id_user'])){

          $output['respuesta']='1';
          $output['logo_empresa']=$row['logo_empresa'];
          $output['nombre_asesor']=$row['nombre_asesor'];
          $output['foto_asesor']=$row['foto_asesor'];
          $output['nombre_inmobiliaria']=$row['nombre_inmobiliaria'];  

          date_default_timezone_set('America/Bogota');
          $fecha_acceso = date("Y-m-d H:i");     

          $output['fecha_acceso']=$fecha_acceso;     

          $autAPI   = new AutenticaAPI();
          $datosAPI = $autAPI->retornarDatosAPI('ip-api','datos_remoto');
          //$data =  file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"]);   
                  
          //$data->HTTP_USER_AGENT = $_SERVER['HTTP_USER_AGENT'];  
          /*$data =  json_decode($data);
          $datosAPI = $autAPI->retornarDatosAPI('ip-api-dns','dns_remoto');
          $data1 = json_decode(file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"]));              
          $data->dns = $data1->dns;*/

          $datos_host_remoto['ip_remota']=$_SERVER['REMOTE_ADDR'];     
          $datos_host_remoto['HTTP_USER_AGENT']=$_SERVER['HTTP_USER_AGENT'];  
          $datos_host_remoto['HTTP_REFERER']=$_SERVER['HTTP_REFERER'];   
          $datos_host_remoto['HTTP_COOKIE']=$_SERVER['HTTP_COOKIE'];  
         
          $this->insertarDatosSession($row['id_user'],$fecha_acceso,$datos_host_remoto);

          $_SESSION['userlogin']=$user;
          $_SESSION['id_user']=$row['id_user'];
          $_SESSION['id_inmobiliaria']=$row['id_inmobiliaria'];
          $_SESSION['logo_empresa']=$row['logo_empresa'];
          $_SESSION['id_user_app_externo']=$row['id_user_app_externo'];  
          $_SESSION['nombre_inmobiliaria']=$row['nombre_inmobiliaria'];  
          $_SESSION['nombre_asesor']=$row['nombre_asesor'];
          $_SESSION['foto_asesor']=$row['foto_asesor'];  
          $_SESSION['fecha_acceso']=$fecha_acceso; 
               
          //echo '1'; 
        }
        else{
          session_destroy();
          $output['respuesta']='0'; 
          /*unset($_SESSION['userlogin']);
          unset($_SESSION['id_user']);
          unset($_SESSION['id_inmobiliaria']);
          unset($_SESSION['id_user_app_externo']);
          unset($_SESSION['nombre_inmobiliaria']);
          unset($_SESSION['nombre_asesor']);
          unset($_SESSION['foto_asesor']);*/  
          //echo '0'; 
        }

        pg_free_result($result);        

		return $output;       

    }   

    function CloseSession($id_user,$fecha_acceso){

        $query = "UPDATE
                    session.tb_users_access
                  SET
                    fecha_salida = CURRENT_TIMESTAMP  
                  WHERE
                    id_user_app = $id_user 
                    AND fecha_acceso = '$fecha_acceso'
                 ";

        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());         

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