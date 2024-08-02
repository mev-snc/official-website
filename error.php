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
        echo 'non è stato possibile memorizzare le modifiche ai dati dell\'utente';
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
        echo 'non è stato possibile trovare il prodotto selezionato';
        break;
    
    case 10:
        echo 'non è stato possibile aggiungere l\'ordine';
        break;
    
    case 11:
        echo 'non è stato possibile accedere  all\'ordine';
        break;
    
    case 12:
        echo 'non è stato possibile recuperare la password';
        break;
    
    case 13:
        echo 'non è stato possibile trovare l\'utente';
        break;
    
    case 14:
        echo 'il carrello è vuoto. l\'ordine non può essere completato ';
        break;
    
    case 15:
        echo 'non è stato possibile eliminare l\'indirizzo di spedizione';
        break;
    
    case 16:
        echo 'non è possibile acquistare i prodotti.<br/>Le quantità superano la disponibilità di magazzino';
        break;
    
    case 17: 
        echo 'non è stato possibile trovare la categoria';
        break;
    
    case 18:
        echo 'non è stato possibile modificare lo stato dell\'ordine. Contattare l\'amministratore ';
        break;

}
?>

</p>
</body>
</html>