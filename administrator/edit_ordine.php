<?php


include_once '../common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

if( empty($_GET['id']) ){
    header('Location: error.php?code=1');
    exit();
}

$ordine = $managerSql->get_ordine($_GET['id']);
if( !$ordine ){
    header('Location: error.php?code=17');
    exit();
}
$fattura = $managerSql->get_fattura_by_ordine($ordine['id_ordine']);

$salvataggio_completato=0;
if(array_key_exists('salva', $_POST)){
    $ordine['data_pagamento'] = $_POST['data_pagamento'];
    $ordine['data_spedizione'] = $_POST['data_spedizione'];
    $ordine['traking_number'] = $_POST['traking_number'];
    $ordine['note_admin'] = $_POST['note_admin'];
    $ordine['stato_ordine'] = $_POST['stato_ordine'];
    if( !$managerSql->modifica_ordine($ordine) ){
        header('Location: error.php?code=20');
        exit();
    }
    
    //se è stato effettuato il pagamento allora emetti fattura
    /*
    if( !empty($ordine['data_pagamento']) && !$fattura ){ 
        $fattura = array();
        $fattura['anno'] = date('Y');
        $fattura['data'] = date('Y-m-d');
        $utente = $managerSql->get_utente($ordine['id_utente']);
        if($utente){
            $fattura['intestazione'] = "{$utente['ragione_sociale']} - {$utente['piva']} - {$utente['cognome']} {$utente['nome']} - {$utente['codice_fiscale']} - {$utente['provincia']} - {$utente['cap']} {$utente['citta']} - {$utente['indirizzo']}";
        }else{
            $fattura['intestazione'] = $ordine['indirizzo_spedizione'];
        }
        $fattura['id_ordine'] = $ordine['id_ordine'];
        if( ! $managerSql->aggiungi_fattura($fattura) ){
            header('Location: error.php?code=43');
            exit();
        }
        $fattura = $managerSql->get_fattura_by_ordine($ordine['id_ordine']);
    }*/
    
    $salvataggio_completato=1;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ADMIN | EDITA ORDINE</title>
<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
</head>

<body>    
	<div class="page">
    <h1>Dettagli dell'ordine</h1>
    
    <?php
        if($salvataggio_completato){
            echo '<p>I dati sono stati salvati con successo!</p>';
        }
    ?>

    <form name="ordine" method="post" action="<?php echo "{$_SERVER['PHP_SELF']}?id={$ordine['id_ordine']}" ?>">
        <table class="table2">
            <tr>
                <th>ID PRODOTTO</th>
                <th>NOME PRODOTTO</th>
                <th>ATTRIBUTI</th>
                <th>PESO</th>
                <th>PREZZO UNITARIO</th>
                <th>ALIQUOTA IVA</th>
                <th>QTA'</th>
                <th>TOTALE</th>
                <th>&nbsp;</th>
            </tr>
        <?php
        $items = $managerSql->lista_item_ordine($ordine['id_ordine']);
        $totale = 0;
        $peso_totale = 0;
        for($j=0; $j<count($items); $j++){
            $item =$items[$j];
            if($item['attivo']==1){
                $totale += $item['prezzo_totale'];
                $peso_totale += ($item['peso']*$item['qta']);
                $txt_azione = "<a href=\"edit_item_ordine.php?id={$item['id_ordine_item']}&act=1\" onclick=\"javascript: return confirm('Sei sicuro di voler eliminare l\'item?');\">Elimina</a>";
            }else{
                $txt_azione = "<a href=\"edit_item_ordine.php?id={$item['id_ordine_item']}&act=2\" onclick=\"javascript: return confirm('Sei sicuro di voler ripristinare l\'item?');\">Ripristina</a>";
            }
            $style='';//stile per segnare le righe cancellate
            if($item['attivo']!=1){
                $style='style="text-decoration: line-through;"';
            }
            if($fattura){
                $txt_azione = ''; //se è stata emessa la fattura l'ordine nn deve essere modificato
            }
            echo "<tr $style>
                    <td>{$item['id_prodotto']}</td>
                    <td>{$item['nome_prodotto']}</td>
                    <td>{$item['attributi']}</td>
                    <td>{$item['peso']}</td>
                    <td>{$item['prezzo_unitario']}</td>
                    <td>{$item['aliquota_iva']}</td>
                    <td>{$item['qta']}</td>
                    <td>{$item['prezzo_totale']}</td>
                    <td>$txt_azione</td>
                </tr>";
        }
        ?>
        </table>
        
        <?php
        include '../common/spedizioni.php';
        foreach ($spedizioni as $spedizione) {
            if($spedizione['sigla'] == $ordine['codice_spedizione'])
                $txt_spedizione = $spedizione['nome'];
        }
        $totale += $ordine['costo_spedizione'];
        ?>
        <p>Peso totale: <?php echo $peso_totale; ?></p>
        <p>Spedizione: <?php echo $txt_spedizione; ?> <br/>
        Costo spedizione: <?php echo $ordine['costo_spedizione'] ?></p>
        
        <?php
        include '../common/pagamenti.php';
        foreach ($pagamenti as $pagamento) {
            if($pagamento['sigla'] == $ordine['codice_pagamento'])
                $txt_pagamento = $pagamento['nome'];
        }
        $totale += $ordine['costo_pagamento'];
        ?>
        <p>Pagamento: <?php echo $txt_pagamento ?><br/>
        Costo pagamento: <?php echo $ordine['costo_pagamento'] ?></p>
        
        <p>Totale Ordine: <?php echo $totale ?> </p>
        
        <p>Data: <?php echo $ordine['data'] ?> <br/>
        <br /><br />
        Stato dell'ordine: <?php 
        $i_a = $p = $s = $c = '';
        switch ($ordine['stato_ordine']) {
            case 'IN_ATTESA': $i_a = 'selected="selected"'; break;
            case 'PAGATO': $p = 'selected="selected"'; break;
            case 'SPEDITO': $s = 'selected="selected"'; break;
            case 'CANCELLATO': $c = 'selected="selected"'; break;
            default:
                break;
        }
        ?>
        <select name="stato_ordine" id="stato_ordine">
            <option <?php echo $i_a; ?> >IN_ATTESA</option>
            <option <?php echo $p; ?> >PAGATO</option>
            <option <?php echo $s; ?> >SPEDITO</option>
            <option <?php echo $c; ?> >CANCELLATO</option>
        </select>
        
        <br /><br /><br />
        
        Indirizzo Spedizione: <br />
		  	<p><?php echo $ordine['indirizzo_spedizione'] ?></p></p>

			<br />
        <p>Note Utente:<br/>
        <?php
        $txt_split = explode('\n', $ordine['note']);
        $ordine['note'] = implode('<br/>', $txt_split);
        unset ($txt_split);
        ?>
        </p>
        
        <br />
		  <p>Data pagamento: <input type="text" name="data_pagamento" id="data_pagamento" value="<?php echo htmlspecialchars($ordine['data_pagamento']); ?>" /></p>
        <p>Data spedizione: <input type="text" name="data_spedizione" id="data_spedizione" value="<?php echo htmlspecialchars($ordine['data_spedizione']); ?>"/> <br/>
            Traking number: <input type="text" name="traking_number" id="traking_number" value="<?php echo htmlspecialchars($ordine['traking_number']); ?>"/> </p>
        <p>
            Note Amministratore:<br/>
            <textarea name="note_admin" id="note_admin" rows="5" cols="45"><?php echo htmlspecialchars($ordine['note_admin']); ?></textarea>
        </p>
        <p><input type="submit" name="salva" id="salva" value="Salva Modifiche"/> </p>
    </form>
       <!-- 
    <p>Fattura: <br/>
    <?php
    if($fattura){
        echo "Numero: {$fattura['num_fattura']} del {$fattura['data']} <br/>
        Intestata a: {$fattura['intestazione']}";
    }
    ?>
    </p>
    -->
 		<a style="font-size:10px; color:#999;" href="admin.php">&lt;&lt; Torna al pannello principale</a>
   
    </body>
</html>
