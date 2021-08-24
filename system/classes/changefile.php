<?php
/**
 * @method local
 */
namespace Short\ShortProject\ChangeFile;
/**
 * @method class Router
 */
class ChangeFile{
    /**
     * @var mixed
     */
    private $content;
    /**
     * @var string - Diretório de links encurtados
     */
    const dirShorts = __DIR__ . '/../../links/';
    /**
     * @var string - Extensão dos arquivos
     */
    const extShorts = '.txt';

    private function FileLine(string $line_type, string $line_content, string $line_newValue, $extra_lineValue = '', $extra_lineNewValue = ''){
        /**
         * @var bool
         */
        $this->content = null;
        /**
         * @method - Definir Linhas
         */
        if($line_type == 'short' || $line_type == 'link'){
            /**
             * @var array
             */
            $this->content = [
                "line0" => "\"" . $line_type . "\": ",
                "line1" => "\"" . $line_type . "\": " . $line_content,
                "line2" => "\"" . $line_type . "\": " . $line_newValue
            ];
        }
        if($line_type == 'private'){
            /**
             * @var array
             */
            $this->content = [
                "line0" => "\"private\": ",
                "line0a" => "\"password\": ",
                "line1" => "\"private\": " . $line_content,
                "line2" => "\"private\": " . $line_newValue,
                "line3" => "\"password\": " . $extra_lineValue,
                "line4" => "\"password\": \"" . $extra_lineNewValue . "\""
            ];
        }
        if($line_type == 'clicks'){
            /**
             * @var array
             */
            $this->content = [
                "line0" => "\"clicks\": ",
                "line1" => "\"clicks\": "  . $line_content,
                "line2" => "\"clicks\": "  . ($line_newValue + 1)
            ];
        }
        /**
         * @return array
         */
        return $this->content;
    }

    private function ChangeFileContent(string $file, string $fileLine, array $extraData = []){
        $file = $this::dirShorts . $file . $this::extShorts;
        /**
         * @var object
         */
        $jsonFile = json_decode(file_get_contents($file), true);
        /**
         * @method - Validar tipo de checagem
         */
        if(!isset($extraData['privacyChange'])){
            /**
             * @return mixed
             */
            $identifyFl = $this->FileLine($fileLine, $jsonFile[$fileLine], $extraData['newValue']);
        }else{
            /**
             * @return mixed
             */
            $identifyFl = $this->FileLine($fileLine, $jsonFile[$fileLine], $extraData['newValue'], $extraData['passwordAtual'], $extraData['newPassword']);
        }
        if(!is_bool($identifyFl)){
            /**
             * @var mixed
             */
            $linha_n = explode($identifyFl['line0'], $identifyFl['line1']);
            $linha_n = $linha_n[1];
            // Verifica se existe o campo password
            if(isset($identifyFl['line0a'])){
                $linha_n2 = explode($identifyFl['line0a'], $identifyFl['line3']);
                $linha_n2 = $linha_n2[1];
            }
            // Abre o arquivo para leitura
            $filename = fopen($file,'r+');
            if ($filename) {
                while(true) {
                    $linha = fgets($filename);
                    if ($linha==null) break;
                    // Verifica se o valor informado corresponde ao inserido no arquivo
                    if(preg_match("/$linha_n/", $linha)) {
                        if($identifyFl['line0'] == "\"clicks\": "){
                            $newV = $identifyFl['line0'] . ($linha_n + 1);
                        }else{
                            $newV = $identifyFl['line2'];
                        }
                        $string .= str_replace($identifyFl['line1'], $newV, $linha);
                    }else{
                        $string .= $linha;
                    }
                }
                rewind($filename);
                // Apaga o conteudo
                ftruncate($filename, 0);
                
                if (!fwrite($filename, $string)) 
                /**
                 * @return array - Error
                 */
                return [
                    "status" => "error",
                    "errorCode" => "", // A Definir
                    "response" => "" // Mensagem de Resposta
                ];
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
            // Fecha o arquivo após processos
            fclose($filename);
            // Verifica se existe o campo password
            if(isset($identifyFl['line0a'])){
                // Abre o arquivo para leitura
                $filename = fopen($file,'r+');
                if ($filename) {
                    while(true) {
                        $linha = fgets($filename);
                        if ($linha==null) break;
                        // Verifica se a senha informada é igual à escrita no arquivo
                        if(preg_match("/$linha_n2/", $linha)) {
                            $newstring .= str_replace($identifyFl['line3'], $identifyFl['line4'], $linha);
                        }else{
                            $newstring .= $linha;
                        }
                    }
                    rewind($filename);
                    // Apaga o conteudo
                    ftruncate($filename, 0);

                    if (!fwrite($filename, $newstring)) 
                    /**
                     * @return array - Error
                     */
                    return [
                        "status" => "error",
                        "errorCode" => "", // A Definir
                        "response" => "" // Mensagem de Resposta
                    ];
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
                // Fecha o arquivo após processos
                fclose($filename);
            }
            /**
             * @return array - Success
             */
            return [
                "status" => "success",
                "errorCode" => 0,
                "response" => "" // Mensagem de Resposta
            ];

        }else{
            /**
             * @return bool - Valores inválidos
             */
            return false;
        }
    }

    public function updateLink(string $short_file, string $tipo_link, array $file_content){
        /**
         * @param mixed
         */
        $upResponse = $this->ChangeFileContent($short_file, $tipo_link, $file_content);
        if(!is_bool($upResponse)){
            // Verifica condições
            return $upResponse['status'] == "success" ? 
            /**
             * @return array
             */
            $upResponse : $upResponse ;
        }else{
            if(!$upResponse){
                /**
                 * @return array
                 */
                return [
                    "status" => "error",
                    "errorCode" => "", // A Definir
                    "response" => "" // Mensagem de Resposta
                ];
            }
        }
    }

    public function updateSecurity(string $short_file, array $file_content){
        /**
         * @param mixed
         */
        $upResponse = $this->ChangeFileContent($short_file, "private", $file_content);
        if(!is_bool($upResponse)){
            // Verifica condições
            return $upResponse['status'] == "success" ? 
            /**
             * @return array
             */
            $upResponse : $upResponse ;
        }else{
            if(!$upResponse){
                /**
                 * @return array
                 */
                return [
                    "status" => "error",
                    "errorCode" => "", // A Definir
                    "response" => "" // Mensagem de Resposta
                ];
            }
        }
    }

    public function updateClick(string $short_file, array $file_content){
        /**
         * @param mixed
         */
        $upResponse = $this->ChangeFileContent($short_file, "clicks", $file_content);
        if(!is_bool($upResponse)){
            // Verifica condições
            return $upResponse['status'] == "success" ? 
            /**
             * @return array
             */
            $upResponse : $upResponse ;
        }else{
            if(!$upResponse){
                /**
                 * @return array
                 */
                return [
                    "status" => "error",
                    "errorCode" => "", // A Definir
                    "response" => "" // Mensagem de Resposta
                ];
            }
        }
    }
}