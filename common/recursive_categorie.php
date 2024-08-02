<?php

function print_option_categorie( $managerSql , $id_categoria_padre, $livello, $id_categoria_selected=0){
    $spaziatore='';
    for($s=0;$s<$livello;$s++){$spaziatore.='_';}
    $categorie = $managerSql->lista_categorie($id_categoria_padre);
    for($i=0; $i<count($categorie); $i++){
        $categoria = $categorie[$i];
        $categoria['nome'] = htmlspecialchars($categoria['nome']);
        $selected = ($categoria['id_categoria']==$id_categoria_selected) ? ' selected="selected" ' : '';
        echo "<option value=\"{$categoria['id_categoria']}\" $selected >$spaziatore [$livello] - {$categoria['nome']}</option>";
        print_option_categorie($managerSql, $categoria['id_categoria'], $livello+1, $id_categoria_selected);
    }
}



function print_menu_categorie( $managerSql , $id_categoria_padre, $livello, $id_categoria_selected=0 ){
    $colors = array();
    $colors[0] = "lavender";
    $colors[1] = "lightcyan";
    $spaziatore='';
    $espandi_div = false;
    
    for($s=0;$s<$livello;$s++){$spaziatore.='_';}
    
    $categorie = $managerSql->lista_categorie($id_categoria_padre);
    $hide = ($id_categoria_padre) ? ' display: none; ' : '';
    
    $indice_colore = $livello%2;
    echo "<div style=\"background-color: {$colors[$indice_colore]}; $hide\" id=\"sub_cat_menu_{$id_categoria_padre}\" >";
    for($i=0; $i<count($categorie); $i++){
        $categoria = $categorie[$i];
        $selected = '';
        if($id_categoria_selected == $categoria['id_categoria']) {
            $selected =  '(*)';
            $espandi_div = true;
        }
        
        $categoria['nome'] = htmlspecialchars($categoria['nome']);
        echo "<a href=\"shop.php?l=visualizza_categorie&cat={$categoria['id_categoria']}\">$spaziatore [$livello] - {$categoria['nome']}</a> $selected ";
        if( $managerSql->lista_categorie($categoria['id_categoria']) ){
            echo "<span onclick=\"javascript: show_hide_submenu({$categoria['id_categoria']});\" >[ + ]</span>";
        }
        echo "<br/>";
        $espandi_div = $espandi_div | print_menu_categorie($managerSql, $categoria['id_categoria'], ($livello+1), $id_categoria_selected);
    }
    echo "</div>";
    
    if($espandi_div){
        echo "<script type=\"text/javascript\">
                  $(document).ready(function(){
                      $('#sub_cat_menu_{$id_categoria_padre}').fadeIn(0, null);
                  });
              </script>";
    }
    
    return $espandi_div ;
}

?>
