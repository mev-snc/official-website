<?php

$salvataggio_completato = 0;
$error=array();

$messaggio = array();
$messaggio['nome'] = '';
$messaggio['cognome'] = '';
$messaggio['telefono'] = '';
$messaggio['email'] = '';
$messaggio['testo'] = '';


if( array_key_exists('invia', $_POST) ){
    include 'common/mail/mail.php';
    
    if( empty ($_POST['nome']) ){ $error[] = 'nome'; }else{ $messaggio['nome'] = $_POST['nome']; }
    if( empty ($_POST['cognome']) ){ $error[] = 'cognome'; }else{ $messaggio['cognome'] = $_POST['cognome']; }
    if( empty ($_POST['telefono']) ){ $error[] = 'telefono'; }else{ $messaggio['telefono'] = $_POST['telefono']; }
    if( empty ($_POST['email']) ){ $error[] = 'email'; }else{ $messaggio['email'] = $_POST['email']; }
    if( empty ($_POST['testo']) ){ $error[] = 'testo'; }else{ $messaggio['testo'] = $_POST['testo']; }
    
    if( !count($error) ){
        
        $txt = "<p>Sei stato contattato da {$messaggio['cognome']} {$messaggio['nome']}<br/>";
        $txt .= "Email: {$messaggio['email']} <br/>";
        $txt .= "Telefono: {$messaggio['telefono']} </p>";
        $txt .= "<p>Messaggio: <br/>";
        $txt .= str_replace("\n", "<br/>", $messaggio['testo']);
        $txt .= "</p>";
        
        if ( invia_mail($txt, 'Contatto dal sito', 'MIAMAIL@MIOSERVER.com', 'MIAMAIL@MIOSERVER.com') ){
            $messaggio['nome'] = $messaggio['cognome'] = $messaggio['telefono'] = $messaggio['email'] = $messaggio['testo'] = '';
            $salvataggio_completato = 1;
        }
    }
    
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

  <head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>MEV snc </title>
	<link rel="stylesheet" type="text/css" href="style.css" media="screen" />

  </head>
  <body>

	<!---header--->
	<?php include("#header.php");?>

	<div id="topcontent" style="margin-top:-70px;"></div>
	<div id="content"><br />
		<div id="wrap">

			<div id="page">

				<img src="image/headpage_contattaci.png" />
				<!--<img src="image/mevimage2.jpg" style="float:left; margin:23px 15px 10px 20px;" />-->
				<h1>BENVENUTI ALLA MEV snc</h1>
				<p>
				Benvenuti alla Mev snc. Siamo l'azienda che realizza prodotti in legno per la tua casa come i gazebi o le tettoie, le fioriere e le panche ed altro come le grondaie in rame, alluminio, preverniciato e altro ancora...<br /><br />
				Ci trovi a <i>Battipaglia</i> (SA), in <i>Via Como al numero 10B</i>. Vieni a trovarci e scopri di persona tutto quello che qui sul nostro sito puoi trovare, e anche di più!<br /><br />
				Restiamo sempre disponibili, per qualsiasi cosa usufruire del servizio mail qui di seguito:
				</p>
				<!--modulo contatto-->
				<div class="contactform">
					<?php
						if( count($error) ){
							$campi = implode(', ', $error);
							echo "
								
								<p class=\"alarm\">
									<font style=\"text-decoration:blink;\">ATTENZIONE - MESSAGGIO NON INVIATO</font>
									<br/><br/>
									Compilare correttamente i campi:
									<br/><br/>
									<font style=\"font-size:18px;\"> $campi </font>
								</p>
								
								";
						}
					?>
					<style>
                         /*FORM*/
                         .alarm{
                           position:					absolute;
                           top:						47px;
                           margin-left:					320px;
                           width:						730px;
                           background:					#CCC;
                           border:						#F00 dotted 3px;
                           font-family:					"Arial Black", Gadget, sans-serif;
                           font-weight:					bold;
                           color:						#F00;
                         }
                         .form_table{
                           padding:					15px 15px 5px 15px;
                         }
                         .form_table tr td{
                           width:						270px;
                         }
                         .textbox {
                           width:						400px;
                           padding:					2px 10px 2px 10px;
                           height: 					auto;
                           float:						left;
                           border:						#666 solid 1px;
                           background:					#CCC;
                           text-align:					left;
                           color:						#000;
                         }
                         .textareabox {
                           width:						828px;
                           max-width:					828px;
                           min-height:					300px;
                           border:						#666 solid 1px;
                           padding:					10px 10px 10px 10px;
                           float:						right;
                           background:					#CCC;
                           text-align:					left;
                           color:						#000;
                         }
                         .resetbutton {
                           margin-left:					17px;
                           border:						1px solid #999;
                           background:					#CCC;
                           font-family:					Tahoma, Geneva, sans-serif;
                           font-size:					14px;
                           font-weight:					bold;
                           color:						#000;
                         }.resetbutton:hover				{ background:#000; color:#F90;}
                         .submitbutton {
                           border:						1px solid #999;
                           background:					#CCC;
                           font-family:					Tahoma, Geneva, sans-serif;
                           font-size:					14px;
                           font-weight:					bold;
                           color:						#000;
                         }.submitbutton:hover			{ background:#000; color:#F90;}
                         </style>
					<form name="frm_mail" action="" method="POST">
						<table class="form_table">
							<tr>
								<td>NOME:&nbsp;			<input type="text" class="textbox" name="nome" id="nome" value="<?php echo htmlspecialchars($messaggio['nome']); ?>" /></td>
								<td>&nbsp;&nbsp;COGNOME:		<input type="text" class="textbox" name="cognome" id="cognome" value="<?php echo htmlspecialchars($messaggio['cognome']); ?>" /></td>
							</tr>
							<tr>
								<td>EMAIL:&nbsp;			<input type="text" class="textbox" name="email" id="email" value="<?php echo htmlspecialchars($messaggio['email']); ?>" /></td>
								<td>&nbsp;&nbsp;TELEFONO:	<input type="text" class="textbox" name="telefono" id="telefono" value="<?php echo htmlspecialchars($messaggio['telefono']); ?>" /></td>
							</tr>
							<tr>
								<td colspan="2">
									MESSAGGIO<br />
									<textarea class="textareabox" name="testo" id="testo" ><?php echo htmlspecialchars($messaggio['testo']); ?></textarea>
								</td>
							</tr>
						</table>	
						<input type="reset" value="Resetta da capo" class="resetbutton" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input alue="Ivia il messaggio" type="submit" class="submitbutton" name="invia" id="invia" />
					</form><br />
				</div>
				<!--/modulo contatto-->

			</div>

			<!---sidebar--->
			<?php include("#sidebar.php");?>

			<div style="clear:both;"></div>

		</div>
	</div>	

	<!---footer--->
	<?php include("#footer.php");?>

  </body>

</html>
