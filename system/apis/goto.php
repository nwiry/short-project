<?php

use Short\ShortProject\Operations\Operations;
use Short\ShortProject\ChangeFile\ChangeFile;

$endpoint = $GLOBALS['run_link_short'];

/**
 * @var object
 */
$verificaLink = new Operations();
$changeFile = new ChangeFile();
// Verifica se o link existe
if($verificaLink->exist_result($endpoint)){
    /**
     * @var object
     */
    $linkObjs = $verificaLink->objects_content($endpoint);
    // Verifica se o link não possui proteção com senha
    if(!$linkObjs->private){
        // Contabilizar clique
        try{
            $changeFile->updateClick($endpoint, [
                "newValue" => $linkObjs->clicks
            ]);
            // Se contabilizou redirecione
            header("location: " . $linkObjs->link);
        }catch(Exception $e){
            // Se nao contabilizou, redirecione, mas não pare o processo
            header("location: " . $linkObjs->link);
        }
    }else{
        // Verificar tipo de proteção e tratar dados
    }
}else{
    // Link não existente
    die(include_once(__DIR__ . '/../../layout/errors/404.html'));
}