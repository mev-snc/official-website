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
<title>ADMIN | GESTIONE CATEGORIE</title>
<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
</head>

<body>    
	<div class="page">
		<h1>Lista categorie</h1>
			<table border="1">
			  <tr>
				 <th>ID</th>
				 <th>Nome</th>
				 <th>Descrizione</th>
				 <th>&nbsp;</th>
				 <th>&nbsp;</th>
			  </tr>
				 
				 
				 <?php
				 function print_righe_categorie( $managerSql , $id_categoria_padre, $livello ){
					  $spaziatore='';
					  for($s=0;$s<$livello;$s++){$spaziatore.='_';}
					  $categorie = $managerSql->lista_categorie($id_categoria_padre);
					  for($i=0; $i<count($categorie); $i++){
							$categoria = $categorie[$i];
							$categoria['descrizione'] = substr($categoria['descrizione'], 0, 100);
							echo "<tr>
									  <td>{$categoria['id_categoria']}</td>
									  <td>$spaziatore [$livello] {$categoria['nome']}</td>
									  <td>{$categoria['descrizione']}</td>
									  <td><a href=\"edit_categoria.php?id={$categoria['id_categoria']}\" >Modifica</a></td>
									  <td><a href=\"del_categoria.php?id={$categoria['id_categoria']}\" onclick=\"javascript: return confirm('Sei sicuro di voler eliminare la categoria?');\">Elimina</a></td>
								 </tr>";
							print_righe_categorie($managerSql, $categoria['id_categoria'], $livello+1);
					  }
				 }
					  
				 print_righe_categorie($managerSql, 0, 1);
				 ?>
			
			</table>
			<a class="voiceadmin" href="add_categoria.php">Nuova Categoria</a>
			<br /><a style="font-size:10px; color:#999;" href="admin.php">&lt;&lt; Torna al pannello principale</a>

		</div>


</body>
    
</html>