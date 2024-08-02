<?php
include_once 'common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

include_once 'common/verifica_login.php';

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
    if( empty($_POST['password']) || empty($_POST['nuova_pwd']) || empty($_POST['nuova_pwd2']) || strcmp($_POST['nuova_pwd'], $_POST['nuova_pwd2'])){
        $error[] = 'Modifica Password';
    }else{
        if(!strcmp(md5($_POST['password']), $utente['password']) && $managerSql->modifica_pwd_utente($utente, $_POST['nuova_pwd']) ){
            $salvataggio_completato = 1;
        }else{
            header('Location: error.php?code=5');
            exit();
        }
    }
}

if(array_key_exists('nuova_sped', $_POST)){
    //aggiungi indirizzo di spedizione
    $indirizzo_spedizione = array();
    $indirizzo_spedizione['id_utente'] = $_SESSION['id_utente'];
    $indirizzo_spedizione['nome'] = '';
    $indirizzo_spedizione['cognome'] = '';
    $indirizzo_spedizione['indirizzo'] = '';
    $indirizzo_spedizione['citta'] = '';
    $indirizzo_spedizione['cap'] = '';
    $indirizzo_spedizione['provincia'] = '';
    $indirizzo_spedizione['ragione_sociale'] = '';
    
    if( empty($_POST['nome_sped']) ){ $error[] = 'nome_sped'; }else{ $indirizzo_spedizione['nome']=$_POST['nome_sped']; }
    if( empty($_POST['cognome_sped']) ){ $error[] = 'cognome_sped'; }else{ $indirizzo_spedizione['cognome']=$_POST['cognome_sped']; }
    if( empty($_POST['indirizzo_sped']) ){ $error[] = 'indirizzo_sped'; }else{ $indirizzo_spedizione['indirizzo']=$_POST['indirizzo_sped']; }
    if( empty($_POST['citta_sped']) ){ $error[] = 'citta_sped'; }else{ $indirizzo_spedizione['citta']=$_POST['citta_sped']; }
    if( empty($_POST['cap_sped']) ){ $error[] = 'cap_sped'; }else{ $indirizzo_spedizione['cap']=$_POST['cap_sped']; }
    if( empty($_POST['provincia_sped']) ){ $error[] = 'provincia_sped'; }else{ $indirizzo_spedizione['provincia']=$_POST['provincia_sped']; }
    if( array_key_exists('ragione_sociale_sped', $_POST) ){ $indirizzo_spedizione['ragione_sociale']=$_POST['ragione_sociale_sped']; }
    
    if( !count($error) ){
        if ( !$managerSql->aggiungi_indirizzo($indirizzo_spedizione) ){
            header('Location: error.php?code=6');
            exit();
        }  else {
            $salvataggio_completato=1;
        }
    }
    
    
}


if( array_key_exists('imposta_predefinito', $_POST) ){
    if( empty ($_POST['indirizzo_predefinito']) ){
        $error[] = 'Indirizzo predefinito';
    }else{
        switch ( $_POST['indirizzo_predefinito'] ) {
            case 'F':
                //il predefinito è l'indirizzo di fatturazione
                if($managerSql->unset_indirizzi_predefiniti($utente) ){
                    $salvataggio_completato=1;
                }else{
                    header('Location: error.php?code=7');
                    exit();
                }
                break;

            default:
                //il predefinito è uno degli indirizzi alternativi
                $managerSql->start_transaction();
                if ($managerSql->unset_indirizzi_predefiniti($utente) && $managerSql->set_indirizzo_predefinito($_POST['indirizzo_predefinito']) ){
                    $managerSql->transaction_commit();
                    $salvataggio_completato=1;
                }else{
                    $managerSql->transaction_rollback();
                }
                
                break;
        }
    }
}

?>


<h1>Profilo Utente </h1>

<?php
if($salvataggio_completato){
    echo '<p>I dati sono stati salvati con successo!</p>';
}
?>
<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i>I campi contrassegnati da (*) sono obbigatori</i><br /><br /><br /><br /></div>

<?php
    if( count($error) ){
        $campi = implode(', ', $error);
        echo "<p><font color='#FF0000'>Compilare correttamente i campi: $campi</font>";
    }
?>



