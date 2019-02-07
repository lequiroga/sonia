<?php

	require_once("../clases/AutenticaAPI.php");

	class Sectores{

		function listaSectores($id_ciudad){

        $autAPI   = new AutenticaAPI();
			  $datosAPI = $autAPI->retornarDatosAPI('wasi','lista_sectores');
			  $data = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].$id_ciudad.'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"]), true );

  			$data1 = array();

  			for($i=0;$i<count($data);$i++){

    		  if(isset($data[$i]['id_zone'])){

      			$data1[$i]=new stdClass();
      			$data1[$i]->id_sector=$data[$i]['id_zone'];
      			$data1[$i]->name=$data[$i]['name'];

    		  }    

  			}

  			$data = json_encode($data1);

  			echo $data;			  

		}

	}
	

?>
