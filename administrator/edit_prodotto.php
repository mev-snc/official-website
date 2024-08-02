<?php
include_once '../common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

if( empty ($_GET['id']) ){
    header('Location: error.php?code=1');
    exit();
}

$prodotto = $managerSql->get_prodotto($_GET['id']);
if( !$prodotto ){
    header('Location: error.php?code=15');
    exit();
}

$salvataggio_completato=0;
$error = array();

if(array_key_exists('salva', $_POST) ){
    
    
    //if( empty($_POST['codice']) ){ $error[]='codice'; }else{ $prodotto['codice'] = $_POST['codice']; }
    if( empty($_POST['nome']) ){ $error[]='nome'; }else{ $prodotto['nome'] = $_POST['nome']; }
    if( empty($_POST['categoria']) ){ $error[]='categoria'; }else{ $prodotto['id_categoria'] = $_POST['categoria']; }
    //if( empty($_POST['prezzo_acquisto']) ){ $error[]='prezzo_acquisto'; }else{ $prodotto['prezzo_acquisto'] = $_POST['prezzo_acquisto']; }
    //if( empty($_POST['prezzo_rivenditore']) ){ $error[]='prezzo_rivenditore'; }else{ $prodotto['prezzo_rivenditore'] = $_POST['prezzo_rivenditore']; }
    //if( empty($_POST['prezzo_pubblico']) ){ $error[]='prezzo_pubblico'; }else{ $prodotto['prezzo_pubblico'] = $_POST['prezzo_pubblico']; }
    //if( empty($_POST['prezzo_negozio']) ){ $error[]='prezzo_negozio'; }else{ $prodotto['prezzo_negozio'] = $_POST['prezzo_negozio']; }
    if( empty($_POST['prezzo_netto']) ){ $error[]='prezzo_netto'; }else{ $prodotto['prezzo_netto'] = str_replace(',', '.', $_POST['prezzo_netto']); }
    if( empty($_POST['aliquota_iva']) ){ $error[]='aliquota_iva'; }else{ $prodotto['id_aliquota_iva'] = $_POST['aliquota_iva']; }
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
    //if( array_key_exists('qta_disponibile', $_POST) ){ $prodotto['qta_disponibile'] = empty($_POST['qta_disponibile']) ? '0' : $_POST['qta_disponibile']; }
    if( empty($_POST['visualizza_qta']) ){ $error[]='visualizza_qta'; }else{ $prodotto['visualizzazione_qta'] = $_POST['visualizza_qta']; }
    if( array_key_exists('promo', $_POST) ){ $prodotto['promo'] = empty($_POST['promo']) ? '0' : '1'; }
    if( array_key_exists('sconto_promo', $_POST) ){ $prodotto['sconto_promo'] = empty($_POST['sconto_promo']) ? '0' : $_POST['sconto_promo']; }
    if( array_key_exists('note', $_POST) ){ $prodotto['note'] = $_POST['note']; }
    //if( array_key_exists('id_marchio', $_POST) ){ $prodotto['id_marchio'] = $_POST['id_marchio']; }
    
    
    if( !count($error) ){
        include '../common/carica_img.php';
        //salva modifiche al prodotto
        $managerSql->start_transaction();
        //se il database è stato aggiornato e il file non è stato inviato oppure è arrivato vuoto oppure è arrivato ed è stato possibile salvarlo
        if( !$managerSql->modifica_prodotto($prodotto) || !(( !array_key_exists('immagine', $_FILES) || ($_FILES['immagine']['size']==0)) || load_image('../images/img_prodotti/', $_FILES['immagine'], $prodotto['id_prodotto']) ) ){
            $managerSql->transaction_rollback();
            header('Location: error.php?code=27');
            exit();
        }
        
        
        $attributi = ( empty($_POST['attributo'])) ? array() : $_POST['attributo'];
        
        foreach ($attributi as $attributo) {
              
            $id_proprieta = $attributo['id_proprieta'];
            $proprieta = $attributo['valore'];
            $prezzi = $attributo['prezzo'];

            if( $attributo['id_attributo'] == '0' ){
                //nuovo attributo + nuove proprietà
                $new_attributo = array();
                $new_attributo['id_prodotto'] = $prodotto['id_prodotto'];
                $new_attributo['nome'] = $attributo['nome'];
                
                if( !empty($new_attributo['nome']) ){
                    $id_attributo = $managerSql->aggiungi_attributo($new_attributo);
                    if(!$id_attributo){ 
                        $managerSql->transaction_rollback();
                        header('Location: error.php?code=27');
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
                                header('Location: error.php?code=27');
                                exit();
                            }
                        }
                    }
                }
                
            }else{
                //modifica attributo
                $old_attributo = array();
                $old_attributo['id_attributo'] = $attributo['id_attributo'];
                $old_attributo['id_prodotto'] = $prodotto['id_prodotto'];
                $old_attributo['nome'] = $attributo['nome'];
                
                if( !$managerSql->modifica_attributo($old_attributo) ){ 
                    $managerSql->transaction_rollback();
                    header('Location: error.php?code=27');
                    exit();
                }
                //modifica o aggiunta di proprietà
                for($j=0; $j<count($proprieta); $j++){
                    if( $id_proprieta[$j] == '0' ){
                        //nuova proprietà
                        $new_proprieta = array();
                        $new_proprieta['id_attributo'] = $attributo['id_attributo']; //id_attributo aggiunto al passo precedente
                        $new_proprieta['valore'] = $proprieta[$j];
                        $new_proprieta['variazione_prezzo'] = empty($prezzi[$j]) ? 0 : $prezzi[$j] ;
                        if( empty($new_proprieta['valore']) || !$managerSql->aggiungi_proprieta($new_proprieta) ){
                            $managerSql->transaction_rollback();
                            header('Location: error.php?code=27');
                            exit();
                        }
                    }else{
                        //modifica proprietà esistente
                        $old_proprieta = array();
                        $old_proprieta['id_proprieta'] = $id_proprieta[$j];
                        $old_proprieta['id_attributo'] = $attributo['id_attributo'];
                        $old_proprieta['valore'] = $proprieta[$j];
                        $old_proprieta['variazione_prezzo'] = empty($prezzi[$j]) ? 0 : $prezzi[$j] ;
                        if( empty($old_proprieta['valore']) || !$managerSql->modifica_proprieta($old_proprieta) ){
                            $managerSql->transaction_rollback();
                            header('Location: error.php?code=27');
                            exit();
                        }
                    }       
                }     
            }
        }
        //ciclo su del_attributo[] e del_proprieta[] per cancellare attributi e proprietà
        $del_proprieta = !empty($_POST['del_proprieta']) ? $_POST['del_proprieta'] : NULL;
        $del_attributi = !empty($_POST['del_attributo']) ? $_POST['del_attributo'] : NULL;
        for($i=0; $i< count($del_proprieta); $i++){
            if(!$managerSql->elimina_proprieta($del_proprieta[$i]) ){
                $managerSql->transaction_rollback();
                header('Location: error.php?code=27');
                exit();
            }
        }
        for($i=0; $i< count($del_attributi); $i++){
            if(!$managerSql->elimina_attributo($del_attributi[$i]) ){
                $managerSql->transaction_rollback();
                header('Location: error.php?code=27');
                exit();
            }
        }
        
        $managerSql->transaction_commit();
        $salvataggio_completato=1;
    }
    
}

