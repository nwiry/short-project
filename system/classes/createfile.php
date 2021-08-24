<?php

/**
 * @method local
 */
namespace Short\ShortProject\CreateFile;
use Short\ShortProject\ChangeFile\ChangeFile;
use PragmaRX\Random\Random;
/**
 * @method class CreateFile
 */
class CreateFile extends ChangeFile{
    /**
     * @var object
     */
    private $random;
    /**
     * @param string
     */
    private $dirLinks;
    /**
     * @param string
     */
    private $extLinks;
    /**
     * @var int
     */
    private $randomChars;

    /** ---- Construtor --- */
    public function __construct(){
        /**
         * @return object
         */
        $this->random = new Random();
        /**
         * @return string
         */
        $this->dirLinks = parent::dirShorts;
        /**
         * @return string
         */
        $this->extLinks = parent::extShorts;
        /**
         * @return rand
         */
        $this->randomChars = rand(3, 5);
    }
    
    /**
     * @return bool - Verifica se o arquivo existe, retornando verdadeiro ou falso
     */
    private function link_exist(string $link){
        // Fixa o arquivo
        $filename = $this->dirLinks . $link . $this->extLinks;
        /**
         * Verifica se o arquivo existe ou não
         */
        if(file_exists($filename)){
            return true;
        }
        return false;
    }

    private function create_file(string $link, array $data){
        // Validações
        if(!isset($data['custom_short'])){ // Se o usuário não optou por escolher o formato da url
            // Gera caracteres aleatórios
            $preshort = $this->random->size($this->randomChars)->get();
            // Verifica se ja existe algum link encurtado com a variável criada
            $vshort = $this->link_exist($preshort);
            if(!$vshort){ // Se o arquivo não existe, retorna o valor
                $short = $preshort;
            }else{ // Se não existe, tenta novamente
                $altshort = $this->random->size($this->randomChars)->get();
                $avshort = $this->link_exist($altshort);
                if(!$avshort){
                    $short = $altshort;
                }else{
                    /**
                     * @return bool - Retorna falso e cancela a função
                     */
                    return false;
                }
            }
        }else{ // Caso contrário, iremos validar abaixo:
            // Verifica se ja existe algum link encurtado com a costumização
            $valCustom = $this->link_exist($data['custom_short_value']);
            if(!$valCustom){ // Se não existe, prossiga
                $short = $data['custom_short_value'];
            }else{
                /**
                 * @return array - Retorna erro, pois o custom ja existe
                 */
                return [
                    "status" => "error",
                    "errorCode" => "", // A definir
                    "response" => "" // A Definir
                ];
            }
        }
        // Retorna o user dono da URL, por padrão é 0 (Publico)
        $user_id = $data['user_id'];
        // Retorna a privacidade da url, por padrão é 0 (Público)
        $privacy = $data['privacy'];
        // Retorna a senha da url, por padrão é 0 (Vazio)
        $password = $data['password'];
        // Define o nome final do arquivo que iremos criar para a URL
        $filename = $this->dirLinks . $short . $this->extLinks;

$content = '{
"short": "' . $short . '", 
"link": "' . $link . '", 
"user": '. $user_id . ', 
"private": ' . $privacy . ', 
"password": ' . $password . ', 
"clicks": 0
}';     

        // Abrir arquivo, caso não exista, irá criar
        $fp = fopen($filename, "wb");
        if($fp){
            // Escrever o conteúdo
            fwrite($fp, $content);
            // Fechar o arquivo
        }else{
            /**
             * @return array - Error
             */
            return [
                "status" => "error",
                "errorCode" => "", // A Definir
                "response" => "" // Mensagem de Resposta
            ];
        }
        fclose($fp);
        // Altera as permissões do arquivo para leitura, execuração e gravação, somente servidor
        if(!chmod ($filename, 0770)){
            /**
             * @return array - Retorna erro caso não consifa efetuar a mudança
             */
            return [
                "status" => "error",
                "errorCode" => "", // A Definir
                "response" => "" // Mensagem de Resposta
            ];
        }
        /**
         * @return array - Success
         */
        return [
            "status" => "success",
            "errorCode" => 0,
            "response" => "", // Mensagem de Resposta
            "perm" => substr(sprintf('%o', fileperms($filename)), -4)
        ];
        
    }
}
