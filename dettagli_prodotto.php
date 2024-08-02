<?php

include_once 'common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}
include_once 'common/load_login_user.php';
//include_once 'common/prezzo_netto_by_group.php';

if( empty($_GET['id']) ){
    header('Location: error.php?code=1');
    exit();
}

$prodotto = $managerSql->get_prodotto($_GET['id']);
if( empty ($prodotto) ){
    header('Location: error.php?code=15');
    exit();
}

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
?>

<script type="text/javascript" src="common/jquery-1.6.2.min.js"></script>
<script type="text/javascript">

function aggiorna_qta_disponibile(){
    id_prodotto = <?php echo $prodotto['id_prodotto']; ?> ;
    num_proprieta = $('select[name="proprieta[]"]').length;
    proprieta= new Array(num_proprieta);
    $.each($('select[name="proprieta[]"]'), function(i,value){
        //alert($(value).val());
        proprieta[i] = $(value).val();
    });
    proprieta = proprieta.join('|');
    //alert(proprieta);
    $('#qta_disponibile').load('ajax_query.php', {'richiesta':'get_qta_disponibile', 'id_prodotto':id_prodotto, 'proprieta':proprieta}, null);
}

$(document).ready(function(){
    aggiorna_qta_disponibile();
});
</script>


<h2>Dettagli Prodotto</h2>

<form method="post" action="add_carrello.php" >

<?php
if(file_exists("images/img_prodotti/{$prodotto['id_prodotto']}.png") ){
    $src_img = "images/img_prodotti/{$prodotto['id_prodotto']}.png";
}else{
    $src_img = "images/no_image.gif";
}
?>
<img style="margin-left:20px;float:left; width:400px; max-width:400px; min-width:400px;" src="<?php echo $src_img; ?>"/>

<input type="hidden" name="id_prodotto" value="<?php echo $prodotto['id_prodotto']; ?>" />
<div style="margin-left:420px;">
<h2><?php echo $prodotto['nome']; ?> </h2>
<p style="margin-top:-20px;">
	<?php if(!empty($promo)){ echo "<p>$promo</p>"; } ?>
	Prezzo: <?php echo $prodotto['prezzo_netto'] . $txt_iva; ?><br />
	Iva: <?php echo $iva['aliquota']; ?> %
</p>
<h2 style="margin-top:-20px;">Descrizione</h2>
<p style="margin-top:-20px;">
	<?php echo $prodotto['descrizione']; ?>
</p>

<h2 style="margin-top:-20px;">Attributi</h2>
<p style="margin-top:-20px;">
<?php
//attributi e proprietà
$attributi = $managerSql->lista_attributi_by_prodotto($prodotto['id_prodotto']);
for($i=0; $i<count($attributi); $i++){
    $attributo = $attributi[$i];
    echo "<p>{$attributo['nome']} <select name=\"proprieta[]\" onchange=\"javascript: aggiorna_qta_disponibile()\" >";

    $lista_proprieta = $managerSql->lista_proprieta_by_attributo($attributo['id_attributo']);
    for($j=0; $j<count($lista_proprieta); $j++){
        $proprieta = $lista_proprieta[$j];
        $variazione = '';
        $simbolo='';
        if( !empty ($proprieta['variazione_prezzo']) ){
            if( $proprieta['variazione_prezzo']>0){
                $simbolo = '+';
            }
            $variazione = " ( $simbolo {$proprieta['variazione_prezzo']} ) ";
        }
        echo "<option value=\"{$proprieta['id_proprieta']}\">{$proprieta['valore']} $variazione ";
    }

    echo "</select></p>";
}

?>


<!-- Quantità disponibile -->
<span id="qta_disponibile"></span> &nbsp;&nbsp;&nbsp;

<!-- Quantità da acquistare-->
<?php
//T testo
//S select
//N nessuna

switch ($prodotto['tipo_quantita']) {
    case 'T':
        echo 'Quantità: <input type="text" value="1" name="qta" size="10" />';
        break;

    case 'S':
        echo "Quantità: <select name=\"qta\"> ";
        for($i=$prodotto['quantita_select_inizio']; $i<=$prodotto['quantita_select_fine']; $i+=$prodotto['quantita_select_incremento']){
            echo "<option>$i</option>";
        }
        echo "</select>";
    default:
        break;
}
?>
<div style="margin-top:-40px;">
<?php
if( !empty($prodotto['tempo_disponibilita']) ){
    echo "<p>Tempo di disponibilità: {$prodotto['tempo_disponibilita']} </p>";
}
?>
<p> <input class="inviastandard" style="cursor:pointer;" type="submit" name="aggiungi" value="Aggiungi al carrello" /> </p>

</div>
</p>
</div>

</form>