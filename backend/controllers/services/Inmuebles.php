<?php

	require_once("../clases/AutenticaAPI.php");
  require_once("../clases/InmueblesSQL.php");  

	class Inmuebles{

    private $objInmuebles;   

    function Inmuebles(){
          $this->objInmuebles=new InmueblesSQL();
    }

    function getInmueblePorID($id_property){
        $autAPI   = new AutenticaAPI();
        $datosAPI = $autAPI->retornarDatosAPI('wasi','propiedad_por_id');
        $data = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].$id_property.'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"].'&skip=0&take=1'), true );

        if($data['status']=='success'){
          unset($data['status']);
          $data1 = array();
          $data1['cant']=1;
          $data1['inmueblesResp'][0]=$data; 
        }
        else{
          unset($data['status']);
          $data1 = array();
          $data1['cant']=0;
        }        
        
        $data = json_encode($data1);

        echo $data; 
    }

    function guardarInmueble($datosInmueble){
       

        if(!isset($_SESSION))
          session_start();
        
        $id_user='';
        if(isset($_SESSION['id_user_app_externo'])){
          $id_user=$_SESSION['id_user_app_externo'];
        }

        $datosInmuebleEnvio = new \stdClass; 
        if(isset($datosInmueble->inmueble->codigoPais)){
          $datosInmuebleEnvio->id_country=$datosInmueble->inmueble->codigoPais->id_pais;          
          $datosInmuebleEnvio->country_label=$datosInmueble->inmueble->codigoPais->name;          
        }         
        if(isset($datosInmueble->inmueble->codigoDepartamento)){
          $datosInmuebleEnvio->id_region=$datosInmueble->inmueble->codigoDepartamento->id_departamento;
          $datosInmuebleEnvio->region_label=$datosInmueble->inmueble->codigoDepartamento->name;          
        }        
        if(isset($datosInmueble->inmueble->codigoCiudad)){
          $datosInmuebleEnvio->id_city=$datosInmueble->inmueble->codigoCiudad->id_ciudad;          
          $datosInmuebleEnvio->city_label=$datosInmueble->inmueble->codigoCiudad->name;          
        }        
        if(isset($datosInmueble->inmueble->codigoSector->name)){
          if(isset($datosInmueble->inmueble->codigoSector->id_sector)){
            $datosInmuebleEnvio->id_zone=$datosInmueble->inmueble->codigoSector->id_sector;          
          }
          $datosInmuebleEnvio->zone_label=$datosInmueble->inmueble->codigoSector->name; 
          //print_r($datosInmuebleEnvio);exit;         
        }        
        if(isset($datosInmueble->inmueble->codigoEstrato->codigo)){
          $datosInmuebleEnvio->stratum=$datosInmueble->inmueble->codigoEstrato->codigo;                   
        }        
        if(isset($datosInmueble->inmueble->codigoZona)&&$datosInmueble->inmueble->codigoCiudad->id_ciudad=='794'){
          $datosInmuebleEnvio->id_location=$datosInmueble->inmueble->codigoZona->id_zona;          
          $datosInmuebleEnvio->location_name=$datosInmueble->inmueble->codigoZona->name;                    
        }        
        /*if(isset($datosInmueble->inmueble->codigoBarrio)){
          print_r($datosInmueble->inmueble->codigoBarrio);exit;
          if(isset($datosInmueble->inmueble->codigoBarrio->estrato)){
            $datosInmuebleEnvio->stratum=$datosInmueble->inmueble->codigoBarrio->estrato;            
          }          
          //$datosInmuebleEnvio .= $datosInmueble->inmueble->codigoEstrato->codigo;          
        }*/        
        if(isset($datosInmueble->inmueble->direccion)){
          $datosInmuebleEnvio->address=$datosInmueble->inmueble->direccion;                 
        }        
        if(isset($datosInmueble->coordenadas->latitud)&&isset($datosInmueble->coordenadas->longitud)){          
          $datosInmuebleEnvio->map=$datosInmueble->coordenadas->latitud.",".$datosInmueble->coordenadas->longitud;
          $datosInmuebleEnvio->latitude=$datosInmueble->coordenadas->latitud;
          $datosInmuebleEnvio->longitude=$datosInmueble->coordenadas->longitud;            
          $datosInmuebleEnvio->id_publish_on_map=3;  
          $datosInmuebleEnvio->publish_on_map_label="Post exact point";  
        }
        else{
          $datosInmuebleEnvio->id_publish_on_map=1;  
          $datosInmuebleEnvio->publish_on_map_label="Do not post"; 
        }        
        if(isset($datosInmueble->inmueble->tipoInmueble->id_tipo_inmueble)){
          $datosInmuebleEnvio->id_property_type=$datosInmueble->inmueble->tipoInmueble->id_tipo_inmueble;        
        }        
        if(isset($datosInmueble->inmueble->titulo)){
          $datosInmuebleEnvio->title=strtoupper ($datosInmueble->inmueble->titulo);                        
        }        
        if(isset($datosInmueble->inmueble->condicion)){
          $datosInmuebleEnvio->id_property_condition=$datosInmueble->inmueble->condicion;          
          
          if($datosInmueble->inmueble->condicion=='1'){
            $datosInmuebleEnvio->property_condition_label="New";            
          }
          else if($datosInmueble->inmueble->condicion=='2'){            
            $datosInmuebleEnvio->property_condition_label="Used";
          }
          else if($datosInmueble->inmueble->condicion=='3'){
            $datosInmuebleEnvio->property_condition_label="Project";            
          }
          else if($datosInmueble->inmueble->condicion=='4'){            
            $datosInmuebleEnvio->property_condition_label="Under construction"; 
          }                     
        }        
        if(isset($datosInmueble->inmueble->area)){
          $datosInmuebleEnvio->area=$datosInmueble->inmueble->area;                        
        }        
        if(isset($datosInmueble->inmueble->tipoInmueble->id_tipo_inmueble)&&($datosInmueble->inmueble->tipoInmueble->id_tipo_inmueble=='1'||$datosInmueble->inmueble->tipoInmueble->id_tipo_inmueble=='2'||$datosInmueble->inmueble->tipoInmueble->id_tipo_inmueble=='3'||$datosInmueble->inmueble->tipoInmueble->id_tipo_inmueble=='4'||$datosInmueble->inmueble->tipoInmueble->id_tipo_inmueble=='7'||$datosInmueble->inmueble->tipoInmueble->id_tipo_inmueble=='8'||$datosInmueble->inmueble->tipoInmueble->id_tipo_inmueble=='10'||$datosInmueble->inmueble->tipoInmueble->id_tipo_inmueble=='11'||$datosInmueble->inmueble->tipoInmueble->id_tipo_inmueble=='12')){
          $datosInmuebleEnvio->id_unit_area="1";                       
        }        
        if(isset($datosInmueble->inmueble->tipoInmueble->id_tipo_inmueble)&&($datosInmueble->inmueble->tipoInmueble->id_tipo_inmueble=='5'||$datosInmueble->inmueble->tipoInmueble->id_tipo_inmueble=='6')){
          $datosInmuebleEnvio->id_unit_area="3";                        
        }        
        if(isset($datosInmueble->inmueble->moneda->id_moneda)){
          $datosInmuebleEnvio->id_currency=$datosInmueble->inmueble->moneda->id_moneda;          
          $moneda=explode(" - ",$datosInmueble->inmueble->moneda->denominacion);           
          $datosInmuebleEnvio->iso_currency=$moneda[0];              
          $datosInmuebleEnvio->name_currency=$moneda[1];                     
        }        
        if(isset($datosInmueble->inmueble->precio)){
          $datosInmuebleEnvio->sale_price=$datosInmueble->inmueble->precio;           
          $datosInmuebleEnvio->sale_price_label='$'.str_replace(',', '.', number_format($datosInmueble->inmueble->precio));                     
        }        
        if(isset($datosInmueble->inmueble->valor_administracion)){
          $datosInmuebleEnvio->maintenance_fee=$datosInmueble->inmueble->valor_administracion;                   
        }        
        $datosCaracteristicas = "";
        $postdataCaracteristicas= "";
        //if(count($datosInmueble->caracteristicas)>0){
          //$caracteristica = array();          
        $caracteristica = (array)$datosInmueble->caracteristicas;  
          
        if(count($caracteristica)>0){
          $output1 = $this->objInmuebles->getListaCaracteristicas($caracteristica,$datosInmueble->inmueble->tipoInmueble->id_tipo_inmueble);

          foreach ($output1 as $key => $value) {            
            $datosCaracteristicas.='&features[]='.$value['id_caracteristica'];                      
          }     
          $postdataCaracteristicas=$datosCaracteristicas;
          $datosCaracteristicas=array();
          $datosCaracteristicas=$output1;

        } 
        else{
          $datosCaracteristicas=array();
        }

        /*}
        else{
          $datosCaracteristicas=array();
        }*/               
        //print_r($postdataCaracteristicas);exit;
        if(isset($datosInmueble->inmueble->habitaciones)){
          $datosInmuebleEnvio->bedrooms=$datosInmueble->inmueble->habitaciones;                      
        }        
        if(isset($datosInmueble->inmueble->banos)){
          $datosInmuebleEnvio->bathrooms=$datosInmueble->inmueble->banos;                      
        }        
        if(isset($datosInmueble->inmueble->parqueadero)){
          $datosInmuebleEnvio->garages=$datosInmueble->inmueble->parqueadero;                        
        }
        if(isset($datosInmueble->inmueble->piso)){
          $datosInmuebleEnvio->floor=$datosInmueble->inmueble->piso;                     
        }
        if(isset($datosInmueble->inmueble->observaciones)){
          $datosInmuebleEnvio->observations=$datosInmueble->inmueble->observaciones;                  
        }
        if(isset($datosInmueble->inmueble->comentario)){
          $datosInmuebleEnvio->comment=$datosInmueble->inmueble->comentario;                        
        }
        if(isset($datosInmueble->inmueble->referencia)){
          $datosInmuebleEnvio->reference=$datosInmueble->inmueble->referencia;                      
        }        
        if(isset($datosInmueble->inmueble->estado)){          
          $datosInmuebleEnvio->id_availability=$datosInmueble->inmueble->estado->id_estado;          
          if($datosInmueble->inmueble->estado->id_estado=='1'){
            $datosInmuebleEnvio->availability_label="Available"; 
          } 
          else if($datosInmueble->inmueble->estado->id_estado=='2'){
            $datosInmuebleEnvio->availability_label="Sold"; 
          }       
          else if($datosInmueble->inmueble->estado->id_estado=='3'){
            $datosInmuebleEnvio->availability_label="Rented"; 
          }      
        }
        
        $datosInmuebleEnvio->for_sale="true";
        $datosInmuebleEnvio->for_rent="false"; 
        $datosInmuebleEnvio->for_transfer="false"; 
        $datosInmuebleEnvio->id_status_on_page=1; 
        $datosInmuebleEnvio->status_on_page_label="Active"; 
        $datosInmuebleEnvio->id_user=$id_user;       
        
        $postdata=http_build_query($datosInmuebleEnvio);
        
        $autAPI   = new AutenticaAPI();
        $datosAPI = $autAPI->retornarDatosAPI('wasi','guardar_propiedad');       
        $data = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"].'&'.$postdata), true );

        if($postdataCaracteristicas!=""){
          $datosAPI = $autAPI->retornarDatosAPI('wasi','actualizar_propiedad');           
          $data1 = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].$data['id_property'].'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"].$postdataCaracteristicas), true );
        }    
        
        if($data['status']=='success'){
          unset($data['status']);
          $data1 = array();
          $data1['respuesta']='1';
          $data1['id_property']=$data['id_property'];
          
          $datosAPI = $autAPI->retornarDatosAPI('wasi','propiedad_por_id');
          $data3 = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].$data1['id_property'].'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"].'&skip=0&take=1'), true );

          if($data3['status']=='success'){
            unset($data3['status']);            
            $data1['inmuebleRegistrado']=$data3; 
            $this->objInmuebles->guardarInmueble($data1['inmuebleRegistrado'],$datosCaracteristicas);            
          }

        }
        else{
          unset($data['status']);
          $data1 = array();
          $data1['respuesta']='0';
        }        
        
        $data = json_encode($data1);

        echo $data; 
    }

    function guardarImagenInmueble($id_property,$foto){      
//print_r($foto->fileType);exit;
      if(isset($foto->fileName)){

          $autAPI   = new AutenticaAPI();
          $datosAPI = $autAPI->retornarDatosAPI('wasi','galerias_propiedad');       
          $data = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].$id_property.'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"]), true );

          $posicion = $data['total']+1;      
          $descripcion='Foto '.$posicion;

          $output1 = '../tmp_files/'.$foto->fileName; 
          $outputFile = $this->base64_to_jpeg($foto->base64StringFile, $output1);    

          $datosAPI = $autAPI->retornarDatosAPI('wasi','subir_imagen_propiedad');  
         
          $ch = curl_init();

          $path = realpath($output1);
          //print_r("@".$output1);exit;
          $post = ['image' => "@".$output1,
                   'position' => $posicion,
                   'description' => $descripcion
                  ];
          
          curl_setopt($ch, CURLOPT_URL, $datosAPI["uri"].$datosAPI["uri_compl"].$id_property.'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"]);
          curl_setopt($ch, CURLOPT_POST,1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $foto);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
          $result=curl_exec ($ch);
          curl_close ($ch);
          
          $datosAPI = $autAPI->retornarDatosAPI('wasi','actualizar_imagen_propiedad');       
          $data = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].$id_property.'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"]), true );

          echo "Operacion exitosa";exit;

      }

    }
      
    public function base64_to_jpeg($base64_string, $output_file) {
        // open the output file for writing
        $ifp = fopen( $output_file, 'wb' ); 

        // split the string on commas
        // $data[ 0 ] == "data:image/png;base64"
        // $data[ 1 ] == <actual base64 string>
        $data = explode( ',', $base64_string );       

        // we could add validation here with ensuring count( $data ) > 1
        fwrite( $ifp, base64_decode( $data[0] ) );
    
        // clean up the file resource
        fclose( $ifp ); 
    
        return $output_file; 
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

          $datosAPI = $autAPI->retornarDatosAPI('wasi','usuarios_por_id');
          $data_user = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].$data_clientes[$i]['id_user'].'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"]), true );              

          $data_clientes[$i]['usuario'] = $data_user['first_name'].' '.$data_user['last_name'];            

        }

        $datosAPI = $autAPI->retornarDatosAPI('wasi','propiedad_por_id');         
        $data_property = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].$id_property.'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"]), true );

        $datosAPI = $autAPI->retornarDatosAPI('wasi','usuarios_por_id');
        $data_user = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].$data_property['id_user'].'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"]), true );    

        $output['datosClientes'] = $data_clientes;
        $output['usuario_inmueble'] = $data_user['first_name'].' '.$data_user['last_name']; 
        $output['usuario_inmueble_telefono'] = $data_user['cell_phone'];
        $output['usuario_inmueble_email'] = $data_user['email'];
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
