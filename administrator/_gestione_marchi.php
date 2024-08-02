<?php
include_once '../common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

include 'verifica_admin.php';

$pagina_attuale = 0;
$inizio = 0;
$marchi_per_pagina = 5;

if( array_key_exists('pagina', $_GET) ){
    $pagina_attuale = $_GET['pagina'];
    $inizio = $marchi_per_pagina * $pagina_attuale;
}

$txt_cerca = NULL;
if(array_key_exists('cerca', $_GET)){
    $txt_cerca = $_GET['txt_cerca'];
}

$marchi = $managerSql->lista_marchi($txt_cerca);
$num_marchi = count($marchi);
if( ($num_marchi>0) && ($inizio>=$num_marchi) )
    exit("accesso ad area non consentito... numero pagina non valido");
$marchi = $managerSql->lista_marchi($txt_cerca, $inizio, $marchi_per_pagina);


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Gestione Marchi</title>
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
<p>Lista Marchi</p>

<form name="form_cerca" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    Ricerca: 
    <input type="text" name="txt_cerca" id="valore" size="25"/>
    <input type="submit" name="cerca" value="Cerca" />
</form>


<table border="1">
  <tr>
    <th>ID</th>
    <th>Nome</th>
    <th>Modifica</th>
    <th>Elimina</th>
  </tr>
    
<?php
    for($i=0; $i<count($marchi); $i++){
        $marchio = $marchi[$i];
        echo "<tr>
                <td>{$marchio['id_marchio']}</td>
                <td>{$marchio['nome']}</td>
                <td><a href=\"edit_marchio.php?id={$marchio['id_marchio']}\">Modifica</a></td>
                <td><a href=\"del_marchio.php?id={$marchio['id_marchio']}\" onclick=\"javascript: return confirm('Sei sicuro di voler eliminare il marchio?');\">Elimina</a></td>
              </tr>";
    }
    
?>
    
</table>


<?php
    //verifico che sia necessario visualizzare il menù delle pagine
    if ( $num_marchi>0 && $num_marchi>$marchi_per_pagina ){
        echo 'Pagine:<select name="pagine" id="pagine" onchange="javascript: cambia_pagina();" >';
        $pagina=0;
        while($num_marchi>0){ //ciclo per creare una 'option' per ogni pagina
            echo "<option value=\"$pagina\"";
            if( $pagina_attuale==$pagina){
                echo 'selected="selected"'; //verrà selezionata la pagina attuale
            }
            echo " >$pagina</option>";
            $num_marchi -= $marchi_per_pagina;
            $pagina++;
        }
        echo '</select>';
    }
?>


</body>
</html>