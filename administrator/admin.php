<?php

include 'verifica_admin.php';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ADMIN | PANNELLO PRINCIPALE</title>
<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
</head>

<body>    
	<div class="page">

		<h1>&nbsp;&nbsp;&nbsp;Amministrazione sito - pannello principale </h1>
		<a class="voiceadmin" href="logout.php">Logout</a>
		<a class="voiceadmin" href="edit_admin_pwd.php">Cambia Password</a>
		<a class="voiceadmin" href="gestione_utenti.php">Gestione Utenti</a>
		
		<a class="voiceadmin" href="gestione_categorie.php">Gestione Categorie</a>
		
		<!--
		<p><a href="gestione_fornitori.php">Gestione Fornitori</a></p>
		<p><a href="add_fornitore.php">Nuovo Fornitore</a></p>
		-->
		<!--
		<p><a href="gestione_marchi.php">Gestione Marchi</a></p>
		<p><a href="add_marchio.php">Nuovo Marchio</a></p>
		-->
		<a class="voiceadmin" href="gestione_aliquote_iva.php">Gestione Aliquote IVA</a>
		
		
		<a class="voiceadmin" href="gestione_prodotti.php">Gestione Prodotti</a>
		
		
		<a class="voiceadmin" href="gestione_ordini.php">Gestione Ordini</a>
		
		<!-- <p><a href="gestione_fatture.php">Gestione Fatture</a></p>
		<p><a href="gestione_news.php">Gestione News</a></p>
		<p><a href="add_news.php">Nuova News</a></p> -->
		
		<a class="voiceadmin" href="edit_costo_corriere_exp.php">Gestione Costi Spedizione</a>
	</div>
</body>
</html>