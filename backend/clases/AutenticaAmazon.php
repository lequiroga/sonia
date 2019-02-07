<?php

  use Aws\S3\S3Client;
  use Aws\S3\Exception\S3Exception;

  require '../../vendor/autoload.php'; 
  require 'AWS/configBucket.php'; 

  /*
  use Aws\Iam\IamClient;
  use Aws\Exception\AwsException;
  */

  class AutenticaAmazon{

    private $objConfigBucket = "";
    private $config = array();

  	public function AutenticaAmazon(){
      $this->objConfigBucket = new configBucket();
      $this->config = $this->objConfigBucket->returnConfigBucket();
  	}  	

    public function autenticaBucket($foto,$output,$id_asesor){     
      
      
      $s3 = S3Client::factory([
      		'key' => $this->config['s3']['key'],
      		'secret' => $this->config['s3']['secret']
      ]);  

      //$pathToFile = realpath($output);    
      $fotos = $foto->fileName;		

      if(!isset($_SESSION)){
      	session_start();
      }

      $inmobiliaria = $_SESSION['id_inmobiliaria'];	  		

	  try {
	    $result = $s3->putObject(array(
	    	'Bucket' => $this->config['s3']['bucket'],
	    	'Key' => "sonia/inmobiliarias/{$inmobiliaria}/asesores/$id_asesor/{$fotos}",	    	
	    	'SourceFile' => $output,	    	
	    	'ACL' => 'public-read',
	    	'Metadata'   => array(
        		'Foo' => 'abc',
        		'Baz' => '123'
    		)
	    ));	
	    unlink($output);

        $iterator = $s3->getIterator('ListObjects', array(
    		'Bucket' => $this->config['s3']['bucket']
		));

		$signedUrl = $s3->getObjectUrl($this->config['s3']['bucket'], "sonia/inmobiliarias/{$inmobiliaria}/asesores/$id_asesor/{$fotos}");

        return $signedUrl;		

	  } catch(S3Exception $e){
	  	die("There was an error uploading that file");
	  }
            

    }

    //
    public function autenticaBucketInmobiliaria($foto,$output,$id_inmobiliaria){     
      
      
      $s3 = S3Client::factory([
      		'key' => $this->config['s3']['key'],
      		'secret' => $this->config['s3']['secret']
      ]);  

      //$pathToFile = realpath($output);    
      $fotos = $foto->fileName;		

      if(!isset($_SESSION)){
      	session_start();
      }

      $inmobiliaria = $_SESSION['id_inmobiliaria'];	  		

	  try {
	    $result = $s3->putObject(array(
	    	'Bucket' => $this->config['s3']['bucket'],
	    	'Key' => "sonia/inmobiliarias/{$inmobiliaria}/logo/{$fotos}",	    	
	    	'SourceFile' => $output,	    	
	    	'ACL' => 'public-read',
	    	'Metadata'   => array(
        		'Foo' => 'abc',
        		'Baz' => '123'
    		)
	    ));	
	    unlink($output);

        $iterator = $s3->getIterator('ListObjects', array(
    		'Bucket' => $this->config['s3']['bucket']
		));

		$signedUrl = $s3->getObjectUrl($this->config['s3']['bucket'], "sonia/inmobiliarias/{$inmobiliaria}/logo/{$fotos}");

        return $signedUrl;		

	  } catch(S3Exception $e){
	  	die("There was an error uploading that file");
	  }
            

    }

  }

?>