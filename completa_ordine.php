<?php

include_once 'common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}
include_once 'common/calc_prezzo.php';

include_once 'common/verifica_login.php';
//include_once 'common/prezzo_netto_by_group.php'; 


$carrello = (array_key_exists('carrello', $_SESSION)) ? $_SESSION['carrello'] : array();
$items = array();
$totale = 0;
$peso = 0;

if( !array_key_exists('carrello', $_SESSION) || empty($carrello) ){
    header('Location: error.php?code=14');
    exit();
}
    
foreach ($carrello as $index => $riga) {
    $prodotto = $managerSql->get_prodotto($riga['id_prodotto']);
    //$prodotto['prezzo_netto'] = get_prezzo_netto_by_group($prodotto, $utente);

    $item['id_prodotto'] = $riga['id_prodotto'];
    //$item['codice_prodotto'] = $prodotto['codice'];
    $item['nome_prodotto'] = $prodotto['nome'];
    $item['descrizione_breve'] = $prodotto['descrizione_breve'];

    $prezzo_unitario=$prodotto['prezzo_netto'];
    $attributi='';
    $lista_proprieta = array();
    if(!empty($riga['proprieta'])){
        $lista_proprieta = explode('|', $riga['proprieta']);
    }
    for($i=0; $i<count($lista_proprieta); $i++){
        $proprieta = $managerSql->get_proprieta($lista_proprieta[$i]);
        $attributo = $managerSql->get_attributo($proprieta['id_attributo']);
        $attributi .= "{$attributo['nome']} : {$proprieta['valore']} <br/>";

        $prezzo_unitario += $proprieta['variazione_prezzo'];
    }
    if($prodotto['promo']){
        $prezzo_unitario -= $prezzo_unitario*$prodotto['sconto_promo'];
    }

    $item['attributi'] = $attributi;
    $item['prezzo_unitario'] = $prezzo_unitario;

    $iva = $managerSql->get_aliquota_iva($prodotto['id_aliquota_iva']);
    $item['aliquota_iva'] = $iva['aliquota'];

    $item['qta'] = $riga['quantita'];

    $item['lista_proprieta'] = empty($riga['proprieta']) ? '' : $riga['proprieta'];//per aggiornamento magazzino, questo campo non viene salvato nel database

    $prezzo_totale = calcola_prezzo($managerSql, $prodotto, $riga['quantita'], $lista_proprieta);
    $item['prezzo_totale'] = $prezzo_totale;

    $item['peso'] = $prodotto['peso'];
    $items[] = $item;
    $totale += $prezzo_totale;
    $peso += $prodotto['peso']*$riga['quantita'];
}



$managerSql->start_transaction();

$ordine = array();
$ordine['id_utente'] = $utente['id_utente'];
$ordine['data'] = date("Y-m-d H:i");
$ordine['codice_spedizione'] = $_SESSION['spedizione'];

if($_POST['indirizzo']=='F'){
    $ordine['indirizzo_spedizione'] = "{$utente['cognome']} {$utente['nome']} - {$utente['ragione_sociale']} - {$utente['citta']} {$utente['cap']} ({$utente['provincia']}) - {$utente['indirizzo']}";
    $provincia = $utente['provincia'];
}else{
    $indirizzo = $managerSql->get_indirizzo($_POST['indirizzo']);
    $ordine['indirizzo_spedizione'] = "{$indirizzo['cognome']} {$indirizzo['nome']} - {$indirizzo['ragione_sociale']} - {$indirizzo['citta']} {$indirizzo['cap']} ({$indirizzo['provincia']}) - {$indirizzo['indirizzo']}";
    $provincia = $indirizzo['provincia'];
}

include 'common/spedizioni.php';
foreach ($spedizioni as $spedizione) {
    if($spedizione['sigla'] == $_SESSION['spedizione']){
        $spedizione['costo'] = calcola_costo_spedizione($spedizione['sigla'], $provincia, $peso); //serve ad avere un costo indicativo
        $ordine['costo_spedizione'] = $spedizione['costo'];
        $totale += $spedizione['costo'];
    }
}

$ordine['codice_pagamento'] = $_SESSION['pagamento'];
include 'common/pagamenti.php';
$ordine['costo_pagamento'] = calcola_costo_pagamento($_SESSION['pagamento'], $totale);
$totale += $ordine['costo_pagamento'];

$ordine['stato_ordine'] = 'IN ATTESA';
$ordine['indirizzo_ip'] = $_SERVER['REMOTE_ADDR'];

$id_ordine = $managerSql->aggiungi_ordine($ordine);
if(!$id_ordine){
    $managerSql->transaction_rollback();
    header('Location: error.php?code=10');
    exit();
}

foreach ($items as $item) {
    //se la quantità che si intende acquistare supera la disponibilità in magazzino
    $disponibilita = $managerSql->get_magazzino($item['id_prodotto'], $item['lista_proprieta'] );
    if( !$disponibilita || (($disponibilita['qta']-$item['qta'])<0) ){
        $managerSql->transaction_rollback();
        header('Location: error.php?code=16');
        exit();
    }
    
    $magazzino=array();
    $magazzino['id_prodotto'] = $item['id_prodotto'];
    $magazzino['attributi'] = $item['lista_proprieta'];
    $magazzino['qta'] = -($item['qta']);
        
    if( ! $managerSql->aggiungi_item_ordine($id_ordine, $item) || !$managerSql->modifica_magazzino($magazzino) ){
        $managerSql->transaction_rollback();
        header('Location: error.php?code=10');
        exit();
    }
}

unset($_SESSION['carrello']);

$managerSql->transaction_commit();
echo "<p>Il tuo ordine è stato confermato. L'ID è $id_ordine</p>";
echo "<p>Per visualizzare i dettagli clicca <a href=\"shop.php?l=view_ordine&id=$id_ordine\">QUI</a></p>";

//azione in base al tipo pagamento scelto.
azione_pagmento($ordine['codice_pagamento'],$id_ordine, $totale);

?>
