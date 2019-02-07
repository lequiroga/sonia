<?php

	require_once("../clases/AutenticaAPI.php");

	class Monedas{

		function listaMonedas(){

      $autAPI   = new AutenticaAPI();
			$datosAPI = $autAPI->retornarDatosAPI('wasi','lista_monedas');

			$data = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"]), true );

  			$data1 = array();

  			for($i=0;$i<count($data);$i++){

    		  if(isset($data[$i]['id_currency'])){

      			$data1[$i]=new stdClass();
      			$data1[$i]->id_moneda=$data[$i]['id_currency'];
      			$data1[$i]->denominacion=$data[$i]['iso'].' - '.$data[$i]['name'];

    		  }    

  			}

  			$data = json_encode($data1);

  			echo $data;			  

		}

    function getConversion($moneda){

      if($moneda=='3'){
        $autAPI   = new AutenticaAPI();
        $datosAPI = $autAPI->retornarDatosAPI('GetTRM','trm');
        $data = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"]), true );
        $conversion = "USD $1 = COP $".round($data,2);
        echo $conversion;
      }      

    }

	}
	

?>
