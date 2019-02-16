<?php

	require_once("../clases/AutenticaAPI.php");
  require_once("../clases/InmueblesSQL.php");

	class Inmuebles{

    private $objInmuebles;   

    function Inmuebles(){
          $this->objInmuebles=new InmueblesSQL();
    }

    function consultarInmuebles($datosInmueble,$caracteristicas,$caracteristicas_opcionales){

        $autAPI   = new AutenticaAPI();
        $datosAPI = $autAPI->retornarDatosAPI('wasi','propiedades_todas');

        $data = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"].'&skip=0&take=1'), true );

        $totalInmuebles = $data['total'];
        $totalInmueblesIt = $totalInmuebles;
        $cant_iteraciones = 0;

        while($totalInmueblesIt>0){
          $totalInmueblesIt-=100;
          $cant_iteraciones++;
        }

        $index = 0;   
        $inmueblesRespuesta = array();     
        for($i=0;$i<$cant_iteraciones;$i++){
          $inicial=$i*100;
          $data1 = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"].'&skip='.$inicial.'&take=100'), true );
          unset($data1['total']);
          unset($data1['status']);
          for($j=0;$j<count($data1);$j++){

            $validaID = 0;            
            if(isset($datosInmueble->ID)){
              if($datosInmueble->ID==$data1[$j]['id_property']){
                $validaID = 1;
              }
              else{
                $validaID = 0;
              }
            }
            else{
              $validaID = 1;
            } 

            $validaPais = 0;

            if(isset($datosInmueble->codigoPais)&&$datosInmueble->codigoPais!=''){
              if($datosInmueble->codigoPais->id_pais==$data1[$j]['id_country']){
                $validaPais = 1;
              }
              else{
                $validaPais = 0;
              }
            }
            else{
              $validaPais = 1;
            }       

            $validaDepto = 0;     

            if(isset($datosInmueble->codigoDepartamento)&&$datosInmueble->codigoDepartamento!=''){
              if($datosInmueble->codigoDepartamento->id_departamento==$data1[$j]['id_region']){
                $validaDepto = 1;
              }
              else{
                $validaDepto = 0;
              }
            }
            else{
              $validaDepto = 1;
            }            

            $validaCiudad = 0;

            if(isset($datosInmueble->codigoCiudad)&&$datosInmueble->codigoCiudad!=''){
              if($datosInmueble->codigoCiudad->id_ciudad==$data1[$j]['id_city']){
                $validaCiudad = 1;
              }
              else{
                $validaCiudad = 0;
              }
            }
            else{
              $validaCiudad = 1;
            }

            $validaSector = 0;

            if(isset($datosInmueble->codigoSector)&&$datosInmueble->codigoSector!=''){
              if($datosInmueble->codigoSector->id_sector==$data1[$j]['id_zone']){
                $validaSector = 1;
              }
              else{
                $validaSector = 0;
              }
            }
            else{
              $validaSector = 1;
            }

            $validaInmueble = 0;

            if(isset($datosInmueble->tipoInmueble)&&$datosInmueble->tipoInmueble!=''){

              if($datosInmueble->tipoInmueble->id_tipo_inmueble==$data1[$j]['id_property_type']){
                $validaInmueble = 1;
              }
              else{
                $validaInmueble = 0;
              }
            }
            else{
              $validaInmueble = 1;
            }   

            $validaEstrato = 0;

            if(isset($datosInmueble->codigoEstrato)&&$datosInmueble->codigoEstrato!=''){

              if($datosInmueble->codigoEstrato->codigo==$data1[$j]['stratum']){
                $validaEstrato = 1;
              }
              else{
                $validaEstrato = 0;
              }
            }
            else{
              $validaEstrato = 1;
            }         

            $validaCondicion = 0;        

            if(isset($datosInmueble->condicion)&&$datosInmueble->condicion!=''){              

              if($datosInmueble->condicion==$data1[$j]['id_property_condition']){
                $validaCondicion = 1;
              }
              else{
                $validaCondicion = 0;
              }
            }
            else{
              $validaCondicion = 1;
            }

            $validaArea = 0;

            if(isset($datosInmueble->area)){

              if($datosInmueble->area==$data1[$j]['area'] || $datosInmueble->area==$data1[$j]['built_area'] || $datosInmueble->area==$data1[$j]['private_area']){
                $validaArea = 1;
              }
              else{
                $validaArea = 0;
              }
            }
            else{
              $validaArea = 1;
            }

            $validaAsesor = 0;

            if(isset($datosInmueble->id_asesor)&&$datosInmueble->id_asesor!=null&&$datosInmueble->id_asesor!=""){
              if($data1[$j]['id_user']==$datosInmueble->id_asesor){
                $validaAsesor=1;
              }
              else{
                $validaAsesor=0;
              }
            }
            else{
              $validaAsesor=1;  
            }

            $validaMoneda = 0;

            if(isset($datosInmueble->moneda)){

              if($datosInmueble->moneda->id_moneda==$data1[$j]['id_currency']){
                $validaMoneda = 1;
              }
              else{
                $validaMoneda = 0;
              }
            }
            else{
              $validaMoneda = 1;
            }

            $validaPrecios = 0;

            if(isset($datosInmueble->precio_inicial)&&isset($datosInmueble->precio_final)){

              if(($data1[$j]['sale_price']>=$datosInmueble->precio_inicial)&&($data1[$j]['sale_price']<=$datosInmueble->precio_final)){
                $validaPrecios = 1;
              }
              else{
                $validaPrecios = 0;
              }
            }
            else{
              $validaPrecios = 1;
            }

            $validaFechas = 0;

            if(isset($datosInmueble->fecha_inicial)&&isset($datosInmueble->fecha_final)){

              if(substr($data1[$j]['updated_at'], 0,1) != '0'){

                if(( date($datosInmueble->fecha_inicial) <= date($data1[$j]['updated_at']) )&&( date($datosInmueble->fecha_final) >= date($data1[$j]['updated_at']) )){
                  $validaFechas = 1;
                }
                else{
                  $validaFechas = 0;
                }

              }
              else{
                
                if(( date($datosInmueble->fecha_inicial) <= date($data1[$j]['created_at']) )&&( date($datosInmueble->fecha_final) >= date($data1[$j]['created_at']) )){
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

            $validaCaracteristicas = 0;  
            
            if(isset($caracteristicas)&&$caracteristicas!=null){
              //print_r($caracteristicas);exit;
              for($n=0;$n<count($caracteristicas);$n++){
                //$car = 1;
                foreach ($caracteristicas[$n] as $key => $value) {   
                  if($value!=''){        
                    $validaCaracteristicas = 0;            
                    if(isset($data1[$j]['features']['internal'])&&count($data1[$j]['features']['internal'])>0){
                      
                      for($m=0;$m<count($data1[$j]['features']['internal']);$m++){
                        //print_r($data1[$j]['features']['internal'][$m]['id']);exit;
                        if($data1[$j]['features']['internal'][$m]['id']==$key){
                          $validaCaracteristicas = 1;
                        }
                      }
                  
                    }
                    if((isset($data1[$j]['features']['external'])&&count($data1[$j]['features']['external'])>0)&&$value!=''){
                      //print_r($data1[$j]['features']);exit;
                      for($m=0;$m<count($data1[$j]['features']['external']);$m++){
                        if($data1[$j]['features']['external'][$m]['id']==$key){
                          $validaCaracteristicas = 1;
                        }
                      }
                    }

                  }
                  else{
                    $validaCaracteristicas = 1;
                  }
                  
                }
              }
            }
            else{
              $validaCaracteristicas = 1;
            }               


            $validaCaracteristicasOpcionales = 0;  
            
            if(isset($caracteristicas_opcionales)&&$caracteristicas_opcionales!=null){
              //print_r($caracteristicas);exit;
              for($n=0;$n<count($caracteristicas_opcionales);$n++){
                //$car = 1;
                foreach ($caracteristicas_opcionales[$n] as $key => $value) {   
                  if($value!=''){        
                    $validaCaracteristicasOpcionales = 0;            
                    if(isset($data1[$j]['features']['internal'])&&count($data1[$j]['features']['internal'])>0){
                      
                      for($m=0;$m<count($data1[$j]['features']['internal']);$m++){
                        //print_r($data1[$j]['features']['internal'][$m]['id']);exit;
                        if($data1[$j]['features']['internal'][$m]['id']==$key){
                          $validaCaracteristicasOpcionales = 1;
                        }
                      }
                  
                    }
                    if((isset($data1[$j]['features']['external'])&&count($data1[$j]['features']['external'])>0)&&$value!=''){
                      //print_r($data1[$j]['features']);exit;
                      for($m=0;$m<count($data1[$j]['features']['external']);$m++){
                        if($data1[$j]['features']['external'][$m]['id']==$key){
                          $validaCaracteristicasOpcionales = 1;
                        }
                      }
                    }

                  }
                  else{
                    $validaCaracteristicasOpcionales = 1;
                  }
                  
                }
              }
            }
            else{
              $validaCaracteristicasOpcionales = 1;
            }              

            $validaHabitaciones = 0;     

            if(isset($datosInmueble->habitaciones)&&$datosInmueble->habitaciones!=''){
              if($datosInmueble->habitaciones==$data1[$j]['bedrooms']){
                $validaHabitaciones = 1;
              }
              else{
                $validaHabitaciones = 0;
              }
            }
            else{
              $validaHabitaciones = 1;
            } 
   
            $validaBanos = 0;     

            if(isset($datosInmueble->banos)&&$datosInmueble->banos!=''){
              if($datosInmueble->banos==$data1[$j]['bathrooms']){
                $validaBanos = 1;
              }
              else{
                $validaBanos = 0;
              }
            }
            else{
              $validaBanos = 1;
            } 
            
            $validaParqueadero = 0;     

            if(isset($datosInmueble->parqueadero)&&$datosInmueble->parqueadero!=''){
              if($datosInmueble->parqueadero==$data1[$j]['garages']){
                $validaParqueadero = 1;
              }
              else{
                $validaParqueadero = 0;
              }
            }
            else{
              $validaParqueadero = 1;
            } 

            $validaPiso = 0;     

            if(isset($datosInmueble->piso)&&$datosInmueble->piso!=''){
              if($datosInmueble->piso==$data1[$j]['floor']){
                $validaPiso = 1;
              }
              else{
                $validaPiso = 0;
              }
            }
            else{
              $validaPiso = 1;
            } 

            $validaEstado = 0;     

            if(isset($datosInmueble->estado)&&$datosInmueble->estado!=''){              
              if($datosInmueble->estado->id_estado==$data1[$j]['id_availability']){
                $validaEstado = 1;
              }
              else{
                $validaEstado = 0;
              }
            }
            else{
              $validaEstado = 1;
            }

            if($validaID==1 && $validaPais==1 && $validaDepto==1 && $validaCiudad==1 && $validaSector==1 && $validaInmueble==1 && $validaEstrato==1 && $validaCondicion==1 && $validaArea==1 && $validaMoneda==1 && $validaPrecios==1 && $validaFechas==1 && $validaCaracteristicas==1 && $validaCaracteristicasOpcionales==1 && $validaHabitaciones==1 && $validaBanos==1 && $validaParqueadero==1 && $validaPiso==1 && $validaEstado == 1 && $validaAsesor == 1){
              $inmueblesRespuesta[$index] = $data1[$j];
              $index++;
            }
            
          }
        }        

        /*$data1 = array();

        for($i=0;$i<count($data);$i++){

          if(isset($data[$i]['id_property_type'])){

            $data1[$i]=new stdClass();
            $data1[$i]->id_tipo_inmueble=$data[$i]['id_property_type'];
            $data1[$i]->name=$data[$i]['name'];

          }    

        }

        $data = json_encode($data1);*/
        $data1 = array();
        if($index>0){
          $data1['cant']=$index;
          $data1['inmueblesResp']=$inmueblesRespuesta;
        }
        else{
          $data1['cant']=0;
        }
        $data = json_encode($data1);

        echo $data; 

    }

    function consultarInfoAdicionalPropiedad($id_property){

        $autAPI   = new AutenticaAPI();
        $datosAPI = $autAPI->retornarDatosAPI('wasi','clientes_propiedades');

        $data = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].$id_property.'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"]), true );

        unset($data['total']);
        unset($data['status']);

        $data_clientes = array();

        for($i=0;$i<count($data);$i++){

          $datosAPI = $autAPI->retornarDatosAPI('wasi','clientes_por_id');
          $data_clientes[$i] = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].$data[$i]['id_client'].'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"]), true );         

        }

        $datosAPI = $autAPI->retornarDatosAPI('wasi','propiedad_por_id');         
        $data_property = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].$id_property.'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"]), true );

        $output['datosClientes'] = $data_clientes;
        $output['visitas'] = $data_property['visits'];
        $output['link'] = $data_property['link'];
        $output['title'] = $data_property['title'];
        $output['sale_price_label'] = $data_property['sale_price_label'];
        $output['id_property'] = $data_property['id_property'];
        
        $data = json_encode($output);

        echo $data;

    }

		function listaTiposInmuebles(){

        $autAPI   = new AutenticaAPI();
			  $datosAPI = $autAPI->retornarDatosAPI('wasi','tipo_inmuebles');

			  $data = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"]), true );

  			$data1 = array();

  			for($i=0;$i<count($data);$i++){

    		  if(isset($data[$i]['id_property_type'])){

      			$data1[$i]=new stdClass();
      			$data1[$i]->id_tipo_inmueble=$data[$i]['id_property_type'];
      			$data1[$i]->name=$data[$i]['name'];

    		  }    

  			}

  			$data = json_encode($data1);

  			echo $data;			  

		}

    //Obtiene la lista de formas de pago de los negocios inmobiliarios
    function getListaFormasPago(){
        $output = $this->objInmuebles->getListaFormasPago();
        echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
    }

    //Obtiene la lista de tipos de prioridad o urgencia de venta o compra del inmueble por parte del cliente
    function getListaPrioridades(){          
        $output = $this->objInmuebles->getListaPrioridades();
        echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
    }

    function listaTiposCaracteristicasInmuebles(){          
        $output = $this->objInmuebles->getTiposCaracteristicasInmuebles();
        echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
    }

    function getCriteriosDiligenciamiento(){          
        $output = $this->objInmuebles->getCriteriosDiligenciamiento();
        echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
    }


    function obtenerDatosExternos($uri,$complemento){
      $curl = curl_init();
      curl_setopt_array($curl, array(
          CURLOPT_URL => $uri.$complemento,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
          CURLOPT_POSTFIELDS => "",
          CURLOPT_HTTPHEADER => array(
            "cache-control: no-cache"
          ),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      if ($err) {
          echo "cURL Error #:" . $err;
      } else {
          echo $response;
      }

    }

    //Por ahora funciona únicamente para la ciudad de Cali
    function getComunasEstratosCiudad($codigoCiudad){          

      if($codigoCiudad=='132'){

        $autAPI   = new AutenticaAPI();
        $datosAPI = $autAPI->retornarDatosAPI('DatosAbiertos','comunas_barrios_cali');
        $data = $this->obtenerDatosExternos($datosAPI["uri"],$datosAPI["uri_compl"]);
        //$data = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"]),true );        
        //print_r($data);exit;

        $datosAPI = $autAPI->retornarDatosAPI('DatosAbiertos','estratos_barrios_cali');
        $data1 = $this->obtenerDatosExternos($datosAPI["uri"],$datosAPI["uri_compl"]);

        

        $data1 = array();

        for($i=0;$i<count($data);$i++){

          if(isset($data[$i]['id_property_type'])){

            $data1[$i]=new stdClass();
            $data1[$i]->id_tipo_inmueble=$data[$i]['id_property_type'];
            $data1[$i]->name=$data[$i]['name'];

          }    

        }

        $data = json_encode($data1);

        echo $data;

      }
      else
        echo 0;
        /*$output = $this->objInmuebles->getComunasEstratosCiudad($codigoCiudad);
        echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);*/
    }

    function guardarCaracteristicaTipoInmueble($datosCaracteristicaTipoInmueble){        
        $output = $this->objInmuebles->guardarCaracteristicaTipoInmueble($datosCaracteristicaTipoInmueble);
        echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
    }

    function caracteristicasTiposInmuebles($id_tipo_inmueble){
        $output['caracteristicasObligatoriasInmueble'] = $this->objInmuebles->caracteristicasTiposInmuebles($id_tipo_inmueble,'Obligatorio');
        $output['caracteristicasOpcionalesInmueble'] = $this->objInmuebles->caracteristicasTiposInmuebles($id_tipo_inmueble,'Opcional');
        $output['caracteristicasInmueble'] = $this->objInmuebles->caracteristicasTiposInmuebles($id_tipo_inmueble,null);
        $listaCaracteristicasTodas = $this->listaTiposCaracteristicas1();       
        //print_r($listaCaracteristicasTodas);exit;
        $caracteristicasOblInm=$output['caracteristicasObligatoriasInmueble']['lista_caracteristicas_inmueble'];
        $caracteristicasOpcInm=$output['caracteristicasOpcionalesInmueble']['lista_caracteristicas_inmueble'];
        $caracteristicasInm=$output['caracteristicasInmueble']['lista_caracteristicas_inmueble'];
        $listaCaracteristicasRestantes = array();
        
        for($i=0;$i<count($caracteristicasOblInm);$i++){
          for($j=0;$j<count($listaCaracteristicasTodas);$j++){            
            if(isset($listaCaracteristicasTodas[$j])&&($listaCaracteristicasTodas[$j]->id_tipo_caracteristica==$caracteristicasOblInm[$i]['id_caracteristica'])){
              $output['caracteristicasObligatoriasInmueble']['lista_caracteristicas_inmueble'][$i]['tipo_caracteristica']=$listaCaracteristicasTodas[$j]->name;              
              $j=count($listaCaracteristicasTodas)+1;
            }            
          }
        }

        for($i=0;$i<count($caracteristicasOpcInm);$i++){
          for($j=0;$j<count($listaCaracteristicasTodas);$j++){            
            if(isset($listaCaracteristicasTodas[$j])&&($listaCaracteristicasTodas[$j]->id_tipo_caracteristica==$caracteristicasOpcInm[$i]['id_caracteristica'])){
              $output['caracteristicasOpcionalesInmueble']['lista_caracteristicas_inmueble'][$i]['tipo_caracteristica']=$listaCaracteristicasTodas[$j]->name;              
              $j=count($listaCaracteristicasTodas)+1;
            }            
          }
        }

        for($i=0;$i<count($caracteristicasInm);$i++){
          for($j=0;$j<count($listaCaracteristicasTodas);$j++){            
            if(isset($listaCaracteristicasTodas[$j])&&($listaCaracteristicasTodas[$j]->id_tipo_caracteristica==$caracteristicasInm[$i]['id_caracteristica'])){
              $output['caracteristicasInmueble']['lista_caracteristicas_inmueble'][$i]['tipo_caracteristica']=$listaCaracteristicasTodas[$j]->name;
              unset($listaCaracteristicasTodas[$j]);
              $j=count($listaCaracteristicasTodas)+1;
            }            
          }
        }
        $output['caracteristicasRestantes'] = $listaCaracteristicasTodas;
        echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
    }

    function seleccionarCaracteristicaInmueble($id_caracteristicas_tipo_inmueble){
        $output = $this->objInmuebles->seleccionarCaracteristicaInmueble($id_caracteristicas_tipo_inmueble);
        echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
    }

    function borrarCaracteristicasTiposInmuebles($id_caracteristicas_tipo_inmueble){
        $output = $this->objInmuebles->borrarCaracteristicasTiposInmuebles($id_caracteristicas_tipo_inmueble);
        echo '1';
    }

    function listaTiposCaracteristicas(){

        $autAPI   = new AutenticaAPI();
        $datosAPI = $autAPI->retornarDatosAPI('wasi','lista_caracteristicas');

        $data = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"]), true );
        $data1 = array();
        $counts = 0;

        for($i=0;$i<count($data['internal']);$i++){

          if(isset($data['internal'][$i]['id'])){

            $data1[$i]=new stdClass();
            $data1[$i]->id_tipo_caracteristica=$data['internal'][$i]['id'];
            $data1[$i]->name=$data['internal'][$i]['nombre'];
            $counts=$i+1;

          }    

        }

        for($i=0;$i<count($data['external']);$i++){

          if(isset($data['external'][$i]['id'])){

            $data1[$counts]=new stdClass();
            $data1[$counts]->id_tipo_caracteristica=$data['external'][$i]['id'];
            $data1[$counts]->name=$data['external'][$i]['nombre'];
            $counts++;

          }    

        }

        $data = json_encode($data1);

        echo $data;       

    }

    function listaTiposCaracteristicas1(){

        $autAPI   = new AutenticaAPI();
        $datosAPI = $autAPI->retornarDatosAPI('wasi','lista_caracteristicas');

        $data = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"]), true );
        $data1 = array();
        $counts = 0;

        for($i=0;$i<count($data['internal']);$i++){

          if(isset($data['internal'][$i]['id'])){

            $data1[$i]=new stdClass();
            $data1[$i]->id_tipo_caracteristica=$data['internal'][$i]['id'];
            $data1[$i]->name=$data['internal'][$i]['nombre'];
            $counts=$i+1;

          }    

        }

        for($i=0;$i<count($data['external']);$i++){

          if(isset($data['external'][$i]['id'])){

            $data1[$counts]=new stdClass();
            $data1[$counts]->id_tipo_caracteristica=$data['external'][$i]['id'];
            $data1[$counts]->name=$data['external'][$i]['nombre'];
            $counts++;

          }    

        }

        //$data = json_encode($data1);

        //echo $data;

        return $data1;       

    }

	}
	

?>
