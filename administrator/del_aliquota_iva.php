<?php

include_once '../common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

if( empty($_GET['id']) ){
    header('Location: error.php?code=1');
    exit();
}

if ( $managerSql->get_aliquota_iva($_GET['id']) && $managerSql->elimina_aliquota_iva($_GET['id'])  ){
        header('Location: gestione_aliquote_iva.php');
        exit();
}else{
    header('Location: error.php?code=12');
    exit();
}

?>
