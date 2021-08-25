<?php
/**
 * @method - Receber POST
 */
if(isset($_POST['shorturl'])){
    /**
     * @var string||mixed
     */
    $shorturl = filter_input(INPUT_POST, "shorturl", FILTER_SANITIZE_STRING);
    $customshort = filter_input(INPUT_POST, "customshort", FILTER_SANITIZE_STRING);
    /**
     * @var object||self&&mixed - Importação de classes
     */
    $shortResponse = new Short\ShortProject\Validations\Validations();
    $newFile = new Short\ShortProject\CreateFile\CreateFile();
    // Validar resultados
    $shortResponse = $shortResponse->short_url($shorturl, $customshort);
    /**
     * @method - Concluir validações
     */
    if($shortResponse['status'] == 'success'){
        // Requisitar a resposta
        $rNf = $newFile->returndata($shorturl, $shortResponse);
        // Exibir a resposta na API
        echo json_encode($rNf);
    }else{
        // Exibir erros
        echo json_encode($shortResponse);
    }
}else{
    // Não há requisição na API
    echo json_encode([
        "status" => "error"
    ]);
}