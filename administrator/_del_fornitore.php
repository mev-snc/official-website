<?php

include_once '../common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

if( empty($_GET['id']) ){
    header('Location: error.php?code=1');
    exit();
}

if ( $managerSql->get_fornitore($_GET['id']) && $managerSql->elimina_fornitore($_GET['id'])  ){
        header('Location: gestione_fornitori.php');
        exit();
}else{
    header('Location: error.php?code=32');
    exit();
}

?>
