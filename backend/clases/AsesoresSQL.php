<?php

  // Conexión a la base de datos
  require_once('ConectaBD.php');

  class AsesoresSQL{       

  	private $conn;
  	private $dbConn;

  	function AsesoresSQL(){
      $conn   = new ConectaBD();
	    $dbConn = $conn->conectarBD();   
	    unset($this->conn);
  	}

    function getTiposAsesores(){  
            
			$query = "SELECT 
	            		id_tipo_asesor AS id_tipo_asesor,
	            		descripcion AS descripcion 
	          	  FROM 
	            		tipos.tb_tipos_asesores";

			$result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());

			if(pg_num_rows($result)>0){

	  			$i = 0;	
	  			while($row = pg_fetch_array($result, null)){	  	
	    			$output[$i]['id_tipo_asesor'] = $row['id_tipo_asesor'];
	    			$output[$i]['descripcion']  = $row['descripcion'];
	    			$i++;	    
	  			}	  

                // Liberando el conjunto de resultados
				pg_free_result($result);				
	  			
			}

			return $output;       

    }

    function retornarCantAsesores($id_asesor){

        $query = "SELECT
      	            COUNT(id_personal) AS cant_casesores
                  FROM 
                    rrhh.tb_personal 
                  WHERE 
                    id_personal='$id_asesor'  
      	         ";
         
        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        $row = pg_fetch_array($result, null);

        return $row['cant_asesores'];

    }

    function datosAsesorPorID($id_asesor){

        $query = "SELECT
      	            a.id_tipo_asesor AS tipoAsesor,
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
                    a.id_personal AS id_asesor,
                    a.porcentaje_ganancia AS porcentaje_ganancia,
                    a.photo AS foto,
                    a.estado AS estado_empleado,
                    COALESCE(c.estado,'') AS estado_usuario_app,
                    COALESCE(c.userlogin,'') AS login_usuario_app
                  FROM 
                    rrhh.tb_personal a
                    LEFT JOIN session.tb_users_app c ON a.id_personal = c.id_personal 
                  WHERE 
                    a.id_personal=$id_asesor  
      	         ";      	
                         
        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        $row = pg_fetch_assoc($result, null);        

        $output['datosAsesor']['tipoCliente']=$row['tipoasesor'];
        $output['datosAsesor']['nombres']=$row['nombres'];
        $output['datosAsesor']['apellidos']=$row['apellidos'];
        $output['datosAsesor']['tipoIdentificacion']=$row['tipoidentificacion'];
        $output['datosAsesor']['numeroIdentificacion']=$row['numeroidentificacion'];
        $output['datosAsesor']['tipoNotificacion']=$row['tiponotificacion'];
        $output['datosAsesor']['telefono_fijo']=$row['telefono_fijo'];
        $output['datosAsesor']['telefono_movil']=$row['telefono_movil'];
        $output['datosAsesor']['correo_electronico']=$row['correo_electronico'];
        $output['datosAsesor']['direccion']=$row['direccion'];
        $output['datosAsesor']['id_ciudad']=$row['id_ciudad'];
        $output['datosAsesor']['id_departamento']=$row['id_departamento'];
        $output['datosAsesor']['id_pais']=$row['id_pais'];
        $output['datosAsesor']['id_asesor']=$row['id_asesor'];
        $output['datosAsesor']['porcentaje_ganancia']=$row['porcentaje_ganancia'];
        $output['datosAsesor']['foto']=$row['foto'];
        $output['datosAsesor']['estado_empleado']=$row['estado_empleado'];
        $output['datosAsesor']['estado_usuario_app']=$row['estado_usuario_app'];
        $output['datosAsesor']['login_usuario_app']=$row['login_usuario_app'];

        return $output;

    }

    function retornarCantAsesoresPorDoc($numero_identificacion){

        $query = "SELECT
      	            COUNT(numero_identificacion) AS cant_asesores
                  FROM 
                    rrhh.tb_personal 
                  WHERE 
                    numero_identificacion='$numero_identificacion'  
      	         ";
         
        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        $row = pg_fetch_array($result, null);

        return $row['cant_asesores'];

    }

    function retornarIdAsesoresPorDoc($numero_identificacion){

        $query = "SELECT
      	            id_personal AS id_asesor
                  FROM 
                    rrhh.tb_personal 
                  WHERE 
                    numero_identificacion='$numero_identificacion'  
      	         ";
         
        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        $row = pg_fetch_array($result, null);

        return $row['id_asesor'];

    }

    function consultarAsesores($datosAsesor){

      $query="SELECT
                a.id_personal AS id_asesor,
                (SELECT
                   aa.abreviatura
                 FROM
                   tipos.tb_tiposidentificacion aa
                 WHERE
                   aa.id_tipo_ident=a.id_tipo_identificacion      
                )||' '||a.numero_identificacion AS identificacion,
                UPPER(a.nombres)||' '||UPPER(a.apellidos) AS asesor,                
                (SELECT
                   aa.descripcion
                 FROM
                   tipos.tb_tipos_asesores aa
                 WHERE
                   aa.id_tipo_asesor=a.id_tipo_asesor      
                ) AS tipo
              FROM  
                rrhh.tb_personal a
              WHERE
                CAST(a.numero_identificacion AS VARCHAR) LIKE '%".$datosAsesor->numero_identificacion."%' 
                AND UPPER(a.nombres) LIKE UPPER('%".$datosAsesor->nombres."%')
                AND UPPER(a.apellidos) LIKE UPPER('%".$datosAsesor->apellidos."%')  
                AND (a.numero_celular LIKE '%".$datosAsesor->telefono."%' OR 
                     a.numero_telefono LIKE '%".$datosAsesor->telefono."%'
                    )
             ";         

      $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());

	    if(pg_num_rows($result)>0){

	  	  $i = 0;	
	  	  while($row = pg_fetch_array($result, null)){	  	
	       $output['lista_asesores'][$i]['id_asesor'] = $row['id_asesor'];
	       $output['lista_asesores'][$i]['identificacion']  = $row['identificacion'];
	       $output['lista_asesores'][$i]['asesor']  = $row['asesor'];
	       $output['lista_asesores'][$i]['telefonos']  = $row['telefonos'];
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

    function guardarAsesores($datosAsesor){  

      if(isset($datosAsesor->telefono_fijo))  
        $telefono_fijo=$datosAsesor->telefono_fijo;
      else
        $telefono_fijo='';

      if(isset($datosAsesor->telefono_movil))
        $telefono_movil=$datosAsesor->telefono_movil;
      else
        $telefono_movil='';

      if(isset($datosAsesor->correo_electronico))
        $correo_electronico=$datosAsesor->correo_electronico;
      else
        $correo_electronico='';  

      if(isset($datosAsesor->direccion))
        $direccion=$datosAsesor->direccion;
      else
        $direccion='';  

      if(isset($datosAsesor->id_ciudad))
        $id_ciudad=$datosAsesor->id_ciudad;
      else
        $id_ciudad='null';   

      if(isset($datosAsesor->id_departamento))
        $id_departamento=$datosAsesor->id_departamento;
      else
        $id_departamento='null';     

      if(isset($datosAsesor->id_pais))
        $id_pais=$datosAsesor->id_pais;
      else
        $id_pais='null';       	

      if(isset($datosAsesor->id_asesor)){     

        if($this->retornarCantAsesores($datosAsesor->id_asesor)==1){

          if(!isset($_SESSION))
          	session_start();

          //Actualizaciones en la tabla de tb_personal, correspondientes a los datos personales del empleado o asesor
          $query="UPDATE 
                    rrhh.tb_personal
                  SET 
                    id_tipo_asesor=".$datosAsesor->tipoAsesor.",
                    nombres=UPPER('".$datosAsesor->nombres."'),
                    apellidos=UPPER('".$datosAsesor->apellidos."'),
                    id_tipo_identificacion=".$datosAsesor->tipoIdentificacion.",
                    numero_identificacion='".$datosAsesor->numeroIdentificacion."',
                    id_tipo_notificacion=".$datosAsesor->tipoNotificacion.",                    
                    numero_telefono='".$telefono_fijo."',
                    numero_celular='".$telefono_movil."',
                    correo_electronico=UPPER('".$correo_electronico."'),
                    direccion=UPPER('".$direccion."'),
                    id_ciudad=".$id_ciudad.",
                    id_departamento=".$id_departamento.",
                    id_pais=".$id_pais.",
                    id_user_modificacion=".$_SESSION['id_user'].",
                    fecha_modificacion=CURRENT_TIMESTAMP,
                    porcentaje_ganancia=".$datosAsesor->porcentaje_comision.",
                    photo='".$datosAsesor->foto."',
                    estado = CASE WHEN UPPER('".$datosAsesor->empleado_activo."')='TRUE' THEN '1' ELSE '0' END 
                  WHERE
                    id_personal=".$datosCliente->id_cliente.""; 

          $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());

          if($datosAsesor->password!=''){
            //Actualizaciones en la tabla de usuarios del aplicativo
            $query="UPDATE
                      session.tb_users_app
                    SET
                      password = MD5('".$datosAsesor->password."'),
                      estado = CASE WHEN UPPER('".$datosAsesor->usuario_activo."')='TRUE' THEN '1' ELSE '0' END
                    WHERE
                      id_personal = ".$datosAsesor->id_asesor."  
                   ";

            $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
          }

          
          $respuesta['respuesta']='3';
          $respuesta['id_asesor']=$datosAsesor->id_asesor;

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