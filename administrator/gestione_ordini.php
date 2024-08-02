<?php

include_once '../common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ADMIN | GESTIONE ORDINI</title>
<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
</head>

<body>    
	<div class="page">
		<h1>Lista ordini</h1>
        
        <table>
            <tr>
                <th>ID</th>
                <th>DATA</th>
                <th>STATO</th>
                <th>TOTALE</th>
                <th>&nbsp;</th>
                <th>&nbsp;</th>
            </tr>
            <?php
            $ordini = $managerSql->lista_ordini();
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
                        <td>{$ordine['id_ordine']}</td>
                        <td>{$ordine['data']}</td>
                        <td>$stato_ordine</td>
                        <td> &euro; $totale</td>
                        <td><a href=\"edit_ordine.php?id={$ordine['id_ordine']}\">Dettagli</a></td>
                        <td><a href=\"del_ordine.php?id={$ordine['id_ordine']}\" onclick=\"return confirm('Sei sicuro di voler eliminare l\'ordine?')\">Elimina</a></td>
                    </tr>";
            }

            ?>
        </table>
			<br /><a style="font-size:10px; color:#999;" href="admin.php">&lt;&lt; Torna al pannello principale</a>
		 </div>
    
    </body>
</html>