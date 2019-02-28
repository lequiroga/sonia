<?php

  require '../../vendor/autoload.php'; 

  use Aws\Iam\IamClient;  

  //Notificaciones SMS de Amazon
  use Aws\Sns\SnsClient;
  //Email amazon  
  use Aws\Ses\SesClient;
  use Aws\Exception\AwsException;  

  /*
  use Aws\Iam\IamClient;
  use Aws\Exception\AwsException;
  */

  class NotificacionSNS{

    public function notificaEmailClienteNuevo($email,$mensaje,$titulo){

      $SesClient = SesClient::factory(array(
        'profile' => 'arn:aws:iam::429978945912:user/leqa0417',
        'region' => 'us-east-1',
        'credentials' => array(
            'key' => 'AKIAIPNFEYQXARRVI7ZA',
            'secret'  => 'AALkZOmdzfptv9LvNX17s0GkQ5oFQ1sKmZer96pB',
        ),
        'version' => 'latest'
      ));      

      // Replace sender@example.com with your "From" address.
      // This address must be verified with Amazon SES.
      $sender_email = 'kike324@msn.com';

      // Replace these sample addresses with the addresses of your recipients. If
      // your account is still in the sandbox, these addresses must be verified.
      $recipient_emails = [$email];

      // Specify a configuration set. If you do not want to use a configuration
      // set, comment the following variable, and the
      // 'ConfigurationSetName' => $configuration_set argument below.
      $configuration_set = 'ConfigSet';
      
      $subject = 'Mensaje de correo de Montes Avila';
      $plaintext_body = $mensaje ;
      $html_body =  $mensaje;
      $char_set = 'UTF-8';

      try {
          $result = $SesClient->sendEmail([
              'Destination' => [
                'ToAddresses' => $recipient_emails,
              ],
              'ReplyToAddresses' => [$sender_email],
              'Source' => $sender_email,
              'Message' => [
                'Body' => [
                  'Html' => [
                    'Charset' => $char_set,
                    'Data' => $html_body,
                  ],
                  'Text' => [
                    'Charset' => $char_set,
                    'Data' => $plaintext_body,
                  ],
                ],
                'Subject' => [
                  'Charset' => $char_set,
                  'Data' => $titulo,
                ],
              ],
              // If you aren't using a configuration set, comment or delete the
              // following line
              'ConfigurationSetName' => $configuration_set,
          ]);
          $messageId = $result['MessageId'];
          //echo("Email sent! Message ID: $messageId"."\n");
      } catch (AwsException $e) {
          // output error message if fails
          echo $e->getMessage();
          echo("The email was not sent. Error message: ".$e->getAwsErrorMessage()."\n");
          echo "\n";
      }

    }
    
    public function notificaSMS($cell_phone,$mensaje){      

      $SnSclient = SnsClient::factory(array(
        'profile' => 'arn:aws:iam::429978945912:user/leqa0417',
        'region' => 'us-east-1',
        'credentials' => array(
            'key' => 'AKIAIPNFEYQXARRVI7ZA',
            'secret'  => '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',
        ),
        'version' => 'latest'
      ));

      $message = $mensaje;
      $phone = '+57'.$cell_phone;
      $protocol = 'sms';
      $endpoint = '+57'.$cell_phone;
      //print_r($endpoint);exit;
      $topic = 'arn:aws:sns:us-east-1:429978945912:crea_clientes';
      //print_r($endpoint);exit;
      $TargetArn = '';
      try {
        $result = $SnSclient->subscribe([
            'Protocol' => $protocol,
            'Endpoint' => $endpoint,
            'ReturnSubscriptionArn' => true,
            'TopicArn' => $topic
        ]);        
        $resu =(array)$result;
        foreach ($resu as $key => $value) {
          # code...
          if(isset($value['SubscriptionArn'])){
            $TargetArn = $value['SubscriptionArn'];
            break;
          }
        }
      } catch (AwsException $e) {
        // output error message if fails
      }

      try {
        $result = $SnSclient->publish([
            'Message' => $message,        
            'TopicArn' => $topic
        ]);
    
      } catch (AwsException $e) {
        // output error message if fails
        error_log($e->getMessage());
      }

      try {
        $result = $SnSclient->unsubscribe([
            'SubscriptionArn' => $TargetArn            
        ]);        
        
      } catch (AwsException $e) {
        // output error message if fails
      }

    }
   

  }

?>