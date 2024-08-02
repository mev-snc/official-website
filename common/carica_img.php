<?php

function load_image($cartella, $file, $name){
    $tipi_consentiti = array("image/gif", "image/jpeg", "image/pjpeg", "image/png", "image/x-png");
    if( in_array( $file['type'], $tipi_consentiti) ){
        if( $file['type']=="image/jpeg" || $file['type']=="image/pjpeg" ){
            $immagine = imagecreatefromjpeg($file['tmp_name']);
        }
        if( $file['type']=="image/x-png" || $file['type']=="image/png" ){
            $immagine = imagecreatefrompng($file['tmp_name']);
        }
        if( $file['type']=="image/gif" ){
            $immagine = imagecreatefromgif($file['tmp_name']);
        }
        
        if (imagepng($immagine, $cartella.$name.'.png') ){
            return true;
        }else{
            return false;
        }
    }else{
        return false;
    }
}

?>
