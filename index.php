<?php

    if(!isset($_SESSION))
      session_start();
      
    session_destroy();

    header('Location: frontend/views/menuPrincipal/formularioMenu.html');

?>
