<?php
if( !isset ($_SESSION) ){
    session_start();
}

include_once 'common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

if( array_key_exists('pagamento', $_POST ) ){
    $_SESSION['pagamento'] = $_POST['pagamento'];
    header('Location: shop.php?l=carrello_completa');
    exit();
}

?>

<h2>Scegli il metodo di pagamento</h2>

<form name="frm_pagamento" action="" method="post" >
    <?php
    include 'common/pagamenti.php';
    foreach ($pagamenti as $key => $pagamento) {
        if(!$key){ $check=' checked =\'checked\' ';}else{$check='';}
        echo "<p><input type=\"radio\" name=\"pagamento\" id=\"{$pagamento['sigla']}\" value=\"{$pagamento['sigla']}\" $check />{$pagamento['nome']}</p>";
    }
    ?>

	&nbsp;&nbsp;<input class="inviastandard" type="submit" name="prosegui" id="prosegui" value="Completa Ordine" style="cursor:pointer;" />
</form>
