<?php
include_once '../common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

if( empty ($_GET['id']) ){
    header('Location: error.php?code=1');
    exit();
}

$fornitore = $managerSql->get_fornitore($_GET['id']);
if( !$fornitore ){
    header('Location: error.php?code=29');
    exit();
}

$salvataggio_completato=0;
$error = array();


if(array_key_exists('salva', $_POST) ){
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
        if( $managerSql->modifica_fornitore($fornitore) ){
            $salvataggio_completato=1;
        }else{
            header('Location: error.php?code=31');
            exit();
        }
    }
}

if( array_key_exists('aggiungi_assoc_marchio', $_POST)){
    if( empty($_POST['id_marchio']) ){
        header('Location: error.php?code=1');
        exit();
    }
    $assoc_marchio_fornitore = array();
    $assoc_marchio_fornitore['id_marchio'] = $_POST['id_marchio'];
    $assoc_marchio_fornitore['id_fornitore'] = $fornitore['id_fornitore'];
    if ( $managerSql->aggiungi_assoc_marchio_fornitore($assoc_marchio_fornitore) ){
        $salvataggio_completato = 1;
    }else{
        header('Location: error.php?code=37');
        exit();
    }
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Modulo modifica fornitore</title>
</head>

<body>
<p>Modifica Fornitore</p>

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
    <input type="submit" name="salva" id="salva" value="Salva modifiche" />
  </p>
</form>


<p>Marchi trattati</p>
<form name="form_marchi" action="" method="post">
<p>Marchio:
    <select name="id_marchio" id="id_marchio" >
        <option value="0">Seleziona un marchio</option>
        <?php
            $marchi = $managerSql->lista_marchi();
            for($i=0; $i<count($marchi); $i++){
                $marchio = $marchi[$i];
                echo "<option value=\"{$marchio['id_marchio']}\">{$marchio['nome']}</option>";
            }
        ?>
    </select>
&nbsp;

<input type="submit" name="aggiungi_assoc_marchio" id="aggiungi_assoc_marchio" value="Aggiungi Marchio Trattato" />
</p>
</form>

<?php
$assoc_marchio_fornitore = $managerSql->lista_assoc_marchi_fornitori( $fornitore['id_fornitore'] );
for($i=0; $i<count($assoc_marchio_fornitore); $i++){
    $assoc = $assoc_marchio_fornitore[$i];
    $marchio = $managerSql->get_marchio($assoc['id_marchio']);
    echo "<p>{$marchio['nome']} 
    <a href=\"del_assoc_marchio_fornitore.php?id_m={$assoc['id_marchio']}&id_f={$assoc['id_fornitore']}\">Elimina</a></p>";
}
?>



</body>
    
</html>