<div id="addutentetabledivcage">

	<!--Dati utente + Dati fatturazione + Dati recupero password-->
	<div>
	<form id="form1" method="post" action="">
		<table cellspacing="0" cellpadding="0">
			<tr>
				<td colspan="2"><h2 style="margin-left:-30px;">Dati Utente</h2></td>
			 </tr>
			 <tr>
				<td> email * </td>
				<td><input name="email" type="text" id="email" tabindex="4" size="40" maxlength="150" value="<?php echo htmlspecialchars($utente['email']); ?>" /></td>
			 </tr>
			 <tr>
				<td> username </td>
				<td><?php echo $utente['username']; ?> </td>
			 </tr>
			 <tr>
				<td colspan="2"><h2 style="margin-left:-30px;">Dati Fatturazione</h2></td>
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
				<td colspan="2"><h2 style="margin-left:-30px;">Dati Recupero Password</h2></td>
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
	
		<div>
			<input type="submit" name="modifica" id="modifica" value="Modifica" />
			<input type="reset" name="ripristina" id="ripristina" value="Ripristina" />
		</div>
	</form>
	</div>

	<!--Cambia password-->
	<div>
	<form id="form2" method="post" action="">
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td colspan="2"><h2 style="margin-left:-30px; margin-top:-20px;">Cambia password</h2></td>
			</tr>
			<tr>
				<td>Vecchia Password</td>
				<td><input name="password" type="password" id="password" tabindex="41" size="40" maxlength="45" value="" /></td>
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

		<div>
			<input type="submit" name="modifica_pwd" id="modifica_pwd" value="Modifica" />
			<input type="reset" name="ripristina2" id="ripristina2" value="Ripristina" />
		</div>
	</form>
	</div>

	<!--Indirizzi di spedizione-->
	<div>
	<h2 style="margin-top:-16px;">Indirizzi di Spedizione</h2>

	<?php
	$indirizzo_predefinito = $managerSql->get_indirizzo_predefinito($utente);
	?>

	<form id="form3" method="post" action="">
		<div style="margin-top:30px;">
			<div style="display:block; float:left; margin:-7px 10px 0px 30px;">
				<input type="radio" name="indirizzo_predefinito" id="indirizzo_predefinito" value="F" <?php if(empty($indirizzo_predefinito)) echo 'checked="checked"'; ?> />
			</div>
			Indirizzo di fatturazione
		</div>

		<?php
		$indirizzi = $managerSql->lista_indirizzi_by_utente($utente);
		for($i=0; $i<count($indirizzi); $i++){
			 $indirizzo = $indirizzi[$i];
			 $checked = '';
			 if(!empty($indirizzo_predefinito) && ($indirizzo_predefinito['id_indirizzo']==$indirizzo['id_indirizzo']) ){
				  $checked = 'checked="checked"';
			 }
			 echo "	
			 			<div style='margin-top:30px;'>
			 				<div style='display:block; float:left; margin:-7px 10px 0px 30px;'>
								<input type=\"radio\" name=\"indirizzo_predefinito\" id=\"indirizzo_predefinito_{$i}\" value=\"{$indirizzo['id_indirizzo']}\" $checked /></div>
								<div style='margin-left:70px;'>
									Nome: {$indirizzo['nome']} </br>
									Cognome: {$indirizzo['cognome']}</br>
									Indirizzo: {$indirizzo['indirizzo']}</br>
									Città: {$indirizzo['citta']}</br>
									Cap: {$indirizzo['cap']}</br>
									Provincia: {$indirizzo['provincia']}</br>
									Ragione Sociale: {$indirizzo['ragione_sociale']}
									<a href=\"del_indirizzo.php?id={$indirizzo['id_indirizzo']}\">Elimina</a>
								</div>
							</div>
						</div>
					";
		}
		?>

		<input type="submit" name="imposta_predefinito" id="imposta_predefinito" value="Imposta come predefinito" />
	</form>
	</div>

	<!--indirizzo spedizione (aggiungi)-->
	<div style="margin:0 0 -20px 33px; text-align:left;">
	
	<div id="addutentetabledivcage">
	<form id="form4" method="post" action="">
		<table cellspacing="0" cellpadding="0">
			 <tr>
				<td colspan="2"><h2 style="margin-left:-30px; margin-top:-10px;">Aggiungi indirizzo spedizione</h2></td>
			 </tr>
			 <tr>
				<td> nome </td>
				<td><input name="nome_sped" size="40" tabindex="50" id="nome_sped" type="text" value="<?php if(!$salvataggio_completato && !empty($indirizzo_spedizione['nome'])){ echo htmlspecialchars($indirizzo_spedizione['nome']); } ?>" /></td>
			 </tr>
			 <tr>
				<td> cognome </td>
				<td><input name="cognome_sped" size="40" tabindex="51" id="cognome_sped" type="text" value="<?php if(!$salvataggio_completato && !empty($indirizzo_spedizione['cognome'])){ echo htmlspecialchars($indirizzo_spedizione['cognome']); } ?>" /></td>
			 </tr>
			 <tr>
				<td> indirizzo </td>
				<td><input name="indirizzo_sped" size="40" tabindex="52" id="indirizzo_sped" type="text" value="<?php if(!$salvataggio_completato && !empty($indirizzo_spedizione['indirizzo'])){ echo htmlspecialchars($indirizzo_spedizione['indirizzo']); } ?>" /></td>
			 </tr>
			 <tr>
				<td> citta </td>
				<td><input name="citta_sped" size="40" tabindex="53" id="citta_sped" type="text" value="<?php if(!$salvataggio_completato && !empty($indirizzo_spedizione['citta'])){ echo htmlspecialchars($indirizzo_spedizione['citta']); } ?>"/></td>
			 </tr>
			 <tr>
				<td> cap </td>
				<td><input name="cap_sped" size="5" tabindex="54" id="cap_sped" type="text" value="<?php if(!$salvataggio_completato && !empty($indirizzo_spedizione['cap'])){ echo htmlspecialchars($indirizzo_spedizione['cap']); } ?>" /></td>
			 </tr>
			 <tr>
				<td> provincia </td>
				<td><input name="provincia_sped" size="40" tabindex="55" id="provincia_sped" type="text" value="<?php if(!$salvataggio_completato && !empty($indirizzo_spedizione['provincia'])){ echo htmlspecialchars($indirizzo_spedizione['provincia']); } ?>" /></td>
			 </tr>
			 <tr>
				<td> ragione_sociale </td>
				<td><input name="ragione_sociale_sped" size="40"  tabindex="56" id="ragione_sociale_sped" type="text" value="<?php if(!$salvataggio_completato && !empty($indirizzo_spedizione['ragione_sociale'])){ echo htmlspecialchars($indirizzo_spedizione['ragione_sociale']); } ?>" /></td>
			 </tr>
		</table>
  
		<input type="submit" name="nuova_sped" id="nuova_sped" value="Aggiungi indirizzo" />
	</form>
	</div>
	</div>


	<!--Ultimi ordini-->
	<div>
	<br /><br /><hr style="background:#666;" />
	<h2>Ultimi Ordini</h2>
	<!-- Ultimi 3 ordini -->
