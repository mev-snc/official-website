<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

  <head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>MEV snc </title>
	<link rel="stylesheet" type="text/css" href="style.css" media="screen" />

  </head>
  <!---header--->
	<?php include("#header.php");?>

   <div id="topcontent" style="margin-top:-70px;"></div>
	<div id="content"><br />
		<div id="wrap">

			<div id="page">

				<?php
				include_once 'common/dbmanager.php';
				if(empty($managerSql)){
					 $managerSql = new dbManager();
				}
				
				if(!isset ($_SESSION)){
					 session_start();
				}
				
				if( !empty($_SESSION['id_utente']) && !empty($_SESSION['username']) ){
					 if ( $managerSql->get_utente($_SESSION['id_utente'], NULL, NULL ) ){
						  echo '<p>Sei già loggato...<a href="logout.php">LogOut</a>';
						  exit();
					 }
				}
				
				$error=0;
				if(!empty($_POST['username']) && !empty($_POST['password'])){
					 $utente = $managerSql->get_utente( NULL, $_POST['username'], $_POST['password']);
					 if($utente){        
						  //session_unset();
						  //elimina solo le variabili login utente e login admin
						  if( isset ($_SESSION['id_utente']) ) unset ($_SESSION['id_utente']);
						  if( isset ($_SESSION['username']) ) unset ($_SESSION['username']);
						  if( isset ($_SESSION['adm_mode']) ) unset ($_SESSION['adm_mode']);
						  
						  $_SESSION['id_utente'] = $utente['id_utente'];
						  $_SESSION['username'] = $utente['username'];
						  /*
						  echo '<p>Login avvenuto con successo<br/>'
						  .'<a href="index.php">Torna alla home</a></p>';
							* */
						  header('Location: shop.php');
						  exit();
					 }else{
						  $error=1;
					 }
				}
				
				?>
					 
					 <?php
						  if($error){
								echo "<p style='text-decoration:blink; border:#900 3px solid; background:#333; padding:5px 5px 5px 5px; font-family:Arial, Helvetica, sans-serif; font-size:16px; color:#F00; width:78%; margin:50px auto -70px auto;'>Dati non corrispondenti<br/>Ritentare il login</p>";
						  }
					 ?>



			<div style="border:2px solid #999; background:#CCC; margin:90px 90px 90px 90px;">   
					<h1 style="margin-left:130px;">Login Utente</h1>
					<div id="addutentetabledivcage">
						<div style="padding:50px 150px 50px 150px;">
						<form id="form1" method="post" action="">
						  Username
						  <input name="username" type="text" id="username" size="40" maxlength="45" />
						  <br />
						  Password
						  <input name="password" type="password" id="password" size="40" maxlength="45" />
						  <br />
						  <input type="submit" name="entra" id="entra" value="Entra" />
						</form>
						</div>
					</div>
				</div>
				<h2 style="margin-top:-60px;"><a href="shop.php?l=recupera_pwd" >Recupera password</a></h2>
			</div>

			<!---sidebar--->
			<?php include("#sidebar.php");?>

			<div style="clear:both;"></div>

		</div>
	</div>

	<div style="clear:both;"></div>
	<!---footer--->
	<?php include("#footer.php");?>

  </body>

</html>