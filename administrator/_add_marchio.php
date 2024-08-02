<?php
include_once '../common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

$marchio = array();
$marchio['nome'] = '';


$error = array();

if(array_key_exists('aggiungi', $_POST)){
    if( empty($_POST['nome']) ){ $error[]='nome'; }else{ $marchio['nome'] = $_POST['nome']; }
    
    if( !count($error) ){
        //aggiungi marchio
        if( $managerSql->aggiungi_marchio($marchio) ){
            header('Location: gestione_marchi.php');
            exit();
        }else{
            header('Location: error.php?code=33');
            exit();
        }
    }
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Modulo aggiunta marchio</title>
</head>

<body>
<p>Aggiungi nuovo marchio</p>

<?php
    if( count($error) ){
        $campi = implode(', ', $error);
        echo "<p>Compilare correttamente i campi: $campi</p>";
    }
?>

<form action="" method="post" id="form1">
  <table cellspacing="0" cellpadding="0">
    <tr>
      <td align="center">nome * </td>
      <td><input name="nome" value="<?php echo htmlspecialchars($marchio['nome']); ?>" size="40" id="nome" type="text" /></td>
    </tr>
  </table>
  <p>
    <input type="submit" name="aggiungi" id="aggiungi" value="Aggiungi" />
  </p>
</form>


</body>
    
</html>