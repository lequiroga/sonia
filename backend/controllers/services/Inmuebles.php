<?php

	require_once("../clases/AutenticaAPI.php");
  require_once("../clases/InmueblesSQL.php");

	class Inmuebles{

    private $objInmuebles;   

    function Inmuebles(){
          $this->objInmuebles=new InmueblesSQL();
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

    function listaTiposCaracteristicasInmuebles(){          
        $output = $this->objInmuebles->getTiposCaracteristicasInmuebles();
        echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
    }

    function getCriteriosDiligenciamiento(){          
        $output = $this->objInmuebles->getCriteriosDiligenciamiento();
        echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
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
