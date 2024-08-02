<?php

include_once '../common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

/*
$costo_spedizione = $managerSql->get_costo_corriere_exp();
if( !$costo_spedizione ){
    header('Location: error.php?code=21');
    exit();
}*/

$costo_spedizione = array();
$costo_spedizione['provincia'] = '';
$costo_spedizione['da_peso'] = '';
$costo_spedizione['a_peso'] = '';
$costo_spedizione['costo'] = '';

$error = array();
$salvataggio_completato=0;

if(array_key_exists('aggiungi', $_POST)){
    if( empty($_POST['provincia']) ){$error[] = 'provincia';}else{$costo_spedizione['provincia']=$_POST['provincia'];}
    if( !array_key_exists('da_peso', $_POST) ){$error[] = 'da_peso';}else{$costo_spedizione['da_peso']= empty($_POST['da_peso']) ? 0 : $_POST['da_peso'];}
    if( !array_key_exists('a_peso', $_POST) ){$error[] = 'a_peso';}else{$costo_spedizione['a_peso'] = empty($_POST['a_peso']) ? 0 : $_POST['a_peso'];}
    if( empty($_POST['costo']) ){$error[] = 'costo';}else{$costo_spedizione['costo']=$_POST['costo'];}
    
    if( count($error) == 0 ){
        if( !$managerSql->modifica_costo_corriere_exp($costo_spedizione) ){
            header('Location: error.php?code=28');
            exit();
        }
        $salvataggio_completato=1;

        //azzera campi
        $costo_spedizione['provincia'] = '';
        $costo_spedizione['da_peso'] = '';
        $costo_spedizione['a_peso'] = '';
        $costo_spedizione['costo'] = '';
    }
}


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ADMIN | EDITA & GESTISCI CORRIERE</title>
<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
</head>

<body>    
	<div class="page">
        <h1>Modifica del costo del corriere espresso</h1>
        
        <?php
        
        if( count($error) ){
            $campi = implode(', ', $error);
            echo "<p>Compilare correttamente i campi: $campi</p>";
        }
        
        if($salvataggio_completato){
            echo '<p>I dati sono stati salvati con successo!</p>';
        }
        ?>
        
        <table cellpadding="0" cellspacing="0" >
            <tr>
                <th>PROVINCIA</th>
                <th>DA PESO</th>
                <th>A PESO</th>
                <th>COSTO</th>
                <th>&nbsp;</th>
            </tr>
            <?php
            $costi = $managerSql->lista_costi_corriere_exp();
            foreach ($costi as $costo) {
                echo "<tr>
                        <td>{$costo['provincia']}</td>
                        <td>{$costo['da_peso']}</td>
                        <td>{$costo['a_peso']}</td>
                        <td>{$costo['costo']}</td>
                        <td><a href=\"del_costo_corriere_exp.php?p={$costo['provincia']}&da={$costo['da_peso']}&a={$costo['a_peso']}\">Elimina</a></td>
                     </tr>";
            }
            ?>
        </table>
        
        <form name="corriere_espresso" id="corriere_espresso" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
            <p>Provincia: <input type="text" name="provincia" id="provincia" value="<?php echo htmlspecialchars($costo_spedizione['provincia']); ?>" /> <br/>
                da peso: <input type="text" name="da_peso" id="da_peso" value="<?php echo htmlspecialchars($costo_spedizione['da_peso']); ?>" /> <br/>
                a peso: <input type="text" name="a_peso" id="a_peso" value="<?php echo htmlspecialchars($costo_spedizione['a_peso']); ?>" /> <br/>
                costo/variazione: <input type="text" name="costo" id="costo" value="<?php echo htmlspecialchars($costo_spedizione['costo']); ?>" /> <br/>
                <input type="submit" name="aggiungi" id="aggiungi" value="aggiungi" />
            </p>
        </form>
  		<br /><a style="font-size:10px; color:#999;" href="admin.php">&lt;&lt; Torna al pannello principale</a>
            
    </body>
</html>
