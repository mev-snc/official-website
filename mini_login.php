<?php

include_once 'common/load_login_user.php';

if(empty($utente)){
?>

	
	 <h2 style="margin-left:-20px;">Login Utente</h2>
    <div style="border-top:#000 1px dotted;border-bottom:#000 2px dotted; padding-bottom:5px;">
    <form id="form1" method="post" action="login.php">
        Username
        <input style="background:#999; width:100%;" name="username" type="text" id="username" size="20" maxlength="45" />
        <br />
        Password
        <input style="background:#999; width:100%;" name="password" type="password" id="password" size="20" maxlength="45" />
        <br />
        <input style="background:#999; width:100%; margin-top:4px;" type="submit" name="entra" id="entra" value="Entra" />
    </form>
	 </div>
		<p style="margin-left:-20px; margin-bottom:-50px; margin-top:-10px;"><a href="shop.php?l=recupera_pwd" >Recupera password</a></p>
		<p style="margin-left:-20px;"><a href="shop.php?l=add_utente">Registrati subito</a></p>
<?php
}else{
?>
    
    <br />
	<div style="margin:15px 0 -20px 0;">
	 <i>Benvenuto... <b><?php echo $utente['username']; ?></b></i>
    <p style="margin-left:-20px; margin-bottom:-50px; margin-top:-10px;"><a href="shop.php?l=profilo">Profilo</a></p>
    <p style="margin-left:-20px;"><a href="logout.php">LogOut</a></p>
	</div>	 
    
<?php
}
?>
