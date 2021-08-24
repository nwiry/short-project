<?php
error_reporting(0); // Desabilita mensagens de erro e noticias
/**
 * @method import composer
 */
require_once(__DIR__ . '/../vendor/autoload.php');
/**
 * @var object
 */
$paths = new Short\ShortProject\ChangeFile\ChangeFile;
/**
 * @var bool
 */
$localDebug = false;

// Requisita a alteração da permissão da pasta de links:
if(chmod($paths::dirShorts, 0760)){
    $cdata = [];
}else{
    $localDebug ? $cdata = [
        "debug" => "chmod can't run"
        ] : null;
}
header('Content-Type: application/json');
die(json_encode([
    "status" => "Server Running",
    "runtime" => $_SERVER['REQUEST_TIME'],
    [
        $cdata
    ]
]));