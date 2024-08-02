<?php


include_once 'common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

include_once 'common/verifica_login.php';

?>


<h2>Lista ordini</h2>

<table style="width:100%; text-align:left; padding:10px 10px 10px 10px; border:1px solid #999;">
    <tr style="width:100%;">
        <th style='background:#CCC;  padding:4px 4px 4px 4px;'>ID</th>
        <th style='background:#F4F4F4;  padding:4px 4px 4px 4px;'>DATA</th>
        <th style='background:#CCC;  padding:4px 4px 4px 4px;'>STATO</th>
        <th style='background:#F4F4F4;  padding:4px 4px 4px 4px;'>TOTALE</th>
        <th style='background:#CCC;  padding:4px 4px 4px 4px;'>&nbsp;</th>
    </tr>
    <?php
    $ordini = $managerSql->lista_ordini_by_utente($utente['id_utente']);
    for($i=0; $i<count($ordini); $i++){
        $ordine = $ordini[$i];
        $stato_ordine = 'IN ATTESA';
        if( !empty($ordine['data_pagamento']) ){ $stato_ordine = 'PAGATO'; }
        if( !empty($ordine['data_spedizione']) ){ $stato_ordine = 'SPEDITO'; }
        $items = $managerSql->lista_item_ordine($ordine['id_ordine']);
        $totale=0;
        for($j=0; $j<count($items); $j++){
            $item =$items[$j];
            $totale += $item['prezzo_totale'];
        }
        $totale += $ordine['costo_spedizione'];
        $totale += $ordine['costo_pagamento'];
        echo "<tr>
                <td style='background:#CCC;  padding:4px 4px 4px 4px;'>{$ordine['id_ordine']}</td>
                <td style='background:#F4F4F4;  padding:4px 4px 4px 4px;'>{$ordine['data']}</td>
                <td style='background:#CCC;  padding:4px 4px 4px 4px;'>$stato_ordine</td>
                <td style='background:#F4F4F4;  padding:4px 4px 4px 4px;'> &euro; $totale</td>
                <td style='background:#CCC;  padding:4px 4px 4px 4px;'><a href=\"shop.php?l=view_ordine&id={$ordine['id_ordine']}\">Dettagli</a></td>
            </tr>";
    }

    ?>
</table>
