<?php
include_once 'common/dbmanager.php';
include 'common/calc_prezzo.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}


if( empty($_POST['id_prodotto']) || empty($_POST['qta'])){
    header('Location: error.php?code=1');
    exit();
}
            
$prodotto = $managerSql->get_prodotto($_POST['id_prodotto']);
if( !$prodotto){
    header('Location: error.php?code=9');
    exit();
}

$lista_proprieta = array();
if(array_key_exists('proprieta', $_POST)){
    $lista_proprieta = $_POST['proprieta'];
}


$riga['id_prodotto'] = $prodotto['id_prodotto'];
$riga['quantita'] = $_POST['qta'];

/*
for($i=0; $i<count($lista_proprieta); $i++){
    $id_proprieta = $lista_proprieta[$i];
    $proprieta = $managerSql->get_proprieta($id_proprieta);
    $attributo = $managerSql->get_attributo($proprieta['id_attributo']);
} */           
$riga['proprieta'] = implode('|', $lista_proprieta);

//verifica disponibilità delle quantità in magazzino
$magazzino = $managerSql->get_magazzino($prodotto['id_prodotto'], $riga['proprieta']);
$qta = 0;
if($magazzino){
    $qta = $magazzino['qta'];
}
if($riga['quantita']<=$qta){
    session_start();
    $_SESSION['carrello'][] = $riga;
}


header('Location: shop.php?l=view_carrello');


?>