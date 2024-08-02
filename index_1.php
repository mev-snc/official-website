<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento senza titolo</title>

<link href="common/template/layout.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="layout">
    <div id="header">Inserire qui il contenuto per  id "header"</div>
    
    <div id="left">
        <?php include 'menu_categorie.php'; ?>
        
        <div><?php include 'mini_login.php'; ?></div>
        
        <div>
            <?php include 'mini_cart.php'; ?>
        </div>
        
        <div>
            <?php include 'mini_search.php'; ?>
        </div>
        
        <div><p><a href="index.php?l=view_news">News</a></p></div>
        
    </div>
    
    <div id="content">
        <?php 
        $pagina = 'home';
        if(!empty($_GET['l'])){
            $pagina = $_GET['l'];
        }

        if (!isset ($pagina) || !file_exists($pagina . '.php')) {
            $pagina = 'home';
        }
        else {
            include $pagina . '.php';
        }
        ?>  
    </div>
    
    <div id="footer">Inserire qui il contenuto per  id "footer"</div>
</div>
</body>
</html>