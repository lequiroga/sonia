<?php

  // Conexión a la base de datos
  require_once('ConectaBD.php');

  class ClientesSQL{       

  	private $conn;
  	private $dbConn;

  	function ClientesSQL(){
      $conn   = new ConectaBD();
	    $dbConn = $conn->conectarBD();   
	    unset($this->conn);
  	}

    function getTiposIdentificacion(){                      			

			$output = array();

			$query = "SELECT 
	            		id_tipo_ident AS id_tipo_identificacion,
	            		desc_tipo_id AS descripcion 
	          		  FROM 
	            		tipos.tb_tiposidentificacion";

			$result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());			

			if(pg_num_rows($result)>0){

	  			$i = 0;	
	  			while($row = pg_fetch_array($result, null)){	  	
	    			$output[$i]['id_tipo_identificacion'] = $row['id_tipo_identificacion'];
	    			$output[$i]['descripcion']  = $row['descripcion'];
	    			$i++;	    
	  			}	

                // Liberando el conjunto de resultados
				pg_free_result($result);				  

			}

			return $output;       

    }

    function getTiposNotificacion(){  
            
			$query = "SELECT 
	            		id_tipo_notificacion AS id_tipo_notificacion,
	            		descripcion AS descripcion 
	          	  	  FROM 
	            		tipos.tb_tipos_notificacion";

			$result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());

			if(pg_num_rows($result)>0){

	  			$i = 0;	
	  			while($row = pg_fetch_array($result, null)){	  	
	    			$output[$i]['id_tipo_notificacion'] = $row['id_tipo_notificacion'];
	    			$output[$i]['descripcion']  = $row['descripcion'];
	    			$i++;	    
	  			}	  

                // Liberando el conjunto de resultados
				pg_free_result($result);				
	  			
			}

			return $output;       

    }

    function getTiposClientes(){  
            
			$query = "SELECT 
	            		id_tipo_cliente AS id_tipo_cliente,
	            		descripcion AS descripcion 
	          	  	  FROM 
	            		tipos.tb_tipos_clientes";

			$result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());

			if(pg_num_rows($result)>0){

	  			$i = 0;	
	  			while($row = pg_fetch_array($result, null)){	  	
	    			$output[$i]['id_tipo_cliente'] = $row['id_tipo_cliente'];
	    			$output[$i]['descripcion']  = $row['descripcion'];
	    			$i++;	    
	  			}	  

                // Liberando el conjunto de resultados
				pg_free_result($result);				
	  			
			}

			return $output;       

    }

    function retornarCantClientes($id_cliente){

        $query = "SELECT
      	            COUNT(id_cliente) AS cant_clientes
                  FROM 
                    clientes.tb_clientes 
                  WHERE 
                    id_cliente='$id_cliente'  
      	         ";
         
        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        $row = pg_fetch_array($result, null);

        return $row['cant_clientes'];

    }

    function datosClientePorID($id_cliente){

        $query = "SELECT
      	            a.id_tipo_cliente AS tipoCliente,
      	            UPPER(a.nombres) AS nombres,
      	            UPPER(a.apellidos) AS apellidos,
      	            a.id_tipo_identificacion AS tipoIdentificacion,
                    (
                      SELECT
                        b.desc_tipo_id
                      FROM
                        tipos.tb_tiposidentificacion b
                      WHERE
                        b.id_tipo_ident = a.id_tipo_identificacion    
                    ) AS descIdentificacion,
                    a.numero_identificacion AS numeroIdentificacion,
                    a.id_tipo_notificacion AS tipoNotificacion, 
                    (
                      SELECT
                        b.descripcion
                      FROM
                        tipos.tb_tipos_notificacion b
                      WHERE
                        b.id_tipo_notificacion = a.id_tipo_notificacion    
                    ) AS descNotificacion,                   
                    a.numero_telefono AS telefono_fijo,
                    a.numero_celular AS telefono_movil,
                    UPPER(a.correo_electronico) AS correo_electronico,
                    UPPER(a.direccion) AS direccion,
                    a.id_ciudad AS id_ciudad,
                    a.id_departamento AS id_departamento,
                    a.id_pais AS id_pais,
                    a.id_cliente AS id_cliente
                  FROM 
                    clientes.tb_clientes a 
                  WHERE 
                    a.id_cliente=$id_cliente  
      	         ";      	
                         
        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        $row = pg_fetch_assoc($result, null);        

        $output['datosCliente']['tipoCliente']=$row['tipocliente'];
        $output['datosCliente']['nombres']=$row['nombres'];
        $output['datosCliente']['apellidos']=$row['apellidos'];
        $output['datosCliente']['tipoIdentificacion']=$row['tipoidentificacion'];
        $output['datosCliente']['numeroIdentificacion']=$row['numeroidentificacion'];
        $output['datosCliente']['tipoNotificacion']=$row['tiponotificacion'];
        $output['datosCliente']['telefono_fijo']=$row['telefono_fijo'];
        $output['datosCliente']['telefono_movil']=$row['telefono_movil'];
        $output['datosCliente']['correo_electronico']=$row['correo_electronico'];
        $output['datosCliente']['direccion']=$row['direccion'];
        $output['datosCliente']['id_ciudad']=$row['id_ciudad'];
        $output['datosCliente']['id_departamento']=$row['id_departamento'];
        $output['datosCliente']['id_pais']=$row['id_pais'];
        $output['datosCliente']['id_cliente']=$row['id_cliente'];

        return $output;

    }

    function retornarCantClientesPorDoc($numero_identificacion){

        $query = "SELECT
      	            COUNT(numero_identificacion) AS cant_clientes
                  FROM 
                    clientes.tb_clientes 
                  WHERE 
                    numero_identificacion='$numero_identificacion'  
      	         ";
         
        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        $row = pg_fetch_array($result, null);

        return $row['cant_clientes'];

    }

    function retornarIdClientesPorDoc($numero_identificacion){

        $query = "SELECT
      	            id_cliente AS id_cliente
                  FROM 
                    clientes.tb_clientes 
                  WHERE 
                    numero_identificacion='$numero_identificacion'  
      	         ";
         
        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        $row = pg_fetch_array($result, null);

        return $row['id_cliente'];

    }

    function consultarClientes($datosCliente){

      $query="SELECT
                a.id_cliente AS id_cliente,
                (SELECT
                   aa.abreviatura
                 FROM
                   tipos.tb_tiposidentificacion aa
                 WHERE
                   aa.id_tipo_ident=a.id_tipo_identificacion      
                )||' '||a.numero_identificacion AS identificacion,
                UPPER(a.nombres)||' '||UPPER(a.apellidos) AS cliente,
                COALESCE(a.numero_telefono,'')||
                CASE WHEN ((COALESCE(a.numero_telefono,'') NOT LIKE '')AND(COALESCE(a.numero_celular,'') NOT LIKE '')) THEN ' - ' ELSE '' END|| 
                COALESCE(a.numero_celular,'') AS telefonos
              FROM  
                clientes.tb_clientes a
              WHERE
                CAST(a.numero_identificacion AS VARCHAR) LIKE '%".$datosCliente->numero_identificacion."%' 
                AND UPPER(a.nombres) LIKE UPPER('%".$datosCliente->nombres."%')
                AND UPPER(a.apellidos) LIKE UPPER('%".$datosCliente->apellidos."%')  
                AND (a.numero_celular LIKE '%".$datosCliente->telefono."%' OR 
                     a.numero_telefono LIKE '%".$datosCliente->telefono."%'
                    )
             ";         

      $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());

	  if(pg_num_rows($result)>0){

	  	$i = 0;	
	  	while($row = pg_fetch_array($result, null)){	  	
	      $output['lista_clientes'][$i]['id_cliente'] = $row['id_cliente'];
	      $output['lista_clientes'][$i]['identificacion']  = $row['identificacion'];
	      $output['lista_clientes'][$i]['cliente']  = $row['cliente'];
	      $output['lista_clientes'][$i]['telefonos']  = $row['telefonos'];
	      $i++;	    
	  	}	  

        $output['cant']=$i;
        // Liberando el conjunto de resultados
		pg_free_result($result);				
	  			
	  }
      else{
        $output['cant']='0';
      }

	  return $output;

    }

    function guardarClientes($datosCliente){  

      if(isset($datosCliente->telefono_fijo))  
        $telefono_fijo=$datosCliente->telefono_fijo;
      else
        $telefono_fijo='';

      if(isset($datosCliente->telefono_movil))
        $telefono_movil=$datosCliente->telefono_movil;
      else
        $telefono_movil='';

      if(isset($datosCliente->correo_electronico))
        $correo_electronico=$datosCliente->correo_electronico;
      else
        $correo_electronico='';  

      if(isset($datosCliente->direccion))
        $direccion=$datosCliente->direccion;
      else
        $direccion='';  

      if(isset($datosCliente->id_ciudad))
        $id_ciudad=$datosCliente->id_ciudad;
      else
        $id_ciudad='null';   

      if(isset($datosCliente->id_departamento))
        $id_departamento=$datosCliente->id_departamento;
      else
        $id_departamento='null';     

      if(isset($datosCliente->id_pais))
        $id_pais=$datosCliente->id_pais;
      else
        $id_pais='null';       	

      if(isset($datosCliente->id_cliente)){     

        if($this->retornarCantClientes($datosCliente->id_cliente)==1){

          if(!isset($_SESSION))
          	session_start();

          $query="UPDATE 
                    clientes.tb_clientes
                  SET 
                    id_tipo_cliente=".$datosCliente->tipoCliente.",
                    nombres=UPPER('".$datosCliente->nombres."'),
                    apellidos=UPPER('".$datosCliente->apellidos."'),
                    id_tipo_identificacion=".$datosCliente->tipoIdentificacion.",
                    numero_identificacion='".$datosCliente->numeroIdentificacion."',
                    id_tipo_notificacion=".$datosCliente->tipoNotificacion.",                    
                    numero_telefono='".$telefono_fijo."',
                    numero_celular='".$telefono_movil."',
                    correo_electronico=UPPER('".$correo_electronico."'),
                    direccion=UPPER('".$direccion."'),
                    id_ciudad=".$id_ciudad.",
                    id_departamento=".$id_departamento.",
                    id_pais=".$id_pais.",
                    id_user_mod=".$_SESSION['id_user'].",
                    fecha_modificacion=CURRENT_TIMESTAMP
                  WHERE
                    id_cliente=".$datosCliente->id_cliente.""; 

          $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
          
          $respuesta['respuesta']='3';
          $respuesta['id_cliente']=$datosCliente->id_cliente;

          return $respuesta;

        }
        else{
          $respuesta['respuesta']='4';
          return $respuesta;
        }			

      }

      else{

        if($this->retornarCantClientesPorDoc($datosCliente->numeroIdentificacion)==0){

          if(!isset($_SESSION))
          	session_start();

          $query="INSERT INTO 
                    clientes.tb_clientes
                    (
                      id_tipo_cliente,
                      nombres,
                      apellidos,
                      id_tipo_identificacion,
                      numero_identificacion,
                      id_tipo_notificacion,
                      numero_telefono,
                      numero_celular,
                      correo_electronico,
                      direccion,
                      id_ciudad,
                      id_departamento,
                      id_pais,
                      id_user
                    ) 
                    VALUES
                    (
                     ".$datosCliente->tipoCliente.",
                     UPPER('".$datosCliente->nombres."'),
                     UPPER('".$datosCliente->apellidos."'),
                     ".$datosCliente->tipoIdentificacion.",
                     '".$datosCliente->numeroIdentificacion."',
                     ".$datosCliente->tipoNotificacion.",                    
                     '".$telefono_fijo."',
                     '".$telefono_movil."',
                     UPPER('".$correo_electronico."'),
                     UPPER('".$direccion."'),
                     ".$id_ciudad.",
                     ".$id_departamento.",
                     ".$id_pais.",
                     ".$_SESSION['id_user'].")";          
//print_r($query);exit;
          $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());

          $id_cliente=$this->retornarIdClientesPorDoc($datosCliente->numeroIdentificacion);
          
          $respuesta['respuesta']='1';
          $respuesta['id_cliente']=$id_cliente;

          return $respuesta;           

        }
        else{
          $respuesta['respuesta']='2';
          return $respuesta;	
        }

      }   

    }

  }

?>