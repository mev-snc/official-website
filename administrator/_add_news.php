<?php
include_once '../common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

include '../common/carica_img.php';

$news = array();
$news['titolo'] = '';
$news['descrizione'] = '';


$error = array();

if(array_key_exists('aggiungi', $_POST)){
    if( empty($_POST['titolo']) ){ $error[]='titolo'; }else{ $news['titolo'] = $_POST['titolo']; }
    if( empty($_POST['descrizione']) ){ $error[]='descrizione'; }else{ $news['descrizione'] = $_POST['descrizione']; }
    
    if( !count($error) ){
        //aggiungi categoria
        $managerSql->start_transaction();
        $id_news = $managerSql->aggiungi_news($news);
        //se il database è stato aggiornato e il file non è stato inviato oppure è arrivato vuoto oppure è arrivato ed è stato possibile salvarlo
        if( $id_news && (( !array_key_exists('immagine', $_FILES) || ($_FILES['immagine']['size']==0)) || load_image('../images/img_news/', $_FILES['immagine'], $id_news) ) ){
            $managerSql->transaction_commit();
            header('Location: gestione_news.php');
            exit();
        }else{
            $managerSql->transaction_rollback();
            header('Location: error.php?code=39');
            exit();
        }
    }
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Modulo aggiunta news</title>
</head>

<body>
<p>Aggiungi nuova news</p>

<?php
    if( count($error) ){
        $campi = implode(', ', $error);
        echo "<p>Compilare correttamente i campi: $campi</p>";
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
    <input type="submit" name="aggiungi" id="aggiungi" value="Aggiungi" />
  </p>
</form>

</body>
    
</html>