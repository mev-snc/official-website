<?php

//prezzo_netto in base al gruppo
function get_prezzo_netto_by_group($prodotto, $utente){
    $gruppo = (!empty ($utente)) ? $utente['gruppo'] : 'CLIENTE';
    switch ($gruppo) {
        case 'CLIENTE':  return $prodotto['prezzo_pubblico'];
        case 'RIVENDITORE':  return $prodotto['prezzo_rivenditore'];
        case 'NEGOZIO':  return $prodotto['prezzo_negozio'];
    }
}
//----Fine prezzo netto in base al gruppo
                
?>
