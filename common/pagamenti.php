<?php

$pagamenti = array();

$bonifico['sigla'] = 'BB';
$bonifico['nome'] = 'Bonifico bancario';
$pagamenti[] = $bonifico;

$paypal['sigla'] = 'PP';
$paypal['nome'] = 'PayPal';
$pagamenti[] = $paypal;

$contrassegno['sigla'] = 'C';
$contrassegno['nome'] = 'Contrassegno ( € 5 )';
$pagamenti[] = $contrassegno;




function calcola_costo_pagamento($sigla, $totale){
    switch ($sigla){
        case 'PP':
            return $totale*0.02;
            break;
        
        case 'C':
            return 5;
            break;
        
        default:
            return 0;
    }
}



function azione_pagmento($sigla,$id_ordine=0, $amount=0){
    if(!empty ($amount)){
        $amount = number_format($amount, 2);
    }
    switch ($sigla){
        case 'PP':
            //set _session per verificare la correttezza del pagamento ID_ORDINE e AMOUNT
            if( !isset ($_SESSION) ){
                session_start();
            }
            $_SESSION['item_number'] = $id_ordine;
            $_SESSION['amount'] = $amount;
            echo "<!--<form action=\"https://www.paypal.com/cgi-bin/webscr\" method=\"post\"> -->
                    <form action=\"https://www.sandbox.paypal.com/cgi-bin/webscr\" method=\"post\">
                    <input type=\"hidden\" name=\"cmd\" value=\"_xclick\">
                    <input type=\"hidden\" name=\"business\" value=\"amedeo_1307374332_biz@gmail.com\">
                    <input type=\"hidden\" name=\"lc\" value=\"IT\">
                    <input type=\"hidden\" name=\"item_name\" value=\"Ordine sul sito Web XXX\">
                    <input type=\"hidden\" name=\"item_number\" value=\"$id_ordine\">
                    <input type=\"hidden\" name=\"amount\" value=\"$amount\">
                    <input type=\"hidden\" name=\"currency_code\" value=\"EUR\">
                    <input type=\"hidden\" name=\"button_subtype\" value=\"services\">
                    <input type=\"hidden\" name=\"no_note\" value=\"0\">
                    <input type=\"hidden\" name=\"bn\" value=\"PP-BuyNowBF:btn_buynowCC_LG.gif:NonHostedGuest\">
                    <!--<input type=\"image\" src=\"https://www.paypalobjects.com/it_IT/IT/i/btn/btn_buynowCC_LG.gif\" border=\"0\" name=\"submit\" alt=\"PayPal - Il sistema di pagamento online più facile e sicuro!\">
                    <img alt=\"\" border=\"0\" src=\"https://www.paypalobjects.com/it_IT/i/scr/pixel.gif\" width=\"1\" height=\"1\">-->
                    <input type=\"image\" src=\"https://www.sandbox.paypal.com/it_IT/IT/i/btn/btn_paynowCC_LG.gif\" border=\"0\" name=\"submit\" alt=\"PayPal - Il sistema di pagamento online più facile e sicuro!\">
                    <img alt=\"\" border=\"0\" src=\"https://www.sandbox.paypal.com/it_IT/i/scr/pixel.gif\" width=\"1\" height=\"1\">
                    </form>";
            break;
            
        case 'C':
            echo "<p>Il pagamento dell'ordine avverrà alla consegna presso l'indirizzo scelto.</p>";
            //invio mail
            break;
        
        case 'BB':
            echo "<p>Per effettuare il pagamento dell'ordine effettuare il bonifico a:<br/>"
                ."XXXXXXXXXXXXXXXXXXXXXXXXX<br/>"
                ."XXXXXXXXXXXXXXXXXXXXXXXXX<br/>"
                ."IBAN 000000000000000000000000000000</p>";
            //invio mail
            break;
    }
}
?>
