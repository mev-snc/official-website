<?php
include_once '../common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

include 'verifica_admin.php';

$pagina_attuale = 0;
$inizio = 0;
$utenti_per_pagina = 5;

if(!array_key_exists('cerca', $_POST) || (empty($_POST['campo']) || empty($_POST['valore'])) ){
    $num_utenti = $managerSql->get_num_utenti();
    if( array_key_exists('pagina', $_GET) ){
        $pagina_attuale = $_GET['pagina'];
        $inizio = $utenti_per_pagina * $pagina_attuale;
        if( $inizio>=$num_utenti)
            exit("accesso ad area non consentito... numero pagina non valido");
    }
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ADMIN | GESTIONE UTENZA</title>
<link rel="stylesheet" type="text/css" href="style.css" media="screen" />

<script type="text/javascript">
    function cambia_pagina(){
        elemReg = document.getElementById('pagine');
        pagina = elemReg[elemReg.selectedIndex].value;
        window.location.href ="<?php echo "{$_SERVER['PHP_SELF']}?pagina="; ?>"+pagina;
    }
</script>
</head>

<body>    
	<div class="page">
		<h1>Lista utenti</h1>

			<form name="form_cerca" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
				 Ricerca: 
				 <select name="campo">
					  <option value="id_utente">Id utente</option>
					  <option value="username">Username</option>
					  <option value="piva">PIVA</option>
					  <option value="codice_fiscale">C.Fiscale</option>
				 </select>
				 <input type="text" name="valore" id="valore" size="10"/>
				 <input type="submit" name="cerca" value="Cerca" />
			</form>
			
			<table cellpadding="0" cellspacing="0">
			  <tr>
				 <th>ID</th>
				 <th>Username</th>
				 <th>Nome Completo</th>
				 <th>Email</th>
				 <th>Modifica</th>
				 <th>Elimina</th>
			  </tr>
				 
				 <?php
				 if(!array_key_exists('cerca', $_POST)){
					  $utenti = $managerSql->lista_utenti(NULL, NULL, $inizio, $utenti_per_pagina);
				 }else{
					  $utenti = $managerSql->lista_utenti($_POST['campo'], $_POST['valore']);
				 }
				 for($i=0; $i<count($utenti); $i++){
					  $utente = $utenti[$i];
					  echo "<tr>
								 <td>{$utente['id_utente']}</td>
								 <td>{$utente['username']}</td>
								 <td>{$utente['cognome']} {$utente['nome']}</td>
								 <td>{$utente['email']}</td>
								 <td><a href=\"edit_utente.php?id={$utente['id_utente']}\">Modifica</a></td>
								 <td><a href=\"del_utente.php?id={$utente['id_utente']}\" onclick=\"javascript: return confirm('Sei sicuro di voler eliminare l\'utente?');\">Elimina</a></td>
							  </tr>";
				 }
				 
				 ?>
				 
			</table>
			
			
			<?php
			if(!array_key_exists('cerca', $_POST) || (empty($_POST['campo']) || empty($_POST['valore']))){
				 //verifico che sia necessario visualizzare il menù delle pagine
				 if ( $num_utenti>0 && $num_utenti>$utenti_per_pagina ){
					  echo 'Pagine:<select name="pagine" id="pagine" onchange="javascript: cambia_pagina();" >';
					  $pagina=0;
					  while($num_utenti>0){ //ciclo per creare una 'option' per ogni pagina
							echo "<option value=\"$pagina\"";
							if( $pagina_attuale==$pagina){
								 echo 'selected="selected"'; //verrà selezionata la pagina attuale
							}
							//echo " onclick=\"javascript: location.href='{$_SERVER['PHP_SELF']}?pagina={$pagina}' \"  >$pagina</option>";
						echo " >$pagina</option>";
							$num_utenti = $num_utenti - $utenti_per_pagina;
							$pagina++;
					  }
					  echo '</select>';
				 }
			}
			?>
			
					<br /><a style="font-size:10px; color:#999;" href="admin.php">&lt;&lt; Torna al pannello principale</a>

		</div>

</body>
</html>