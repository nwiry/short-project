<?php

/**
 * @method - Proibir o acesso direto ao index.php && followlinks
 */
if($_SERVER['REQUEST_URI'] == $_SERVER['PHP_SELF']){
    die(include('./layout/errors/404.html'));
}

<<<<<<< HEAD
// Importar Arquivos.....
=======
/**
 * @method import composer
 */
require_once('./vendor/autoload.php');
/**
 * @package export
 */
use Router\Router;
/**
 * @global object Router
 */
$GLOBALS['Router'] = new Router();
/**
 * @method import router pages
 */
require_once('./router/pages.php');
/**
 * @param timezone
 */
date_default_timezone_set('America/Sao_Paulo');
>>>>>>> 0caf39c28d17f7d1a813c900b99563669e2ce1fc