if(array_key_exists('aggiungi_a_magazzino', $_POST)){
    $magazzino = array();
    $magazzino['id_prodotto'] = $prodotto['id_prodotto'];
    $magazzino['attributi']=array();
    $magazzino['qta']=0;
    if( array_key_exists('attributi', $_POST) ){
        $attributi = $_POST['attributi'];
        $lista_proprieta = array();
        foreach ($attributi as $attributo) {
            if(!empty($attributo)){ //aggiungi solo se dal menu select è stato scelto un elemento
                $lista_proprieta[] = $attributo;
            }
        }
        $magazzino['attributi'] = $lista_proprieta;
    }
    if( array_key_exists('qta_magazzino', $_POST) ){ $magazzino['qta'] = empty($_POST['qta_magazzino']) ? '0' : $_POST['qta_magazzino']; }
    if (!$managerSql->aggiungi_a_magazzino($magazzino)) {
        header('Location: error.php?code=44');
        exit();
    }
    $salvataggio_completato = 1;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ADMIN | EDITA PRODOTTO</title>
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
        num_attributi = $('#div_attributi').find('div').length;
        if( num_attributi ){
            last_div_id = $('#div_attributi').find('div')[num_attributi-1].id;
            num = last_div_id.split('_')[2];
            num++;
            num = ''+num;
            while (num.length < 3) {
                num = '0' + num;
            }
        }else{
            num = '000';
        }
        //numero di proprietà
        p_num = calcola_prossimo_num_proprieta();
        new_div = "<div id=\"div_attributo_"+num+"\">";
        new_div += "<p>Nome Attributo";
        new_div += "<input type=\"hidden\" name=\"attributo["+num+"][id_attributo]\" id=\"attributo_id_"+num+"\" value=\"0\" />"; //id 0 indica che è un attributo nuovo non presente nel database
        new_div += "<input type=\"text\" name=\"attributo["+num+"][nome]\" id=\"attributo_"+num+"_nome\" />";
        new_div += "<input type=\"button\" name=\"elimina_"+num+"\" id=\"elimina_"+num+"\" value=\"Elimina Attributo\" onclick=\"javascript: elimina_attributo('"+num+"');\"/>";
        new_div += "<input type=\"button\" name=\"n_p_"+num+"\" id=\"n_p_"+num+"\" value=\"Aggiungi Proprietà\" onclick=\"javascript: nuova_proprieta("+num+");\"/>";
        new_div += "<span id=\"span_p_"+p_num+"\"><br />";
        new_div += "<input type=\"hidden\" name=\"attributo["+num+"][id_proprieta][]\" id=\"proprieta_id_"+p_num+"\" value=\"0\" />"; //id 0 indica che è una proprietà nuova non presente nel database
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
        if( confirm("Sei sicuro di voler eliminare l'attributo?") ){
            id_attributo = $('#attributo_id_'+num).val();
            if( id_attributo != "0" ){
                //aggiungi a div nascosto per ricordare l'id'
                to_append = "<input type=\"hidden\" name=\"del_attributo[]\" value=\""+id_attributo+"\" />";
                $('#eliminare').append(to_append);
            }
            $('#div_attributo_'+num).remove();
        }
        
    }
    
   
    function nuova_proprieta( a_num ){
        p_num = calcola_prossimo_num_proprieta();
        a_num = ''+a_num;
        while (a_num.length < 3) {
            a_num = '0' + a_num;
        }
        //alert(p_num);
        new_p = "<span id=\"span_p_"+p_num+"\"><br/>";
        new_p += "<input type=\"hidden\" name=\"attributo["+a_num+"][id_proprieta][]\" id=\"proprieta_id_"+p_num+"\" value=\"0\" />"; //id 0 indica che è una proprietà nuova non presente nel database
        new_p += "Proprietà<input type=\"text\" name=\"attributo["+a_num+"][valore][]\" id=\"attributo_"+a_num+"_valore_"+p_num+"\" />";
        new_p += "- Variazione Prezzo";
        new_p += "<input type=\"text\" name=\"attributo["+a_num+"][prezzo][]\" id=\"attributo_"+a_num+"_prezzo_"+p_num+"\" />";
        new_p +="<input type=\"button\" name=\"e_p_"+p_num+"\" id=\"e_p_"+p_num+"\" value=\"Elimina Proprietà\" onclick=\"javascript: elimina_proprieta('"+p_num+"');\" /></span>";
        //alert(new_p);
        $('#div_attributo_'+a_num+' p').append(new_p);
    }
    
    
    function elimina_proprieta( p_num ){
        //alert(p_num);
        if( confirm("Sei sicuro di voler eliiminare la proprietà?") ){
            id_proprieta = $('#proprieta_id_'+p_num).val();
            if( id_proprieta != "0" ){
                //aggiungi a div nascosto per ricordare l'id'
                to_append = "<input type=\"hidden\" name=\"del_proprieta[]\" value=\""+id_proprieta+"\" />";
                $('#eliminare').append(to_append);
            }
            $('#span_p_'+p_num).remove();
        }
    }
    
