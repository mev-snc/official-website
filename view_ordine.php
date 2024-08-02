<?php


include_once 'common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

include_once 'common/verifica_login.php';

if( empty($_GET['id']) ){
    header('Location: error.php?code=1');
    exit();
}

$ordine = $managerSql->get_ordine($_GET['id']);
if( (!$ordine) || ($ordine['id_utente']!=$utente['id_utente']) ){
    header('Location: error.php?code=11');
    exit();
}

/*
$stato_ordine = 'IN ATTESA';
if( !empty($ordine['data_pagamento']) ){ $stato_ordine = 'PAGATO'; }
if( !empty($ordine['data_spedizione']) ){ $stato_ordine = 'SPEDITO'; }
  */      
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Dettagli Ordine</title>
<style type="text/css">
    .barrato{
        text-decoration: line-through;
    }
</style>
</head>

    <body>
<h2>Dettagli ordine:</h2>
<hr style="background:#CCC;" />
        
<table style="width:100%; text-align:left; padding:10px 10px 10px 10px; border:1px solid #999;">
    <tr style="width:100%;">
                <th style="background:#CCC;  padding:4px 4px 4px 4px;">ID PRODOTTO</th>
                <th style='background:#F4F4F4;  padding:4px 4px 4px 4px;'>NOME PRODOTTO</th>
                <th style="background:#CCC;  padding:4px 4px 4px 4px;">ATTRIBUTI</th>
                <th style='background:#F4F4F4;  padding:4px 4px 4px 4px;'>PREZZO UNITARIO</th>
                <th style="background:#CCC;  padding:4px 4px 4px 4px;">ALIQUOTA IVA</th>
                <th style='background:#F4F4F4;  padding:4px 4px 4px 4px;'>QTA'</th>
                <th style="background:#CCC;  padding:4px 4px 4px 4px;">TOTALE</th>
            </tr>
        <?php
        $items = $managerSql->lista_item_ordine($ordine['id_ordine']);
        $totale = 0;
        for($j=0; $j<count($items); $j++){
            $item =$items[$j];
            $rowclass ='';
            if($item['attivo']==1){
                $totale += $item['prezzo_totale'];
            }else{
                $rowclass = "barrato";
            }
            echo "<tr class=\"$rowclass\">
                    <td style='background:#CCC;  padding:4px 4px 4px 4px;'>{$item['id_prodotto']}</td>
                    <td style='background:#F4F4F4;  padding:4px 4px 4px 4px;'>{$item['nome_prodotto']}</td>
                    <td style='background:#CCC;  padding:4px 4px 4px 4px;'>{$item['attributi']}</td>
                    <td style='background:#F4F4F4;  padding:4px 4px 4px 4px;'>{$item['prezzo_unitario']}</td>
                    <td style='background:#CCC;  padding:4px 4px 4px 4px;'>{$item['aliquota_iva']}</td>
                    <td style='background:#F4F4F4;  padding:4px 4px 4px 4px;'>{$item['qta']}</td>
                    <td style='background:#CCC;  padding:4px 4px 4px 4px;'>{$item['prezzo_totale']}</td>
                </tr>";
        }
        ?>
        </table>
        
        <?php
        include 'common/spedizioni.php';
        foreach ($spedizioni as $spedizione) {
            if($spedizione['sigla'] == $ordine['codice_spedizione'])
                $txt_spedizione = $spedizione['nome'];
        }
        $totale += $ordine['costo_spedizione'];
        ?>
        <p><b>Spedizione:</b> <?php echo $txt_spedizione ?> <br/><br/>
        <b>Costo spedizione:</b> <?php echo $ordine['costo_spedizione'] ?><br /><br/>
        
        <?php
        include 'common/pagamenti.php';
        foreach ($pagamenti as $pagamento) {
            if($pagamento['sigla'] == $ordine['codice_pagamento'])
                $txt_pagamento = $pagamento['nome'];
        }
        $totale += $ordine['costo_pagamento'];
        ?>
        <b>Pagamento:</b> <?php echo $txt_pagamento ?><br/><br/>
        <b>Costo pagamento:</b> <?php echo $ordine['costo_pagamento'] ?><br /><br/>
        
        <b>Totale Ordine:</b> <?php echo $totale ?> <br /><br/>
        
        <b>Data:</b> <?php echo $ordine['data'] ?> <br/><br/>
        <b>Stato dell'ordine:</b> <?php echo $ordine['stato_ordine']; ?></p>
        
        
		  <?php
        if($ordine['stato_ordine'] == 'IN ATTESA'){
            azione_pagmento($ordine['codice_pagamento'], $ordine['id_ordine'], $totale);
        }
        ?>
    </body>
</html>