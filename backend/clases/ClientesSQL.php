<?php

  // Conexión a la base de datos
  require_once('ConectaBD.php');

  class ClientesSQL{       

  	private $conn;
  	private $dbConn;

  	function ClientesSQL(){
      $conn   = new ConectaBD();
	    $dbConn = $conn->conectarBD();   
	    //unset($this->conn);
  	}

    function getTiposIdentificacion(){                      			

			$output = array();

			$query = "SELECT 
	            		id_tipo_ident AS id_tipo_identificacion,
	            		desc_tipo_id AS descripcion,
                  abreviatura AS abreviatura 
	          		  FROM 
	            		tipos.tb_tiposidentificacion";

			$result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());			

			if(pg_num_rows($result)>0){

	  			$i = 0;	
	  			while($row = pg_fetch_array($result, null)){	  	
	    			$output[$i]['id_tipo_identificacion'] = $row['id_tipo_identificacion'];
	    			$output[$i]['descripcion']  = $row['descripcion'];
            $output[$i]['abreviatura']  = $row['abreviatura'];
	    			$i++;	    
	  			}	

                // Liberando el conjunto de resultados
				pg_free_result($result);				  

			}

			return $output;       

    }

    function getListaRedesSociales(){                            

      $output = array();

      $query = "SELECT 
                  id_red_social AS id_red_social,
                  nombre AS nombre,
                  imagen AS imagen 
                FROM 
                  generales.tb_redes_sociales";

      $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());     

      if(pg_num_rows($result)>0){

          $i = 0; 
          while($row = pg_fetch_array($result, null)){      
            $output[$i]['id_red_social'] = $row['id_red_social'];
            $output[$i]['nombre']  = $row['nombre'];
            $output[$i]['imagen']  = $row['imagen'];
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

    function retornarCantClientes($id_client){

      $this->conn   = new ConectaBD();
      $this->dbConn = $this->conn->conectarBD(); 

        $query = "SELECT
      	            COUNT(id_client) AS cant_clientes
                  FROM 
                    clientes.tb_clientes 
                  WHERE 
                    id_client='$id_client'  
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

    function retornarCantClientesPorDoc($identification){

        $query = "SELECT
      	            COUNT(identification) AS cant_clientes
                  FROM 
                    clientes.tb_clientes 
                  WHERE 
                    identification='$identification'  
      	         ";
         
        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        $row = pg_fetch_array($result, null);

        return $row['cant_clientes'];

    }

    function retornarIdClientesPorDoc($numero_identificacion){

        $query = "SELECT
      	            id_client AS id_client
                  FROM 
                    clientes.tb_clientes 
                  WHERE 
                    identification='$numero_identificacion'  
      	         ";
         
        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        $row = pg_fetch_array($result, null);

        return $row['id_client'];

    }

    //Consulta que la red social que se intenta asociar no esté relacionada a otro cliente
    function consultarCantRedesSocialesCliente($nombre_cuenta,$id_red_social,$id_cliente){        

        $query = "SELECT
                    count(id_redes_sociales_cliente) AS cant_redes_cliente
                  FROM 
                    clientes.tb_redes_sociales_cliente
                  WHERE 
                    cuenta = '$nombre_cuenta' 
                    AND id_red_social = $id_red_social
                    AND id_cliente <> $id_cliente 
                    ";

        //$query.=$id_red_social_cliente;          

        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        $row = pg_fetch_array($result, null);

        return $row['cant_redes_cliente'];                  

    }

    //Consultar la red social que tiene asociado el cliente por medio del ID
    function consultarRedSocialCliente($id_red_social_cliente,$id_cliente){        

        $query = "SELECT
                    id_red_social AS id_red_social
                  FROM 
                    clientes.tb_redes_sociales_cliente
                  WHERE 
                    id_redes_sociales_cliente = $id_red_social_cliente                     
                    AND id_cliente = $id_cliente 
                    ";                 

        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        $row = pg_fetch_array($result, null);

        return $row['id_red_social'];                  

    }


    //Consulta si el cliente tiene una determinada cuenta de red social asociada
    function consultarCantRedesSocialesCliente1($nombre_cuenta,$id_red_social,$id_cliente){
        
        $query = "SELECT
                    count(id_redes_sociales_cliente) AS cant_redes_cliente
                  FROM 
                    clientes.tb_redes_sociales_cliente
                  WHERE 
                    cuenta = '$nombre_cuenta' 
                    AND id_red_social = $id_red_social
                    AND id_cliente = $id_cliente 
                    ";           

        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        $row = pg_fetch_array($result, null);

        return $row['cant_redes_cliente'];                  

    }

    function listarRedesSocialesCliente($id_cliente){

        $query = "SELECT
                    a.id_redes_sociales_cliente AS id_redes_sociales_cliente,
                    b.id_red_social AS id_red_social,
                    b.nombre AS red_social,
                    b.imagen AS imagen,
                    a.cuenta AS cuenta
                  FROM
                    clientes.tb_redes_sociales_cliente a
                    INNER JOIN generales.tb_redes_sociales b ON a.id_red_social = b.id_red_social
                  WHERE
                    a.id_cliente = $id_cliente
                    AND a.estado='1'
                  ";

        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());     

        if(pg_num_rows($result)>0){

          $i = 0; 
          while($row = pg_fetch_array($result, null)){      
            $output[$i]['id_red_social_cliente'] = $row['id_redes_sociales_cliente'];
            $output[$i]['red_social']  = $row['red_social'];
            $output[$i]['id_red_social']  = $row['id_red_social'];
            $output[$i]['imagen']  = $row['imagen'];
            $output[$i]['cuenta']  = $row['cuenta'];
            $i++;     
          } 
                // Liberando el conjunto de resultados
          pg_free_result($result);  

          $respuesta['redes_sociales_cliente'] = $output;
          $respuesta['respuesta'] = $i;        

        }
        else
          $respuesta['cantidad_redes_sociales_cliente'] = '0';  

        return $respuesta;        

    }

    function guardarRedesSocialesCliente($datosRedesCliente){

        if(!isset($_SESSION)){
          session_start();
        }

        $row = array();

        if(isset($datosRedesCliente->id_redes_cliente)){
          
          if(($this->consultarCantRedesSocialesCliente($datosRedesCliente->nombre_cuenta,$datosRedesCliente->id_red_social,$datosRedesCliente->id_cliente) == 0)&&($this->consultarCantRedesSocialesCliente1($datosRedesCliente->nombre_cuenta,$datosRedesCliente->id_red_social,$datosRedesCliente->id_cliente) == 1)){            

            $query = "UPDATE
                        clientes.tb_redes_sociales_cliente
                      SET
                        cuenta = '".$datosRedesCliente->nombre_cuenta."', 
                        id_red_social = ".$datosRedesCliente->id_red_social.",
                        id_user_mod = ".$_SESSION['id_user'].",
                        fecha_modificacion = CURRENT_TIMESTAMP,
                        estado='1'
                      WHERE
                        id_redes_sociales_cliente = ".$datosRedesCliente->id_redes_cliente;

            $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());

            $row['respuesta'] = '1';      
            $row['id_redes_sociales_cliente'] =  $datosRedesCliente->id_redes_cliente;                     

          }

          else if($this->consultarCantRedesSocialesCliente($datosRedesCliente->nombre_cuenta,$datosRedesCliente->id_red_social,$datosRedesCliente->id_cliente) == 1){
            $row['respuesta'] = '2';      
            $row['id_redes_sociales_cliente'] =  $datosRedesCliente->id_redes_cliente; 
          }

          else if(($this->consultarCantRedesSocialesCliente1($datosRedesCliente->nombre_cuenta,$datosRedesCliente->id_red_social,$datosRedesCliente->id_cliente) == 0)){

            if($this->consultarRedSocialCliente($datosRedesCliente->id_redes_cliente,$datosRedesCliente->id_cliente)!=$datosRedesCliente->id_red_social){

              $query = "INSERT INTO
                          clientes.tb_redes_sociales_cliente
                          (
                            cuenta,
                            id_red_social,
                            id_cliente,
                            id_user,
                            estado
                          )
                        VALUES
                          (
                            '".$datosRedesCliente->nombre_cuenta."',
                            ".$datosRedesCliente->id_red_social.",
                            ".$datosRedesCliente->id_cliente.",
                            ".$_SESSION['id_user'].",
                            '1'
                          )  
                        RETURNING 
                          id_redes_sociales_cliente
                        ";

              $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
              $row = pg_fetch_array($result, null);
  
              $row['respuesta'] = '3'; 

            } 
            else{
              $query = "UPDATE
                          clientes.tb_redes_sociales_cliente
                        SET
                          cuenta = '".$datosRedesCliente->nombre_cuenta."', 
                          id_red_social = ".$datosRedesCliente->id_red_social.",
                          id_user_mod = ".$_SESSION['id_user'].",
                          fecha_modificacion = CURRENT_TIMESTAMP,
                          estado='1'
                        WHERE
                          id_redes_sociales_cliente = ".$datosRedesCliente->id_redes_cliente;

              $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
  
              $row['respuesta'] = '1';      
              $row['id_redes_sociales_cliente'] =  $datosRedesCliente->id_redes_cliente;        
            }           

          }
          

        }

        else{

          if($this->consultarCantRedesSocialesCliente($datosRedesCliente->nombre_cuenta,$datosRedesCliente->id_red_social,$datosRedesCliente->id_cliente) == 0){

            $query = "INSERT INTO
                        clientes.tb_redes_sociales_cliente
                        (
                          cuenta,
                          id_red_social,
                          id_cliente,
                          id_user,
                          estado
                        )
                      VALUES
                        (
                          '".$datosRedesCliente->nombre_cuenta."',
                          ".$datosRedesCliente->id_red_social.",
                          ".$datosRedesCliente->id_cliente.",
                          ".$_SESSION['id_user'].",
                          '1'
                        )  
                      RETURNING 
                        id_redes_sociales_cliente
                      ";

            $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
            $row = pg_fetch_array($result, null);

            $row['respuesta'] = '3';            

          }

          else{

            //$row['respuesta'] = '4'; 
            /*$query = "UPDATE
                        clientes.tb_redes_sociales_cliente
                      SET                        
                        id_user_mod = ".$_SESSION['id_user'].",
                        fecha_modificacion = CURRENT_TIMESTAMP,
                        estado='1'
                      WHERE
                        id_red_social = ".$datosRedesCliente->id_red_social."
                        AND cuenta = '".$datosRedesCliente->nombre_cuenta."'
                      RETURNING  
                        id_redes_sociales_cliente";

            $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
            $row = pg_fetch_array($result, null);*/

            $row['respuesta'] = '2';      
            //$row['id_redes_sociales_cliente'] =  $datosRedesCliente->id_redes_cliente;            

          }         
          
        }

        return $row;

    }

    function borrarRedSocialCliente($id_red_social_cliente){
        if(!isset($_SESSION)){
          session_start();
        }

        $query = "UPDATE
                    clientes.tb_redes_sociales_cliente
                  SET
                    estado='0',
                    id_user_mod = ".$_SESSION['id_user'].",
                    fecha_modificacion = CURRENT_TIMESTAMP
                  WHERE
                    id_redes_sociales_cliente = ".$id_red_social_cliente;

        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());        
        $row['respuesta'] = '1';            

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

      $data_cliente = $datosCliente;
      //$datosCliente = json
      $datosCliente = (object)$datosCliente;

      if(isset($datosCliente->phone))  
        $phone=$datosCliente->phone;
      else
        $phone='';

      if(isset($datosCliente->cell_phone))
        $cell_phone=$datosCliente->cell_phone;
      else
        $cell_phone='';

      if(isset($datosCliente->email))
        $email=$datosCliente->email;
      else
        $email='';  

      if(isset($datosCliente->address))
        $address=$datosCliente->address;
      else
        $address='';  

      if(isset($datosCliente->id_city))
        $id_city=$datosCliente->id_city;
      else
        $id_city='null';   

      if(isset($datosCliente->id_region))
        $id_region=$datosCliente->id_region;
      else
        $id_region='null';     

      if(isset($datosCliente->id_country))
        $id_country=$datosCliente->id_country;
      else
        $id_country='null';       	

      if(isset($datosCliente->id_client)){     

        if($this->retornarCantClientes($datosCliente->id_client)==1){

          if(!isset($_SESSION))
          	session_start();

          $query="UPDATE 
                    clientes.tb_clientes
                  SET 
                    id_client_type=".$datosCliente->id_client_type.",
                    first_name=UPPER('".$datosCliente->first_name."'),
                    last_name=UPPER('".$datosCliente->last_name."'),
                    id_tipo_identificacion=".$datosCliente->tipoIdentificacion.",
                    identification='".$datosCliente->identification."',
                    id_tipo_notificacion=".$datosCliente->tipoNotificacion.",                    
                    phone='".$phone."',
                    cell_phone='".$cell_phone."',
                    email=UPPER('".$email."'),
                    address=UPPER('".$address."'),
                    id_city=".$id_city.",
                    id_region=".$id_region.",
                    id_country=".$id_pais.",
                    id_user_mod=".$datosCliente->id_user.",
                    fecha_modificacion=CURRENT_TIMESTAMP
                  WHERE
                    id_client=".$datosCliente->id_client.""; 

          $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
          
          $respuesta['respuesta']='3';
          $respuesta['id_client']=$datosClient->id_client;

          return $respuesta;

        }
        else{
          /*$respuesta['respuesta']='4';
          return $respuesta;*/
          $query="INSERT INTO 
                    clientes.tb_clientes
                    (
                      id_client_type,
                      first_name,
                      last_name,
                      
                      identification,
                      
                      phone,
                      cell_phone,
                      email,
                      address,
                      id_city,
                      id_region,
                      id_country,
                      id_user,
                      id_client
                    ) 
                    VALUES
                    (
                     ".$datosCliente->id_client_type.",
                     UPPER('".$datosCliente->first_name."'),
                     UPPER('".$datosCliente->last_name."'),
                     
                     '".$datosCliente->identification."',
                                         
                     '".$phone."',
                     '".$cell_phone."',
                     UPPER('".$email."'),
                     UPPER('".$address."'),
                     ".$id_city.",
                     ".$id_region.",
                     ".$id_country.",
                     ".$datosCliente->id_user.",
                     ".$datosCliente->id_client."
                   )";          
//print_r($query);exit;
          $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());

          $id_client=$this->retornarIdClientesPorDoc($datosCliente->identification);
          
          $respuesta['respuesta']='1';
          $respuesta['id_client']=$id_client;

          return $respuesta;           

        }			

      }

      else{

        if($this->retornarCantClientesPorDoc($datosCliente->identification)==0){

          if(!isset($_SESSION))
          	session_start();

          $query="INSERT INTO 
                    clientes.tb_clientes
                    (
                      id_client_type,
                      first_name,
                      last_name,
                      id_tipo_identificacion,
                      identification,
                      id_tipo_notificacion,
                      phone,
                      cell_phone,
                      email,
                      address,
                      id_city,
                      id_region,
                      id_country,
                      id_user
                    ) 
                    VALUES
                    (
                     ".$datosCliente->id_cliente_type.",
                     UPPER('".$datosCliente->first_name."'),
                     UPPER('".$datosCliente->last_name."'),
                     ".$datosCliente->tipoIdentificacion.",
                     '".$datosCliente->identification."',
                     ".$datosCliente->tipoNotificacion.",                    
                     '".$phone."',
                     '".$cell_phone."',
                     UPPER('".$email."'),
                     UPPER('".$address."'),
                     ".$id_city.",
                     ".$id_region.",
                     ".$id_country.",
                     ".$datosCliente->id_user.")";          
//print_r($query);exit;
          $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());

          $id_client=$this->retornarIdClientesPorDoc($datosCliente->identification);
          
          $respuesta['respuesta']='1';
          $respuesta['id_client']=$id_client;

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