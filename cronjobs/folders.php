<?php
error_reporting(0); // Desabilita mensagens de erro e noticias
/**
 * @method import composer
 */
require_once(__DIR__ . '/../vendor/autoload.php');
/**
 * @var object
 */
$paths = new Short\ShortProject\ChangeFile\ChangeFile();
/**
 * @var bool
 */
$localDebug = true;

$nFd = explode("/links.json", $paths::pathLinks);
$nFd = $nFd[0];

// Requisita a alteração da permissão da pasta de links:
if (chmod($paths::pathLinks, 0760) && chmod($nFd, 0760)) {
    $cdata = [];
} else {
    $localDebug ? $cdata = [
        "debug" => "chmod can't run"
    ] : $cdata = null;
}
// Altera permissão de leitura da pasta layouts (Arquivos .php/.html)
$layoutPath = __DIR__ . '/../layout/';
$types = array('php', 'html');
$dir = new DirectoryIterator($layoutPath);
foreach ($dir as $fileInfo) {
    $ext = strtolower($fileInfo->getExtension());
    if (in_array($ext, $types)){
        if(chmod($fileInfo->getFilename(), 0760)){
            $cdata2[] = [];
        }else{
            $localDebug ? $cdata2[] = ["debug" => "chmod2 can't run"] : $cdata2[] = null;
        }
    }
}
header('Content-Type: application/json');
die(json_encode([
    "status" => "Server Running",
    "runtime" => $_SERVER['REQUEST_TIME'],
    [
        $cdata,
        $cdata2
    ]
]));
