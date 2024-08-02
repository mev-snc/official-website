<?php

include_once '../common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

if( empty($_GET['p']) || !array_key_exists('da', $_GET) || !array_key_exists('a', $_GET) ){
    header('Location: error.php?code=1');
    exit();
}

if ( $managerSql->get_costo_corriere_exp($_GET['p'], $_GET['da'], $_GET['a'] ) && $managerSql->elimina_costo_corriere_exp($_GET['p'], $_GET['da'], $_GET['a'] )  ){
        header('Location: edit_costo_corriere_exp.php');
        exit();
}else{
    header('Location: error.php?code=45');
    exit();
}

?>