<table style="width:100%; text-align:left; padding:10px 10px 10px 10px; border:1px solid #999;">
    <tr style="width:100%;">
			  <th style="background:#CCC;  padding:4px 4px 4px 4px;">ID</th>
			  <th style='background:#F4F4F4;  padding:4px 4px 4px 4px;'>DATA</th>
			  <th style="background:#CCC;  padding:4px 4px 4px 4px;">STATO</th>
			  <th style='background:#F4F4F4;  padding:4px 4px 4px 4px;'>TOTALE</th>
			  <th style="background:#CCC;  padding:4px 4px 4px 4px;">&nbsp;</th>
		 </tr>
		 
		 <?php
		 $ordini = $managerSql->lista_ordini_by_utente($utente['id_utente'], 0, 3);
		 for($i=0; $i<count($ordini); $i++){
			  $ordine = $ordini[$i];
			  $stato_ordine = 'IN ATTESA';
			  if( !empty($ordine['data_pagamento']) ){ $stato_ordine = 'PAGATO'; }
			  if( !empty($ordine['data_spedizione']) ){ $stato_ordine = 'SPEDITO'; }
			  
			  $items = $managerSql->lista_item_ordine($ordine['id_ordine']);
			  $totale=0;
			  for($j=0; $j<count($items); $j++){
					$item =$items[$j];
					$totale += $item['prezzo_totale'];
			  }
			  $totale += $ordine['costo_spedizione'];
			  $totale += $ordine['costo_pagamento'];
			  echo "<tr>
						 <td style='background:#CCC;  padding:4px 4px 4px 4px;'>{$ordine['id_ordine']}</td>
						 <td style='background:#F4F4F4;  padding:4px 4px 4px 4px;'>{$ordine['data']}</td>
						 <td style='background:#CCC;  padding:4px 4px 4px 4px;'>$stato_ordine</td>
						 <td style='background:#F4F4F4;  padding:4px 4px 4px 4px;'> &euro; $totale</td>
						 <td style='background:#CCC;  padding:4px 4px 4px 4px;'><a href=\"shop.php?l=view_ordine&id={$ordine['id_ordine']}\">Dettagli</a></td>
					</tr>";
		 }
		 
		 ?>
		
	</table>
	</div>
</div>

<br />
<div style="margin-left:30px;"><a class="inviastandard" href="shop.php?l=lista_ordini">Lista Ordini--></a></div>
<br /><br />
