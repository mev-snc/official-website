<?php

include_once '../common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

if( empty($_GET['id']) ){
    header('Location: error.php?code=1');
    exit();
}

if ( $managerSql->get_ordine($_GET['id']) && $managerSql->elimina_ordine($_GET['id'])  ){
        header('Location: gestione_ordini.php');
        exit();
}else{
    header('Location: error.php?code=48');
    exit();
}

?>
