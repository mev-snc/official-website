<?php
include_once '../common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

$fornitore = array();
$fornitore['denominazione'] = '';
$fornitore['piva'] = '';
$fornitore['provincia'] = '';
$fornitore['citta'] = '';
$fornitore['email'] = '';
$fornitore['telefono'] = '';
$fornitore['fax'] = '';
$fornitore['nome_banca'] = '';
$fornitore['iban'] = '';


$error = array();

if(array_key_exists('aggiungi', $_POST)){
    if( empty($_POST['denominazione']) ){ $error[]='denominazione'; }else{ $fornitore['denominazione'] = $_POST['denominazione']; }
    if( empty($_POST['piva']) ){ $error[]='piva'; }else{ $fornitore['piva'] = $_POST['piva']; }
    if( empty($_POST['provincia']) ){ $error[]='provincia'; }else{ $fornitore['provincia'] = $_POST['provincia']; }
    if( empty($_POST['citta']) ){ $error[]='citta'; }else{ $fornitore['citta'] = $_POST['citta']; }
    if( array_key_exists('email', $_POST) ){ $fornitore['email'] = $_POST['email']; }
    if( array_key_exists('telefono', $_POST) ){ $fornitore['telefono'] = $_POST['telefono']; }
    if( array_key_exists('fax', $_POST) ){ $fornitore['fax'] = $_POST['fax']; }
    if( array_key_exists('nome_banca', $_POST) ){ $fornitore['nome_banca'] = $_POST['nome_banca']; }
    if( array_key_exists('iban', $_POST) ){ $fornitore['iban'] = $_POST['iban']; }
    
    if( !count($error) ){
        //aggiungi categoria
        if( $managerSql->aggiungi_fornitore($fornitore) ){
            header('Location: gestione_fornitori.php');
            exit();
        }else{
            header('Location: error.php?code=30');
            exit();
        }
    }
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Modulo aggiunta fornitore</title>
</head>

<body>
<p>Aggiungi nuovo fornitore</p>

<?php
    if( count($error) ){
        $campi = implode(', ', $error);
        echo "<p>Compilare correttamente i campi: $campi</p>";
    }
?>

<form action="" method="post" id="form1">
  <table cellspacing="0" cellpadding="0">
    <tr>
      <td align="center">denominazione * </td>
      <td><input name="denominazione" value="<?php echo htmlspecialchars($fornitore['denominazione']); ?>" size="40" id="denominazione" type="text" /></td>
    </tr>
    <tr>
      <td align="center"> piva * </td>
      <td><input name="piva" value="<?php echo htmlspecialchars($fornitore['piva']); ?>" size="33"  id="piva" type="text" /></td>
    </tr>
    <tr>
      <td align="center"> provincia * </td>
      <td><input name="provincia" value="<?php echo htmlspecialchars($fornitore['provincia']); ?>" size="40" id="provincia" type="text" /></td>
    </tr>
    <tr>
      <td align="center"> citta * </td>
      <td><input name="citta" value="<?php echo htmlspecialchars($fornitore['citta']); ?>" size="40" id="citta" type="text" /></td>
    </tr>
    <tr>
      <td align="center"> email </td>
      <td><input name="email" value="<?php echo htmlspecialchars($fornitore['email']); ?>" size="40" id="email" type="text" /></td>
    </tr>
    <tr>
      <td align="center"> telefono </td>
      <td><input name="telefono" value="<?php echo htmlspecialchars($fornitore['telefono']); ?>" size="40" tabindex="19" id="telefono" type="text" /></td>
    </tr>
    <tr>
      <td align="center"> fax </td>
      <td><input name="fax" value="<?php echo htmlspecialchars($fornitore['fax']); ?>" size="40" id="fax" type="text" /></td>
    </tr>
    <tr>
      <td align="center"> noma_banca </td>
      <td><input name="nome_banca" value="<?php echo htmlspecialchars($fornitore['nome_banca']); ?>" size="40" id="nome_banca" type="text" /></td>
    </tr>
    <tr>
      <td align="center"> iban </td>
      <td><input name="iban" value="<?php echo htmlspecialchars($fornitore['iban']); ?>" size="40" id="iban" type="text" /></td>
    </tr>
  </table>
  <p>
    <input type="submit" name="aggiungi" id="aggiungi" value="Aggiungi" />
  </p>
</form>


</body>
    
</html>