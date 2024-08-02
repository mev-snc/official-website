<?php
include_once '../common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

include 'verifica_admin.php';

$pagina_attuale = 0;
$inizio = 0;
$fornitori_per_pagina = 5;
/*
if(!array_key_exists('cerca', $_POST) || empty($_POST['txt_cerca']) ){
    $num_utenti = $managerSql->get_num_fornitori();
    if( array_key_exists('pagina', $_GET) ){
        $pagina_attuale = $_GET['pagina'];
        $inizio = $utenti_per_pagina * $pagina_attuale;
        if( $inizio>=$num_utenti)
            exit("accesso ad area non consentito... numero pagina non valido");
    }
}*/
if( array_key_exists('pagina', $_GET) ){
    $pagina_attuale = $_GET['pagina'];
    $inizio = $fornitori_per_pagina * $pagina_attuale;
}

$txt_cerca = NULL;
if(array_key_exists('cerca', $_GET)){
    $txt_cerca = $_GET['txt_cerca'];
}

$fornitori = $managerSql->lista_fornitori($txt_cerca);
$num_fornitori = count($fornitori);
if( ($num_fornitori>0) && ($inizio>=$num_fornitori) )
    exit("accesso ad area non consentito... numero pagina non valido");
$fornitori = $managerSql->lista_fornitori($txt_cerca, $inizio, $fornitori_per_pagina);


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Gestione Fornitori</title>
<script type="text/javascript">
    function cambia_pagina(){
        txt_cerca = "<?php echo $txt_cerca; ?>";
        if(txt_cerca!=""){
            txt_cerca = "&cerca="+txt_cerca;
        }
        elemReg = document.getElementById('pagine');
        pagina = elemReg[elemReg.selectedIndex].value;
        window.location.href ="<?php echo "{$_SERVER['PHP_SELF']}?pagina="; ?>"+pagina+txt_cerca;
    }
</script>
</head>

<body>
<p>Lista Fornitori</p>

<form name="form_cerca" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    Ricerca: 
    <input type="text" name="txt_cerca" id="valore" size="25"/>
    <input type="submit" name="cerca" value="Cerca" />
</form>


<table border="1">
  <tr>
    <th>ID</th>
    <th>Denominazione</th>
    <th>Provincia</th>
    <th>Email</th>
    <th>Modifica</th>
    <th>Elimina</th>
  </tr>
    
<?php
    for($i=0; $i<count($fornitori); $i++){
        $fornitore = $fornitori[$i];
        echo "<tr>
                <td>{$fornitore['id_fornitore']}</td>
                <td>{$fornitore['denominazione']}</td>
                <td>{$fornitore['provincia']}</td>
                <td>{$fornitore['email']}</td>
                <td><a href=\"edit_fornitore.php?id={$fornitore['id_fornitore']}\">Modifica</a></td>
                <td><a href=\"del_fornitore.php?id={$fornitore['id_fornitore']}\" onclick=\"javascript: return confirm('Sei sicuro di voler eliminare il fornitore?');\">Elimina</a></td>
              </tr>";
    }
    
?>
    
</table>


<?php
    //verifico che sia necessario visualizzare il menù delle pagine
    if ( $num_fornitori>0 && $num_fornitori>$fornitori_per_pagina ){
        echo 'Pagine:<select name="pagine" id="pagine" onchange="javascript: cambia_pagina();" >';
        $pagina=0;
        while($num_fornitori>0){ //ciclo per creare una 'option' per ogni pagina
            echo "<option value=\"$pagina\"";
            if( $pagina_attuale==$pagina){
                echo 'selected="selected"'; //verrà selezionata la pagina attuale
            }
            echo " >$pagina</option>";
            $num_fornitori -= $fornitori_per_pagina;
            $pagina++;
        }
        echo '</select>';
    }
?>


</body>
</html>