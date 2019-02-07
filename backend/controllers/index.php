<?php  

  $data=file_get_contents("php://input");
  $request=json_decode($data);  

  if(isset($request->accion)){
  	if($request->accion=='validateLogin'){
  	  require_once('services/ValidateLogin.php');	
      $obj = new ValidateLogin();      
      $obj->validarLogin();
  	}
  	if($request->accion=='validateSession'){
  	  require_once('services/ValidateSession.php');	
  	  $obj = new ValidateSession();	
  	  $obj->validarSession($request->user,$request->password);	            
  	}
  	if($request->accion=='tiposIdentificacion'){
  	  require_once('services/Clientes.php');	  	  
      $obj = new Clientes(); 
      $obj->getTiposIdentificacion();
  	}
    if($request->accion=='tiposClientes'){
      require_once('services/Clientes.php');        
      $obj = new Clientes(); 
      $obj->getTiposClientes();
    }

    //Obtiene la zona a la cual pertenece un barrio
    if($request->accion=='getZonaBarrioCali'){
      require_once('services/Zonas.php');        
      $obj = new Zonas();       
      $obj->getZonaBarrioCali($request->id_barrio);
    }

    //Listar tipos asesores
    if($request->accion=='listarTiposAsesores'){      
      require_once('services/Asesores.php');        
      $obj = new Asesores(); 
      $obj->getTiposAsesores();
    }

    if($request->accion=='consultarClientes'){
      require_once('services/Clientes.php');        
      $obj = new Clientes(); 
      $obj->consultarClientes($request->clienteBusq);
    }

    if($request->accion=='consultarInmuebles'){
      print_r($request);exit;
      require_once('services/Clientes.php');        
      $obj = new Clientes(); 
      $obj->consultarClientes($request->clienteBusq);
    }

    if($request->accion=='datosClientePorID'){
      require_once('services/Clientes.php');        
      $obj = new Clientes(); 
      $obj->datosClientePorID($request->id_cliente);
    }

    if($request->accion=='saveAsesores'){
      if(isset($request->foto)){
        $foto = $request->foto;
      }
      else{
        $foto = null;
      }
      require_once('services/Asesores.php');        
      $obj = new Asesores();       
      $obj->saveAsesores($request->datos_asesor,$foto);
    }

    if($request->accion=='listaRedesSociales'){
      require_once('services/Clientes.php');        
      $obj = new Clientes(); 
      $obj->getListaRedesSociales();
    }

    if($request->accion=='consultarAsesores'){
      require_once('services/Asesores.php');        
      $obj = new Asesores(); 
      $obj->getListaAsesores($request->asesorBusq);
    }

    if($request->accion=='informacionInmobiliaria'){
      require_once('services/Inmobiliaria.php');        
      $obj = new Inmobiliaria(); 
      $obj->getInformacionInmobiliaria();
    }

    //Para guardar o actualizar la información de la inmobiliaria
    if($request->accion=='guardarInformacionInmobiliaria'){
      if(isset($request->foto)){
        $foto = $request->foto;
      }
      else{
        $foto = null;
      }
      require_once('services/Inmobiliaria.php');        
      $obj = new Inmobiliaria(); 
      $obj->guardarInformacionInmobiliaria($request->datos_inmobiliaria,$foto);
    }

    if($request->accion=='guardarRedesSocialesCliente'){
      require_once('services/Clientes.php');        
      $obj = new Clientes(); 
      $obj->guardarRedesSocialesCliente($request->datosRedesCliente);
    }

    if($request->accion=='guardarClasificacionBarrio'){
      require_once('services/Zonas.php');        
      $obj = new Zonas();       
      $obj->guardarClasificacionBarrio($request->clasificacion);
    }

    if($request->accion=='listarRedesSocialesCliente'){
      require_once('services/Clientes.php');        
      $obj = new Clientes(); 
      $obj->listarRedesSocialesCliente($request->id_cliente);
    }

    //Servicio que entrega la lista de barrios asociados a una zona
    if($request->accion=='listarBarriosZona'){
      require_once('services/Zonas.php');        
      $obj = new Zonas(); 
      $obj->listarBarriosZona($request->id_sector,$request->id_ciudad);
    }

    //Servicio para des-asociar un barrio de una zona
    if($request->accion=='borrarBarrioZona'){      
      require_once('services/Zonas.php');        
      $obj = new Zonas(); 
      $obj->borrarBarrioZona($request->id_zona_barrio);
    }

    if($request->accion=='borrarCuentaRedSocialCliente'){
      require_once('services/Clientes.php');        
      $obj = new Clientes(); 
      $obj->borrarRedSocialCliente($request->id_red_social_cliente);
    }

    if($request->accion=='caracteristicasTiposInmuebles'){
      require_once('services/Inmuebles.php');        
      $obj = new Inmuebles();       
      $obj->caracteristicasTiposInmuebles($request->id_tipo_inmueble);
    }

    if($request->accion=='borrarCaracteristicasInmuebles'){
      require_once('services/Inmuebles.php');        
      $obj = new Inmuebles(); 
      $obj->borrarCaracteristicasTiposInmuebles($request->id_caracteristicas_tipo_inmueble);
    }

    if($request->accion=='seleccionarCaracteristicaInmueble'){
      require_once('services/Inmuebles.php');        
      $obj = new Inmuebles(); 
      $obj->seleccionarCaracteristicaInmueble($request->id_caracteristicas_tipo_inmueble);
    }

    if($request->accion=='guardarClientes'){
      require_once('services/Clientes.php');        
      $obj = new Clientes(); 
      $obj->guardarClientes($request->datosCliente);
    }

    if($request->accion=='guardarCaracteristicaTipoInmueble'){
      require_once('services/Inmuebles.php');        
      $obj = new Inmuebles(); 
      $obj->guardarCaracteristicaTipoInmueble($request->datosCaracteristicaTipoInmueble);
    }

  	if($request->accion=='listaPaises'){
  	  require_once('services/Paises.php');	  	  
      $obj = new Paises(); 
      $obj->listaPaises();
  	}
    if($request->accion=='listaMonedas'){
      require_once('services/Monedas.php');        
      $obj = new Monedas(); 
      $obj->listaMonedas();
    }
    if($request->accion=='getConversion'){
      require_once('services/Monedas.php');        
      $obj = new Monedas(); 
      $obj->getConversion($request->moneda);
    }
  	if($request->accion=='tiposInmuebles'){
  	  require_once('services/Inmuebles.php');	  	  
      $obj = new Inmuebles(); 
      $obj->listaTiposInmuebles();
  	}
  	if($request->accion=='tiposCaracteristicasInm'){
  	  require_once('services/Inmuebles.php');	  	  
      $obj = new Inmuebles(); 
      $obj->listaTiposCaracteristicas();
  	}
    if($request->accion=='getCriteriosDiligenciamiento'){
      require_once('services/Inmuebles.php');       
      $obj = new Inmuebles(); 
      $obj->getCriteriosDiligenciamiento();
    }
    if($request->accion=='tiposCaracteristicasInmuebles'){
      require_once('services/Inmuebles.php');       
      $obj = new Inmuebles(); 
      $obj->listaTiposCaracteristicasInmuebles();
    }
  	if($request->accion=='listaDepartamentos'){
  	  require_once('services/Departamentos.php');	  	  
      $obj = new Departamentos(); 
      $obj->listaDepartamentos($request->id_pais);
  	}
  	if($request->accion=='listaCiudades'){
  	  require_once('services/Ciudades.php');	  	  
      $obj = new Ciudades(); 
      $obj->listaCiudades($request->id_departamento);
  	}    
  	if($request->accion=='listaZonas'){
  	  require_once('services/Zonas.php');	  	  
      $obj = new Zonas(); 
      $obj->listaZonas($request->id_ciudad);
  	}
    //WS que lista las zonas o sectores definidos para cada ciudad
  	if($request->accion=='listaSectores'){
  	  require_once('services/Sectores.php');	  	  
      $obj = new Sectores(); 
      $obj->listaSectores($request->id_ciudad);
  	}
    //Por ahora para la ciudad de Cali. Devuelve las comunas y estratos
    if($request->accion=='getComunasEstratosCiudad'){
      require_once('services/Inmuebles.php');        
      $obj = new Inmuebles(); 
      $obj->getComunasEstratosCiudad($request->id_ciudad);
    }
  	if($request->accion=='tiposNotificacion'){
  	  require_once('services/Clientes.php');
  	  $obj = new Clientes();
  	  $obj->getTiposNotificacion();	            
  	}
  	if($request->accion=='closeSession'){
  	  require_once('services/CloseSession.php');
  	  $obj = new CloseSession();	            
  	}
  	if($request->accion=='resetPassword'){
      require_once('services/ValidateSession.php');
      $obj = new ValidateSession();  	  
  	  $obj->resetPassword($request->password_act,$request->password_new);	            
  	}
  }  
  else{
  	$output["respuesta"]="Datos incorrectos";
  	echo json_encode($output);
  }

?>