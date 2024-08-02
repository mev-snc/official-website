<?php
include_once '../common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

if( empty($_GET['id']) ){
    header('Location: error.php?code=1');
    exit();
}


$aliquota_iva = $managerSql->get_aliquota_iva($_GET['id']);
if( !$aliquota_iva ){
    header('Location: error.php?code=22');
    exit();
}

$salvataggio_completato=0;
$error = array();

if(array_key_exists('salva', $_POST)){
    if( empty($_POST['nome']) ){ $error[]='nome'; }else{ $aliquota_iva['nome'] = $_POST['nome']; }
    if( !array_key_exists('aliquota', $_POST) || ($_POST['aliquota']=='') ){ $error[]='aliquota'; }else{ $aliquota_iva['aliquota'] = $_POST['aliquota']; }
    
    if( !count($error) ){
        if( ! $managerSql->modifica_aliquota_iva($aliquota_iva) ){
            header('Location: error.php?code=23');
            exit();
        }
        $salvataggio_completato=1;
    }
}

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Modulo modifica Passowrd Admin</title>
<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
</head>

<body>    
	<div class="page">
		<h1>ADMIN | EDITA ALIQUOTA IVA</h1>

			 <?php
			 
				  if( count($error) ){
						$campi = implode(', ', $error);
						echo "<p>Compilare correttamente i campi: $campi</p>";
				  }
				  if($salvataggio_completato){
						echo '<p>I dati sono stati salvati con successo!</p>';
				  }
			 ?>
		
		<form id="form1" method="post" action="">
		  Nome
		  <input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($aliquota_iva['nome']); ?>" />
		  <br />
		  Aliquota  (Es. 21% = 0.21)
		  <input type="text" name="aliquota" id="aliquota" value="<?php echo htmlspecialchars($aliquota_iva['aliquota']); ?>" />
		  <br />
		  <input type="submit" name="salva" id="salva" value="Salva modifiche" />
		</form>
		<br /><a style="font-size:10px; color:#999;" href="admin.php">&lt;&lt; Torna al pannello principale</a>
	</div>
</body>
</html>