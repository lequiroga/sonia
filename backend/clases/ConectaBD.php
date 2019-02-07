<?php

// Conexión a la base de datos

class ConectaBD{

  function conectarBD(){
    $dbconn = pg_connect("host=localhost port=5432 dbname=sonia_db user=postgres password=postgres")
                        or die('No se ha podido conectar: ' . pg_last_error());
    return $dbconn;
  }

}

?>
