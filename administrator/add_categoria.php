<?php
include_once '../common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

include '../common/carica_img.php';

$categoria = array();
$categoria['nome'] = '';
$categoria['descrizione'] = '';
$categoria['id_categoria_padre'] = '0';


$error = array();

if(array_key_exists('aggiungi', $_POST)){
    if( empty($_POST['nome']) ){ $error[]='nome'; }else{ $categoria['nome'] = $_POST['nome']; }
    if( empty($_POST['descrizione']) ){ $error[]='descrizione'; }else{ $categoria['descrizione'] = $_POST['descrizione']; }
    if( !array_key_exists('livello', $_POST) ){ $error[]='livello'; }else{ $categoria['id_categoria_padre'] = $_POST['livello']; }
    
    if( !count($error) ){
        //aggiungi categoria
        $managerSql->start_transaction();
        $id_categoria = $managerSql->aggiungi_categoria($categoria);
        //se il database è stato aggiornato e il file non è stato inviato oppure è arrivato vuoto oppure è arrivato ed è stato possibile salvarlo
        if( $id_categoria && (( !array_key_exists('immagine', $_FILES) || ($_FILES['immagine']['size']==0)) || load_image('../images/img_categorie/', $_FILES['immagine'], $id_categoria) ) ){
            $managerSql->transaction_commit();
            header('Location: gestione_categorie.php');
            exit();
        }else{
            $managerSql->transaction_rollback();
            header('Location: error.php?code=8');
            exit();
        }
    }
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ADMIN | CREA CATEGORIA</title>
<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
</head>

<body>    
	<div class="page">
		<h1>Aggiungi una nuova categoria</h1>
			
			<?php
				 if( count($error) ){
					  $campi = implode(', ', $error);
					  echo "<p>Compilare correttamente i campi: $campi</p>";
				 }
			?>
			
			<form action="" method="post" enctype="multipart/form-data" id="form1">
			  <h2>Nome Categoria*</h2>
					<input type="text" name="nome" id="nome" value="<?php echo htmlspecialchars($categoria['nome']); ?>" />
				<br /><br /><br />
				<h2>Immagine</h2>
				 <input style="margin-top:-20px;" type="file" name="immagine" id="immagine" />
				 <br /><br /><br />
				 <h2>Descrizione*</h2>
				 <textarea name="descrizione" id="descrizione" cols="45" rows="5"><?php echo htmlspecialchars($categoria['descrizione']); ?></textarea>
				 <br /><br /><br />
				 
				 Livello
				 <select name="livello" id="livello">
					  <option value="0">[0] - Primo Livello</option>
					  <?php
					  include '../common/recursive_categorie.php';
					  print_option_categorie($managerSql, 0, 1);
					  ?>
				 </select>
				 <br /><br /><br />
				 <input type="submit" name="aggiungi" id="aggiungi" value="Aggiungi" />
			  </p>
			</form>
			<br /><a style="font-size:10px; color:#999;" href="admin.php">&lt;&lt; Torna al pannello principale</a>
		</div>

</body>
    
</html>