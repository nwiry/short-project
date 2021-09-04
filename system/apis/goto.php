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
    $linkObjs = $verificaLink->array_content($endpoint);
    // Verifica se o link não possui proteção com senha 
    if(!$linkObjs[$endpoint]["private"]){
        // Contabilizar clique 
        try{
            $changeFile->updateClick($endpoint, [
                "newValue" => $linkObjs[$endpoint]["clicks"]
            ]);
            // Se contabilizou redirecione
            header("location: " . $linkObjs[$endpoint]["link"]);
            // Encerre a execução
            die;
        }catch(Exception $e){
            // Se nao contabilizou, redirecione, mas não pare o processo
            header("location: " . $linkObjs[$endpoint]["link"]);
            // Encerre a execução
            die;
        }
    }else{
        // Verificar tipo de proteção e tratar dados
         /**
          * @method - Ate lançar a solução
          */
        die(header("location: /"));
    }
}else{
    // Link não corresponde ao conteudo na short
    die(include_once(__DIR__ . '/../../layout/errors/404.html'));
}