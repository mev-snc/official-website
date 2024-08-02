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


$news = $managerSql->get_news($_GET['id']);
if( !$news ){
    header('Location: error.php?code=40');
    exit();
}

$error = array();
$salvataggio_completato=0;
if(array_key_exists('salva', $_POST)){
    if( empty($_POST['titolo']) ){ $error[]='titolo'; }else{ $news['titolo'] = $_POST['titolo']; }
    if( empty($_POST['descrizione']) ){ $error[]='descrizione'; }else{ $news['descrizione'] = $_POST['descrizione']; }
    $managerSql->start_transaction();
    if( !count($error) ){
        if( !$managerSql->modifica_news($news) || !(( !array_key_exists('immagine', $_FILES) || ($_FILES['immagine']['size']==0)) || load_image('../images/img_news/', $_FILES['immagine'], $news['id_news']) ) ){
            $managerSql->transaction_rollback();
            header('Location: error.php?code=41');
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
<title>Modulo modifica news</title>
</head>

<body>
<p>Modifica news</p>

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
  <p>Titolo *
      <input type="text" name="titolo" id="titolo" value="<?php echo htmlspecialchars($news['titolo']); ?>" />
<br />
    Descrizione*
    <textarea name="descrizione" id="descrizione" cols="45" rows="5"><?php echo htmlspecialchars($news['descrizione']); ?></textarea>
    <br />
    Immagine
    <input type="file" name="immagine" id="immagine" />
    <br />
    <input type="submit" name="salva" id="salva" value="Salva modifiche" />
  </p>
</form>


</body>
    
</html>