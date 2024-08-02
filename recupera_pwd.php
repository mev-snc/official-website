<?php

include_once 'common/dbmanager.php';
if(empty($managerSql)){
    $managerSql = new dbManager();
}

$recupero_ok = 0; //visualizza form domanda segreta

if (array_key_exists('recupera', $_POST) ) {
    
    if( empty ($_POST['domanda']) || empty ($_POST['risposta']) ){
        header('Location: error.php?code=1');
        exit();
    }
    $utenti = $managerSql->lista_utenti('username', $_POST['username']);
    if( count($utenti) != 1 ){
        header('Location: error.php?code=12');
        exit();
    }   
    $utente = $utenti[0];
    if( !strcmp($utente['domanda'], $_POST['domanda']) && !strcmp($utente['risposta'], $_POST['risposta']) ){
        //recupero avvenuto
        $recupero_ok = 1; //visualizza form nuova password
        if(!isset($_SESSION)){
            session_start();
        }
        $_SESSION['id_utente'] = $utente['id_utente'];
    }else{
        header('Location: error.php?code=12');
        exit();
    }
    
}

if (array_key_exists('imposta', $_POST) ) {
    $recupero_ok = 1; //visualizza form nuova password
    session_start();
    if( empty($_SESSION['id_utente'])){
        header('Location: error.php?code=1');
        exit();
    }
    $utente = $managerSql->get_utente($_SESSION['id_utente']);
    if( empty($utente) ){
        header('Location: error.php?code=13');
        exit();
    }
    if( !empty ($_POST['new_password']) && !empty ($_POST['new_password2']) && !strcmp($_POST['new_password'], $_POST['new_password2']) ){
        if ($managerSql->modifica_pwd_utente($utente, $_POST['new_password'])  ){
            //password modificata
            header('Location: shop.php?l=login');
            exit();
        }else{
            header('Location: error.php?code=5');
            exit();
        }
        
    }
    
}
?>



    <div style="border:2px solid #999; background:#CCC; margin:90px 90px 90px 90px;">   
					<h1 style="margin-left:130px; margin-bottom:-40px;">RECUPERA PASSWORD</h1>
					<div id="addutentetabledivcage">
						<div style="padding:50px 150px 50px 150px;">
							<?php if ( $recupero_ok == 0 ){ ?>
								 <form name="frm_recupera_pwd" action="" method="post" >
									  <p style="text-align:left;">
											Username <input type="text" name="username" id="username" size="40" value="" /> <br/>
											Domanda segreta <input type="text" name="domanda" id="domanda" size="70" value="" /> <br/>
											Risposta <input type="text" name="risposta" id="risposta" size="70" value="" /> <br/>
											<input type="submit" name="recupera" id="recupera" value="Recupera Password" />
									  </p>
									  
								 </form>
							<?php }  ?>
								 
							<?php if ( $recupero_ok == 1 ){ ?>
								 <form name="frm_new_pwd" action="" method="post" >
									  <p>
											Nuova Password <input type="password" name="new_password" id="password" size="40" value="" /> <br/>
											Riperete la password <input type="password" name="new_password2" id="password2" size="40" value="" /> <br/>
											<input type="submit" name="imposta" id="imposta" value="Imposta Nuova Password" />
									  </p>
									  
								 </form>
								 
							<?php }  ?>
					</div>
				</div>
			</div>



    