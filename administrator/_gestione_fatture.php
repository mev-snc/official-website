<?php

include_once '../common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lista Fatture</title>
</head>

    <body>
        <p>Lista fatture</p>
        
        <table border="1">
            <tr>
                <th>N°FATTURA</th>
                <th>DEL</th>
                <th>INTESTAZIONE</th>
                <th>ID ORDINE</th>
                <th>&nbsp;</th>
            </tr>
            <?php
            $fatture = $managerSql->lista_fatture();
            foreach ($fatture as $fattura) {
                echo "<tr>
                        <td>{$fattura['num_fattura']}</td>
                        <td>{$fattura['data']}</td>
                        <td>{$fattura['intestazione']}</td>
                        <td>{$fattura['id_ordine']}</td>
                        <td><a href=\"edit_fattura.php?num={$fattura['num_fattura']}&anno={$fattura['anno']}\">Modifica</a></td>
                    </tr>";
            }

            ?>
        </table>
    
    </body>
</html>