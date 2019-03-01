<?php

  class configBucket{

  	private $s3 = array();

    public function configBucket(){

      $this->s3 = [
            's3' => [
                'key' => 'AKIAINIFSAL2RIPJGPOQ',
                'secret' => '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',
                'bucket' => 's0n14'
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