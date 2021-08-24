<?php

use Short\ShortProject\Operations\Operations;

$endpoint = $GLOBALS['run_link_short'];

/**
 * @var object
 */
$verificaLink = new Operations();
// Verifica se o link existe
if($verificaLink->exist_result($endpoint)){
    /**
     * @var object
     */
    $linkObjs = $verificaLink->objects_content($endpoint);
    // Verifica se o link não possui proteção com senha
    if(!$linkObjs->private){
        die(header("location: " . $linkObjs->link));
    }else{
        // Verificar tipo de proteção e tratar dados
    }
}