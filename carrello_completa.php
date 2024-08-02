<?php

include_once 'common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}
include_once 'common/calc_prezzo.php';
include_once 'common/verifica_login.php';
//include_once 'common/prezzo_netto_by_group.php';
?>

<h2>Riepilogo...</h2>
<hr style="background:#CCC;" />


<table style="width:100%; text-align:left; padding:10px 10px 10px 10px; border:1px solid #999;">
    <tr style="width:100%;">
        <th style="background:#CCC;  padding:4px 4px 4px 4px;">Prodotto</th>
        <th style='background:#F4F4F4;  padding:4px 4px 4px 4px;'>Attributi</th>
        <th style="background:#CCC;  padding:4px 4px 4px 4px;">Prezzo Un.</th>
        <th style='background:#F4F4F4;  padding:4px 4px 4px 4px;'>Quantità</th>
        <th style="background:#CCC;  padding:4px 4px 4px 4px;">Prezzo Totale(IVA Inc.)</th>
    </tr>

<?php
$carrello = array();
$totale = 0;
$peso = 0;
if(array_key_exists('carrello', $_SESSION)){
    $carrello = $_SESSION['carrello'];
    foreach ($carrello as $index => $riga) {
        $prodotto = $managerSql->get_prodotto($riga['id_prodotto']);
        //$prodotto['prezzo_netto'] = get_prezzo_netto_by_group($prodotto, $utente);
        $prezzo_unitario=$prodotto['prezzo_netto'];
        $peso += $prodotto['peso']*$riga['quantita'];

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
        $prezzo_totale = calcola_prezzo($managerSql, $prodotto, $riga['quantita'], $lista_proprieta);
        echo "<tr>
                <td style=' padding:4px 4px 4px 4px; background:#CCC;'>{$prodotto['nome']}</td>
                <td style='  padding:4px 4px 4px 4px; background:#F4F4F4;'>$attributi</td>
                <td style='  padding:4px 4px 4px 4px; background:#CCC;' >$prezzo_unitario</td>
                <td style='  padding:4px 4px 4px 4px; background:#F4F4F4; text-align:left'>{$riga['quantita']}</td>
                <td style='  padding:4px 4px 4px 4px; background:#CCC;'>$prezzo_totale</td>
            </tr>";
        $totale += $prezzo_totale;
    }

}
?>
</table>

<h2 style="color:#000; float:left; margin-left:-20px; margin-right:-30px;">Totale: &nbsp;&nbsp;&nbsp;</h2><h2 style="color:#6F0; font-size:30px; font-weight:bold; margin:6px 0 0 0px;"><?php echo $totale; ?></h2>

<p>
<b> >>> Spedizione: </b>
<?php 
    include 'common/spedizioni.php';
    foreach ($spedizioni as $spedizione) {
        if($spedizione['sigla'] == $_SESSION['spedizione']){
            $indirizzo = $managerSql->get_indirizzo_predefinito($utente);
            if($indirizzo){
                $provincia = strtoupper($indirizzo['provincia']);
            }else{
                $provincia = strtoupper($utente['provincia']);
            }
            $spedizione['costo'] = calcola_costo_spedizione($spedizione['sigla'], $provincia, $peso); //serve ad avere un costo indicativo
            echo "{$spedizione['nome']} - € {$spedizione['costo']}";
            $totale += $spedizione['costo'];
        }

    }
?>

<br />

<b> >>> Pagamento: </b>
<?php 
    include 'common/pagamenti.php';
    foreach ($pagamenti as $pagamento) {
        if($pagamento['sigla'] == $_SESSION['pagamento']){
            echo "{$pagamento['nome']}";
        }
    }
    $totale+= calcola_costo_pagamento($_SESSION['pagamento'], $totale);
?>

<br />

<b> >>> Indirizzo spedizione:</b>
<div style="border:1px solid #CCC; margin:-10px 50px 0px 50px;">
<?php
$indirizzo_predefinito = $managerSql->get_indirizzo_predefinito($utente);
?>

<form id="form3" method="post" action="shop.php?l=completa_ordine">
  <p><input type="radio" name="indirizzo" id="indirizzo_F" value="F" <?php if(empty($indirizzo_predefinito)) echo 'checked="checked"'; ?> />Indirizzo di fatturazione</p>
<?php
$indirizzi = $managerSql->lista_indirizzi_by_utente($utente);
for($i=0; $i<count($indirizzi); $i++){
    $indirizzo = $indirizzi[$i];
    $checked = '';
    if(!empty($indirizzo_predefinito) && ($indirizzo_predefinito['id_indirizzo']==$indirizzo['id_indirizzo']) ){
        $checked = 'checked="checked"';
    }
    echo "<p> <input type=\"radio\" name=\"indirizzo\" id=\"indirizzo_predefinito_{$i}\" value=\"{$indirizzo['id_indirizzo']}\" $checked />
        Nome: {$indirizzo['nome']} </br>
        Cognome: {$indirizzo['cognome']}</br>
        Indirizzo: {$indirizzo['indirizzo']}</br>
        Città: {$indirizzo['citta']}</br>
        Cap: {$indirizzo['cap']}</br>
        Provincia: {$indirizzo['provincia']}</br>
        Ragione Sociale: {$indirizzo['ragione_sociale']}</p>";
}
?>
</div>

<br /><br />
<hr style="background:#CCC;" />
<div style=" margin-top:-20px; margin-bottom:-50px;">
<h2 style="color:#000; float:left; margin-left:-20px; margin-right:-30px; margin-bottom:4px; margin-top:6px;">Totale effettivo (iva compresa): &nbsp;&nbsp;&nbsp;</h2><h2 style="color:#6F0; font-size:30px; font-weight:bold;"><?php echo $totale; ?></h2>
<div style="clear:both;"></div>
<br />
<br />
</div>
<hr style="background:#CCC;" />

<br />
<input class="inviastandard" type="submit" name="completa" value="Completa Ordine" style="cursor:pointer;" />
</form>
