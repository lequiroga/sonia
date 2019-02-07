<?php

  // Conexión a la base de datos
  require_once('ConectaBD.php');

  class InmobiliariaSQL{       

  	private $conn;
  	private $dbConn;

    //Constructor de la clase que instancia la conexión a la base de datos
  	function InmobiliariaSQL(){
      $conn   = new ConectaBD();
	    $dbConn = $conn->conectarBD();   
	    unset($this->conn);
  	}    

    //Función para consultar la información de la inmobiliaria
    function getInformacionInmobiliaria(){

      if(!isset($_SESSION)){
        session_start();
      }

      $id_inmobiliaria = $_SESSION['id_inmobiliaria']; 

      $query = "SELECT
                  id_inmobiliaria AS id_inmobiliaria,
                  nombre_razon_social AS nombre_razon_social,
                  imagen_logo AS imagen_logo
                FROM
                  rrhh.tb_inmobiliaria
                WHERE
                  id_inmobiliaria = $id_inmobiliaria                     
               ";

      $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
      $row = pg_fetch_array($result, null);

      $output['datosInmobiliaria']['id_inmobiliaria'] = $row['id_inmobiliaria'];
      $output['datosInmobiliaria']['nombre_razon_social']  = $row['nombre_razon_social'];
      $output['datosInmobiliaria']['imagen_logo']  = $row['imagen_logo'];

      return $output;         

    }
    
    //Función para actualizar la información de la inmobiliaria
    function guardarInformacionInmobiliaria($datos_inmobiliaria){

      if(!isset($_SESSION)){
        session_start();
      }

      $id_inmobiliaria = $_SESSION['id_inmobiliaria'];

      $nombre_razon_social = $datos_inmobiliaria->nombre_razon_social;       

      $query = "UPDATE
                  rrhh.tb_inmobiliaria
                SET  
                  nombre_razon_social = '$nombre_razon_social'
                WHERE
                  id_inmobiliaria = $id_inmobiliaria                     
               ";

      $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
      $row = pg_fetch_array($result, null);

      $row['id_inmobiliaria'] = $id_inmobiliaria;
      $row['respuesta'] = '1';

      return $row;

    }

    function guardarImagenInmobiliaria($id_inmobiliaria,$urlImagen){      

      $query = "UPDATE 
                  rrhh.tb_inmobiliaria
                SET
                  imagen_logo = '$urlImagen'   
                WHERE
                  id_inmobiliaria=$id_inmobiliaria";

      $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
    
    }
    
  }

?>