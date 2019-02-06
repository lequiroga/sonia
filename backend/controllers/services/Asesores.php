<?php

	require_once("../clases/AsesoresSQL.php");
	require_once("../clases/AutenticaAPI.php");

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

		function saveAsesores($datosAsesor){			
			$output = $this->objAsesores->guardarAsesores($datosAsesor);
	  		echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
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
