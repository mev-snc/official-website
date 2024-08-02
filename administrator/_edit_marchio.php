<?php
include_once '../common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

if( empty ($_GET['id']) ){
    header('Location: error.php?code=1');
    exit();
}

$marchio = $managerSql->get_marchio($_GET['id']);
if( !$marchio ){
    header('Location: error.php?code=34');
    exit();
}

$salvataggio_completato=0;
$error = array();


if(array_key_exists('salva', $_POST) ){
    if( empty($_POST['nome']) ){ $error[]='nome'; }else{ $marchio['nome'] = $_POST['nome']; }
    
    if( !count($error) ){
        if( $managerSql->modifica_marchio($marchio) ){
            $salvataggio_completato=1;
        }else{
            header('Location: error.php?code=35');
            exit();
        }
    }
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Modulo modifica marchio</title>
</head>

<body>
<p>Modifica marchio</p>

<?php
    if( count($error) ){
        $campi = implode(', ', $error);
        echo "<p>Compilare correttamente i campi: $campi</p>";
    }
    
    if($salvataggio_completato){
        echo '<p>I dati sono stati salvati con successo!</p>';
    }
?>

<form action="" method="post" id="form1">
  <table cellspacing="0" cellpadding="0">
    <tr>
      <td align="center">denominazione * </td>
      <td><input name="nome" value="<?php echo htmlspecialchars($marchio['nome']); ?>" size="40" id="nome" type="text" /></td>
    </tr>
  </table>
  <p>
    <input type="submit" name="salva" id="salva" value="Salva modifiche" />
  </p>
</form>

<p>Fornito da:<br/>
<?php
$assoc_marchio_fornitori = $managerSql->lista_assoc_marchi_fornitori(NULL, $marchio['id_marchio']);
foreach ($assoc_marchio_fornitori as $assoc_marchio_fornitore) {
    $fornitore = $managerSql->get_fornitore($assoc_marchio_fornitore['id_fornitore']);
    echo "{$fornitore['denominazione']} - {$fornitore['citta']} ({$fornitore['provincia']}) - [<a href=\"edit_fornitore.php?id={$fornitore['id_fornitore']}\">Visualizza</a>] <br/>";
}
?>

</p>

</body>
    
</html>