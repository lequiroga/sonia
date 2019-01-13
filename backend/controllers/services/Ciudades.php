<?php

	require_once("../clases/AutenticaAPI.php");

	class Ciudades{

		function listaCiudades($id_region){

        $autAPI   = new AutenticaAPI();
			  $datosAPI = $autAPI->retornarDatosAPI('wasi','ciudades_depto');

			  $data = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].$id_region.'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"]), true );

  			$data1 = array();

  			for($i=0;$i<count($data);$i++){

    		  if(isset($data[$i]['id_city'])){

      			$data1[$i]=new stdClass();
      			$data1[$i]->id_ciudad=$data[$i]['id_city'];
      			$data1[$i]->name=$data[$i]['name'];

    		  }    

  			}

  			$data = json_encode($data1);

  			echo $data;			  

		}

	}
	

?>
