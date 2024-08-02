<?php

include_once 'common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

include_once 'common/load_login_user.php';
//include_once 'common/prezzo_netto_by_group.php';

$pagina_attuale = 0;
$inizio = 0;
$prodotti_per_pagina = 3;
if( array_key_exists('pagina', $_GET) ){
    $pagina_attuale = $_GET['pagina'];
    $inizio = $prodotti_per_pagina * $pagina_attuale;
}

$txt_cerca = NULL;
if(array_key_exists('cerca', $_GET)){
    $txt_cerca = $_GET['cerca'];
}

$prodotti = $managerSql->lista_prodotti( $txt_cerca );
$num_prodotti = count($prodotti);
if( ($num_prodotti>0) && ($inizio>=$num_prodotti) )
    exit("accesso ad area non consentito... numero pagina non valido");
$prodotti = $managerSql->lista_prodotti($txt_cerca, $inizio, $prodotti_per_pagina);

?>

<script type="text/javascript">
    function cambia_pagina(){
        txt_cerca = "<?php echo $txt_cerca; ?>";
        if(txt_cerca!=""){
            txt_cerca = "&cerca="+txt_cerca;
        }
        elemReg = document.getElementById('pagine');
        pagina = elemReg[elemReg.selectedIndex].value;
        window.location.href ="<?php echo "shop.php?l=cerca_prodotti&pagina="; ?>"+pagina+txt_cerca;
    }
</script>

        
<p>Prodotti</p>

<?php
if(!empty($prodotti)){
    for($i=0; $i<count($prodotti); $i++){
        $prodotto = $prodotti[$i];

        //$prodotto['prezzo_netto'] = get_prezzo_netto_by_group($prodotto, $utente);

        $iva = $managerSql->get_aliquota_iva($prodotto['id_aliquota_iva']);
        $txt_iva = '( IVA esclusa )';
        if($prodotto['iva_inclusa'] == 'SI' ){
            $prodotto['prezzo_netto'] += $prodotto['prezzo_netto']*$iva['aliquota'];
            $txt_iva = '( IVA inclusa )';
        }
        $iva['aliquota'] *= 100;
        echo "<p>Nome: <a href=\"shop.php?l=dettagli_prodotto&id={$prodotto['id_prodotto']}\">{$prodotto['nome']}</a><br/>
              Descrizione: {$prodotto['descrizione_breve']}<br/>
              Prezzo: {$prodotto['prezzo_netto']} $txt_iva <br/>
              Iva: {$iva['aliquota']} %
            </p>";
    }

}
?>


<?php
//verifico che sia necessario visualizzare il menù delle pagine
if ( $num_prodotti>0 && $num_prodotti>$prodotti_per_pagina ){
    echo 'Pagine:<select name="pagine" id="pagine" onchange="javascript: cambia_pagina();" >';
    $pagina=0;
    while($num_prodotti>0){ //ciclo per creare una 'option' per ogni pagina
        echo "<option value=\"$pagina\"";
        if( $pagina_attuale==$pagina){
            echo 'selected="selected"'; //verrà selezionata la pagina attuale
        }
        echo " >$pagina</option>";
        $num_prodotti -= $prodotti_per_pagina;
        $pagina++;
    }
    echo '</select>';
}
?>
        