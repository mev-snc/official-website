<?php
include_once 'common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

$utente = array();
$utente['email']='';
$utente['username']='';
$utente['nome']='';
$utente['cognome']='';
$utente['codice_fiscale']='';
$utente['piva']='';
$utente['indirizzo']='';
$utente['citta']='';
$utente['cap']='';
$utente['provincia']='';
$utente['ragione_sociale']='';
$utente['fax']='';
$utente['domanda']='';
$utente['risposta']='';


$error = array();

if(array_key_exists('registra', $_POST)){
    //Aggiungi utente al sistema
    if( empty($_POST['email']) ){ $error[] = 'email'; }else{ $utente['email']=$_POST['email']; }
    if( empty($_POST['username']) ){ $error[] = 'username'; }else{ $utente['username']=$_POST['username']; }
    if( empty($_POST['password']) || empty($_POST['password2']) || strcmp($_POST['password'], $_POST['password2']) ){ $error[] = 'password'; }else{ $utente['password']=$_POST['password']; }
    
    if( empty($_POST['nome']) ){ $error[] = 'nome'; }else{ $utente['nome']=$_POST['nome']; }
    if( empty($_POST['cognome']) ){ $error[] = 'cognome'; }else{ $utente['cognome']=$_POST['cognome']; }
    if( empty($_POST['codice_fiscale']) ){ $error[] = 'codice_fiscale'; }else{ $utente['codice_fiscale']=$_POST['codice_fiscale']; }
    if( array_key_exists('piva', $_POST) ){ $utente['piva']=$_POST['piva']; }
    if( empty($_POST['indirizzo']) ){ $error[] = 'indirizzo'; }else{ $utente['indirizzo']=$_POST['indirizzo']; }
    if( empty($_POST['citta']) ){ $error[] = 'citta'; }else{ $utente['citta']=$_POST['citta']; }
    if( empty($_POST['cap']) ){ $error[] = 'cap'; }else{ $utente['cap']=$_POST['cap']; }
    if( empty($_POST['provincia']) ){ $error[] = 'provincia'; }else{ $utente['provincia']=$_POST['provincia']; }
    if( array_key_exists('ragione_sociale', $_POST) ){ $utente['ragione_sociale']=$_POST['ragione_sociale']; }
    if( array_key_exists('fax', $_POST) ){ $utente['fax']=$_POST['fax']; }
    if( empty($_POST['domanda']) ){ $error[] = 'domanda'; }else{ $utente['domanda']=$_POST['domanda']; }
    if( empty($_POST['risposta']) ){ $error[] = 'risposta'; }else{ $utente['risposta']=$_POST['risposta']; }
    if(!array_key_exists('contratto', $_POST) || $_POST['contratto']!='1' ){ $error[] = 'contratto'; }
    
    if( !count($error) ){
        if ( $managerSql->aggiungi_utente($utente) ){
            //echo 'utente registrato correttamente';
            header('Location: shop.php');
            //invio mail di conferma della registrazione;
            exit();
        }else{
            header('Location: error.php?code=2');
            exit();
        }
    }
}
?>


<p>Registrazione Utente </p>
<p>I campi contrassegnati da (*) sono obbigatori</p>

<?php
    if( count($error) ){
        $campi = implode(', ', $error);
        echo "<p style='text-decoration:blink; border:#900 3px solid; background:#333; padding:5px 5px 5px 5px; font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#F00; width:100%;'>Compilare correttamente i campi: $campi</p>";
    }
