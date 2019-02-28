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

    //Obtiene los ID de las características de los tipos de inmuebles
    function getListaCaracteristicas($caracteristica,$id_tipo_inmueble){    

      $lista_caracteristicas="(";
      $lista_valores=array();      
      $i=0;
      foreach ($caracteristica as $key => $value) {
        $caracteristica_act = (array)$value;
        foreach ($caracteristica_act as $key1 => $value1) {
          $lista_caracteristicas.=$key1; 
          $lista_caracteristicas.=',';
          $lista_valores[$i]=$value1;
          $i++;
        }        
      }
      
      $lista_caracteristicas=substr($lista_caracteristicas, 0, (strlen($lista_caracteristicas)-1) );
      $lista_caracteristicas.=')';

      $query = "SELECT
                  id_caracteristica AS id_caracteristica
                FROM
                  inmuebles.tb_caracteristicas_tipo_inmueble
                WHERE
                  id_caracteristicas_tipo_inmueble IN ".$lista_caracteristicas;

      $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());     
      $output = array();

      if(pg_num_rows($result)>0){

          $i = 0; 
          while($row = pg_fetch_array($result, null)){      
            $output[$i]['id_caracteristica'] = $row['id_caracteristica']; 
            $output[$i]['caracteristica'] = $lista_valores[$i];            
            $i++;     
          } 

                // Liberando el conjunto de resultados
          pg_free_result($result);          

      }
      
      return $output;

    }

    //Guarda los datos del inmueble en la base de datos
    function guardarInmueble($datosInmueble,$datosCaracteristicas){ 

      /*
        [0] => Array
        (
            [id_caracteristica] => 6
            [caracteristica] => 1
        )

        [1] => Array
        (
            [id_caracteristica] => 8
            [caracteristica] => 1
        )
      */     

      $id_property              =   $datosInmueble['id_property'];                  
      $id_user                  =   $datosInmueble['id_user'];                  
      $for_sale                 =   $datosInmueble['for_sale'];                 
      $for_rent                 =   $datosInmueble['for_rent'];                 
      $for_transfer             =   $datosInmueble['for_transfer'];             
      $id_property_type         =   $datosInmueble['id_property_type'];         
      $id_country               =   $datosInmueble['id_country'];               
      $country_label            =   $datosInmueble['country_label'];            
      $id_region                =   $datosInmueble['id_region'];                
      $region_label             =   $datosInmueble['region_label'];             
      $id_city                  =   $datosInmueble['id_city'];                  
      $city_label               =   $datosInmueble['city_label'];               
      $id_location              =   $datosInmueble['id_location'];              
      $location_label           =   $datosInmueble['location_label'];           
      $id_zone                  =   $datosInmueble['id_zone'];                  
      $zone_label               =   $datosInmueble['zone_label'];               
      $id_currency              =   $datosInmueble['id_currency'];              
      $iso_currency             =   $datosInmueble['iso_currency'];             
      $name_currency            =   $datosInmueble['name_currency'];            
      $title                    =   $datosInmueble['title'];                    
      $address                  =   $datosInmueble['address'];                  
      $area                     =   $datosInmueble['area'];                     
      $id_unit_area             =   $datosInmueble['id_unit_area'];             
      $unit_area_label          =   $datosInmueble['unit_area_label'];          
      $built_area               =   $datosInmueble['built_area'];               
      $id_unit_built_area       =   $datosInmueble['id_unit_built_area'];       
      $unit_built_area_label    =   $datosInmueble['unit_built_area_label'];    
      $private_area             =   $datosInmueble['private_area'];             
      $id_unit_private_area     =   $datosInmueble['id_unit_private_area'];     
      $unit_private_area_label  =   $datosInmueble['unit_private_area_label'];  
      $maintenance_fee          =   $datosInmueble['maintenance_fee'];          
      $sale_price               =   $datosInmueble['sale_price'];               
      $sale_price_label         =   $datosInmueble['sale_price_label'];         
      $rent_price               =   $datosInmueble['rent_price'];               
      $rent_price_label         =   $datosInmueble['rent_price_label'];         
      $bedrooms                 =   $datosInmueble['bedrooms'];                 
      $bathrooms                =   $datosInmueble['bathrooms'];                
      $garages                  =   $datosInmueble['garages'];                  
      $floor                    =   $datosInmueble['floor'];                    
      $stratum                  =   $datosInmueble['stratum'];                  
      $observations             =   $datosInmueble['observations'];             
      $video                    =   $datosInmueble['video'];                    
      $id_property_condition    =   $datosInmueble['id_property_condition'];    
      $property_condition_label =   $datosInmueble['property_condition_label']; 
      $id_status_on_page        =   $datosInmueble['id_status_on_page'];        
      $status_on_page_label     =   $datosInmueble['status_on_page_label'];     
      $map                      =   $datosInmueble['map'];                      
      $latitude                 =   $datosInmueble['latitude'];                 
      $longitude                =   $datosInmueble['longitude'];                
      $building_date            =   $datosInmueble['building_date'];            
      $network_share            =   $datosInmueble['network_share'];            
      $visits                   =   $datosInmueble['visits'];                   
      $created_at               =   $datosInmueble['created_at'];               
      $updated_at               =   $datosInmueble['updated_at'];               
      $reference                =   $datosInmueble['reference'];                
      $comment                  =   $datosInmueble['comment'];                  
      $id_rents_type            =   $datosInmueble['id_rents_type'];            
      $rents_type_label         =   $datosInmueble['rents_type_label'];         
      $zip_code                 =   $datosInmueble['zip_code'];                 
      $id_availability          =   $datosInmueble['id_availability'];          
      $availability_label       =   $datosInmueble['availability_label'];       
      $id_publish_on_map        =   $datosInmueble['id_publish_on_map'];        
      $publish_on_map_label     =   $datosInmueble['publish_on_map_label'];     
      $label                    =   $datosInmueble['label'];                    
      $label_color              =   $datosInmueble['label_color'];              
      $owner                    =   $datosInmueble['owner'];                    

      $query = "INSERT INTO
                  inmuebles.tb_inmuebles_registrados
                  (
                    id_property,              
                    id_user,                  
                    for_sale,                 
                    for_rent,                 
                    for_transfer,             
                    id_property_type,         
                    id_country,               
                    country_label,           
                    id_region,                
                    region_label,             
                    id_city,                  
                    city_label,               
                    id_location,              
                    location_label,           
                    id_zone,                  
                    zone_label,               
                    id_currency,              
                    iso_currency,             
                    name_currency,            
                    title,                    
                    address,                  
                    area,                     
                    id_unit_area,             
                    unit_area_label,          
                    built_area,               
                    id_unit_built_area,       
                    unit_built_area_label,    
                    private_area,             
                    id_unit_private_area,     
                    unit_private_area_label,  
                    maitenance_fee,          
                    sale_price,               
                    sale_price_label,         
                    rent_price,               
                    rent_price_label,         
                    bedrooms,                 
                    bathrooms,                
                    garages,                  
                    floor,                    
                    stratum,                  
                    observations,             
                    video,                    
                    id_property_condition,    
                    property_condition_label, 
                    id_status_on_page,        
                    status_on_page_label,     
                    map,                      
                    latitude,                 
                    longitude,                
                    building_date,            
                    network_share,            
                    visits,                   
                    created_at,               
                    updated_at,               
                    reference,                
                    comment,                  
                    id_rents_type,            
                    rents_type_label,         
                    zip_code,                 
                    id_availability,          
                    availability_label,       
                    id_publish_on_map,        
                    publish_on_map_label,     
                    label,                    
                    label_color,              
                    owner                    
                  )
                VALUES
                  (
                    $id_property,              
                    $id_user,                  
                    $for_sale,                 
                    $for_rent,                 
                    $for_transfer,             
                    $id_property_type,         
                    $id_country,               
                    '$country_label',            
                    $id_region,                
                    '$region_label',             
                    $id_city,                  
                    '$city_label',               
                    $id_location,              
                    '$location_label',           
                    $id_zone,                  
                    '$zone_label',               
                    $id_currency,              
                    '$iso_currency',             
                    '$name_currency',            
                    '$title',                    
                    '$address',                  
                    '$area',                     
                    $id_unit_area,             
                    '$unit_area_label',          
                    '$built_area',               
                    $id_unit_built_area,       
                    '$unit_built_area_label',    
                    '$private_area',             
                    $id_unit_private_area,     
                    '$unit_private_area_label',  
                    $maintenance_fee,          
                    $sale_price,               
                    '$sale_price_label',         
                    $rent_price,               
                    '$rent_price_label',         
                    '$bedrooms',                 
                    '$bathrooms',                
                    '$garages',                  
                    '$floor',                    
                    '$stratum',                  
                    '$observations',             
                    '$video',                    
                    $id_property_condition,    
                    '$property_condition_label', 
                    $id_status_on_page,        
                    '$status_on_page_label',     
                    '$map',                      
                    '$latitude',                 
                    '$longitude',                
                    '$building_date',            
                    $network_share,            
                    $visits,                   
                    '$created_at',               
                    '$updated_at',               
                    '$reference',                
                    '$comment',                  
                    $id_rents_type,            
                    '$rents_type_label',         
                    '$zip_code',                 
                    $id_availability,          
                    '$availability_label',       
                    $id_publish_on_map,        
                    '$publish_on_map_label',     
                    '$label',                    
                    '$label_color',              
                    '$owner'                    
                  )  
               ";
      //print_r($query);exit;
      $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());

      for($i=0;$i<count($datosCaracteristicas);$i++){

        $query = "INSERT INTO
                    inmuebles.tb_caracteristicas_inmuebles
                    (
                      id_caracteristica,
                      id_property,
                      valor_caracteristica
                    )  
                  VALUES
                    (
                      ".$datosCaracteristicas[$i]['id_caracteristica'].",
                      $id_property,
                      '".$datosCaracteristicas[$i]['caracteristica']."'
                    )  
                 ";  

        $result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());
                  
      }      
      //print_r('hola');exit;

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