</script>
</head>

<body>    
	<div class="page">
		<h1>Modifica Prodotto</h1>
			
			<?php
				 if( count($error) ){
					  $campi = implode(', ', $error);
					  echo "<p>Compilare correttamente i campi: $campi</p>";
				 }
			 
				 if($salvataggio_completato){
					  echo '<p>I dati sono stati salvati con successo!</p>';
				 }
			?>
			
			<form action="" method="post" enctype="multipart/form-data" id="form1">
			  <table cellspacing="0" cellpadding="0">
				 <!-- <tr>
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
								 print_option_categorie($managerSql, 0, 1, $prodotto['id_categoria']);
							?>
						 </select>
					</td>
				 </tr>
					<!--
				 <tr>
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
							<option value="">Seleziona un'aliquota</option>
							<?php
							$aliquote = $managerSql->lista_aliquote_iva();
							for($i=0; $i<count($aliquote); $i++){
								 $aliquota = $aliquote[$i];
								 $aliquota['nome'] = htmlspecialchars($aliquota['nome']);
								 $aliquota['aliquota'] *= 100;
								 $selected = ($aliquota['id_aliquota_iva']==$prodotto['id_aliquota_iva']) ? ' selected="selected" ' : '';
								 echo "<option value=\"{$aliquota['id_aliquota_iva']}\" $selected>{$aliquota['nome']} ({$aliquota['aliquota']}%)</option>";
							}
							?>
						 </select>
					</td>
				 </tr>
				 <tr>
					<td>Visualizza iva inclusa</td>
					<?php
					$iva_inclusa = array();
					$iva_inclusa['SI'] = $iva_inclusa['NO'] = '';
					if($prodotto['iva_inclusa']=='SI'){ $iva_inclusa['SI']='checked="checked"';}else{$iva_inclusa['NO']='checked="checked"';}
					?>
					<td><input name="iva_inclusa" type="radio" id="iva_inclusa" value="SI" <?php echo $iva_inclusa['SI']; ?> />
					Si      
					  <input type="radio" name="iva_inclusa" id="iva_inclusa" value="NO" <?php echo $iva_inclusa['NO']; ?> /> 
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
								 $selected = ($marchio['id_marchio']==$prodotto['id_marchio']) ? 'selected="selected"' : '';
								 $marchio['nome'] = htmlspecialchars($marchio['nome']);
								 echo "<option value=\"{$marchio['id_marchio']}\" $selected >{$marchio['nome']}</option>";
							}
							?>
						 </select>
					</td> -->
				 </tr>
				 <tr>
					<td> descrizione_breve </td>
					<td><textarea name="descrizione_breve" rows="7" cols="40" dir="ltr" id="descrizione_breve" tabindex="16"><?php echo htmlspecialchars($prodotto['descrizione_breve']); ?></textarea></td>
				 </tr>
				 <tr>
					<td> descrizione </td>
					<td><textarea name="descrizione" rows="7" cols="40" dir="ltr" id="descrizione" tabindex="19"><?php echo htmlspecialchars($prodotto['descrizione']); ?></textarea></td>
				 </tr>
				 <tr>
					<td> peso </td>
					<td><input name="peso" size="11" tabindex="28" id="peso" type="text" value="<?php echo htmlspecialchars($prodotto['peso']); ?>" /></td>
				 </tr>
				 <tr>
					<td align="center"> tipo_quantita </td>
					<?php
					$select_tipo_qta = array();
					$select_tipo_qta['T'] = $select_tipo_qta['S'] = $select_tipo_qta['N'] = '';
					switch ($prodotto['tipo_quantita']) {
						 case 'T': $select_tipo_qta['T'] = 'selected="selected"'; break;
						 case 'S': $select_tipo_qta['S'] = 'selected="selected"'; break;
						 case 'N': $select_tipo_qta['N'] = 'selected="selected"'; break;
					}
					?>
					<td><select name="tipo_quantita" tabindex="22" id="tipo_quantita" >
							  <option value="T" <?php echo $select_tipo_qta['T']; ?> >Text</option>
							  <option value="S" <?php echo $select_tipo_qta['S']; ?> >Select</option>
							  <option value="N" <?php echo $select_tipo_qta['N']; ?> >Nessuna</option>
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
					<td><input name="qta_disponibile" type="text" id="qta_disponibile" tabindex="37" size="15" maxlength="15" value="<?php echo htmlspecialchars($prodotto['qta_disponibile']); ?>" /></td>
				 </tr>
					-->
				 <tr>
					<td>Visualizzazione qta</td>
					<?php
					$chk_visual_qta = array();
					$chk_visual_qta['1'] = $chk_visual_qta['2'] = $chk_visual_qta['3'] = '';
					switch ($prodotto['visualizzazione_qta']) {
						 case 1: $chk_visual_qta['1'] = 'checked="checked"'; break;
						 case 2: $chk_visual_qta['2'] = 'checked="checked"'; break;
						 case 3: $chk_visual_qta['3'] = 'checked="checked"'; break;
					}
					?>
					<td>
					<div style="height:60px">
					  <div>
					  		<input style="position:absolute; margin:0 0 0 -500px;"  name="visualizza_qta" type="radio" id="visualizza_qta_1" value="1" <?php echo $chk_visual_qta['1']; ?> />
					  		<p style="position:absolute;">Quantità esatta</p>
						</div>
					  
					  <div>
					  		<input  style="position:absolute; margin:22px 0 0 -500px;" name="visualizza_qta" type="radio" id="visualizza_qta_2" value="2" <?php echo $chk_visual_qta['2']; ?> />
					  		<p style="position:absolute; margin:20px 0 0 0;">Disponibile - Non Disponibile</p>
					 	</div>
					  
					  <div>
					  		<input  style="position:absolute; margin:44px 0 0 -500px;" name="visualizza_qta" type="radio" id="visualizza_qta_3" value="3" <?php echo $chk_visual_qta['3']; ?> />
					   	<p style="position:absolute; margin:40px 0 0 0;">Non visualizzare</p>
						</div>
					</div>
				 	
					</td>
						
				 </tr>
					<tr>
						 <td>Promo</td>
						 <?php
						 $chk_promo = array();
						 $chk_promo['NO'] = $chk_promo['SI'] = '';
						 switch ($prodotto['promo']){
							  case '0': $chk_promo['NO'] = 'checked="checked"'; break;
							  case '1': $chk_promo['SI'] = 'checked="checked"'; break;
						 }
						 ?>
						 <td>
							<div style="height:38px">
							  	<div>
							  		<input style="position:absolute; margin:0 0 0 -500px;" name="promo" type="radio" id="promo_no" value="0" <?php echo $chk_promo['NO']; ?> />
									<p style="position:absolute; margin:0 0 0 0;">NO</p>
							  	</div>
							  	<div>
							  		<input style="position:absolute; margin:22px 0 0 -500px;" name="promo" type="radio" id="promo_si" value="1" <?php echo $chk_promo['SI']; ?> />
									<p style="position:absolute; margin:20px 0 0 0;">SI</p>
						 		</div>
							</div>
						 </td>
					</tr>
					<tr>
						 <td>Sconto Promo</td>
						 <td>
							  <input type="text" name="sconto_promo" id="sconto_promo" value="<?php echo htmlspecialchars($prodotto['sconto_promo']); ?>" /> (Es. 5% = 0.05)
						 </td>
					</tr>
				 <tr>
					<td>Immagine</td>
					<td><input type="file" name="immagine" id="immagine" /></td>
				 </tr>
			  </table>
			  <p>&nbsp;</p>
			  
			  <p><h2>Attributi</h2> 
				 <input type="button" name="st" id="st" value="Aggiungi Attributo" onclick="javascript: nuovo_attributo();"/>
			  </p>
			  <div id="div_attributi">
				 <?php
				 $attributi = $managerSql->lista_attributi_by_prodotto($prodotto['id_prodotto']);
				 $num_proprieta=0;//serve per dare un id univoco agli span delle proprieta per poterli cancellare
				 for($i=0; $i<count($attributi);$i++){
					  $attributo = $attributi[$i];
					  $attributo['nome'] = htmlspecialchars($attributo['nome']);
					  $id_div = ''.$i;
					  while(strlen($id_div)<3){ $id_div = '0'.$id_div; }
					  echo "<div id=\"div_attributo_$id_div\">
								 <p>Nome Attributo
									  <input type=\"hidden\" name=\"attributo[{$id_div}][id_attributo]\" id=\"attributo_id_{$id_div}\" value=\"{$attributo['id_attributo']}\" />
									  <input type=\"text\" name=\"attributo[{$id_div}][nome]\" id=\"attributo_{$id_div}_nome\" value=\"{$attributo['nome']}\" />
									  <input type=\"button\" name=\"elimina_{$id_div}\" id=\"elimina_{$id_div}\" value=\"Elimina Attributo\" onclick=\"javascript: elimina_attributo('{$id_div}');\"/>
									  <input type=\"button\" name=\"n_p_{$id_div}\" id=\"n_p_{$id_div}\" value=\"Aggiungi Proprietà\" onclick=\"javascript: nuova_proprieta('{$id_div}');\"/>";
					  $lista_proprieta = $managerSql->lista_proprieta_by_attributo($attributo['id_attributo']);
					  for($j=0; $j<count($lista_proprieta); $j++){
							$proprieta = $lista_proprieta[$j];
							$proprieta['valore'] = htmlspecialchars($proprieta['valore']);
							$proprieta['variazione_prezzo'] = htmlspecialchars($proprieta['variazione_prezzo']);
							$id_span = $num_proprieta++;
							while(strlen($id_span)<3){ $id_span = '0'.$id_span; }
							echo "<span id=\"span_p_{$id_span}\"><br />
									  <input type=\"hidden\" name=\"attributo[{$id_div}][id_proprieta][]\" id=\"proprieta_id_{$id_span}\" value=\"{$proprieta['id_proprieta']}\" />
									  Proprietà <input type=\"text\" name=\"attributo[{$id_div}][valore][]\" id=\"attributo_{$id_div}_valore_{$id_span}\" value=\"{$proprieta['valore']}\" />
									  - 
									  Variazione Prezzo <input type=\"text\" name=\"attributo[{$id_div}][prezzo][]\" id=\"attributo_{$id_div}_prezzo_{$id_span}\" value=\"{$proprieta['variazione_prezzo']}\" />
									  <input type=\"button\" name=\"e_p_{$id_span}\" id=\"e_p_{$id_span}\" value=\"Elimina Proprietà\" onclick=\"javascript: elimina_proprieta('{$id_span}');\" /></span>";
					  }
					  echo "</p> </div>";
				 }
				 ?>
			  </div>
			  
			  <div id="eliminare">
					
			  </div>
			  
			  <p>&nbsp;</p>
			  
			  <p><h2>NOTE DELL'AMMINISTRATORE:</h2><br/>
					<textarea name="note" rows="7" cols="40" dir="ltr" id="note" tabindex="16"><?php echo htmlspecialchars($prodotto['note']); ?></textarea>
			  </p>
			  
			  <p>
				 <input type="submit" name="salva" id="salva" value="Salva modifiche" />
			  </p>
			</form>
			<p>&nbsp;</p>
			
			
			
			<br /><br /><br /><br />
			<p><h1>Magazzino:<h1>
				 <?php
				 $in_magazzino = $managerSql->lista_magazzino_by_prodotto($prodotto['id_prodotto']);
				 foreach ($in_magazzino as $key => $giacenza) {
					  $lista_proprieta = $giacenza['attributi'];
					  for($i=0; $i<count($lista_proprieta); $i++){
							$proprieta = $managerSql->get_proprieta($lista_proprieta[$i]);
							echo "{$proprieta['valore']} - ";
					  }
					  echo "{$giacenza['qta']} <br/>";
				 }
				 ?>
			</p>
			<form name="form_magazzino" id="form_magazzino" method="post" action="">
				 <p><h2>Aggiungi in magazzino:<h2>
					  <?php
							foreach ($attributi as $key => $attributo) {
								 $attributo['nome'] = htmlspecialchars($attributo['nome']);
								 echo "Scegli {$attributo['nome']}<select name=\"attributi[]\" id=\"attributo_$key\">";
								 $lista_proprieta = $managerSql->lista_proprieta_by_attributo($attributo['id_attributo']);
								 foreach ($lista_proprieta as $key => $proprieta) {
									  echo "<option value=\"{$proprieta['id_proprieta']}\">{$proprieta['valore']}</option>";
								 }
								 echo "</select>";
							}
					  ?>
					  <input type="text" name="qta_magazzino" id="qta_magazzino" value="" />
					  <input type="submit" name="aggiungi_a_magazzino" id="aggiungi_a_magazzino" value="Aggiungi in magazzino" />
				 </p>
			</form>

			<br /><a style="font-size:10px; color:#999;" href="admin.php">&lt;&lt; Torna al pannello principale</a>
	</div>


</body>
</html>
