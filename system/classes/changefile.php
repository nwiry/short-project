<?php
/**
 * @method local
 */
namespace Short\ShortProject\ChangeFile;
/**
 * @method class ChangeFile
 */
class ChangeFile{
    /**
     * @var mixed
     */
    private $content;
    /**
     * @var string - Diretório de links encurtados
     */
    const pathLinks = __DIR__ . '/../../links/links.json';
    /**
     * @var object||array - Vai retornar os dados da JSON
     */
    private $linkObject;
    private $linkArray;
    /**
     * @var bool - Vai definir o tipo de consulta
     */
    private $json_type = null;

    public function __construct(){
        // Retorna os dados
        $this->linkObject = json_decode(file_get_contents($this::pathLinks));
        $this->linkArray = json_decode(file_get_contents($this::pathLinks), true);
    }

    private function FileLine(string $line_type, string $fileUrl, $line_newValue = '', $extra_lineValue = '', $extra_lineNewValue = ''){
        /**
         * @var bool
         */
        $this->content = null;
        // Define o conteúdo completo
        $full = "\"" . $fileUrl . ":{\"link\":\"" . $this->linkArray[$fileUrl]["link"] . "\",\"user\":" . $this->linkArray[$fileUrl]["user"] . ",\"private\":" . $this->linkArray[$fileUrl]["private"] . ",\"password\":" . $this->linkArray[$fileUrl]["password"] . ",\"clicks\":" . $this->linkArray[$fileUrl]["clicks"];
        /**
         * @method - Definir Linhas
         */
        if($line_type == 'link'){
            /**
             * @var array
             */
            $this->content = [
                "line0" => $line_type,
                "line1" => $line_newValue
            ];
        }
        if($line_type == 'private'){
            /**
             * @var array
             */
            $this->content = [
                "line0" => $line_type,
                "line1" => $line_newValue,
                "line2" => $extra_lineValue,
                "line3" => $extra_lineNewValue
            ];
        }
        if($line_type == 'clicks'){
            /**
             * @var array
             */
            $this->content = [
                "line0" => $line_type
            ];
        }
        /**
         * @return array
         */
        return $this->content;
    }

