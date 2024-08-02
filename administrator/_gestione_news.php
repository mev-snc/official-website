<?php
include_once '../common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

$news = $managerSql->lista_news();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Gestione News</title>
</head>

<body>
<p>Lista news</p>
<table border="1">
  <tr>
    <th>ID</th>
    <th>Titolo</th>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
  </tr>
    
    
    <?php
    foreach ($news as $riga) {
        echo "<tr>
                <td>{$riga['id_news']}</td>
                <td>{$riga['titolo']}</td>
                <td><a href=\"edit_news.php?id={$riga['id_news']}\" >Modifica</a></td>
                <td><a href=\"del_news.php?id={$riga['id_news']}\" onclick=\"javascript: return confirm('Sei sicuro di voler eliminare la news?');\">Elimina</a></td>
            </tr>";
    }
    
    ?>

</table>
<p>&nbsp;</p>


</body>
    
</html>