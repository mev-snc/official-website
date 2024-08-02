<?php

session_start();

if( empty($_SESSION['adm_mode']) || ($_SESSION['adm_mode']!='ON') ){
    header('Location: index.php');
    exit();
}


?>
