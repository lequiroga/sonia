<?php

  class configBucket{

  	private $s3 = array();

    public function configBucket(){

      $this->s3 = [
            's3' => [
                'key' => 'AKIAIPNFEYQXARRVI7ZA',
                'secret' => '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',
                'bucket' => 'lquiroga1130612'
            ]		
          ];

    }      

    public function returnConfigBucket(){

      //Devuelve la configuración de seguridad para acceder al bucket de Amazon AWS
      return $this->s3;	
      //return 

    }

  }  

?>