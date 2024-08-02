<?php


include_once '../common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

if( empty($_GET['num']) || empty($_GET['anno']) ){
    header('Location: error.php?code=1');
    exit();
}

$fattura = $managerSql->get_fattura($_GET['num'], $_GET['anno']);
if(!$fattura){
    header('Location: error.php?code=46');
    exit();
}

$ordine = $managerSql->get_ordine($fattura['id_ordine']);
if( !$ordine ){
    header('Location: error.php?code=17');
    exit();
}

$salvataggio_completato=0;
$error = array();

if(array_key_exists('salva', $_POST)){
    $old_num_fattura = $fattura['num_fattura'];
    if( empty($_POST['numero']) ){ $error[] = 'numero'; } else{ $fattura['num_fattura'] = $_POST['numero']; }
    if( empty($_POST['data']) ){ $error[] = 'data'; } else{ $fattura['data'] = $_POST['data']; }
    
    if(!count($error)){
        if( !$managerSql->modifica_fattura($old_num_fattura, $fattura) ){
            header('Location: error.php?code=47');
            exit();
        }
        header('Location: gestione_fatture.php');
    }
    
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Dettagli Fattura</title>
</head>

    <body>
    <p>Dettagli della fattura</p>
    
    <?php
        if( count($error) ){
            $campi = implode(', ', $error);
            echo "<p>Compilare correttamente i campi: $campi</p>";
        }
        /*
        if($salvataggio_completato){
            echo '<p>I dati sono stati salvati con successo!</p>';
        }
         */
    ?>

    <form name="frm_fattura" method="post" action="">
        <p>Fattura: <br/>
            Numero: <input type="text" name="numero" id="numero" value="<?php echo htmlspecialchars($fattura['num_fattura']);  ?>" />
            del <input type="text" name="data" id="data" value="<?php echo htmlspecialchars($fattura['data']);  ?>" />
            <input type="submit" name="salva" id="salva" value="Salva Modifiche" onclick="return confirm('Sei sicuro di voler salvare le modifiche alla fattura?')"/> <br/>
            Intestata a: <?php echo htmlspecialchars($fattura['intestazione']); ?>
        </p>
    </form>
    
    <p>Per l'ordine <?php echo $ordine['id_ordine']; ?> del <?php echo $ordine['data']; ?> </p>
    <table border="1">
        <tr>
            <th>ID PRODOTTO</th>
            <th>CODICE PRODOTTO</th>
            <th>NOME PRODOTTO</th>
            <th>ATTRIBUTI</th>
            <th>PREZZO UNITARIO</th>
            <th>ALIQUOTA IVA</th>
            <th>QTA'</th>
            <th>TOTALE</th>
        </tr>
        <?php
        $items = $managerSql->lista_item_ordine($ordine['id_ordine']);
        $totale = 0;
        for($j=0; $j<count($items); $j++){
            $item =$items[$j];
            if($item['attivo']==1){
                $totale += $item['prezzo_totale'];
                echo "<tr>
                        <td>{$item['id_prodotto']}</td>
                        <td>{$item['codice_prodotto']}</td>
                        <td>{$item['nome_prodotto']}</td>
                        <td>{$item['attributi']}</td>
                        <td>{$item['prezzo_unitario']}</td>
                        <td>{$item['aliquota_iva']}</td>
                        <td>{$item['qta']}</td>
                        <td>{$item['prezzo_totale']}</td>
                    </tr>";
            }
            
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
        
        <p>Indirizzo Spedizione: <?php echo $ordine['indirizzo_spedizione'] ?></p>
        
        <p>Note Utente:<br/>
        <?php
        $txt_split = explode('\n', $ordine['note']);
        $ordine['note'] = implode('<br/>', $txt_split);
        unset ($txt_split);
        ?>
        </p>

    </body>
</html>