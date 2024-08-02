<?php

include_once 'common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

if( empty($_GET['id']) ){
    header('Location: error.php?code=1');
    exit();
}

if ( $managerSql->get_indirizzo($_GET['id']) && $managerSql->elimina_indirizzo($_GET['id'])  ){
        header('Location: index.php?l=profilo');
        exit();
}else{
    header('Location: error.php?code=15');
    exit();
}

?>
