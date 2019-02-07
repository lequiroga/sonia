<?php

	require_once("../clases/AutenticaAPI.php");

	class Departamentos{

		function listaDepartamentos($id_pais){

        $autAPI   = new AutenticaAPI();
			  $datosAPI = $autAPI->retornarDatosAPI('wasi','deptos_pais');

			  $data = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].$id_pais.'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"]), true );

  			$data1 = array();

  			for($i=0;$i<count($data);$i++){

    		  if(isset($data[$i]['id_region'])){

      			$data1[$i]=new stdClass();
      			$data1[$i]->id_departamento=$data[$i]['id_region'];
      			$data1[$i]->name=$data[$i]['name'];

    		  }    

  			}

  			$data = json_encode($data1);

  			echo $data;			  

		}

	}
	

?>
