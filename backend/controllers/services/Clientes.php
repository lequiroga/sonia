<?php

	require_once("../clases/ClientesSQL.php");
	require_once("../clases/AutenticaAPI.php");

	class Clientes{

		private $objClientes;		

		function Clientes(){
          $this->objClientes=new ClientesSQL();
		}

		function getTiposIdentificacion(){        	
        	$output = $this->objClientes->getTiposIdentificacion();
	  		echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
		}

		function getTiposClientes(){        

            $autAPI   = new AutenticaAPI();
			$datosAPI = $autAPI->retornarDatosAPI('wasi','tipos_clientes');

			$data = json_decode( file_get_contents($datosAPI["uri"].$datosAPI["uri_compl"].'?'.$datosAPI["id_api"].'&'.$datosAPI["token_api"]), true );

  			$data1 = array();

            $j=0;
  			for($i=0;$i<count($data);$i++){

    		  if(isset($data[$i]['id_client_type']) && $data[$i]['public']){

      			$data1[$j]=new stdClass();
      			$data1[$j]->id_tipo_cliente=$data[$i]['id_client_type'];
      			$data1[$j]->descripcion=$data[$i]['name'];

      			$j++;

    		  }    

  			}

  			$data = json_encode($data1);

  			echo $data;			   

        	/*$output = $this->objClientes->getTiposClientes();
	  		echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);*/
		}

		function getTiposNotificacion(){        	
        	$output = $this->objClientes->getTiposNotificacion();
	  		echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
		}

		function guardarClientes($datosCliente){			
			$output = $this->objClientes->guardarClientes($datosCliente);
	  		echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
		}

		function datosClientePorID($id_cliente){			
			$output = $this->objClientes->datosClientePorID($id_cliente);
	  		echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
		}

		function consultarClientes($datosCliente){			
			$output = $this->objClientes->consultarClientes($datosCliente);
	  		echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
		}

	}
	

?>
