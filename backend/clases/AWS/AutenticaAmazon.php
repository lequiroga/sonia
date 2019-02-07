<?php

  require '../../vendor/autoload.php';

  use Aws\Iam\IamClient;
  use Aws\Exception\AwsException;

  class AutenticaAmazon{

    public function autenticaAPI(){
      
      $client = new IamClient([
        'profile' => 'arn:aws:iam::429978945912:user/leqa0417',
        'region' => 'us-west-2',
        'version' => '2018-08-09 20:26 EST'
	  ]);

	  try {
        $result = $client->createAccessKey([
            'UserName' => 'arn:aws:iam::429978945912:user/leqa0417',
        ]);
        $keyID = $result['AccessKey']['AccessKeyId'];
        $createDate = $result['AccessKey']['CreateDate'];
        $userName = $result['AccessKey']['UserName'];
        $status = $result['AccessKey']['Status'];
        // $secretKey = $result['AccessKey']['SecretAccessKey']
        echo "<p>AccessKey " . $keyID . " created on " . $createDate . "</p>";
        echo "<p>Username: " . $userName . "</p>";
        echo "<p>Status: " . $status . "</p>";
	  } catch (AwsException $e) {
        // output error message if fails
        error_log($e->getMessage());
	  }	

    }

  }

?>