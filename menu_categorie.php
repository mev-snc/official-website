<script type="text/javascript" src="common/jquery-1.6.2.min.js"></script>
<script type="text/javascript">
    function show_hide_submenu(indice){
        if( $('#sub_cat_menu_'+indice).is(':visible') ){
            $('#sub_cat_menu_'+indice).fadeOut(400, null);
        }else{
        $('#sub_cat_menu_'+indice).fadeIn(400, null);
        }
    }
</script>

<?php

include_once 'common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

$id_categoria = 0;
if(!empty($_GET['cat'])){ $id_categoria = $_GET['cat']; }

include_once 'common/recursive_categorie.php';
print_menu_categorie($managerSql, 0, 0, $id_categoria);

?>
