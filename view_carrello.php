<?php

include_once 'common/dbmanager.php';
include_once 'common/calc_prezzo.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}
include_once 'common/load_login_user.php';
//include_once 'common/prezzo_netto_by_group.php';

?>

<h2>Il tuo carrello...</h2>
<table style="width:100%; text-align:left; padding:10px 10px 10px 10px; border:1px solid #999;">
    <tr style="width:100%;">
        <th style="background:#CCC;  padding:4px 4px 4px 4px;">Prodotto</th>
        <th style='background:#F4F4F4;  padding:4px 4px 4px 4px;'>Attributi</th>
        <th style="background:#CCC;  padding:4px 4px 4px 4px;">Prezzo Un.</th>
        <th style='background:#F4F4F4;  padding:4px 4px 4px 4px;'>Quantità / Azioni</th>
        <th style="background:#CCC;  padding:4px 4px 4px 4px;">Prezzo Totale(IVA Inc.)</th>
    </tr>

<?php
$carrello = array();
$totale = 0;
$peso = 0;
if(array_key_exists('carrello', $_SESSION)){
    $carrello = $_SESSION['carrello'];
    foreach ($carrello as $index => $riga) {
        $prodotto = $managerSql->get_prodotto($riga['id_prodotto']);
        //$prodotto['prezzo_netto'] = get_prezzo_netto_by_group($prodotto, $utente);
        $prezzo_unitario=$prodotto['prezzo_netto'];
        $peso += $prodotto['peso']*$riga['quantita'];

        $attributi='';
        $lista_proprieta = array();
        if(!empty($riga['proprieta'])){
            $lista_proprieta = explode('|', $riga['proprieta']);
        }
        for($i=0; $i<count($lista_proprieta); $i++){
            $proprieta = $managerSql->get_proprieta($lista_proprieta[$i]);
            $attributo = $managerSql->get_attributo($proprieta['id_attributo']);
            $attributi .= "{$attributo['nome']} : {$proprieta['valore']} <br/>";
            $prezzo_unitario += $proprieta['variazione_prezzo'];
        }
        if($prodotto['promo']){
            $prezzo_unitario -= $prezzo_unitario*$prodotto['sconto_promo'];
        }
        $prezzo_totale = calcola_prezzo($managerSql, $prodotto, $riga['quantita'], $lista_proprieta);
        echo "<tr>
                <td style=' padding:4px 4px 4px 4px; background:#CCC;'>{$prodotto['nome']}</td>
                <td style='  padding:4px 4px 4px 4px; background:#F4F4F4;'>$attributi</td>
                <td style='  padding:4px 4px 4px 4px; background:#CCC;'>$prezzo_unitario</td>
                <td style='  padding:4px 4px 4px 4px; background:#F4F4F4; text-align:left'>
					 		<form method=\"GET\" action=\"action_carrello.php\" >
                        <input style='float:left;' type=\"hidden\" name=\"index\" value=\"$index\" />
                        <input style='float:left;' type=\"hidden\" name=\"action\" value=\"2\" />
                        <input style='float:left;' type=\"text\" name=\"qta\" value=\"{$riga['quantita']}\" size=\"10\" />
                        <input style='float:left; cursor:pointer;' type=\"submit\" name=\"aggiorna_qta\" value=\"Aggiorna Qtà\" onclick=\"javascript: return confirm('Aggiornare la quantità del prodotto nel carrello?');\" />
                    </form>
                    &nbsp;&nbsp;<a href=\"action_carrello.php?action=1&index=$index\" onclick=\"javascript: return confirm('Sei sicuro di voler eliminare il prodotto dal carrello?');\" >Elimina</a>
                </td>
                <td style='  padding:4px 4px 4px 4px; background:#CCC;'>$prezzo_totale</td>
            </tr>";
        $totale += $prezzo_totale;
    }

}
?>
</table>

<h2 style="color:#000; float:left; margin-left:-20px; margin-right:-30px;">Totale: &nbsp;&nbsp;&nbsp;</h2><h2 style="color:#6F0; font-size:30px; font-weight:bold; margin:6px 0 0 0px;"><?php echo $totale; ?></h2>


<hr style="background:#CCC;" />
<h2>Scegli il metodo di spedizione</h2>
<form action="select_spedizione.php" method="POST">
    <?php
    include 'common/spedizioni.php';
    foreach ($spedizioni as $key => $spedizione) {
        if(!$key){ $check=' checked =\'checked\' ';}else{$check='';}
        if(empty($utente)){
            $provincia= 'TUTTE';
        }else{
            $indirizzo = $managerSql->get_indirizzo_predefinito($utente);
            if($indirizzo){
                $provincia = strtoupper($indirizzo['provincia']);
            }else{
                $provincia = strtoupper($utente['provincia']);
            }
        }
        $spedizione['costo'] = calcola_costo_spedizione($spedizione['sigla'], $provincia, $peso); //serve ad avere un costo indicativo
        echo "<p><input name=\"spedizione\" type=\"radio\" id=\"{$spedizione['sigla']}\" value=\"{$spedizione['sigla']}\" $check />{$spedizione['nome']} - € {$spedizione['costo']}</p>";
    }
    ?>
  	&nbsp;&nbsp;<input class="inviastandard" type="submit" name="prosegui" id="prosegui" value="Prosegui" style="cursor:pointer;" />
</form>
