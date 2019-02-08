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

    //Para listar los tipos de asesores, a excepción de 'ADMINISTRADOR GENERAL'
    function getTiposAsesores(){  
            
			$query = "SELECT 
	            		id_tipo_asesor AS id_tipo_asesor,
	            		descripcion AS descripcion 
	          	  FROM 
	            		tipos.tb_tipos_asesores
                WHERE
                  descripcion NOT LIKE 'ADMINISTRADOR GENERAL'  
                  ";

			$result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());

			if(pg_num_rows($result)>0){

	  			$i = 0;	
	  			while($row = pg_fetch_array($result, null)){	  	
	    			$output['tipos_asesores'][$i]['id_tipo_asesor'] = $row['id_tipo_asesor'];
	    			$output['tipos_asesores'][$i]['descripcion']  = $row['descripcion'];
	    			$i++;	    
	  			}	  

                // Liberando el conjunto de resultados
				pg_free_result($result);				
	  			
			}

			return $output;       

    }

    function guardarImagenAsesores($id_asesor,$urlImagen){

      $query = "UPDATE 
                  rrhh.tb_personal
                SET
                  photo = '$urlImagen'   
                WHERE
                  id_personal=$id_asesor";

      $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());

    }

    function getTiposAsesoresAdministrador(){  
            
      $query = "SELECT 
                  id_tipo_asesor AS id_tipo_asesor,
                  descripcion AS descripcion 
                FROM 
                  tipos.tb_tipos_asesores";

      $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());

      if(pg_num_rows($result)>0){

          $i = 0; 
          while($row = pg_fetch_array($result, null)){      
            $output['tipos_asesores'][$i]['id_tipo_asesor'] = $row['id_tipo_asesor'];
            $output['tipos_asesores'][$i]['descripcion']  = $row['descripcion'];
            $i++;     
          }   

                // Liberando el conjunto de resultados
        pg_free_result($result);        
          
      }

      return $output;       

    }

    function retornarCantAsesores($id_asesor){

        $query = "SELECT
      	            COUNT(id_personal) AS cant_asesores
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
                    UPPER(a.address) AS direccion,
                    a.id_ciudad AS id_ciudad,
                    a.id_departamento AS id_departamento,
                    a.id_pais AS id_pais,
                    a.id_personal AS id_asesor,
                    a.porcentaje_ganancia AS porcentaje_ganancia,
                    a.photo AS foto,
                    a.estado AS empleado_activo,
                    COALESCE(c.estado,1) AS usuario_activo,
                    COALESCE(UPPER(c.userlogin),'') AS usuario_aplicativo
                  FROM 
                    rrhh.tb_personal a
                    LEFT JOIN session.tb_users_app c ON a.id_personal = c.id_personal 
                  WHERE 
                    a.id_personal=$id_asesor  
      	         ";    

                 //print_r($query);exit;  	
                         
        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        $row = pg_fetch_assoc($result, null);        

        $output['datosAsesor']['tipoAsesor']=$row['tipoasesor'];
        $output['datosAsesor']['nombres']=$row['nombres'];
        $output['datosAsesor']['apellidos']=$row['apellidos'];
        $output['datosAsesor']['tipoIdentificacion']=$row['tipoidentificacion'];
        $output['datosAsesor']['numeroIdentificacion']=$row['numeroidentificacion'];
        $output['datosAsesor']['tipoNotificacion']=$row['tiponotificacion'];
        $output['datosAsesor']['telefono_fijo']=$row['telefono_fijo'];
        $output['datosAsesor']['telefono_movil']=$row['telefono_movil'];
        $output['datosAsesor']['correo_electronico']=$row['correo_electronico'];
        $output['datosAsesor']['direccion']=$row['direccion'];
        $output['datosAsesor']['codigoCiudad']=$row['id_ciudad'];
        $output['datosAsesor']['codigoDepartamento']=$row['id_departamento'];
        $output['datosAsesor']['codigoPais']=$row['id_pais'];
        $output['datosAsesor']['id_asesor']=$row['id_asesor'];
        $output['datosAsesor']['porcentaje_comision']=$row['porcentaje_ganancia'];
        $output['datosAsesor']['foto_asesor']=$row['foto'];
        $output['datosAsesor']['empleado_activo']=$row['empleado_activo'];
        $output['datosAsesor']['usuario_activo']=$row['usuario_activo'];
        $output['datosAsesor']['usuario_aplicativo']=$row['usuario_aplicativo'];

        //print_r($output);exit;

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

    //Cantidad de usuarios asesores
    function retornarCantUsuariosPorIDAsesor($id_asesor){

        $query = "SELECT
                    COUNT(numero_identificacion) AS cant_asesores
                  FROM 
                    rrhh.tb_personal 
                  WHERE 
                    id_personal=$id_asesor
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
                ) AS tipo,
                a.photo AS foto
              FROM  
                rrhh.tb_personal a
              WHERE
                CAST(a.numero_identificacion AS VARCHAR) LIKE '%".$datosAsesor->numero_identificacion."%' 
                AND UPPER(a.nombres) LIKE UPPER('%".$datosAsesor->nombres."%')
                AND UPPER(a.apellidos) LIKE UPPER('%".$datosAsesor->apellidos."%')
                AND a.id_tipo_asesor <> 5                
             ";         

      $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());

	    if(pg_num_rows($result)>0){

	  	  $i = 0;	
	  	  while($row = pg_fetch_array($result, null)){	  	
	       $output['lista_asesores'][$i]['id_asesor'] = $row['id_asesor'];
	       $output['lista_asesores'][$i]['identificacion']  = $row['identificacion'];
	       $output['lista_asesores'][$i]['asesor']  = $row['asesor'];
	       $output['lista_asesores'][$i]['foto']  = $row['foto'];
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

    /*Consulta la cantidad de usuarios creados para la inmobiliaria existentes que coincidan con el parámetro enviado $usuario_aplicativo*/
    function consultarUsuariosCreados($usuario_aplicativo){

        $query = "SELECT
                    COUNT(a.id_personal) AS cant_usuarios
                  FROM 
                    session.tb_users_app a 
                  WHERE 
                    UPPER(a.userlogin)=UPPER('$usuario_aplicativo')
                    AND a.id_personal IN
                    (
                      SELECT
                        b.id_personal
                      FROM
                        rrhh.tb_personal b
                      WHERE
                        b.id_inmobiliaria = ".$_SESSION['id_inmobiliaria']."    
                    )
                 ";
         
        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
        $row = pg_fetch_array($result, null);

        return $row['cant_usuarios'];

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

      if(isset($datosAsesor->codigoCiudad))
        $id_ciudad=$datosAsesor->codigoCiudad;
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

      if(isset($datosAsesor->photo))
        $photo=$datosAsesor->photo;
      else
        $photo=null;       	

      if(isset($datosAsesor->empleado_activo)&&($datosAsesor->empleado_activo==true)){
        $empleado_activo = $datosAsesor->empleado_activo;
      }
      else{
        $empleado_activo = false;
      }

      if(isset($datosAsesor->usuario_activo)&&($datosAsesor->usuario_activo==true)){
        $usuario_activo = $datosAsesor->usuario_activo;
      }
      else{
        $usuario_activo = false;
      }

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
                    address=UPPER('".$direccion."'),
                    id_ciudad=".$id_ciudad.",
                    id_departamento=".$id_departamento.",
                    id_pais=".$id_pais.",
                    porcentaje_ganancia=".$datosAsesor->porcentaje_comision.",                    
                    estado = CASE WHEN UPPER('".$empleado_activo."')='1' THEN '1' ELSE '0' END,
                    id_user_modificacion = ".$_SESSION['id_user'].",
                    fecha_modificacion = CURRENT_TIMESTAMP
                  WHERE  
                    id_personal=".$datosAsesor->id_asesor;           

          $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());          

          if($datosAsesor->tipoAsesor!='4'){

            //Consultar la existencia en la tabla de usuarios y si se encontró, se actualiza
            if($this->retornarCantUsuariosPorIDAsesor($datosAsesor->id_asesor)=='1'){

              if(isset($datosAsesor->password) && $datosAsesor->password!=''){
                //Actualizaciones en la tabla de usuarios del aplicativo
                $query = "UPDATE
                            session.tb_users_app
                          SET
                            password = MD5('".$datosAsesor->password."'),
                            estado = CASE WHEN UPPER('".$usuario_activo."')='1' THEN 1 ELSE 0 END,
                            id_user_modificacion = ".$_SESSION['id_user'].",
                            fecha_modificacion = CURRENT_TIMESTAMP
                          WHERE
                            id_personal = ".$datosAsesor->id_asesor;

                            //print_r($query);exit;

                $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());

              }
              else{
                //Actualizaciones en la tabla de usuarios del aplicativo (estado)
                $query = "UPDATE
                            session.tb_users_app
                          SET                        
                            estado = CASE WHEN UPPER('".$usuario_activo."')='1' THEN 1 ELSE 0 END,
                            id_user_modificacion = ".$_SESSION['id_user'].",
                            fecha_modificacion = CURRENT_TIMESTAMP
                          WHERE
                            id_personal = ".$datosAsesor->id_asesor."  
                        ";

                $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());

              }

              if(isset($datosAsesor->motivo)){

                if(!isset($empleado_activo) || $empleado_activo==false){

                  if(isset($usuario_activo) || $usuario_activo==true){

                    $query = "INSERT INTO
                                rrhh.tb_motivos_inactivacion
                                (
                                  id_user,
                                  motivo_modificacion,
                                  estado,
                                  id_user_creacion
                                )  
                              VALUES
                                (
                                  (
                                    SELECT
                                      id_user
                                    FROM
                                      session.tb_users_app
                                    WHERE
                                      id_personal = ".$datosAsesor->id_asesor."
                                  ),
                                  UPPER('".$datosAsesor->motivo."'),
                                  '1',
                                  ".$_SESSION['id_user']."
                                )  
                             ";

                    $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());        

                  }
                  else{

                    $query = "INSERT INTO
                                rrhh.tb_motivos_inactivacion
                                (
                                  id_personal,
                                  id_user,
                                  motivo_modificacion,
                                  estado,
                                  id_user_creacion
                                )  
                              VALUES
                                (
                                  ".$datosAsesor->id_asesor.",
                                  (
                                    SELECT
                                      id_user
                                    FROM
                                      session.tb_users_app
                                    WHERE
                                      id_personal = ".$datosAsesor->id_asesor."
                                  ),
                                  UPPER('".$datosAsesor->motivo."'),
                                  '0',
                                  ".$_SESSION['id_user']."
                                )  
                             ";

                    $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());

                  }                  

                }

                else{

                  $query = "INSERT INTO
                              rrhh.tb_motivos_inactivacion
                              (
                                id_user,
                                motivo_modificacion,
                                estado,
                                id_user_creacion
                              )  
                            VALUES
                              (
                                ".$datosAsesor->id_asesor.",                                
                                UPPER('".$datosAsesor->motivo."'),
                                '1',
                                ".$_SESSION['id_user']."
                              )  
                           ";

                  $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());

                  if(isset($datosAsesor->usuario_activo) || $datosAsesor->usuario_activo==true){

                    $query = "INSERT INTO
                                rrhh.tb_motivos_inactivacion
                                (
                                  id_user,
                                  motivo_modificacion,
                                  estado,
                                  id_user_creacion
                                )  
                              VALUES
                                (
                                  (
                                    SELECT
                                      id_user
                                    FROM
                                      session.tb_users_app
                                    WHERE
                                      id_personal = ".$datosAsesor->id_asesor."
                                  ),
                                  UPPER('".$datosAsesor->motivo."'),
                                  '1',
                                  ".$_SESSION['id_user']."
                                )  
                             ";

                    $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());        

                  }
                  else{

                    $query = "INSERT INTO
                                rrhh.tb_motivos_inactivacion
                                (
                                  id_personal,
                                  id_user,
                                  motivo_modificacion,
                                  estado,
                                  id_user_creacion
                                )  
                              VALUES
                                (
                                  ".$datosAsesor->id_asesor.",
                                  (
                                    SELECT
                                      id_user
                                    FROM
                                      session.tb_users_app
                                    WHERE
                                      id_personal = ".$datosAsesor->id_asesor."
                                  ),
                                  UPPER('".$datosAsesor->motivo."'),
                                  '0',
                                  ".$_SESSION['id_user']."
                                )  
                             ";

                    $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());

                  }

                }                

              }

            }
            else{

              //Se inserta en la tabla de usuarios si no existe
              $query = "INSERT INTO
                          session.tb_users_app
                          (
                            userlogin,
                            password,
                            estado,
                            id_personal,
                            id_user_creacion
                          )
                        VALUES
                          (
                            UPPER('".$datosAsesor->usuario_aplicativo."'),
                            MD5('".$datosAsesor->password."'),
                            1,
                            ".$datosAsesor->id_asesor.",
                            ".$_SESSION['id_user']." 
                          )
                       ";

              $usuario_activo = true;

              $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());

            }            

          }

          
          $respuesta['respuesta']='3';
          /*if(isset($datosAsesor->empleado_activo)&&$datosAsesor->empleado_activo==true){
            $respuesta['empleado_activo']=true;
          }
          else{
            $respuesta['empleado_activo']=false;
          }*/
          $respuesta['empleado_activo']=$empleado_activo;
          $respuesta['usuario_activo']=$usuario_activo;
          $respuesta['id_asesor']=$datosAsesor->id_asesor;

          return $respuesta;

        }
        else{
          $respuesta['respuesta']='4';
          return $respuesta;
        }			

      }

      else{

        if($this->retornarCantAsesoresPorDoc($datosAsesor->numeroIdentificacion)==0){

          if(!isset($_SESSION))
          	session_start();

          $query="INSERT INTO 
                    rrhh.tb_personal
                    (
                      id_tipo_asesor,
                      nombres,
                      apellidos,
                      id_tipo_identificacion,
                      numero_identificacion,
                      id_tipo_notificacion,
                      numero_telefono,
                      numero_celular,
                      correo_electronico,
                      address,
                      id_ciudad,  
                      id_departamento,
                      id_pais,                    
                      id_user_creacion,
                      porcentaje_ganancia,
                      estado,
                      id_inmobiliaria
                    ) 
                  VALUES
                    (
                     ".$datosAsesor->tipoAsesor.",
                     UPPER('".$datosAsesor->nombres."'),
                     UPPER('".$datosAsesor->apellidos."'),
                     ".$datosAsesor->tipoIdentificacion.",
                     '".$datosAsesor->numeroIdentificacion."',
                     ".$datosAsesor->tipoNotificacion.",                    
                     '".$telefono_fijo."',
                     '".$telefono_movil."',
                     UPPER('".$correo_electronico."'),
                     UPPER('".$direccion."'),
                     ".$id_ciudad.",     
                     ".$id_departamento.",
                     ".$id_pais.",               
                     ".$_SESSION['id_user'].",
                     ".$datosAsesor->porcentaje_comision.",
                     '1',
                     ".$_SESSION['id_inmobiliaria']."
                  RETURNING id_personal"; 

                  //print_r($query);exit;

          $empleado_activo = true;        

          //print_r($query);exit;        

          $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());     

          $id_asesor=$this->retornarIdAsesoresPorDoc($datosAsesor->numeroIdentificacion);

          if($datosAsesor->tipoAsesor!='4'){

            if(isset($datosAsesor->password)){
              $password=$datosAsesor->password;  
            }
            else{
              $password=$datosAsesor->numeroIdentificacion;
            }

            if($this->consultarUsuariosCreados($datosAsesor->usuario_aplicativo)==0){

              $query = "INSERT INTO
                          session.tb_users_app
                          (
                            userlogin,
                            password,
                            estado,
                            id_personal,
                            id_user_creacion
                          )
                        VALUES  
                          (
                            UPPER('".$datosAsesor->usuario_aplicativo."'),
                            MD5('$password'),
                            1,
                            ".$id_asesor.",
                            ".$_SESSION['id_user']."
                          )";

              $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());

              $usuario_activo = true;

              $respuesta['respuesta']='1';

            }
            else{

              $usuario_activo = false;

              $respuesta['respuesta']='5';

            }            
            
          
          }
          else{
            $usuario_activo = false;

            $respuesta['respuesta']='1';
          }
          
          //$respuesta['respuesta']='1';
          $respuesta['empleado_activo']=$empleado_activo;
          $respuesta['usuario_activo']=$usuario_activo;
          $respuesta['id_asesor']=$id_asesor;

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