<?php
require_once('class.phpmailer.php');
include_once("class.smtp.php");


function invia_mail($testo, $oggetto, $indirizzo_from, $indirizzo_to  ){

    $mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
    $mail->IsSMTP(); // telling the class to use SMTP

    try {
         //$mail->Host       = "smtp.gmail.com"; // SMTP server
	  
	  $mail->SMTPAuth  	= true;
	  $mail->Host       = "smtp.gmail.com";
	  $mail->SMTPSecure = 'ssl';
	  $mail->Port 		= 465;
	  $mail->Username 	="amaracontact@gmail.com";
	  $mail->Password 	= "amarapass";
	  

           //$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)

          $mail->AddAddress($indirizzo_to);
          $mail->SetFrom($indirizzo_from, 'amaracontact@gmail.com');
          $mail->Subject = $oggetto;
          $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!'; // optional - MsgHTML will create an alternate automatically

          $mail_txt = $testo;
    
          $mail->MsgHTML($mail_txt);
          $mail->Send();
      
      
      return true;
    } catch (phpmailerException $e) {
            echo $e->errorMessage(); //Pretty error messages from PHPMailer
            //return false;
    } catch (Exception $e) {
          echo $e->getMessage(); //Boring error messages from anything else!
          //return false;
    }

}

?>
