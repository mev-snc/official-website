<?php
include_once 'common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

extract($_GET);
extract($_POST);

if(empty($richiesta)){
    echo 'x';//non significa niente ma c'è per debug
    exit();
}

switch ($richiesta){
    case "get_qta_disponibile": get_qta_disponibile(); break;
}


function get_qta_disponibile(){
    global $managerSql, $id_prodotto, $proprieta;
    $magazzino = $managerSql->get_magazzino($id_prodotto, $proprieta);
    $qta = 0;
    if($magazzino){
        $qta = $magazzino['qta'];
    }
    //echo $qta;
    
    //1 - visualizza quantità
    //2 - disponibile - non disponibile
    //3 - nn visualizzare
    $prodotto = $managerSql->get_prodotto($id_prodotto);
    if(!$prodotto){
        echo '0'; return;
    }
    switch ($prodotto['visualizzazione_qta']) {
        case '1':
            echo "Disponibili: $qta";
            break;
        case '2': 
            if($qta>0)
                echo "Prodotto disponibile";
            else
                echo "Prodotto NON disponibile";
            break;
        default:
            break;
    }
    return;
}

?>
