<?php


$spedizioni = array();

$corriere['sigla'] = 'express';
$corriere['nome'] = 'Corriere Espresso';
$spedizioni[] = $corriere;

$ritiro['sigla'] = 'ritiro';
$ritiro['nome'] = 'Ritiro in sede';
$spedizioni[] = $ritiro;


function calcola_costo_spedizione($sigla,$provincia,$peso){
    include_once 'dbmanager.php';
    if(empty($managerSql)){
        $managerSql = new dbManager();
    }
    
    switch ($sigla) {
        case 'ritiro':
            return 0;
            break;
        
        case 'express':
            $costo = $managerSql->get_calcolo_costo_corriere_espresso($provincia, $peso);
            if( !empty($costo) ) return $costo['costo']; else return 0;
            break;
        default:
            break;
    }
}

?>
