<?php

include_once '../common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

if( empty($_GET['id']) ){
    header('Location: error.php?code=1');
    exit();
}

if ( $managerSql->get_marchio($_GET['id']) && $managerSql->elimina_marchio($_GET['id'])  ){
        header('Location: gestione_marchi.php');
        exit();
}else{
    header('Location: error.php?code=36');
    exit();
}

?>
