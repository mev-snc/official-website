<?php
include_once '../common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

include '../common/carica_img.php';


if( empty($_GET['id']) ){
    header('Location: error.php?code=1');
    exit();
}


$categoria = $managerSql->get_categoria($_GET['id']);
if( !$categoria ){
    header('Location: error.php?code=24');
    exit();
}

$salvataggio_completato=0;
$error = array();

if(array_key_exists('salva', $_POST)){
    if( empty($_POST['nome']) ){ $error[]='nome'; }else{ $categoria['nome'] = $_POST['nome']; }
    if( empty($_POST['descrizione']) ){ $error[]='descrizione'; }else{ $categoria['descrizione'] = $_POST['descrizione']; }
    if( !array_key_exists('livello', $_POST) ){ $error[]='livello'; }else{ $categoria['id_categoria_padre'] = $_POST['livello']; }
    
    if( !count($error) ){
        $managerSql->start_transaction();
        if( !$managerSql->modifica_categoria($categoria) || !(( !array_key_exists('immagine', $_FILES) || ($_FILES['immagine']['size']==0)) || load_image('../images/img_categorie/', $_FILES['immagine'], $categoria['id_categoria']) ) ){
            $managerSql->transaction_rollback();
            header('Location: error.php?code=25');
            exit();
        }

        $managerSql->transaction_commit();
        $salvataggio_completato=1;
    }
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ADMIN | EDITA CATEGORIA</title>
<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
</head>

<body>    
	<div class="page">
		<h1>Modifica categoria</h1>

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
				<h2>Nome Categoria*</h2>
				<input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($categoria['nome']); ?>" />
				<br /><br />
				<h2>Immagine</h2>
				 <input style="margin-top:-10px; margin-bottom:30px;" type="file" name="immagine" id="immagine" />
				 <br />

				<h2>Descrizione*</h2>
				<textarea name="descrizione" id="descrizione" cols="45" rows="5"><?php echo htmlspecialchars($categoria['descrizione']); ?></textarea>
				<br /><br /><br />

				 
				 <p>Livello
				 <select name="livello" id="livello">
					  <option value="0">[0] - Primo Livello</option>
					  <?php
					  include '../common/recursive_categorie.php';
					  print_option_categorie($managerSql, 0, 1, $categoria['id_categoria_padre']);
					  ?>
				 </select>
				 </p>
				 <br /><br />

				 <input type="submit" name="salva" id="salva" value="Salva modifiche" />
			</form>
			<br /><a style="font-size:10px; color:#999;" href="admin.php">&lt;&lt; Torna al pannello principale</a>
		</div>
		

</body>
    
</html>