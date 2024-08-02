<?php
include_once '../common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

include_once 'verifica_admin.php';

if( empty ($_GET['id']) ){
    header('Location: error.php?code=1');
    exit();
}
$utente = $managerSql->get_utente($_GET['id']);
if( !$utente ){
    header('Location: error.php?code=26');
    exit();
}

$error=array();
$salvataggio_completato=0;

if(array_key_exists('modifica', $_POST)){
    //Modifica utente nel sistema
    if( empty($_POST['email']) ){ $error[] = 'email'; }else{ $utente['email']=$_POST['email']; }
    
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
    //if( empty($_POST['gruppo']) ){ $error[] = 'gruppo'; }else{ $utente['gruppo']=$_POST['gruppo']; }
    
    if( !count($error) ){
        if ( !$managerSql->modifica_utente($utente) ){
            header('Location: error.php?code=4');
            exit();
        }  else {
            $salvataggio_completato=1;
        }
    }
}

if(array_key_exists('modifica_pwd', $_POST)){
    //Modifica password dell'utente
    if( empty($_POST['nuova_pwd']) || empty($_POST['nuova_pwd2']) || strcmp($_POST['nuova_pwd'], $_POST['nuova_pwd2'])){
        $error[] = 'Modifica Password';
    }else{
        if( $managerSql->modifica_pwd_utente($utente, $_POST['nuova_pwd']) ){
            $salvataggio_completato = 1;
        }else{
            header('Location: error.php?code=5');
            exit();
        }
    }
}



?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ADMIN | EDITA UTENTE</title>
<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
</head>

