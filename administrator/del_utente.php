<?php

include_once '../common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

if( empty($_GET['id']) ){
    header('Location: error.php?code=1');
    exit();
}

if ( $managerSql->get_utente($_GET['id']) && $managerSql->elimina_utente($_GET['id']) ) {
    header('Location: gestione_utenti.php');
    exit();
}else{
    header('Location: error.php?code=10');
    exit();
}

?>
