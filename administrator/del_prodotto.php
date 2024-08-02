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

if ( $managerSql->get_prodotto($_GET['id']) && 
     $managerSql->elimina_prodotto($_GET['id']) && 
    (!file_exists("../images/img_prodotti/{$_GET['id']}.png") || unlink("../images/img_prodotti/{$_GET['id']}.png") ) ){
        
        $managerSql->transaction_commit();
        header('Location: gestione_prodotti.php');
        exit();
}else{
    $managerSql->transaction_rollback();
    header('Location: error.php?code=14');
    exit();
}

?>
