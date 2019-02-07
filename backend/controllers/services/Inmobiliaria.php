<?php

	require_once("../clases/InmobiliariaSQL.php");
  require_once("../controllers/services/Asesores.php");
  require_once("../clases/AutenticaAmazon.php");

	class Inmobiliaria{

    private $objInmobiliaria;  
    private $objAsesores;   

    function Inmobiliaria(){
          $this->objInmobiliaria=new InmobiliariaSQL();
          $this->objAsesores=new Asesores();
    }

		function getInformacionInmobiliaria(){
      
			$output = $this->objInmobiliaria->getInformacionInmobiliaria();
      echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);			  

		}

    function guardarInformacionInmobiliaria($datos_inmobiliaria,$foto){

      $output = $this->objInmobiliaria->guardarInformacionInmobiliaria($datos_inmobiliaria);

      if($output["respuesta"]=='1'){
        if(isset($foto->fileName)){

          $output1 = '../tmp_files/'.$foto->fileName; 
          $outputFile = $this->objAsesores->base64_to_jpeg($foto->base64StringFile, $output1);             

          //print_r($foto);exit;
          $objAutenticaAmazon = new AutenticaAmazon();
          $urlImagen = $objAutenticaAmazon->autenticaBucketInmobiliaria($foto,$output1,$output["id_inmobiliaria"]);   

          $this->objInmobiliaria->guardarImagenInmobiliaria($output["id_inmobiliaria"],$urlImagen); 
          $output["imagen_logo"] = $urlImagen;   

        }   
      }

      echo json_encode($output, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);

    }

	}
	

?>
