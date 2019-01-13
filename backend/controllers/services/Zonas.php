<?php

	require_once("../clases/AutenticaAPI.php");

	class Zonas{

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

	}
	

?>
