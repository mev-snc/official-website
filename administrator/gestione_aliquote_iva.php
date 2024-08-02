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
<title>ADMIN | GESTISCI ALIQUOTE</title>
<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
</head>

<body>    
	<div class="page">
			<h1>Lista aliquote IVA</h1>
				<table border="1">
				  <tr>
					 <th>ID</th>
					 <th>Nome</th>
					 <th>Aliquota</th>
					 <th>&nbsp;</th>
					 <th>&nbsp;</th>
				  </tr>
				
					 <?php
					 $aliquote = $managerSql->lista_aliquote_iva();
					 for($i=0; $i<count($aliquote); $i++){
						  $aliquota = $aliquote[$i];
						  echo "<tr>
									 <td>{$aliquota['id_aliquota_iva']}</td>
									 <td>{$aliquota['nome']}</td>
									 <td>{$aliquota['aliquota']}</td>
									 <td><a href=\"edit_aliquota_iva.php?id={$aliquota['id_aliquota_iva']}\" >Modifica</a></td>
									 <td><a href=\"del_aliquota_iva.php?id={$aliquota['id_aliquota_iva']}\" onclick=\"javascript: return confirm('Sei sicuro di voler eliminare l\'aliquota?');\">Elimina</a></td>
								  </tr>";
					 }
					 ?>
			 
			 	</table>
				<a class="voiceadmin" href="add_aliquota_iva.php">Nuova Aliquota IVA</a>
				<br /><a style="font-size:10px; color:#999;" href="admin.php">&lt;&lt; Torna al pannello principale</a>
			</div>
</body>
    
</html>