<body>    
	<div class="page">
        <h1>Profilo Utente</h1>

                    <?php
                    if($salvataggio_completato){
                        echo '<p>I dati sono stati salvati con successo!</p>';
                    }
                    ?>

                    <h2>I campi contrassegnati da (*) sono obbigatori</h2>

                    <?php
                        if( count($error) ){
                            $campi = implode(', ', $error);
                            echo "<p>Compilare correttamente i campi: $campi</p>";
                        }
                    ?>

                    <form id="form1" method="post" action="<?php echo "{$_SERVER['PHP_SELF']}?id={$utente['id_utente']}" ?>">
                      <table cellspacing="0" cellpadding="0">
                        <tr>
                          <td colspan="2"><h2>Dati Utente<h2></td>
                        </tr>
                        <tr>
                          <td>email *</td>
                          <td><input name="email" type="text" id="email" tabindex="4" size="40" maxlength="150" value="<?php echo $utente['email']; ?>" /></td>
                        </tr>
                        <tr>
                          <td> username </td>
                          <td><?php echo $utente['username']; ?> </td>
                        </tr>
                        <!-- <tr>
                          <td align="center"> gruppo </td>
                          <?php
                          $selected = array();
                          $selected['CLIENTE'] = $selected['RIVENDITORE'] = $selected['NEGOZIO'] = '';
                          $selected[$utente['gruppo']] = 'selected="selected"';
                          ?>
                          <td><select name="gruppo" id="gruppo">
                                  <option value="CLIENTE" <?php echo $selected['CLIENTE']; ?> >Cliente</option>
                                  <option value="RIVENDITORE" <?php echo $selected['RIVENDITORE']; ?> >Rivenditore</option>
                                  <option value="NEGOZIO" <?php echo $selected['NEGOZIO']; ?> >Negozio</option>
                              </select>
                          </td>
                        </tr> -->
                        <tr>
                          <td colspan="2" ><br /><br /><h2>Dati Fatturazione</h2></td>
                        </tr>
                        <tr>
                          <td > nome *</td>
                          <td><input name="nome" type="text" id="nome" tabindex="13" size="40" maxlength="45" value="<?php echo $utente['nome']; ?>" /></td>
                        </tr>
                        <tr>
                          <td> cognome *</td>
                          <td><input name="cognome" type="text" id="cognome" tabindex="16" size="40" maxlength="45" value="<?php echo $utente['cognome']; ?>" /></td>
                        </tr>
                        <tr>
                          <td> codice_fiscale *</td>
                          <td><input name="codice_fiscale" type="text" id="codice_fiscale" tabindex="19" size="40" maxlength="16" value="<?php echo $utente['codice_fiscale']; ?>" /></td>
                        </tr>
                        <tr>
                          <td> piva </td>
                          <td><input name="piva" type="text" id="piva" tabindex="22" size="40" maxlength="11" value="<?php echo $utente['piva']; ?>" /></td>
                        </tr>
                        <tr>
                          <td> indirizzo *</td>
                          <td><input name="indirizzo" type="text" id="indirizzo" tabindex="25" size="40" maxlength="150" value="<?php echo $utente['indirizzo']; ?>" /></td>
                        </tr>
                        <tr>
                          <td> città *</td>
                          <td><input name="citta" type="text" id="citta" tabindex="28" size="40" maxlength="45" value="<?php echo $utente['citta']; ?>" /></td>
                        </tr>
                        <tr>
                          <td> cap *</td>
                          <td><input name="cap" type="text" id="cap" tabindex="31" size="10" maxlength="5" value="<?php echo $utente['cap']; ?>" /></td>
                        </tr>
                        <tr>
                          <td> provincia *</td>
                          <td><input name="provincia" type="text" id="provincia" tabindex="34" size="40" maxlength="45" value="<?php echo $utente['provincia']; ?>" /></td>
                        </tr>
                        <tr>
                          <td> ragione_sociale </td>
                          <td><input name="ragione_sociale" type="text" id="ragione_sociale" tabindex="37" size="40" maxlength="150" value="<?php echo $utente['ragione_sociale']; ?>" /></td>
                        </tr>
                        <tr>
                          <td> fax </td>
                          <td><input name="fax" type="text" id="fax" tabindex="40" size="40" maxlength="45" value="<?php echo $utente['fax']; ?>" /></td>
                        </tr>
                        <tr>
                          <td colspan="2"><br /><br /><h2>Dati Recupero Password</h2></td>
                        </tr>
                        <tr>
                          <td> domanda </td>
                          <td><input name="domanda" type="text" id="domanda" tabindex="40" size="40" maxlength="250" value="<?php echo htmlspecialchars($utente['domanda']); ?>" /></td>
                        </tr>
                        <tr>
                          <td> risposta </td>
                          <td><input name="risposta" type="text" id="risposta" tabindex="40" size="40" maxlength="250" value="<?php echo htmlspecialchars($utente['risposta']); ?>" /></td>
                        </tr>
                      </table>

                        <input type="submit" name="modifica" id="modifica" value="Modifica" />
                        <input type="reset" name="ripristina" id="ripristina" value="Ripristina" />
                        <br />
                      </p>
                    </form>
                    <form id="form2" method="post" action="<?php echo "{$_SERVER['PHP_SELF']}?id={$utente['id_utente']}" ?>">
                      <table cellpadding="0" cellspacing="0">
                        <tr>
                          <td colspan="2"><br /><br /><h2>Cambia password</h2></td>
                        </tr>
                        <tr>
                          <td> Nuova Password</td>
                          <td><input name="nuova_pwd" type="password" id="nuova_pwd" tabindex="42" size="40" maxlength="45" value="" /></td>
                        </tr>
                        <tr>
                          <td>Ripeti Password</td>
                          <td><input name="nuova_pwd2" type="password" id="nuova_pwd2" tabindex="43" size="40" maxlength="45" value="" /></td>
                        </tr>
                      </table>
                      <br />
                      <input type="submit" name="modifica_pwd" id="modifica_pwd" value="Modifica" />
                      <input type="reset" name="ripristina2" id="ripristina2" value="Ripristina" />
                    <p>&nbsp;</p>
                    </form>
                    
                    <p>Indirizzi Spedizione.....</p>
							<br /><a style="font-size:10px; color:#999;" href="admin.php">&lt;&lt; Torna al pannello principale</a>
					</div>

	</body>

</html>