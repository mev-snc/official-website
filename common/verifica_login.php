<?php

if( !isset ($_SESSION) ){
    session_start();
}


$utente=array();
if( empty($_SESSION['id_utente']) || !($utente=$managerSql->get_utente($_SESSION['id_utente'], NULL, NULL )) ){
    header('Location: error.php?code=3');
    exit();
}

?>
