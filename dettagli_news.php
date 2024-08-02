<?php

include_once 'common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

if( empty($_GET['id']) ){
    header('Location: error.php?code=1');
    exit();
}

$news = $managerSql->get_news($_GET['id']);
if( empty ($news) ){
    header('Location: error.php?code=40');
    exit();
}

?>

<p>Dettagli News</p>


<?php
if(file_exists("images/img_news/{$news['id_news']}.png") ){
    $src_img = "images/img_news/{$news['id_news']}.png";
}else{
    $src_img = "images/no_image.gif";
}
?>
<p><img src="<?php echo $src_img; ?>"/></p>

<p>Titolo: <?php echo $news['titolo']; ?> </p>

<?php 
$news['descrizione'] = str_replace("\n", "<br/>", $news['descrizione']);
echo $news['descrizione']; ?>

