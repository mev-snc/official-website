<?php

session_start();

if(array_key_exists('login', $_POST) && !empty( $_POST['password'] ) ){
    include_once '../common/dbmanager.php';

    $managerSql = new dbManager();
    if ( $managerSql->verifica_pwd_admin($_POST['password']) ){
        session_unset();
        $_SESSION['adm_mode'] = 'ON';
    }else{
        session_destroy();
        header('Location: error.php?code=16');
        exit();
    }
}


if( !empty($_SESSION['adm_mode']) && ($_SESSION['adm_mode']=='ON') ){
    header('Location: admin.php');
    exit();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PANNELLO ADMIN | ACCESSO</title>
<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
</head>

<body>    
	<div class="page">
	<h1>Login Amministrazione </h1>

			<div class="box">
				<form id="form1" method="post" action="">
				  <p> Password <br /> <input style="width:900px !important;" type="password" name="password" id="password" /> </p>
				  <p> <input style="width:910px !important;" type="submit" name="login" id="login" value="LogIn" /> </p>
				</form>
			<p><a href="../index.php">Torna alla home Utente</a> </p>
			</div>

</body>
</html>