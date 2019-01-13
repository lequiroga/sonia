<?php

  // Conexión a la base de datos
  require_once('ConectaBD.php');

  class AutenticaAPI{    

    function retornarDatosAPI($provider,$servicio){

            $conn   = new ConectaBD();
			$dbConn = $conn->conectarBD(); 

			unset($conn);

			$output = array();

            $query = "SELECT 
	            		a.id_api AS id_api,
	            		a.token_api AS token_api,
	            		a.uri AS uri,
	            		(
                          SELECT
                            b.uri_compl
                          FROM
                            generales.tb_aut_externas_services b
                          WHERE    
                            b.name_service='$servicio'
                            AND b.id_aut_externas=a.id_aut_externas
	            		) as uri_compl
	          		  FROM 
	            		generales.tb_aut_externas a
                      WHERE 
                        a.provider='$provider'
	            		";

			$result = pg_query($query) or die('La consulta fallo: ' . pg_last_error());

			if(pg_num_rows($result)>0){
	
	  			while($row = pg_fetch_array($result, null)){	  	
	    			$output['id_api'] = $row['id_api'];
	    			$output['token_api']  = $row['token_api'];	 
	    			$output['uri']  = $row['uri'];  
	    			$output['uri_compl']  = $row['uri_compl'];  			    
	  			}	

                // Liberando el conjunto de resultados
				pg_free_result($result);

				pg_close($dbConn);

			}

			return $output;       

    }

  }

?>