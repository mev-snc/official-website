<?php

/*
 * CARICA L'UTENTE LOGGATO SE IN SESSIONE SONO SETTATE LE VARIABILI
 */

if( !isset ($_SESSION) ){
    session_start();
}

include_once 'dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

$utente=array();
if( !empty($_SESSION['id_utente']) ){
    $utente = $managerSql->get_utente($_SESSION['id_utente'], NULL, NULL );
}

?>
