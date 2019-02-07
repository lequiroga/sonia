<?php

	require_once("../clases/AutenticaAPI.php");
  require_once("../clases/ZonasSQL.php");

	class Zonas{

    private $objZonas;

    function Zonas(){
          $this->objZonas=new ZonasSQL();
    }

		function listaZonas($id_ciudad){

        $autAPI   = new AutenticaAPI();
			  $datosAPI = $autAPI->retornarDatosAPI('wasi','lista_zonas');

			  $data = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].$id_ciudad.'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"]), true );
        
  			$data1 = array();

  			for($i=0;$i<count($data);$i++){

    		  if(isset($data[$i]['id_location'])){

      			$data1[$i]=new stdClass();
      			$data1[$i]->id_zona=$data[$i]['id_location'];
      			$data1[$i]->name=$data[$i]['name'];

    		  }    

  			}

  			$data = json_encode($data1);

  			echo $data;			  

		}

    //Función para guardar la asociación entre un barrio y una zona de una determinada ciudad
    function guardarClasificacionBarrio($datosClasificacion){
        $output = $this->objZonas->guardarClasificacionBarrio($datosClasificacion);
        echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
    }

    //Des-asocia la relación entre un barrio y una zona de una ciudad
    function borrarBarrioZona($id_zona_barrio){
        $output = $this->objZonas->borrarBarrioZona($id_zona_barrio);
        echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
    }

    //Función para listar los barrios asociados a una zona de una ciudad
    function listarBarriosZona($id_sector,$id_ciudad){
        $output = $this->objZonas->listarBarriosZona($id_sector,$id_ciudad);
        echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
    }

    //Función para obtener la zona a la que se encuentra asociado un determinado barrio de Cali
    function getZonaBarrioCali($id_barrio){
        $output = $this->objZonas->getZonaBarrioCali($id_barrio);
        echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
    }

	}
	

?>