?>
<div id="addutentetabledivcage">
<form id="form1" method="post" action="">
  <table class="tableaddutente" cellspacing="0" cellpadding="0">
    <tr>
      <td colspan="2" align="left"><h2>Dati Utente</h2></td>
    </tr>
    <tr>
      <td>email *</td>
      <td><input name="email" type="text" id="email" tabindex="4" size="40" maxlength="150" value="<?php echo htmlspecialchars($utente['email']); ?>" /></td>
    </tr>
    <tr>
      <td> username *</td>
      <td><input name="username" type="text" id="username" tabindex="7" size="40" maxlength="45" value="<?php echo htmlspecialchars($utente['username']); ?>"/></td>
    </tr>
    <tr>
      <td> password *</td>
      <td><input name="password" type="password" id="password" tabindex="10" size="40" maxlength="45" value="" /></td>
    </tr>
    <tr>
      <td><font color="#FF9900">ripeti password *</font></td>
      <td><input name="password2" type="password" id="password2" tabindex="10" size="40" maxlength="45" value="" /></td>
    </tr>
    <tr>
      <td colspan="2"><h2>Dati Fatturazione</h2></td>
    </tr>
    <tr>
      <td> nome *</td>
      <td><input name="nome" type="text" id="nome" tabindex="13" size="40" maxlength="45" value="<?php echo htmlspecialchars($utente['nome']); ?>" /></td>
    </tr>
    <tr>
      <td> cognome *</td>
      <td><input name="cognome" type="text" id="cognome" tabindex="16" size="40" maxlength="45" value="<?php echo htmlspecialchars($utente['cognome']); ?>" /></td>
    </tr>
    <tr>
      <td> codice_fiscale *</td>
      <td><input name="codice_fiscale" type="text" id="codice_fiscale" tabindex="19" size="40" maxlength="16" value="<?php echo htmlspecialchars($utente['codice_fiscale']); ?>" /></td>
    </tr>
    <tr>
      <td> piva </td>
      <td><input name="piva" type="text" id="piva" tabindex="22" size="40" maxlength="11" value="<?php echo htmlspecialchars($utente['piva']); ?>" /></td>
    </tr>
    <tr>
      <td> indirizzo *</td>
      <td><input name="indirizzo" type="text" id="indirizzo" tabindex="25" size="40" maxlength="150" value="<?php echo htmlspecialchars($utente['indirizzo']); ?>" /></td>
    </tr>
    <tr>
      <td> città *</td>
      <td><input name="citta" type="text" id="citta" tabindex="28" size="40" maxlength="45" value="<?php echo htmlspecialchars($utente['citta']); ?>" /></td>
    </tr>
    <tr>
      <td> cap *</td>
      <td><input name="cap" type="text" id="cap" tabindex="31" size="10" maxlength="5" value="<?php echo htmlspecialchars($utente['cap']); ?>" /></td>
    </tr>
    <tr>
      <td> provincia *</td>
      <td><input name="provincia" type="text" id="provincia" tabindex="34" size="40" maxlength="45" value="<?php echo htmlspecialchars($utente['provincia']); ?>" /></td>
    </tr>
    <tr>
      <td> ragione_sociale </td>
      <td><input name="ragione_sociale" type="text" id="ragione_sociale" tabindex="37" size="40" maxlength="150" value="<?php echo htmlspecialchars($utente['ragione_sociale']); ?>" /></td>
    </tr>
    <tr>
      <td> fax </td>
      <td><input name="fax" type="text" id="fax" tabindex="40" size="40" maxlength="45" value="<?php echo htmlspecialchars($utente['fax']); ?>" /></td>
    </tr>
    <tr>
      <td> domanda di sicurezza *</td>
      <td><input name="domanda" type="text" id="domanda" tabindex="40" size="40" maxlength="250" value="<?php echo htmlspecialchars($utente['domanda']); ?>" /></td>
    </tr>
    <tr>
      <td> risposta *</td>
      <td><input name="risposta" type="text" id="risposta" tabindex="40" size="40" maxlength="250" value="<?php echo htmlspecialchars($utente['risposta']); ?>" /></td>
    </tr>
  </table>
  <div style="margin-top:30px;"><div style="display:block; float:left; margin:-7px 10px 0px 30px;"><input style="display:inline-block;" name="contratto" type="checkbox" id="contratto" value="1" /></div><div>Accetta i termini del contratto *</div></
  <div style="margin-top:-10px;">
    <input type="submit" name="registra" id="registra" value="Registrati" />
  </div>
</form>
</div>