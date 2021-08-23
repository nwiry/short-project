<?php

/**
 * @method - Proibir o acesso direto ao arquivo local
 */
if($_SERVER['REQUEST_METHOD'] == $_SERVER['REQUEST_METHOD'] && realpath(__FILE__) == realpath($_SERVER['SCRIPT_FILENAME'])){
    die(include(__DIR__ . '/../layout/errors/404.html'));
}

/**
 * @var object $router
 */
$router = $GLOBALS['Router'];

/**
 * @method - Pagina Inicial e Erro 404
 */
$router->catch_exception(function(){
    /**
     * @var bool
     */
    $uriGET = null;
    /**
     * @method
     */
    if(isset($_SERVER['QUERY_STRING'])){
        /**
         * @var mixed
         * @return bool
         */
        $getHome = "/?" . $_SERVER['QUERY_STRING'];
        $getHome == $_SERVER['REQUEST_URI'] ? $uriGET = true : $uriGET = false;
    }else{
        /**
         * @var mixed
         * @return bool
         */
        $g3tHome = "/?";
        $g3tHome == $_SERVER['REQUEST_URI'] ? $uriGET = true : $uriGET = false;
    }
    /**
     * @method - Define a página inicial do encurtador
     */
    if($_SERVER['REQUEST_URI'] == '/' || $uriGET){
        /**
         * @param import-file
         */
        // Incluir pagina inicial aqui
        /**
         * @method - Erro 404
         */
    }else{
        /**
         * @param import-file
         */
        include_once(__DIR__ . '/../layout/errors/404.html');
    }
});