<?php

	require_once("../clases/AsesoresSQL.php");
	require_once("../clases/AutenticaAPI.php");
	require_once("../clases/AutenticaAmazon.php");	

	class Asesores{

		private $objAsesores;			

		function Asesores(){
          $this->objAsesores=new AsesoresSQL();
		}		

		//Para obtener el tipo de asesor o empleado de la inmobiliaria
		function getTiposAsesores(){			
			$output = $this->objAsesores->getTiposAsesores();
	  		echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE); 
		}		

		function saveAsesores($datosAsesor,$foto){
			
			$output = $this->objAsesores->guardarAsesores($datosAsesor);
			
			if($output["respuesta"]=='1'||$output["respuesta"]=='3'){
			  if(isset($foto->fileName)){

			  	$output1 = '../tmp_files/'.$foto->fileName; 
 				$outputFile = $this->base64_to_jpeg($foto->base64StringFile, $output1);							

			   	//print_r($foto);exit;
			  	$objAutenticaAmazon = new AutenticaAmazon();
			  	$urlImagen = $objAutenticaAmazon->autenticaBucket($foto,$output1,$output["id_asesor"]);		

			  	$this->objAsesores->guardarImagenAsesores($output["id_asesor"],$urlImagen);	
			  	$output["fotoAsesor"] = $urlImagen; 	

			  }		
			}			
			
	  		echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
		}	

		function getListaAsesores($datosAsesor){
			//print_r($datosAsesor);exit;
			$output = $this->objAsesores->consultarAsesores($datosAsesor);
	  		echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
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

		function datosAsesorPorID($id_asesor){			
			$output = $this->objAsesores->datosAsesorPorID($id_asesor);
	  		echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
		}

		function consultarAsesores($datosAsesor){			
			$output = $this->objAsesores->consultarAsesores($datosAsesor);
	  		echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
		}

	}
	

?>
