<?php

include_once '../common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

include '../common/spedizioni.php';
include '../common/pagamenti.php';

if(empty($_GET['id']) || empty($_GET['act']) ){
    header('Location: error.php?code=1');
    exit();
}

$item = $managerSql->get_item($_GET['id']);

if( !$item ){
    header('Location: error.php?code=18');
    exit();
}

switch ($_GET['act']) {
    case 1:
        //Elimina
        $item['attivo'] = 0;
        break;
        
    case 2:
        //ripristina
        $item['attivo'] = 1;
        break;
    
    default:
        break;
}

$managerSql->start_transaction();
if ( ! $managerSql->modifica_item_ordine($item) ){
    header('Location: error.php?code=19');
    exit();
}
$ordine = $managerSql->get_ordine($item['id_ordine']);
if( !$ordine ){
    $managerSql->transaction_rollback();
    header('Location: error.php?code=17');
    exit();
}
//calcolo costo spedizione
foreach ($spedizioni as $spedizione) {
    if($spedizione['sigla'] == $ordine['codice_spedizione'])
        $ordine['costo_spedizione'] = $spedizione['costo'];
}
//calcolo costo pagamento
$items = $managerSql->lista_item_ordine($ordine['id_ordine']);
$totale = 0;
for($j=0; $j<count($items); $j++){
    $item =$items[$j];
    if($item['attivo']==1){
        $totale += $item['prezzo_totale'];
    }
}
$ordine['costo_pagamento'] = calcola_costo_pagamento($ordine['codice_pagamento'], $totale);
if( ! $managerSql->modifica_ordine($ordine) ){
    $managerSql->transaction_rollback();
    //header('Location: error.php?code=20');
    exit();
}
$managerSql->transaction_commit();
header('Location: edit_ordine.php?id='.$ordine['id_ordine']);
exit();
?>
