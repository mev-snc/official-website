<?php

include_once '../common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

if( empty($_GET['id_m']) || empty($_GET['id_f']) ){
    header('Location: error.php?code=1');
    exit();
}

if ( $managerSql->get_assoc_marchio_fornitore($_GET['id_m'], $_GET['id_f']) && $managerSql->elimina_assoc_marchio_fornitore($_GET['id_m'],$_GET['id_f']) ){
        header('Location: edit_fornitore.php?id='.$_GET['id_f']);
        exit();
}else{
    header('Location: error.php?code=38');
    exit();
}

?>
