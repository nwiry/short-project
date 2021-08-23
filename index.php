<?php
/**
 * @method - Proibir o acesso direto ao index.php && followlinks
 */
if($_SERVER['REQUEST_URI'] == $_SERVER['PHP_SELF']){
    die(include('./layout/errors/404.html'));
}

// Importar Arquivos.....