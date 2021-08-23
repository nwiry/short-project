<?php
/**
 * @method local
 */
namespace Short\ChangeFile;
/**
 * @method class Router
 */
class ChangeFile{
    /**
     * @var mixed
     */
    private $content;

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
        $file = __DIR__ . '/../../links/' . $file . ".txt";
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
            if(isset($identifyFl['line0a'])){
                $linha_n2 = explode($identifyFl['line0a'], $identifyFl['line3']);
                $linha_n2 = $linha_n2[1];
            }
            $filename = fopen($file,'r+');
            if ($filename) {
                while(true) {
                    $linha = fgets($filename);
                    if ($linha==null) break;
                    
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
                
                if (!fwrite($filename, $string)) return [
                    "status" => "error",
                    "errorCode" => "", // A Definir
                    "response" => "" // Mensagem de Resposta
                ];
            }else{
                return [
                    "status" => "error",
                    "errorCode" => "", // A Definir
                    "response" => "" // Mensagem de Resposta
                ];
            }

            fclose($filename);

            if(isset($identifyFl['line0a'])){
                $filename = fopen($file,'r+');
                if ($filename) {
                    while(true) {
                        $linha = fgets($filename);
                        if ($linha==null) break;
                        if(preg_match("/$linha_n/", $linha)) {
                            $newstring .= str_replace($identifyFl['line3'], $identifyFl['line4'], $linha);
                        }else{
                            $newstring .= $linha;
                        }
                    }
                    rewind($filename);
                    // Apaga o conteudo
                    ftruncate($filename, 0);

                    if (!fwrite($filename, $newstring)) return [
                        "status" => "error",
                        "errorCode" => "", // A Definir
                        "response" => "" // Mensagem de Resposta
                    ];
                }else{
                    return [
                        "status" => "error",
                        "errorCode" => "", // A Definir
                        "response" => "" // Mensagem de Resposta
                    ];
                }
                fclose($filename);
            }

            return [
                "status" => "success",
                "errorCode" => 0,
                "response" => "" // Mensagem de Resposta
            ];

        }else{
            return false;
        }
    }
}