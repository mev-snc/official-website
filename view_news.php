<?php
include_once 'common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

$pagina_attuale = 0;
$inizio = 0;
$elementi_per_pagina = 3;
if( array_key_exists('pagina', $_GET) ){
        $pagina_attuale = $_GET['pagina'];
        $inizio = $elementi_per_pagina * $pagina_attuale;
}

$news = $managerSql->lista_news();
$num_elementi = count($news);
if( ($num_elementi>0) && ($inizio>=$num_elementi) )
    exit("Accesso ad area non consentito... Numero pagina non valido");
$news = $managerSql->lista_news( $inizio, $elementi_per_pagina );

?>

<script type="text/javascript">
    function cambia_pagina(){
        elemReg = document.getElementById('pagine');
        pagina = elemReg[elemReg.selectedIndex].value;
        window.location.href ="<?php echo "index.php?l=view_news&pagina="; ?>"+pagina;
    }
</script>

<p>Lista news</p>
<table border="1">
  <tr>
    <th>ID</th>
    <th>Immagine</th>
    <th>Titolo</th>
    <th>Descrizione</th>
    <th>&nbsp;</th>
  </tr>
    
    <?php
    foreach ($news as $riga) {
        $riga['descrizione'] = str_replace("\n", "<br/>", substr($riga['descrizione'], 0, 200) );
        echo "<tr>
                <td>{$riga['id_news']}</td>
                <td><img src=\"images/img_news/{$riga['id_news']}.png\"/></td>
                <td>{$riga['titolo']}</td>
                <td>{$riga['descrizione']} ...</td>
                <td><a href=\"index.php?l=dettagli_news&id={$riga['id_news']}\">Dettaglio</a></td>
            </tr>";
    }
    
    ?>

</table>



<?php
//verifico che sia necessario visualizzare il menù delle pagine
if ( $num_elementi>0 && $num_elementi>$elementi_per_pagina ){
    echo 'Pagine:<select name="pagine" id="pagine" onchange="javascript: cambia_pagina();" >';
    $pagina=0;
    while($num_elementi>0){ //ciclo per creare una 'option' per ogni pagina
        echo "<option value=\"$pagina\"";
        if( $pagina_attuale==$pagina){
            echo 'selected="selected"'; //verrà selezionata la pagina attuale
        }
        echo " >$pagina</option>";
        $num_elementi -= $elementi_per_pagina;
        $pagina++;
    }
    echo '</select>';
}
?>