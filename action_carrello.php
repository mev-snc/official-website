<?php

include_once 'common/dbmanager.php';
include 'common/calc_prezzo.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

session_start();

if( empty($_GET['action']) || !array_key_exists('index', $_GET) || !array_key_exists('carrello', $_SESSION)){
    header('Location: error.php?code=1');
    exit();
}        

$carrello = $_SESSION['carrello'];

switch ($_GET['action']) {
    //cancella riga dal carrello
    case 1:        
        if( isset ($carrello[$_GET['index']])){
            unset ($carrello[$_GET['index']]);
        }
        break;
        
    //modifica quantità del prodotto    
    case 2:
        if( !empty($_GET['qta']) ){
            if( isset ($carrello[$_GET['index']]) ){
                $riga = $carrello[$_GET['index']];
                $riga['quantita'] = $_GET['qta'];
                $carrello[$_GET['index']] = $riga;
            }
        }
        break;
    
    default:
        break;
}

$_SESSION['carrello'] = $carrello;
header('Location: shop.php?l=view_carrello');

?>
