<?php

include_once 'common/load_login_user.php';

include_once 'common/dbmanager.php';
include_once 'common/calc_prezzo.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

//include_once 'common/prezzo_netto_by_group.php';


$carrello = array();
$totale = 0;
if(array_key_exists('carrello', $_SESSION)){
    $carrello = $_SESSION['carrello'];
    foreach ($carrello as $index => $riga) {
        $prodotto = $managerSql->get_prodotto($riga['id_prodotto']);
        //$prodotto['prezzo_netto'] = get_prezzo_netto_by_group($prodotto, $utente);
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
        $prezzo_totale = calcola_prezzo($managerSql, $prodotto, $riga['quantita'], $lista_proprieta);
        
        $totale += $prezzo_totale;
    }

}

?>

<h2 style="margin-left:-20px;">Mini Cart</h2>
<div style="border-top:#000 1px dotted;border-bottom:#000 2px dotted; padding-bottom:5px;">
<p style="margin-left:-20px; margin-bottom:-40px; margin-top:-10px;">Hai <?php echo count($carrello); ?> prodotti nel carrello </p>
<p style="margin-left:-20px; margin-bottom:-50px; margin-top:-10px;">Totale: <?php echo $totale; ?></p>
<p style="margin-left:-20px;  margin-bottom:-15px;" ><a href="shop.php?l=view_carrello">Vai al carrello</a> </p>
</div>