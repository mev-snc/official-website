<?php
include_once '../common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

$prodotto = array();
//$prodotto['codice'] = '';
$prodotto['nome'] = '';
$prodotto['categoria'] = '';
//$prodotto['prezzo_acquisto'] = '';
//$prodotto['prezzo_rivenditore'] = '';
//$prodotto['prezzo_pubblico'] = '';
//$prodotto['prezzo_negozio'] = '';
$prodotto['prezzo_netto'] = '';
$prodotto['aliquota_iva'] = '';
$prodotto['iva_inclusa'] = '';
$prodotto['descrizione_breve'] = '';
$prodotto['descrizione'] = '';
$prodotto['peso'] = '';
$prodotto['tipo_quantita'] = '';
$prodotto['quantita_select_inizio'] = '0';
$prodotto['quantita_select_fine'] = '0';
$prodotto['quantita_select_incremento'] = '0';
$prodotto['tempo_disponibilita'] = '';
$prodotto['visualizzazione_qta'] = '1';
$prodotto['promo'] = '0';
$prodotto['sconto_promo'] = '0';
$prodotto['note'] = '';
//$prodotto['id_marchio'] = 'NULL';

$error = array();

if(array_key_exists('aggiungi', $_POST) ){
    
    //if( empty($_POST['codice']) ){ $error[]='codice'; }else{ $prodotto['codice'] = $_POST['codice']; }
    if( empty($_POST['nome']) ){ $error[]='nome'; }else{ $prodotto['nome'] = $_POST['nome']; }
    if( empty($_POST['categoria']) ){ $error[]='categoria'; }else{ $prodotto['categoria'] = $_POST['categoria']; }
    //if( empty($_POST['prezzo_acquisto']) ){ $error[]='prezzo_acquisto'; }else{ $prodotto['prezzo_acquisto'] = $_POST['prezzo_acquisto']; }
    //if( empty($_POST['prezzo_rivenditore']) ){ $error[]='prezzo_rivenditore'; }else{ $prodotto['prezzo_rivenditore'] = $_POST['prezzo_rivenditore']; }
    //if( empty($_POST['prezzo_pubblico']) ){ $error[]='prezzo_pubblico'; }else{ $prodotto['prezzo_pubblico'] = $_POST['prezzo_pubblico']; }
    //if( empty($_POST['prezzo_negozio']) ){ $error[]='prezzo_negozio'; }else{ $prodotto['prezzo_negozio'] = $_POST['prezzo_negozio']; }
    if( empty($_POST['prezzo_netto']) ){ $error[]='prezzo_netto'; }else{ $prodotto['prezzo_netto'] = str_replace(',', '.', $_POST['prezzo_netto']); }
    if( empty($_POST['aliquota_iva']) ){ $error[]='aliquota_iva'; }else{ $prodotto['aliquota_iva'] = $_POST['aliquota_iva']; }
    if( empty($_POST['iva_inclusa']) ){ $error[]='iva_inclusa'; }else{ $prodotto['iva_inclusa'] = $_POST['iva_inclusa']; }
    if( array_key_exists('descrizione_breve', $_POST) ){ $prodotto['descrizione_breve'] = $_POST['descrizione_breve']; }
    if( array_key_exists('descrizione', $_POST) ){ $prodotto['descrizione'] = $_POST['descrizione']; }
    if( empty($_POST['peso']) ){ $error[]='peso'; }else{ $prodotto['peso'] = $_POST['peso']; }
    if( empty($_POST['tipo_quantita']) ){ $error[]='tipo_quantita'; }else{ $prodotto['tipo_quantita'] = $_POST['tipo_quantita']; }
    if( array_key_exists('select_inizio', $_POST) ){ $prodotto['quantita_select_inizio'] = empty($_POST['select_inizio'])? '0' : $_POST['select_inizio']; }
    if( array_key_exists('select_fine', $_POST) ){ $prodotto['quantita_select_fine'] = empty($_POST['select_fine'])? '0' : $_POST['select_fine']; }
    if( array_key_exists('select_incremento', $_POST) ){ $prodotto['quantita_select_incremento'] = empty($_POST['select_incremento'])? '0' : $_POST['select_incremento']; }
    if( ($prodotto['tipo_quantita']=='S') && ($prodotto['quantita_select_incremento']==0) ){ $error[]='select_incremento'; }
    if( array_key_exists('tempo_disponibilita', $_POST) ){ $prodotto['tempo_disponibilita'] = $_POST['tempo_disponibilita']; }
    if( empty($_POST['visualizza_qta']) ){ $error[]='visualizza_qta'; }else{ $prodotto['visualizzazione_qta'] = $_POST['visualizza_qta']; }
    if( array_key_exists('promo', $_POST) ){ $prodotto['promo'] = empty($_POST['promo']) ? '0' : '1'; }
    if( array_key_exists('sconto_promo', $_POST) ){ $prodotto['sconto_promo'] = empty($_POST['sconto_promo']) ? '0' : $_POST['sconto_promo']; }
    if( array_key_exists('note', $_POST) ){ $prodotto['note'] = $_POST['note']; }
    //if( array_key_exists('id_marchio', $_POST) ){ $prodotto['id_marchio'] = $_POST['id_marchio']; }
    
    if( !count($error) ){
        include '../common/carica_img.php';
        //aggiungi prodotto
        $managerSql->start_transaction();
        $id_prodotto = $managerSql->aggiungi_prodotto($prodotto);
        //se il database è stato aggiornato e il file non è stato inviato oppure è arrivato vuoto oppure è arrivato ed è stato possibile salvarlo
        if( $id_prodotto && (( !array_key_exists('immagine', $_FILES) || ($_FILES['immagine']['size']==0)) || load_image('../images/img_prodotti/', $_FILES['immagine'], $id_prodotto) ) ){
            //prodotto registrato correttamente
            header('Location: gestione_prodotti.php');
        }else{
            $managerSql->transaction_rollback();
            header('Location: error.php?code=13');
            exit();
        }

    
        $attributi = $_POST['attributo'];
        for($i=0; $i<count($attributi); $i++){
            $attributo = $attributi[$i];
            $proprieta = $attributo['valore'];
            $prezzi = $attributo['prezzo'];

            $new_attributo = array();
            $new_attributo['id_prodotto'] = $id_prodotto;
            $new_attributo['nome'] = $attributo['nome'];

            if( !empty($new_attributo['nome']) ){
                $id_attributo = $managerSql->aggiungi_attributo($new_attributo);
                if(!$id_attributo){ 
                    $managerSql->transaction_rollback();
                    header('Location: error.php?code=13');
                    exit();
                }
                for($j=0; $j<count($proprieta); $j++){
                    $new_proprieta = array();
                    $new_proprieta['id_attributo'] = $id_attributo; //id_attributo aggiunto al passo precedente
                    $new_proprieta['valore'] = $proprieta[$j];
                    $new_proprieta['variazione_prezzo'] = empty($prezzi[$j]) ? 0 : $prezzi[$j] ;
                    if( !empty($new_proprieta['valore']) ){
                        if ( !$managerSql->aggiungi_proprieta($new_proprieta) ){
                            $managerSql->transaction_rollback();
                            header('Location: error.php?code=13');
                            exit();
                        }
                    }
                }
            }

        }
        //echo 'prodotto registrato correttamente';
        header('Location: gestione_prodotti.php');
        $managerSql->transaction_commit();
        exit();
    }
    
    
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ADMIN | CREA UN PRODOTTO</title>
<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
<script type="text/javascript" src="../common/jquery-1.6.2.min.js"></script>
<script type="text/javascript">
    
    function calcola_prossimo_num_proprieta(){
        spans = new Array();
        if($('#div_attributi').find('span').length==0){
            return '000';
        }
        for(i=0; i< $('#div_attributi').find('span').length; i++){
            spans[i]= $('#div_attributi').find('span')[i].id;
        }
        spans.sort();
        p_num = spans[spans.length-1].split('_')[2];
        p_num++;
        str = ''+p_num;
        while (str.length < 3) {
            str = '0' + str;
        }
        p_num = str;
        return p_num;
    }
    
    function nuovo_attributo(){
        //numero di attributi
        last_div_id = $('#div_attributi').find('div')[$('#div_attributi').find('div').length-1].id;
        num = last_div_id.split('_')[2];
        num++;
        //numero di proprietà
        p_num = calcola_prossimo_num_proprieta();
        new_div = "<div id=\"div_attributo_"+num+"\">";
        new_div += "<p>Nome Attributo";
        new_div += "<input type=\"text\" name=\"attributo["+num+"][nome]\" id=\"attributo_"+num+"_nome\" />";
        new_div += "<input type=\"button\" name=\"elimina_"+num+"\" id=\"elimina_"+num+"\" value=\"Elimina Attributo\" onclick=\"javascript: elimina_attributo('"+num+"');\"/>";
        new_div += "<input type=\"button\" name=\"n_p_"+num+"\" id=\"n_p_"+num+"\" value=\"Aggiungi Proprietà\" onclick=\"javascript: nuova_proprieta("+num+");\"/>";
        new_div += "<span id=\"span_p_"+p_num+"\"><br />";
        new_div += "Proprietà";
        new_div += "<input type=\"text\" name=\"attributo["+num+"][valore][]\" id=\"attributo_"+num+"_valore_"+p_num+"\" />";
        new_div += "- Variazione Prezzo";
        new_div += "<input type=\"text\" name=\"attributo["+num+"][prezzo][]\" id=\"attributo_"+num+"_prezzo_"+p_num+"\" />";
        new_div +="<input type=\"button\" name=\"e_p_"+p_num+"\" id=\"e_p_"+p_num+"\" value=\"Elimina Proprietà\" onclick=\"javascript: elimina_proprieta('"+p_num+"');\" /></span>";
        new_div += "</p>";
        new_div += "</div>";
        
        $('#div_attributi').append(new_div);
    }
    
    function elimina_attributo( num ){
        $('#div_attributo_'+num).remove();
    }
    
    function nuova_proprieta( a_num ){
        p_num = calcola_prossimo_num_proprieta();
        new_p = "<span id=\"span_p_"+p_num+"\"><br/>Proprietà";
        new_p += "<input type=\"text\" name=\"attributo["+a_num+"][valore][]\" id=\"attributo_"+a_num+"_valore_"+p_num+"\" />";
        new_p += "- Variazione Prezzo";
        new_p += "<input type=\"text\" name=\"attributo["+a_num+"][prezzo][]\" id=\"attributo_"+a_num+"_prezzo_"+p_num+"\" />";
        new_p +="<input type=\"button\" name=\"e_p_"+p_num+"\" id=\"e_p_"+p_num+"\" value=\"Elimina Proprietà\" onclick=\"javascript: elimina_proprieta('"+p_num+"');\" /></span>";
        $('#div_attributo_'+a_num+' p').append(new_p);
    }
    
    function elimina_proprieta( p_num ){
        $('#span_p_'+p_num).remove();
    }
    
</script>
</head>

<body>    
	<div class="page">
		<h1>Nuovo Prodotto</h1>

			<?php
				 if( count($error) ){
					  $campi = implode(', ', $error);
					  echo "<p>Compilare correttamente i campi: $campi</p>";
				 }
			?>
			
			<form action="" method="post" enctype="multipart/form-data" id="form1">
			  <table cellspacing="0" cellpadding="0">
				 <!--<tr>
					<td align="center">codice </td>
					<td><input name="codice" size="40" tabindex="4" id="codice" type="text" value="<?php echo htmlspecialchars($prodotto['codice']); ?>" /></td>
				 </tr> -->
				<tr>
					<td>nome </td>
					<td><input name="nome" size="40" tabindex="5" id="nome" type="text" value="<?php echo htmlspecialchars($prodotto['nome']); ?>" /></td>
				 </tr>
				 <tr>
					<td> id_categoria </td>
					<td>
						 <select name="categoria" id="categoria">
							<option value="" >Seleziona una categoria</option>
							<?php
								 include '../common/recursive_categorie.php';
								 print_option_categorie($managerSql, 0, 1);
							?>
						 </select>
					</td>
				 </tr>
				 <!-- <tr>
					<td align="center"> prezzo_acquisto </td>
					<td><input name="prezzo_acquisto" type="text" id="prezzo_acquisto" tabindex="10" size="20" maxlength="15" value="<?php echo htmlspecialchars($prodotto['prezzo_acquisto']); ?>" /></td>
				 </tr>
					<tr>
					<td align="center"> prezzo_rivenditore </td>
					<td><input name="prezzo_rivenditore" type="text" id="prezzo_rivenditore" tabindex="10" size="20" maxlength="15" value="<?php echo htmlspecialchars($prodotto['prezzo_rivenditore']); ?>" /></td>
				 </tr>
					<tr>
					<td align="center"> prezzo_pubblico </td>
					<td><input name="prezzo_pubblico" type="text" id="prezzo_pubblico" tabindex="10" size="20" maxlength="15" value="<?php echo htmlspecialchars($prodotto['prezzo_pubblico']); ?>" /></td>
				 </tr>
					<tr>
					<td align="center"> prezzo_negozio </td>
					<td><input name="prezzo_negozio" type="text" id="prezzo_negozio" tabindex="10" size="20" maxlength="15" value="<?php echo htmlspecialchars($prodotto['prezzo_negozio']); ?>" /></td>
				 </tr> -->
				 <tr>
					<td> prezzo_netto </td>
					<td><input name="prezzo_netto" type="text" id="prezzo_netto" tabindex="10" size="20" maxlength="15" value="<?php echo htmlspecialchars($prodotto['prezzo_netto']); ?>" /></td>
				 </tr>
				 <tr>
					<td> id_aliquota_iva </td>
					<td>
						 <select name="aliquota_iva" id="aliquota_iva">
							<!-- <option value="">Seleziona un'aliquota</option> -->
							<?php
							$aliquote = $managerSql->lista_aliquote_iva();
							for($i=0; $i<count($aliquote); $i++){
								 $aliquota = $aliquote[$i];
								 $aliquota['nome'] = htmlspecialchars($aliquota['nome']);
								 $aliquota['aliquota'] *= 100;
								 echo "<option value=\"{$aliquota['id_aliquota_iva']}\">{$aliquota['nome']} ({$aliquota['aliquota']}%)</option>";
							}
							?>
						 </select>
					</td>
				 </tr>
				 <tr>
					<td>Visualizza iva inclusa</td>
					<td><input name="iva_inclusa" type="radio" id="iva_inclusa" value="SI" checked="checked" />
					Si      
					  <input type="radio" name="iva_inclusa" id="iva_inclusa" value="NO" /> 
					  No
					</td>
				 </tr>
					<!--
				 <tr>
					<td align="center"> Marchio </td>
					<td>
						 <select name="id_marchio" id="id_marchio">
							<option value="">Seleziona un marchio</option>
							<?php
							$marchi = $managerSql->lista_marchi();
							for($i=0; $i<count($marchi); $i++){
								 $marchio = $marchi[$i];
								 $marchio['nome'] = htmlspecialchars($marchio['nome']);
								 echo "<option value=\"{$marchio['id_marchio']}\">{$marchio['nome']}</option>";
							}
							?>
						 </select>
					</td>
				 </tr> -->
				 <tr>
					<td> descrizione_breve </td>
					<td><textarea name="descrizione_breve" rows="7" cols="40" dir="ltr" id="descrizione_breve" tabindex="16"><?php echo htmlspecialchars($prodotto['descrizione_breve']); ?></textarea></td>
				 </tr>
				 <tr>
					<td> descrizione </td>
					<td><textarea name="descrizione" rows="7" cols="40" dir="ltr" id="descrizione" tabindex="19"><?php echo htmlspecialchars($prodotto['descrizione']); ?></textarea></td>
				 </tr>
				 <tr>
					<td> peso (KG) </td>
					<td><input name="peso" size="11" tabindex="28" id="peso" type="text" value="<?php echo htmlspecialchars($prodotto['peso']); ?>" /></td>
				 </tr>
				 <tr>
					<td> tipo_quantita </td>
					<td><select name="tipo_quantita" tabindex="22" id="tipo_quantita" >
							  <option value="T">Text</option>
							  <option value="S">Select</option>
							  <option value="N">Nessuna</option>
						 </select>
					</td>
				 </tr>
				 <tr>
					<td> quantita_select_inizio </td>
					<td><input name="select_inizio" size="11" tabindex="25" id="select_inizio" type="text" value="<?php echo htmlspecialchars($prodotto['quantita_select_inizio']); ?>" /></td>
				 </tr>
				 <tr>
					<td> quantita_select_fine </td>
					<td><input name="select_fine" size="11" tabindex="28" id="select_fine" type="text" value="<?php echo htmlspecialchars($prodotto['quantita_select_fine']); ?>" /></td>
				 </tr>
				 <tr>
					<td> quantita_select_incremento </td>
					<td><input name="select_incremento" size="11" tabindex="31" id="select_incremento" type="text" value="<?php echo htmlspecialchars($prodotto['quantita_select_incremento']); ?>" /></td>
				 </tr>
				 <tr>
					<td> tempo_disponibilita </td>
					<td><input name="tempo_disponibilita" size="40" tabindex="34" id="tempo_disponibilita" type="text" value="<?php echo htmlspecialchars($prodotto['tempo_disponibilita']); ?>" /></td>
				 </tr>
					<!--
				 <tr>
					<td align="center"> qta_disponibile </td>
					<td><input name="qta_disponibile" type="text" id="qta_disponibile" tabindex="37" size="15" maxlength="15" value="<?php echo $prodotto['qta_disponibile']; ?>" /></td>
				 </tr>
					-->
				 <tr>
					<td>Visualizzazione qta</td>
					<td>
					<div style="height:60px">
					  <div>
					  		<input style="position:absolute; margin:0 0 0 -500px;"  type="radio" id="visualizza_qta_1" value="1" checked="checked"  />
					  		<p style="position:absolute;">Quantità esatta</p>
						</div>
					  
					  <div>
					  		<input  style="position:absolute; margin:22px 0 0 -500px;" type="radio" name="visualizza_qta" id="visualizza_qta_2" value="2"  />
					  		<p style="position:absolute; margin:20px 0 0 0;">Disponibile - Non Disponibile</p>
					 	</div>
					  
					  <div>
					  		<input  style="position:absolute; margin:44px 0 0 -500px;" type="radio" name="visualizza_qta" id="visualizza_qta_3" value="3" />
					   	<p style="position:absolute; margin:40px 0 0 0;">Non visualizzare</p>
						</div>
					</div>
					</td>
				 </tr>
					<tr>
						 <td >Promo</td>
						 <td>
						 	<div style="height:38px">
							  	<div>
							  		<input style="position:absolute; margin:0 0 0 -500px;" name="promo" type="radio" id="promo_no" value="0" checked="checked" />
									<p style="position:absolute; margin:0 0 0 0;">NO</p>
							  	</div>
							  	<div>
							  		<input style="position:absolute; margin:22px 0 0 -500px;" type="radio" id="promo_si" value="1" />
									<p style="position:absolute; margin:20px 0 0 0;">SI</p>
						 		</div>
							</div>

						 </td>
					</tr>
					<tr>
						 <td >Sconto Promo   (Es. 5% = 0.05)</td>
						 <td>
							  <input type="text" name="sconto_promo" id="sconto_promo" value="<?php echo htmlspecialchars($prodotto['sconto_promo']); ?>" />
						 </td>
					</tr>
				 <tr>
					<td >Immagine</td>
					<td><input type="file" name="immagine" id="immagine" /></td>
				 </tr>
			  </table>
			  <p>&nbsp;</p>
			  <p>Attributi 
				 <input type="button" name="st" id="st" value="Aggiungi Attributo" onclick="javascript: nuovo_attributo();"/>
			  </p>
			  <div id="div_attributi">
				 <div id="div_attributo_0">
					<p>Nome Attributo
					  <input type="text" name="attributo[0][nome]" id="attributo_0_nome" />
					  <input type="button" name="n_p_0" id="n_p_0" value="Aggiungi Proprietà" onclick="javascript: nuova_proprieta(0);"/>
					  <span id="span_p_0"><br />
					  Proprietà
					  <input type="text" name="attributo[0][valore][]" id="attributo_0_valore_0" />
					  - Variazione Prezzo
					  <input type="text" name="attributo[0][prezzo][]" id="attributo_0_prezzo_0" />
					  <input type="button" name="e_p_0" id="e_p_0" value="Elimina Proprietà" onclick="javascript: elimina_proprieta(0);" /></span>
					</p>
				 </div>
			  </div>
			  <p>&nbsp;</p>
			  <p>NOTE DELL'AMMINISTRATORE:<br/>
					<textarea name="note" rows="7" cols="40" dir="ltr" id="note" tabindex="16"><?php echo htmlspecialchars($prodotto['note']); ?></textarea>
			  </p>
			  <p>
				 <input type="submit" name="aggiungi" id="aggiungi" value="Aggiungi" />
			  </p>
			</form>
			<br />
			<h2>Crea un nuovo prodotto</h2>
			<a class="voiceadmin" href="add_prodotto.php">Nuovo Prodotto</a>
		<a style="font-size:10px; color:#999;" href="admin.php">&lt;&lt; Torna al pannello principale</a>

	</div>
</body>
</html>
