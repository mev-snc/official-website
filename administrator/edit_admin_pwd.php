<?php
include_once '../common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

$error = array();

if(array_key_exists('salva', $_POST) ){
    if( empty($_POST['new_password']) ){ $error[]='new_password'; }
    
    if( !count($error) ){
        if( $managerSql->modifica_pwd_admin($_POST['new_password']) ){
            include 'logout.php';
        }else{
            header('Location: error.php?code=49');
            exit();
        }
    }
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ADMIN | CAMBIA PASSWORD</title>
<link rel="stylesheet" type="text/css" href="style.css" media="screen" />
</head>

<body>    
	<div class="page">

		<h1>Modifica Passowrd Admin</h1>

		<?php
			 if( count($error) ){
				  $campi = implode(', ', $error);
				  echo "<p>Compilare correttamente i campi: $campi</p>";
			 }
		?>
		

		<form  action="" method="post" id="form1">
		  <table class="table_admin" class="form_admin" cellspacing="0" cellpadding="0">
			 <tr>
				<td>Nuova Password </td>
				<td><input class="inviastandard" name="new_password" value="" size="40" id="new_password" type="password" /></td>
			 </tr>
		  </table>
		  <p>
			 <input type="submit" name="salva" id="salva" value="Salva modifiche" />
		  </p>
		</form>
		
		<br /><a style="font-size:10px; color:#999;" href="admin.php">&lt;&lt; Torna al pannello principale</a>

	</div>
</body>
</html>