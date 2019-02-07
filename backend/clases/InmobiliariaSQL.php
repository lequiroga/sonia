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
                  imagen_logo AS imagen_logo,
                  telefono AS telefono,
                  correo_electronico AS correo_electronico,
                  direccion AS direccion,
                  id_pais AS id_pais,
                  id_departamento AS id_departamento,
                  id_ciudad AS id_ciudad
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
      $output['datosInmobiliaria']['telefono'] = $row['telefono'];
      $output['datosInmobiliaria']['correo_electronico']  = $row['correo_electronico'];
      $output['datosInmobiliaria']['direccion']  = $row['direccion'];
      $output['datosInmobiliaria']['id_pais'] = $row['id_pais'];
      $output['datosInmobiliaria']['id_departamento']  = $row['id_departamento'];
      $output['datosInmobiliaria']['id_ciudad']  = $row['id_ciudad'];

      return $output;         

    }
    
    //Función para actualizar la información de la inmobiliaria
    function guardarInformacionInmobiliaria($datos_inmobiliaria){

      if(!isset($_SESSION)){
        session_start();
      }

      $id_inmobiliaria = $_SESSION['id_inmobiliaria'];

      $nombre_razon_social = $datos_inmobiliaria->nombre_razon_social;       

      $telefono = $datos_inmobiliaria->telefono;  
      $direccion = $datos_inmobiliaria->direccion;
      $id_pais = $datos_inmobiliaria->id_pais;
      $id_departamento = $datos_inmobiliaria->id_departamento;
      $id_ciudad = $datos_inmobiliaria->id_ciudad;         

      $query = "UPDATE
                  rrhh.tb_inmobiliaria
                SET  
                  nombre_razon_social = UPPER('$nombre_razon_social')";

      if(isset($datos_inmobiliaria->correo_electronico)) 
        $query .=",correo_electronico = UPPER('".$datos_inmobiliaria->correo_electronico."')";    

      if(isset($datos_inmobiliaria->telefono)) 
        $query .=",telefono = '".$datos_inmobiliaria->telefono."'";  

      if(isset($datos_inmobiliaria->direccion)) 
        $query .=",direccion = UPPER('".$datos_inmobiliaria->direccion."')";                      

      if(isset($datos_inmobiliaria->id_ciudad)) 
        $query .=",id_ciudad = '".$datos_inmobiliaria->id_ciudad."'";

      if(isset($datos_inmobiliaria->id_departamento)) 
        $query .=",id_departamento = '".$datos_inmobiliaria->id_departamento."'";

      if(isset($datos_inmobiliaria->id_pais)) 
        $query .=",id_pais = '".$datos_inmobiliaria->id_pais."'"; 

      $query .= " WHERE
                  id_inmobiliaria = $id_inmobiliaria";            

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