
<?php

//verifica login
include 'common/verifica_login.php';

// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-synch';

$tx_token = $_GET['tx'];

$auth_token = "tDYt1z-qupt8Q99Z4hLS-CDD9Z0O0ZD7QuMOMG_ENqP1X0ZAkxqM1dO0h0K";
//$auth_token = "";

$req .= "&tx=$tx_token&at=$auth_token";


// post back to PayPal system to validate
$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
//$fp = fsockopen ('www.paypal.com', 80, $errno, $errstr, 30);
// If possible, securely post back to paypal using HTTPS
// Your PHP server will need to be SSL enabled
 $fp = fsockopen ('ssl://www.sandbox.paypal.com', 443, $errno, $errstr, 30);

if (!$fp) {
// HTTP ERROR
} else {
fputs ($fp, $header . $req);
// read the body data
$res = '';
$headerdone = false;
while (!feof($fp)) {
$line = fgets ($fp, 1024);
if (strcmp($line, "\r\n") == 0) {
// read the header
$headerdone = true;
}
else if ($headerdone)
{
// header has been read. now read the contents
$res .= $line;
}
}

// parse the data
$lines = explode("\n", $res);
$keyarray = array();
if (strcmp ($lines[0], "SUCCESS") == 0) {
for ($i=1; $i<count($lines);$i++){
list($key,$val) = explode("=", $lines[$i]);
$keyarray[urldecode($key)] = urldecode($val);
}


// check the payment_status is Completed
if( strcmp($keyarray['payment_status'], "Completed") != 0 ){
    exit('Il pagamento non è andato a buon fine... Riprovare o contattare l\'amministratore');
}

// check item_number is $_SESSION['item_number']
if( empty($_SESSION['item_number']) || (strcmp($keyarray['item_number'], $_SESSION['item_number'] ) != 0) ){
    exit('Pagamento effettuato in modo illegale');
}

// check that txn_id has not been previously processed
// check that receiver_email is your Primary PayPal email
if( strcmp($keyarray['receiver_email'], "amedeo_1307374332_biz@gmail.com") != 0 ){
    exit('Pagamento effettuato in modo illegale');
}

// check that payment_amount/payment_currency are correct

if( strcmp($keyarray['mc_gross'], $_SESSION['amount']) != 0 ){
    exit('Pagamento effettuato in modo illegale');
}


// process payment
include_once 'common/dbmanager.php';
if (empty($managerSql)) {
    $managerSql = new dbManager();
}
$ordine = $managerSql->get_ordine($_SESSION['item_number']);
if(!$ordine){
    header('Location: error.php?code=11');
    exit();
}
$ordine['stato_ordine'] = 'PAGATO';
$ordine['data_pagamento'] = date('Y-m-d');
if(!$managerSql->modifica_ordine($ordine)){
    header('Location: error.php?code=18');
    exit();
}



//----- INIZIO Stampa di conferma pagamento
$firstname = $keyarray['first_name'];
$lastname = $keyarray['last_name'];
$itemname = $keyarray['item_name'];
$itemnumber = $keyarray['item_number'];
$amount = $keyarray['mc_gross'];

echo ("<p><h3>Grazie per il pagamento</h3></p>");

echo ("<b>Dettagli del pagamento</b><br>\n");
echo ("<li>Name: $firstname $lastname</li>\n");
echo ("<li>Item: $itemname</li>\n");
echo ("<li>Item number: $itemnumber</li>\n");
echo ("<li>Amount: $amount</li>\n");
//-- FINE stampa conferma pagamento

//pagamento andato a buon fine. è possibile tornare alla pagina del profilo
header('Location: profilo.php');
exit();


}
else if (strcmp ($lines[0], "FAIL") == 0) {
// log for manual investigation
    echo 'Sono avvenuti errori nel pagamento... Contattare l\'amministratore';
}

}

fclose ($fp);

?>