    private function ChangeFileContent(string $file, string $fileLine, array $extraData = [], string $typeChange = 'content'){
        if($typeChange == 'content'):
            /**
            * @method - Validar tipo de checagem
            */
            if(!isset($extraData['privacyChange'])){
                /**
                 * @return mixed
                 */
                $identifyFl = $this->FileLine($fileLine, $file, $extraData['newValue']);
            }else{
                /**
                 * @return mixed
                 */
                $identifyFl = $this->FileLine($fileLine, $file, $extraData['newValue'], $extraData['passwordAtual'], $extraData['newPassword']);
            }
            if(!is_bool($identifyFl)){
                // Lê o arquivo
                $filename = file_get_contents($this::pathLinks);
                if ($filename) {
                    $data = $this->linkArray;
                    if($identifyFl["line0"] == 'link'){
                        foreach ($data as $key => $entry) {
                            if($key == $file){
                                $data[$key]["link"] = $identifyFl["line1"];
                            }
                        }
                    }
                    if($identifyFl["line0"] == 'private'){
                        // Verifica o tipo de mudança de privacidade do link
                        if($identifyFl["line1"]){ // O usuario esta alterando o link para privado ou alterando a senha
                            // Verifica se ja existe alguma senha definida
                            if(!$data[$file]["password"]){
                                foreach ($data as $key => $entry) {
                                    if($key == $file){
                                        $data[$key]["private"] = 1;
                                        $data[$key]["password"] = $identifyFl["line3"];
                                    }
                                }
                            }else{ // O usuario precisa confirmar a senha informada
                                if($identifyFl["line2"] == $data[$file]["password"]){
                                    foreach ($data as $key => $entry) {
                                        if($key == $file){
                                            $data[$key]["private"] = 1;
                                            $data[$key]["password"] = $identifyFl["line3"];
                                        }
                                    }
                                }else{
                                    /**
                                     * @return array - A senha informada não confere
                                     */
                                    return [
                                        "status" => "error",
                                        "errorCode" => -12, // Falha ao atualizar valor em arquivo
                                        "response" => "A senha informada não corresponde a senha cadastrada no sistema!" // Mensagem de Resposta
                                    ];
                                }
                            }
                        }else{ // O usuario esta retirando a privacidade do link
                            // O usuario precisa confirmar a senha informada
                            if($identifyFl["line2"] == $data[$file]["password"]){
                                foreach ($data as $key => $entry) {
                                    if($key == $file){
                                        $data[$key]["private"] = 0;
                                        $data[$key]["password"] = 0;
                                    }
                                }
                            }else{
                                /**
                                 * @return array - A senha informada não confere
                                 */
                                return [
                                    "status" => "error",
                                    "errorCode" => -12, // Falha ao atualizar valor em arquivo
                                    "response" => "A senha informada não corresponde a senha cadastrada no sistema!" // Mensagem de Resposta
                                ];
                            }
                        }
                    }
                    if($identifyFl["line0"] == 'clicks'){
                        foreach ($data as $key => $entry) {
                            if($key == $file){
                                $data[$key]["clicks"] = $data[$key]["clicks"] + 1;
                            }
                        }
                    }

                    $newData = json_encode($data);
                    
                    if (!file_put_contents($this::pathLinks, $newData)) 
                    /**
                     * @return array - Não foi possiel atualizar valores no arquivo JSON
                     */
                    return [
                        "status" => "error",
                        "errorCode" => -14, // Falha ao atualizar valor em arquivo
                        "response" => null // Manipular valor
                    ];
                }else{
                    /**
                     * @return array - Não foi possivel abrir o arquivo
                     */
                    return [
                        "status" => "error",
                        "errorCode" => -10, // Falha ao abrir arquivo
                        "response" => "Falha ao abrir arquivo" // Mensagem de Resposta
                    ];
                }
                /**
                 * @return array - Success
                 */
                return [
                    "status" => "success",
                    "errorCode" => 0,
                    "response" => null // Manipular valor
                ];
    
            }else{
                /**
                 * @return bool - Valores inválidos
                 */
                return false;
            }
        endif;
        if($typeChange == 'newurl'):
            // Dados da nova URL a ser adicionada ao arquivo JSON
            $dataShort = [
                $extraData["short"] => [
                    "link" => $file,
                    "user" => $extraData["shortUser"],
                    "private" => $extraData["shortPrivacy"],
                    "password" => $extraData["shortPass"],
                    "clicks" => isset($extraData["shortClicks"]) ? $extraData["shortClicks"] : 0
                ]
            ];
            // Adicionando os dados do novo link encurtado ao JSON de links atuais
            $mergeArrays = array_merge($this->linkArray, $dataShort);
            // Codificando para JSON
            $encodeArrays = json_encode($mergeArrays);
            // Edite o arquivo JSON com os novos dados
            if(file_put_contents($this::pathLinks, $encodeArrays)){
                /**
                 * @return array - Retorna os dados para manuseio
                 */
                return [
                    "status" => "success",
                    "short" => $extraData['short'],
                    "errorCode" => 0,
                    "response" => "URL encurtada com sucesso!"
                ];
            }else{
                return [
                    "status" => "error",
                    "errorCode" => -14, // Falha ao atualizar valor em arquivo
                    "response" => null // Manipular valor
                ];
            }
        endif;
        // Parametros invalidos, retorne falso
        return 0;
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
                    "errorCode" => null, // Erro ao realizar processo
                    "response" => null // Valor multiplo, manipular resposta
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
                    "errorCode" => null, // Erro ao realizar processo
                    "response" => null // Valor multiplo, manipular resposta
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
                    "errorCode" => -14,
                    "response" => "Falha ao atualizar número de cliques" // Mensagem de Resposta
                ];
            }
        }
    }

    public function returndata(string $shortlink, array $datashort){
        /**
         * @param mixed
         */
        $upResponse = $this->ChangeFileContent($shortlink, "", $datashort, "newurl");
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
                    "status" => "warning",
                    "errorCode" => -14,
                    "response" => "Falha ao processar a sua solicitação! Tente novamente mais tarde." // Mensagem de Resposta
                ];
            }
        }
    }
}