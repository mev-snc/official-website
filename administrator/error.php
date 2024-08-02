<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento senza titolo</title>
</head>

<body>
<p>Pagina di errore</p>
<p>

<?php

//se l'accesso alla pagina avviene senza fornire un codice
if( empty($_GET['code']) ){
    $code = 0;
}


$code = $_GET['code'];

switch ($code) {
    case 0:
        echo 'errore nell accesso alla pagina';
        break;

    case 1:
        echo 'i dati non sono stati forniti in modo corretto';
        break;

    case 2:
        echo 'non è stato possibile memorizzare i dati del nuovo utente';
        break;
    
    case 3:
        echo 'non hai le autorizzazioni necessarie per accedere alla pagina';
        break;
    
    case 4:
        echo 'non è stato memorizzare le modifiche ai dati dell\'utente';
        break;
    
    case 5:
        echo 'non è stato possibile modificare la password dell\'utente';
        break;
    
    case 6:
        echo 'non è stato possibile aggiungere l\'indirizzo di spedizione';
        break;
    
    case 7:
        echo 'non è stato possibile modificare le impostazioni dell\'indirizzo predefinito';
        break;
    
    case 8:
        echo 'non è stato possibile memorizzare la nuova categoria';
        break;
    
    case 9:
        echo 'non è stato possibile eliminare la categoria';
        break;
    
    case 10:
        echo 'non è stato possibile eliminare l\'utente';
        break;

    case 11:
        echo 'non è stato possibile aggiungere l\'aliquota iva';
        break;
    
    case 12:
        echo 'non è stato possibile eliminare l\'aliquota iva';
        break;
    
    case 13:
        echo 'non è stato possibile memorizzare il nuovo prodotto';
        break;
    
    case 14:
        echo 'non è stato possibile eliminare il prodotto';
        break;
    
    case 15:
        echo 'non è stato possibile trovare il prodotto';
        break;
    
    case 16:
        echo 'i dati per l\'accesso non sono corretti';
        break;
    
    case 17:
        echo 'non è stato possibile accedere  all\'ordine';
        break;
    
    case 18:
        echo 'non è stato possibile trovare l\'item dell\'ordine';
        break;
    
    case 19:
        echo 'non è stato possibile modificare l\'item dell\'ordine';
        break;
    
    case 20:
        echo 'non è stato possibile modificare l\'ordine';
        break;
    
    case 21:
        echo 'non è stato possibile accedere  al costo del corriere espresso';
        break;
    
    case 22:
        echo 'non è stato possibile accedere  all\'aliquota iva';
        break;
    
    case 23:
        echo 'non è stato possibile modificare l\'aliquota iva';
        break;
    
    case 24:
        echo 'non è stato possibile accedere  alla categoria';
        break;
    
    case 25:
        echo 'non è stato possibile modificare la categoria';
        break;
    
    case 26:
        echo 'non è stato possibile trovare l\'utente';
        break;
    
    case 27:
        echo 'non è stato possibile memorizzare le modifiche al prodotto';
        break;
    
    case 28:
        echo 'non è stato possibile modificare il costo del corriere espresso';
        break;
    
    case 29:
        echo 'non è stato possibile trovare il fornitore';
        break;
    
    case 30:
        echo 'non è stato possibile memorizzare il nuovo fornitore';
        break;
    
    case 31:
        echo 'non è stato possibile memorizzare le modifiche al fornitore';
        break;
    
    case 32:
        echo 'non è stato possibile eliminare il fornitore';
        break;
    
    case 33:
        echo 'non è stato possibile memorizzare il nuovo marchio';
        break;
    
    case 34:
        echo 'non è stato possibile trovare il marchio';
        break;
    
    case 35:
        echo 'non è stato possibile memorizzare le modifiche al marchio';
        break;
    
    case 36:
        echo 'non è stato possibile eliminare il marchio';
        break;
    
    case 37:
        echo 'non è stato possibile aggiungere l\'associazione marchio -> fornitore';
        break;
    
    case 38:
        echo 'non è stato possibile eliminare l\'associazione marchio -> fornitore';
        break;
    
    case 39:
        echo 'non è stato possibile memorizzare la nuova news';
        break;
    
    case 40:
        echo 'non è stato possibile accedere  alla news';
        break;
    
    case 41:
        echo 'non è stato possibile modificare la news';
        break;
    
    case 42:
        echo 'non è stato possibile eliminare la news';
        break;
    
    case 43:
        echo 'non è stato possibile memorizzare la nuova fattura';
        break;
    
    case 44:
        echo 'non è stato possibile aggiungere il prodotto in magazzino';
        break;
    
    case 45:
        echo 'non è stato possibile eliminare il costo corriere espresso';
        break;
    
    case 46:
        echo 'non è stato possibile accedere alla fattura';
        break;
    
    case 47:
        echo 'non è stato possibile modificare la fattura';
        break;
    
    case 48:
        echo 'non è stato possibile eliminare il fornitore';
        break;
    
    case 49:
        echo 'non è stato possibile modificare la password dell\'amministratore';
        break;
    
}
?>

</p>
</body>
</html>