<?php
include_once '../common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ADMIN | GESTISCI I PRODOTTI</title>
<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
</head>

<body>    
	<div class="page2">
		<h1>Gestione Prodotti</h1>
		<table class="table2">
		  <tr>
			 <th>ID Prodotto</th>
			 <!-- <th>Codice</th> -->
			 <th>Nome</th>
			 <th>Categoria</th>
			 <th>prezzo netto</th>
			 <th>aliquota iva</th>
			 <th>descrizione breve</th>
			 <th>descrizione</th>
			 <th>tipo quantità</th>
			 <th>select inizio</th>
			 <th>select fine</th>
			 <th>select incremento</th>
			 <th>tempo disponibilità</th>
			 <!-- <th>qtà_disponibile</th> -->
			 <th>MODIFICA</th>
			 <th>ELIMINA</th>
		  </tr>
			 
			 <?php
			 $prodotti = $managerSql->lista_prodotti();
			 for($i=0; $i<count($prodotti); $i++){
				  $prodotto = $prodotti[$i];
				  echo "<tr>
							 <td>{$prodotto['id_prodotto']}</td>
							 <td>{$prodotto['nome']}</td>
							 <td>{$prodotto['id_categoria']}</td>
							 <td>{$prodotto['prezzo_netto']}</td>
							 <td>{$prodotto['id_aliquota_iva']}</td>
							 <td>{$prodotto['descrizione_breve']}</td>
							 <td>{$prodotto['descrizione']}</td>
							 <td>{$prodotto['tipo_quantita']}</td>
							 <td>{$prodotto['quantita_select_inizio']}</td>
							 <td>{$prodotto['quantita_select_fine']}</td>
							 <td>{$prodotto['quantita_select_incremento']}</td>
							 <td>{$prodotto['tempo_disponibilita']}</td>
							 <td><a href=\"edit_prodotto.php?id={$prodotto['id_prodotto']}\">Modifica</a></td>
							 <td><a href=\"del_prodotto.php?id={$prodotto['id_prodotto']}\" onclick=\"javascript: return confirm('Sei sicuro di voler eliminare il prodotto?');\">Elimina</a></td>
						  </tr>";
			 }
			 
			 ?>
			 
			 
		</table>
		<a class="voiceadmin" href="add_prodotto.php">Aggiungi un nuovo prodotto</a>
		<br /><a style="font-size:10px; color:#999;" href="admin.php">&lt;&lt; Torna al pannello principale</a>
	</div>

</body>
</html>