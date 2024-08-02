<?php

include_once 'common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}
include_once 'common/load_login_user.php';
//include_once 'common/prezzo_netto_by_group.php';

$id_categoria = 0;
$categoria_attuale = array();
if(!empty($_GET['cat'])){ 
    $id_categoria = $_GET['cat']; 
    $categoria_attuale = $managerSql->get_categoria($id_categoria);
    if(!$categoria_attuale){
        header('Location: error.php?code=17');
        exit();
    }
    $categoria_attuale['descrizione'] = str_replace("\n", "<br/>", $categoria_attuale['descrizione']);
}




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

$prodotti = $managerSql->lista_prodotti_by_categoria( $id_categoria, $txt_cerca);
$num_prodotti = count($prodotti);
if( ($num_prodotti>0) && ($inizio>=$num_prodotti) )
    exit("accesso ad area non consentito... numero pagina non valido");
$prodotti = $managerSql->lista_prodotti_by_categoria($id_categoria,$txt_cerca,$inizio, $prodotti_per_pagina);

?>




<script type="text/javascript">
    function cambia_pagina(){
        txt_cerca = "<?php echo $txt_cerca; ?>";
        if(txt_cerca!=""){
            txt_cerca = "&cerca="+txt_cerca;
        }
        elemReg = document.getElementById('pagine');
        pagina = elemReg[elemReg.selectedIndex].value;
        window.location.href ="<?php echo "shop.php?l=visualizza_categorie&cat=$id_categoria&pagina="; ?>"+pagina+txt_cerca;
    }
</script>
    
<?php 

if(!empty($categoria_attuale) ){
    echo "<h1>{$categoria_attuale['nome']}</h1>";
    echo "<p style='margin-top:-20px;'>{$categoria_attuale['descrizione']}</p>";
}


include_once 'common/recursive_categorie.php';
$categorie = $managerSql->lista_categorie($id_categoria);
foreach ($categorie as $categoria) {
    $img = '';
    if(file_exists("images/img_categorie/{$categoria['id_categoria']}.png")){
        $img ="<div class='catimage'><img src=\"images/img_categorie/{$categoria['id_categoria']}.png\" /></div>";
    }
    echo "<div style=\"float: left\">
                    $img<br/>
                   <h2 style='padding:10px 0px 10px 0px; margin-top: -16px; margin:2px 2px 2px 2px; max-width:203px; width:203px; background:#CCC; border-radius: 5px; -moz-border-radius: 5px;  -webkit-border-radius: 5px;  border: 0px solid #000000;'><a style='color:#000; margin:0 5px 0 5px;' href=\"shop.php?l=visualizza_categorie&cat={$categoria['id_categoria']}\">{$categoria['nome']}</a></h2>
         </div>";
}

?> 


<?php
if(!empty($prodotti)){
    echo "<h2>Prodotti</h2>";
    
    for($i=0; $i<count($prodotti); $i++){
        $prodotto = $prodotti[$i];

        //$prodotto['prezzo_netto'] = get_prezzo_netto_by_group($prodotto, $utente);

        $iva = $managerSql->get_aliquota_iva($prodotto['id_aliquota_iva']);
        $txt_iva = '( IVA esclusa )';
        if($prodotto['promo']){
            $prodotto['prezzo_netto'] -= $prodotto['prezzo_netto']*$prodotto['sconto_promo'];
        }
        if($prodotto['iva_inclusa'] == 'SI' ){
            $prodotto['prezzo_netto'] += $prodotto['prezzo_netto']*$iva['aliquota'];
            $txt_iva = '( IVA inclusa )';
        }
        $iva['aliquota'] *= 100;
        $prodotto['sconto_promo'] *= 100;
        $promo = ($prodotto['promo'])? "Il prodotto è in promozione al {$prodotto['sconto_promo']} %<br/>": '';
        echo "<div style='float:left; padding:10px 0px 10px 0px; margin-top: -16px; margin:2px 2px 2px 2px; max-width:203px; width:203px; background:#CCC; border-radius: 5px; -moz-border-radius: 5px;  -webkit-border-radius: 5px;  border: 0px solid #000000;'><p>Nome: <a href=\"shop.php?l=dettagli_prodotto&id={$prodotto['id_prodotto']}&cat={$prodotto['id_categoria']}\">{$prodotto['nome']}</a><br/>
              Descrizione:
				  <br />
					{$prodotto['descrizione_breve']}
					<br/>
              $promo
              Prezzo: {$prodotto['prezzo_netto']} $txt_iva <br/>
              Iva: {$iva['aliquota']} %
            </p></div>";
    }

}
?>

<div style="clear:both;"></div>

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
