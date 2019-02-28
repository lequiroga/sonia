<?php

	require_once("../clases/ClientesSQL.php");
	require_once("../clases/AutenticaAPI.php");

	class Clientes{

		private $objClientes;		

		function Clientes(){
          $this->objClientes=new ClientesSQL();
		}

		function getTiposIdentificacion(){        	
        	$output = $this->objClientes->getTiposIdentificacion();
	  		echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
		}

		function getListaRedesSociales(){
		    $output = $this->objClientes->getListaRedesSociales();
	  		echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);	
		}

		function borrarRedSocialCliente($id_red_social_cliente){
			$output = $this->objClientes->borrarRedSocialCliente($id_red_social_cliente);
	  		echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
		}

		function consultarOtrosDatosCliente($id_client){

			$output = $this->objClientes->consultarOtrosDatosCliente($id_client);
	  		echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);

		}

		function getTiposClientes(){        

            $autAPI   = new AutenticaAPI();
			$datosAPI = $autAPI->retornarDatosAPI('wasi','tipos_clientes');

			$data = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"]), true );

  			$data1 = array();

            $j=0;
  			for($i=0;$i<count($data);$i++){

    		  if(isset($data[$i]['id_client_type']) && $data[$i]['public']){

      			$data1[$j]=new stdClass();
      			$data1[$j]->id_tipo_cliente=$data[$i]['id_client_type'];
      			$data1[$j]->descripcion=$data[$i]['name'];

      			$j++;

    		  }    

  			}

  			$data = json_encode($data1);
  			//print_r($data);exit;

  			echo $data;			   

        	/*$output = $this->objClientes->getTiposClientes();
	  		echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);*/
		}

		//Consultar los inmuebles asociados a un cliente
		function getPropiedadesClientes($id_cliente,$id_user){

			$autAPI   = new AutenticaAPI();
			$datosAPI = $autAPI->retornarDatosAPI('wasi','propiedades_clientes');

			$data = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].$id_cliente.'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"]), true );

			$datosAPI = $autAPI->retornarDatosAPI('wasi','usuarios_por_id');
          	$data_user = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].$id_user.'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"]), true );         	

			$total = $data['total'];
			unset($data['total']);
  			$data1 = array();  			

            $j=0;
            $data_property = array();

  			for($i=0;$i<$total;$i++){

  			  $id_property = $data[$i]['id_property'];
  			  $datosAPI = $autAPI->retornarDatosAPI('wasi','propiedad_por_id');  			  
			  $data_property['propiedades'][$j] = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].$id_property.'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"]), true );
			  
			  $data_property['propiedades'][$j]['tipo_cliente']=$data[$i]['client_type_label'];

			  $datosAPI = $autAPI->retornarDatosAPI('wasi','usuarios_por_id');
          	  $data_user_prop = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].$data_property['propiedades'][$j]['id_user'].'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"]), true ); 

          	  $data_property['propiedades'][$j]['asesor'] = $data_user_prop['first_name'].' '.$data_user_prop['last_name'];
          	  $data_property['propiedades'][$j]['asesor_telefono'] = $data_user_prop['cell_phone'];
          	  $data_property['propiedades'][$j]['asesor_email'] = $data_user_prop['email'];          	  

			  $datosAPI = $autAPI->retornarDatosAPI('wasi','propiedad_visitas');  			  
			  $data_visitas = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].$id_property.'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"]), true ); 			 

			  $data_property['propiedades'][$j]['visitas']=$data_visitas['visits'];

			  $j++;   		  

  			} 

  			$data_property['asesor_cliente']=$data_user['first_name'].' '.$data_user['last_name'];  			

  			$data = json_encode($data_property);  			

  			echo $data;

		}

		function datosUsuarioCliente($id_user){

			$autAPI   = new AutenticaAPI();
			$datosAPI = $autAPI->retornarDatosAPI('wasi','usuarios_por_id');
          	$data_user = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].$id_user.'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"]), true ); 

          	$data = json_encode($data_user); 

          	echo $data;
		}

		function getClientePorID($id_client){
			$autAPI   = new AutenticaAPI();
			$datosAPI = $autAPI->retornarDatosAPI('wasi','clientes_por_id');

			$data = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].$id_client.'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"]), true );			

			if($data['status']!='error'){

				unset($data['status']);
				$data1=array();
				$data1['cant']=1;	
				$data1['lista_clientes'][0]=$data;
				$data = json_encode($data1);

			}
			else{
				$data1['cant']=0;
			}			  			

  			echo $data;	

		}

		function consultarClientes($clienteBusq){    			   

            $autAPI   = new AutenticaAPI();
			$datosAPI = $autAPI->retornarDatosAPI('wasi','clientes_todos');

			$data = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"].'&skip=0&take=100'), true );	

			$totalClientes = $data['total'];
        	$totalClientesIt = $totalClientes;
        	$cant_iteraciones = 0;

        	while($totalClientesIt>0){
          	  $totalClientesIt-=100;
         	  $cant_iteraciones++;
        	}
        	//print_r($cant_iteraciones);exit;

        	$index = 0;   
        	$clientesRespuesta = array();   

        	for($i=0;$i<$cant_iteraciones;$i++){

          	  $inicial=$i*100;
          	  if($i==0){
          	  	$data1 = $data;
          	  }
          	  else{
          	  	$data1 = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"].'&skip='.$inicial.'&take=100'), true );          	  	
          	  }          	  
          	  
          	  unset($data1['total']);
           	  unset($data1['status']);

          	  for($j=0;$j<count($data1);$j++){          	  	

            	$validaTipoCliente = 0;

            	if(isset($clienteBusq->id_tipo_cliente)&&$clienteBusq->id_tipo_cliente!=null){            		
            	  if($data1[$j]['id_client_type']==$clienteBusq->id_tipo_cliente){
            	  	$validaTipoCliente = 1;
            	  }
            	  else{
            	  	$validaTipoCliente=0;
            	  }
            	}
            	else{
            	  $validaTipoCliente=1;	
            	}   

            	$validaPais = 0;     

            	if(isset($clienteBusq->id_pais)&&$clienteBusq->id_pais!=null){            		
            	  if($data1[$j]['id_country']==$clienteBusq->id_pais){
            	  	$validaPais=1;
            	  }
            	  else{
            	  	$validaPais=0;
            	  }
            	}
            	else{
            	  $validaPais=1;	
            	}

            	$validaDepartamento = 0;

            	if(isset($clienteBusq->id_departamento)&&$clienteBusq->id_departamento!=null){
            	  if($data1[$j]['id_region']==$clienteBusq->id_departamento){
            	  	$validaDepartamento=1;
            	  }
            	  else{
            	  	$validaDepartamento=0;
            	  }
            	}
            	else{
            	  $validaDepartamento=1;	
            	}

            	$validaCiudad = 0;

            	if(isset($clienteBusq->id_ciudad)&&$clienteBusq->id_ciudad!=null){
            	  if($data1[$j]['id_city']==$clienteBusq->id_ciudad){
            	  	$validaCiudad=1;
            	  }
            	  else{
            	  	$validaCiudad=0;
            	  }
            	}
            	else{
            	  $validaCiudad=1;	
            	}

            	$validaAsesor = 0;

            	if(isset($clienteBusq->id_asesor)&&$clienteBusq->id_asesor!=null&&$clienteBusq->id_asesor!=""){
            	  if($data1[$j]['id_user']==$clienteBusq->id_asesor){
            	  	$validaAsesor=1;
            	  }
            	  else{
            	  	$validaAsesor=0;
            	  }
            	}
            	else{
            	  $validaAsesor=1;	
            	}

            	$validaTelefono = 0;

            	if(isset($clienteBusq->telefono)&&$clienteBusq->telefono!=null&&$clienteBusq->telefono!=''){

            	  $pos = strpos($data1[$j]['phone'], ((String)$clienteBusq->telefono));	
            	  $pos1 = strpos($data1[$j]['cell_phone'], ((String)$clienteBusq->telefono));	
            	  if(($pos !== false)||($pos1 !== false)){
            	  	$validaTelefono=1;
            	  }
            	  else{
            	  	$validaTelefono=0;
            	  }
            	}
            	else{
            	  $validaTelefono=1;	
            	}

            	$validaFechas = 0;

            	if((isset($clienteBusq->fecha_inicial)&&isset($clienteBusq->fecha_final))&&($clienteBusq->fecha_final!=''&&$clienteBusq->fecha_inicial!='')){

              	  if(substr($data1[$j]['updated_at'], 0,1) != '0'){

                	if(( date($clienteBusq->fecha_inicial) <= date($data1[$j]['updated_at']) )&&( date($clienteBusq->fecha_final) >= date($data1[$j]['updated_at']) )){
                  	  $validaFechas = 1;
                	}
                	else{
                  	  $validaFechas = 0;
                	}

              	  }
              	  else{
                
                    if(( date($clienteBusq->fecha_inicial) <= date($data1[$j]['created_at']) )&&( date($clienteBusq->fecha_final) >= date($data1[$j]['created_at']) )){
                  	  $validaFechas = 1;
                    }
                    else{
                  	  $validaFechas = 0;
                    }

              	  }
                }
                else{
              	  $validaFechas = 1;
                }

            	$validaNombres = 0;

            	if(isset($clienteBusq->nombres)&&$clienteBusq->nombres!=null&&$clienteBusq->nombres!=''){
            	  $pos = strpos(strtoupper($data1[$j]['first_name']),strtoupper($clienteBusq->nombres));	
            	  if($pos !== false){
            	  	$validaNombres=1;
            	  }
            	  else{
            	  	$validaNombres=0;
            	  }
            	}
            	else{
            	  $validaNombres=1;	
            	}

            	$validaApellidos = 0;

            	if(isset($clienteBusq->apellidos)&&$clienteBusq->apellidos!=null&&$clienteBusq->apellidos!=''){
            	  $pos = strpos(strtoupper($data1[$j]['last_name']),strtoupper($clienteBusq->apellidos));	
            	  if($pos !== false){
            	  	$validaApellidos=1;
            	  }
            	  else{
            	  	$validaApellidos=0;
            	  }
            	}
            	else{
            	  $validaApellidos=1;	
            	}

            	$validaNumeroIdentificacion = 0;

            	if(isset($clienteBusq->numero_identificacion)&&$clienteBusq->numero_identificacion!=null&&$clienteBusq->numero_identificacion!=''){
            	  $pos = strpos($data1[$j]['identification'],((String)$clienteBusq->numero_identificacion));	
            	  if($pos !== false){
            	  	$validaNumeroIdentificacion=1;
            	  }
            	  else{
            	  	$validaNumeroIdentificacion=0;
            	  }
            	}
            	else{
            	  $validaNumeroIdentificacion=1;	
            	}

            	if($validaTipoCliente==1 && $validaPais==1 && $validaDepartamento==1 && $validaCiudad==1 && $validaTelefono==1 && $validaNombres==1 && $validaApellidos==1 && $validaNumeroIdentificacion==1 && $validaFechas==1 && $validaAsesor==1){
            	  $clientesRespuesta[$index]=$data1[$j];
            	  $index++;	
            	}

              }	
      		  		
            }  			

  			//print_r($clientesRespuesta);exit;

  			$data1 = array();
        	if($index>0){
          		$data1['cant']=$index;
          		$data1['lista_clientes']=$clientesRespuesta;
        	}
        	else{
          	 	$data1['cant']=0;
        	}
        	$data = json_encode($data1);  			

  			echo $data;			   

        	/*$output = $this->objClientes->getTiposClientes();
	  		echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);*/
            	
		}

		function getTiposNotificacion(){        	
        	$output = $this->objClientes->getTiposNotificacion();
	  		echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
		}

		function guardarClientes($datosCliente){			

			if(!isset($_SESSION))
			  session_start();

			if(isset($_SESSION['id_user_app_externo'])&&$_SESSION['id_user_app_externo']!=''&&$_SESSION['id_user_app_externo']!=null&&$_SESSION['id_user_app_externo']!='null'&&$_SESSION['id_user_app_externo']!='NULL'&&$_SESSION['id_user_app_externo']!='Null'){

				$datosCliente->id_user = $_SESSION['id_user_app_externo'];				

				$autAPI   = new AutenticaAPI();

				if(!isset($datosCliente->id_client)){

					$postdata = http_build_query($datosCliente);

					$datosAPI = $autAPI->retornarDatosAPI('wasi','agregar_cliente');
					$data = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"].'&'.$postdata), true );
					//print_r($data);exit;
				}
				else{

					if(isset($datosCliente->email)){
						$email_noti = $datosCliente->email;
					    unset($datosCliente->email);
					}					
					
					$postdata = http_build_query($datosCliente);

					$datosAPI = $autAPI->retornarDatosAPI('wasi','actualizar_cliente');
					$data = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].$datosCliente->id_client.'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"].'&'.$postdata), true );
					
					if(isset($email_noti))
						$data['email_noti'] = $email_noti;

				}				

				if($data['status']=='success'){


					if(isset($datosCliente->tipoNotificacion)&&$datosCliente->tipoNotificacion!=null)
						$data['id_tipo_notificacion'] = $datosCliente->tipoNotificacion;
					else
						$data['id_tipo_notificacion'] = 'null';

					if(isset($datosCliente->tipoIdentificacion)&&$datosCliente->tipoIdentificacion!=null)
						$data['id_tipo_identificacion'] = $datosCliente->tipoIdentificacion;
					else
						$data['id_tipo_identificacion'] = 'null';

					$output = $this->objClientes->guardarClientes($data);
	  				echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);

				}
				else{

					//No se pudo actualizar en la API
					$output['respuesta'] = '5';

				}

			}
			else{

				//El usuario no está registrado en WASI
				$output['respuesta'] = '6';


			}			

			
		}

		function guardarRedesSocialesCliente($datosRedesCliente){			
			$output = $this->objClientes->guardarRedesSocialesCliente($datosRedesCliente);
	  		echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
		}

		function listarRedesSocialesCliente($id_cliente){
			$output = $this->objClientes->listarRedesSocialesCliente($id_cliente);
	  		echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
		}

		function datosClientePorID($id_cliente){			
			$output = $this->objClientes->datosClientePorID($id_cliente);
	  		echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
		}

		function consultarClientesInternos($datosCliente){			
			$output = $this->objClientes->consultarClientes($datosCliente);
	  		echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
		}

	}
	

?>
