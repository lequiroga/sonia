<?php

// Conexión a la base de datos

class ConectaBD{    

  function conectarBD(){
    $dbconn = pg_connect("host=localhost port=5432 dbname=montesavibd user=postgres password=postgres0417")
                        or die('No se ha podido conectar: ' . pg_last_error());      
    return $dbconn;                    
  }

}

?>