<?php

  // Conexión a la base de datos
  require_once('ConectaBD.php');

  class InmueblesSQL{       

  	private $conn;
  	private $dbConn;

  	function InmueblesSQL(){
      $conn   = new ConectaBD();
	    $dbConn = $conn->conectarBD();   
	    unset($this->conn);
  	}

    function getTiposCaracteristicasInmuebles(){                      			

			$output = array();

			$query = "SELECT 
	            		id_tipos_caracteristicas_inmuebles AS id_tipo_caracteristica_inmueble,
	            		descripcion AS name 
	          		FROM 
	            		inmuebles.tb_tipos_caracteristicas_inmuebles";

			$result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());			

			if(pg_num_rows($result)>0){

	  			$i = 0;	
	  			while($row = pg_fetch_array($result, null)){	  	
	    			$output[$i]['id_tipo_caracteristica_inmueble'] = $row['id_tipo_caracteristica_inmueble'];
	    			$output[$i]['name']  = $row['name'];
	    			$i++;	    
	  			}	

                // Liberando el conjunto de resultados
				pg_free_result($result);				  

			}

			return $output;       

    }

    //Obtiene las formas de pago posibles de los negocios inmobiliarios
    function getListaFormasPago(){

      $output = array();

      $query = "SELECT 
                  id_forma_pago AS id_forma_pago,
                  descripcion AS descripcion
                FROM 
                  generales.tb_formas_pago";

      $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());     

      if(pg_num_rows($result)>0){

          $i = 0; 
          while($row = pg_fetch_array($result, null)){      
            $output['listaFormasPago'][$i]['id_forma_pago'] = $row['id_forma_pago'];
            $output['listaFormasPago'][$i]['descripcion']  = $row['descripcion'];
            $i++;     
          } 

                // Liberando el conjunto de resultados
        pg_free_result($result);          

      }

      return $output;       

    }

    //Obtiene las prioridades o urgencias de negocio
    function getListaPrioridades(){

      $output = array();

      $query = "SELECT 
                  id_prioridad AS id_prioridad,
                  descripcion AS descripcion
                FROM 
                  generales.tb_prioridades";

      $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());     

      if(pg_num_rows($result)>0){

          $i = 0; 
          while($row = pg_fetch_array($result, null)){      
            $output['listaPrioridades'][$i]['id_prioridad'] = $row['id_prioridad'];
            $output['listaPrioridades'][$i]['descripcion']  = $row['descripcion'];
            $i++;     
          } 

                // Liberando el conjunto de resultados
        pg_free_result($result);          

      }

      return $output;       

    }

    function getCriteriosDiligenciamiento(){                            

      $output = array();

      $query = "SELECT 
                  id_criterio_diligenciamiento AS id_criterio_diligenciamiento,
                  descripcion AS name 
                FROM 
                  generales.tb_criterios_diligenciamiento";

      $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());     

      if(pg_num_rows($result)>0){

          $i = 0; 
          while($row = pg_fetch_array($result, null)){      
            $output[$i]['id_criterio_diligenciamiento'] = $row['id_criterio_diligenciamiento'];
            $output[$i]['name']  = $row['name'];
            $i++;     
          } 

                // Liberando el conjunto de resultados
        pg_free_result($result);          

      }

      return $output;       

    }


    function consultarCaracteristicaTipoInmueble($id_tipo_inmueble,$id_caracteristica){

        if(!isset($_SESSION)){
          session_start();
        }

        $id_inmobiliaria = $_SESSION['id_inmobiliaria'];

        $query = "SELECT
                    COUNT(*) AS cant_caracteristicas
                  FROM 
                    inmuebles.tb_caracteristicas_tipo_inmueble 
                  WHERE 
                    id_tipo_inmueble='$id_tipo_inmueble' 
                    AND id_caracteristica='$id_caracteristica'
                    AND id_inmobiliaria = $id_inmobiliaria
                 ";
         
        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        $row = pg_fetch_array($result, null);

        return $row['cant_caracteristicas'];
    }


    function consultarCantidadCaracteristicasTipoInmueble($id_tipo_inmueble){
  
        if(!isset($_SESSION)){
          session_start();
        }

        $id_inmobiliaria = $_SESSION['id_inmobiliaria'];

        $query = "SELECT
                    COUNT(*) AS cantidad
                  FROM 
                    inmuebles.tb_caracteristicas_tipo_inmueble 
                  WHERE 
                    id_tipo_inmueble='$id_tipo_inmueble' 
                    AND estado='1'
                    AND id_inmobiliaria=$id_inmobiliaria
                 ";
         
        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        $row = pg_fetch_array($result, null);

        return $row['cantidad'];
    }


    function consultarCantidadCaracteristicasOblTipoInmueble($id_tipo_inmueble){

        if(!isset($_SESSION)){
          session_start();
        }

        $id_inmobiliaria = $_SESSION['id_inmobiliaria'];

        $query = "SELECT
                    COUNT(*) AS cantidad
                  FROM 
                    inmuebles.tb_caracteristicas_tipo_inmueble 
                  WHERE 
                    id_tipo_inmueble='$id_tipo_inmueble' 
                    AND estado='1' 
                    AND id_criterio_diligenciamiento='2'
                    AND id_inmobiliaria = $id_inmobiliaria
                 ";
         
        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        $row = pg_fetch_array($result, null);

        return $row['cantidad'];
    }


    function consultarCantidadCaracteristicasOpcTipoInmueble($id_tipo_inmueble){

        if(!isset($_SESSION)){
          session_start();
        }

        $id_inmobiliaria = $_SESSION['id_inmobiliaria'];

        $query = "SELECT
                    COUNT(*) AS cantidad
                  FROM 
                    inmuebles.tb_caracteristicas_tipo_inmueble 
                  WHERE 
                    id_tipo_inmueble='$id_tipo_inmueble' 
                    AND estado='1' 
                    AND id_criterio_diligenciamiento='3'
                    AND id_inmobiliaria = id_inmobiliaria
                 ";
         
        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        $row = pg_fetch_array($result, null);

        return $row['cantidad'];
    }


    function seleccionarCaracteristicaInmueble($id_caracteristicas_tipo_inmueble){

          $query = "SELECT
                      id_criterio_diligenciamiento AS criterio_diligenciamiento,                      
                      id_tipo_inmueble AS tipoInmueble,
                      id_caracteristica AS tipoCaracteristica,
                      descripcion AS descripcion,
                      id_tipos_caracteristicas_inmuebles AS tipoCaracteristicaInmueble
                    FROM
                      inmuebles.tb_caracteristicas_tipo_inmueble                        
                    WHERE
                      id_caracteristicas_tipo_inmueble=$id_caracteristicas_tipo_inmueble                      
                   ";                 

        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        $row = pg_fetch_assoc($result, null);        

        $output['caracteristicasInmuebles']['criterio_diligenciamiento']=$row['criterio_diligenciamiento'];
        $output['caracteristicasInmuebles']['tipoInmueble']=$row['tipoinmueble'];
        $output['caracteristicasInmuebles']['tipoCaracteristica']=$row['tipocaracteristica'];
        $output['caracteristicasInmuebles']['descripcion']=$row['descripcion'];
        $output['caracteristicasInmuebles']['tipoCaracteristicaInmueble']=$row['tipocaracteristicainmueble'];         

        return $output;       
    }


    function caracteristicasTiposInmuebles($id_tipo_inmueble,$obligatoriedad){

        if(!isset($_SESSION)){
          session_start();
        }

        $id_inmobiliaria = $_SESSION['id_inmobiliaria'];

        if($obligatoriedad == null){
          $sql = "";
          $respuesta['cantidad'] = $this->consultarCantidadCaracteristicasTipoInmueble($id_tipo_inmueble);
          $respuesta['cantidad_opc'] = $this->consultarCantidadCaracteristicasOpcTipoInmueble($id_tipo_inmueble);
          $respuesta['cantidad_obl'] = $this->consultarCantidadCaracteristicasOblTipoInmueble($id_tipo_inmueble);
          $cant_opc = $respuesta['cantidad'];
        }
        else{
          $sql = "AND b.descripcion LIKE '$obligatoriedad'";
          if($obligatoriedad == 'Obligatorio'){
            $cant_opc = $this->consultarCantidadCaracteristicasOblTipoInmueble($id_tipo_inmueble);
          }
          else if($obligatoriedad == 'Opcional'){
            $cant_opc = $this->consultarCantidadCaracteristicasOpcTipoInmueble($id_tipo_inmueble);
          }
        }  
              

        if($cant_opc!='0'){
          $query = "SELECT
                      ROW_NUMBER () OVER (ORDER BY a.id_caracteristicas_tipo_inmueble) AS numero_fila,
                      a.id_caracteristicas_tipo_inmueble AS id_caracteristicas_tipo_inmueble,
                      b.descripcion AS criterio_diligenciamiento,
                      b.id_criterio_diligenciamiento AS id_criterio_diligenciamiento,
                      c.id_tipos_caracteristicas_inmuebles AS id_tipos_caracteristicas_inmuebles,
                      c.descripcion AS tipo_caracteristica_inmueble,
                      a.id_tipo_inmueble AS id_tipo_inmueble,
                      a.id_caracteristica AS id_caracteristica,
                      a.descripcion AS descripcion
                    FROM
                      inmuebles.tb_caracteristicas_tipo_inmueble a
                      INNER JOIN generales.tb_criterios_diligenciamiento b ON a.id_criterio_diligenciamiento=b.id_criterio_diligenciamiento
                      INNER JOIN inmuebles.tb_tipos_caracteristicas_inmuebles c ON a.id_tipos_caracteristicas_inmuebles=c.id_tipos_caracteristicas_inmuebles   
                    WHERE
                      a.id_tipo_inmueble=$id_tipo_inmueble
                      AND a.id_inmobiliaria = $id_inmobiliaria
                      AND a.estado='1' ".$sql;

          $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());

          if(pg_num_rows($result)>0){
            $i=0;  
            while($row = pg_fetch_array($result, null)){     
              $respuesta['lista_caracteristicas_inmueble'][$i]['numero_fila'] = $row['numero_fila']; 
              $respuesta['lista_caracteristicas_inmueble'][$i]['id_caracteristicas_tipo_inmueble'] = $row['id_caracteristicas_tipo_inmueble'];
              $respuesta['lista_caracteristicas_inmueble'][$i]['criterio_diligenciamiento']  = $row['criterio_diligenciamiento'];
              $respuesta['lista_caracteristicas_inmueble'][$i]['id_criterio_diligenciamiento']  = $row['id_criterio_diligenciamiento'];
              $respuesta['lista_caracteristicas_inmueble'][$i]['id_tipos_caracteristicas_inmuebles']  = $row['id_tipos_caracteristicas_inmuebles'];
              $respuesta['lista_caracteristicas_inmueble'][$i]['tipo_caracteristica_inmueble'] = $row['tipo_caracteristica_inmueble'];
              $respuesta['lista_caracteristicas_inmueble'][$i]['id_tipo_inmueble']  = $row['id_tipo_inmueble'];
              $respuesta['lista_caracteristicas_inmueble'][$i]['id_caracteristica']  = $row['id_caracteristica'];
              $respuesta['lista_caracteristicas_inmueble'][$i]['descripcion']  = $row['descripcion'];
              $i++;     
            }              
            // Liberando el conjunto de resultados
            pg_free_result($result);          
          }
          else{
            $respuesta['lista_caracteristicas_inmueble']=array();
          }             

        }
        else{
          $respuesta['lista_caracteristicas_inmueble']=array();
        }
         
        return $respuesta; 

    }


    function borrarCaracteristicasTiposInmuebles($id_caracteristicas_tipo_inmueble){
      $query = "UPDATE
                  inmuebles.tb_caracteristicas_tipo_inmueble
                SET
                  estado='0'  
                WHERE 
                  id_caracteristicas_tipo_inmueble=$id_caracteristicas_tipo_inmueble";

      $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());            
    }


    function guardarCaracteristicaTipoInmueble($datosCaracteristicaTipoInmueble){                            
      
      if(!isset($_SESSION)){
        session_start();
      }

      if($this->consultarCaracteristicaTipoInmueble($datosCaracteristicaTipoInmueble->tipoInmueble,$datosCaracteristicaTipoInmueble->tipoCaracteristica)=='0'){

        if(!isset($_SESSION)){
          session_start();
        }

        $id_inmobiliaria = $_SESSION['id_inmobiliaria']; 

        $query = "INSERT INTO
                    inmuebles.tb_caracteristicas_tipo_inmueble 
                    (
                      estado,
                      id_criterio_diligenciamiento,
                      id_tipo_inmueble,
                      id_caracteristica,
                      id_tipos_caracteristicas_inmuebles,
                      id_user_creacion,
                      descripcion,
                      id_inmobiliaria
                    )
                  VALUES 
                    (
                      '1',
                      ".$datosCaracteristicaTipoInmueble->criterio_diligenciamiento.",
                      ".$datosCaracteristicaTipoInmueble->tipoInmueble.",
                      ".$datosCaracteristicaTipoInmueble->tipoCaracteristica.",
                      ".$datosCaracteristicaTipoInmueble->tipoCaracteristicaInmueble.",
                      ".$_SESSION['id_user'].",
                      '".$datosCaracteristicaTipoInmueble->descripcion."',
                      $id_inmobiliaria
                    )
                    ";

        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());        
          
        $respuesta['respuesta']='1';      

        return $respuesta;           

      }
      else{

        if(!isset($_SESSION)){
          session_start();
        }

        $id_inmobiliaria = $_SESSION['id_inmobiliaria']; 

        $query = "UPDATE
                    inmuebles.tb_caracteristicas_tipo_inmueble 
                  SET
                    estado='1',
                    id_criterio_diligenciamiento=".$datosCaracteristicaTipoInmueble->criterio_diligenciamiento.",                    
                    id_tipos_caracteristicas_inmuebles=".$datosCaracteristicaTipoInmueble->tipoCaracteristicaInmueble.",
                    id_user_modificacion=".$_SESSION['id_user'].",
                    fecha_modificacion=CURRENT_TIMESTAMP,
                    descripcion='".$datosCaracteristicaTipoInmueble->descripcion."'
                  WHERE
                    id_tipo_inmueble=".$datosCaracteristicaTipoInmueble->tipoInmueble." AND
                    id_caracteristica=".$datosCaracteristicaTipoInmueble->tipoCaracteristica." AND 
                    id_inmobiliaria=".$id_inmobiliaria;

        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());        
          
        $respuesta['respuesta']='1';      

        return $respuesta;     
      }                

    }   

    
  }

?>