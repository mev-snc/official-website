<?php

function calcola_prezzo($managerSql, $prodotto, $quantita, $lista_proprieta){
    $prezzo_unitario = $prodotto['prezzo_netto'];
    
    for($i=0; $i<count($lista_proprieta); $i++){
        $id_proprieta = $lista_proprieta[$i];
        $proprieta = $managerSql->get_proprieta($id_proprieta);
        $attributo = $managerSql->get_attributo($proprieta['id_attributo']);
        $prezzo_unitario += $proprieta['variazione_prezzo'];
    }
    if($prodotto['promo']){
        $prezzo_unitario -= $prezzo_unitario*$prodotto['sconto_promo'];
    }
    $prezzo_totale = $prezzo_unitario * $quantita;
    $iva = $managerSql->get_aliquota_iva($prodotto['id_aliquota_iva']);
    $prezzo_totale += $prezzo_totale * $iva['aliquota'];
    
    return $prezzo_totale;
}

?>
