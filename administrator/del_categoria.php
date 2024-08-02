<?php

include_once '../common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

if( empty($_GET['id']) ){
    header('Location: error.php?code=1');
    exit();
}

$managerSql->start_transaction();

if ( $managerSql->get_categoria($_GET['id']) && 
     $managerSql->elimina_categoria($_GET['id']) && 
    (!file_exists("../images/img_categorie/{$_GET['id']}.png") || unlink("../images/img_categorie/{$_GET['id']}.png") ) ){
        
        $managerSql->transaction_commit();
        header('Location: gestione_categorie.php');
        exit();
}else{
    $managerSql->transaction_rollback();
    header('Location: error.php?code=9');
    exit();
}

?>
