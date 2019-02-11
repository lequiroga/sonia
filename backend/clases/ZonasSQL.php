<?php

  // Conexión a la base de datos
  require_once('ConectaBD.php');

  class ZonasSQL{       

  	private $conn;
  	private $dbConn;

    //Constructor de la clase que instancia la conexión a la base de datos
  	function ZonasSQL(){
      $conn   = new ConectaBD();
	    $dbConn = $conn->conectarBD();   
	    unset($this->conn);
  	}

    //Consulta la existencia de la relación zona-barrio-ciudad
    function consultarZonasBarrios($id_barrio,$id_ciudad,$id_zona){

      if(!isset($_SESSION)){
        session_start();
      }

      $id_inmobiliaria = $_SESSION['id_inmobiliaria'];

      $query = "SELECT
                  COUNT(*) AS cant_barrio_zona
                FROM  
                  generales.tb_zonas_barrios
                WHERE
                  id_zona=$id_zona
                  AND id_barrio=$id_barrio
                  AND id_ciudad=$id_ciudad
                  AND id_inmobiliaria=$id_inmobiliaria     
               ";

      $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
      $row = pg_fetch_array($result, null);

      return $row['cant_barrio_zona'];

    }

    //Función para consultar la zona a la cual se encuentra asociado un barrio de Cali
    function getZonaBarrioCali($id_barrio){

      if(!isset($_SESSION)){
        session_start();
      }

      $id_inmobiliaria = $_SESSION['id_inmobiliaria'];

      $query = "SELECT
                  id_zona AS id_zona
                FROM
                  generales.tb_zonas_barrios
                WHERE
                  id_barrio = $id_barrio 
                  AND estado='1' 
                  AND id_inmobiliaria=$id_inmobiliaria  
               ";

      $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
      $row = pg_fetch_array($result, null);

      return $row['id_zona'];         

    }

    //Des-asocia la relación entre un barrio y una zona de una ciudad
    function borrarBarrioZona($id_zona_barrio){

      //Se cambia el estado de la relación creada
      $query = "UPDATE
                  generales.tb_zonas_barrios
                SET
                  estado='0'
                WHERE
                  id_zona_barrio=$id_zona_barrio    
               ";

      $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
      
      return '1';

    } 

    //Consulta en la base de datos la información de los barrios asociados a una determinada zona
    function listarBarriosZona($id_zona,$id_ciudad){

      $output = array();
      $output['cant_barrios_zona'] = 0;

      if(!isset($_SESSION)){
        session_start();
      }

      $id_inmobiliaria = $_SESSION['id_inmobiliaria'];
      
      $query = "SELECT
                  id_zona_barrio AS id_zona_barrio,
                  id_zona AS id_zona,
                  id_barrio AS id_barrio,
                  id_ciudad AS id_ciudad
                FROM
                  generales.tb_zonas_barrios
                WHERE
                  estado = '1'
                  AND id_zona = $id_zona  
                  AND id_ciudad = $id_ciudad
                  AND id_inmobiliaria = $id_inmobiliaria
               ";

      $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());     

      if(pg_num_rows($result)>0){

          $i = 0; 
          while($row = pg_fetch_array($result, null)){      
            $output['barrios_zona'][$i]['id_zona_barrio'] = $row['id_zona_barrio'];
            $output['barrios_zona'][$i]['id_zona']  = $row['id_zona'];
            $output['barrios_zona'][$i]['id_barrio']  = $row['id_barrio'];
            $output['barrios_zona'][$i]['id_ciudad']  = $row['id_ciudad'];
            $i++;     
          } 

          $output['cant_barrios_zona'] = $i;

          // Liberando el conjunto de resultados
          pg_free_result($result);          

      }      
      
      return $output;    

    }


    //Guarda en la base de datos la relación de la zona con el barrio
    function guardarClasificacionBarrio($datosClasificacion){

      $id_barrio = $datosClasificacion->id_barrio;
      $id_ciudad = $datosClasificacion->id_ciudad;
      $id_zona = $datosClasificacion->id_sector;

      if(!isset($_SESSION)){
        session_start();
      }

      $id_user = $_SESSION['id_user'];
      $id_inmobiliaria = $_SESSION['id_inmobiliaria'];
      $row = array();      

      if($this->consultarZonasBarrios($id_barrio,$id_ciudad,$id_zona)==0){



        $query = "INSERT INTO
                    generales.tb_zonas_barrios
                    (
                      id_zona,
                      id_barrio,
                      id_user_creacion,
                      fecha_creacion,
                      id_ciudad,
                      estado,
                      id_inmobiliaria
                    )
                  VALUES
                    (
                      $id_zona,
                      $id_barrio,
                      $id_user,
                      CURRENT_TIMESTAMP,
                      $id_ciudad,
                      '1',
                      $id_inmobiliaria
                    ) 
                  RETURNING
                    id_zona_barrio   
                  ";

        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        $row = pg_fetch_array($result, null);
        $row['respuesta'] = '1';

      }
      else{

        $query = "UPDATE
                    generales.tb_zonas_barrios
                  SET
                    estado='0',
                    id_user_modificacion = ".$_SESSION['id_user'].",
                    fecha_modificacion = CURRENT_TIMESTAMP 
                  WHERE
                    id_zona <> $id_zona
                    AND id_ciudad = $id_ciudad
                    AND id_barrio = $id_barrio  
                    AND id_inmobiliaria = $id_inmobiliaria
                 ";

        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());

        $query = "UPDATE
                    generales.tb_zonas_barrios
                  SET
                    estado='1',
                    id_user_modificacion = ".$_SESSION['id_user'].",
                    fecha_modificacion = CURRENT_TIMESTAMP 
                  WHERE
                    id_zona = $id_zona
                    AND id_ciudad = $id_ciudad
                    AND id_barrio = $id_barrio
                    AND id_inmobiliaria = $id_inmobiliaria
                  RETURNING
                    id_zona_barrio  
                 ";

        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        $row = pg_fetch_array($result, null);
        $row['respuesta'] = '1';

      }    
      //print_r($row);exit;
      return $row;    

    }     

    
  }

?>