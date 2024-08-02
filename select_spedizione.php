<?php
session_start();

include_once 'common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

if( empty($_POST['spedizione']) ){
    header('Location: error.php?code=1');
    exit();
}

$_SESSION['spedizione'] = $_POST['spedizione'];

header('Location: shop.php?l=carrello_pagamento');
exit();

?>