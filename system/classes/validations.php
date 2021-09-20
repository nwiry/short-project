<?php

/**
 * @method local
 */

namespace Short\ShortProject\Validations;

use PragmaRX\Random\Random;
use Short\ShortProject\ChangeFile\ChangeFile;

/**
 * @method class Validations
 */
class Validations extends Random
{
    /**
     * @var array - Dominios banidos (Não podem ser encurtados aqui)
     * Dominio e subdominios devem ser listados - (case sensitive)
     */
    const BanUrls = [];
    /**
     * @var object - Retorna funções e atributos da classe Random
     */
    private $random;
    private $changeFile;

    public function __construct()
    {
        $this->changeFile = new ChangeFile();
    }

    private function validar_url($url)
    {
        // Verifica se o url esta no formato http(s)://domain.ext
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return true;
        }
        return false;
    }

    private function checkBanUrls($url)
    {
        $parse = parse_url($url);
        if (in_array($parse['host'], $this::BanUrls)) {
            return true;
        }
        return false;
    }

    private function exist_short($short)
    {
        // Obtem o arquivo de links para leitura
        $path = file_get_contents($this->changeFile::pathLinks);
        // Transforma o resultado em array
        $readJson = json_decode($path, true);
        // Verifica se o short escolhido ja esta em uso
        if (array_key_exists($short, $readJson)) {
            return true;
        } else {
            return false;
        }
    }

    public function short_url(string $url, $customShort)
    {
        // Verifica se o link informado é valido
        $linkVer = $this->validar_url($url);
        // Gera um tamanho aleatorio para o encurtamento de urls
        $randChars = rand(3, 7);
        if ($linkVer) {
            if (empty($customShort) || $customShort == NULL) {
                do {
                    // Obtem uma variação
                    $this->random = parent::size($randChars)->get();
                    $existShort = $this->exist_short($this->random);
                } while ($existShort == true); // Ira gerar outros caracteres enquanto a variação escolhida existir
                // Cria o array com os dados do novo link
                $newUrl = [
                    $this->random => [
                        "link" => $url,
                        "user" => 0,
                        "private" => 0,
                        "password" => 0,
                        "clicks" => 0
                    ]
                ];
                // Obtem o arquivo de links para leitura
                $path = file_get_contents($this->changeFile::pathLinks);
                // Transforma o resultado em array
                $readJson = json_decode($path, true);
                // Junta os novos dados em um só array
                $addNewUrl = json_encode(array_merge($readJson, $newUrl));
                // Solicita a chamada da função
                $createLink = $this->changeFile->ChangeFile(
                    $this->random, // Short
                    "",
                    [
                        /**
                         * @param array - Dados para uso na função
                         */
                        "short" => $this->random,
                        "shortUser" => $newUrl[$this->random]["user"],
                        "shortPrivacy" => $newUrl[$this->random]["private"],
                        "shortPass" => $newUrl[$this->random]["password"],
                        "shortClicks" => $newUrl[$this->random]["clicks"],
                    ],
                    "newurl" // Metodo
                );
                /**
                 * @return mixed - Retorna o resultado para manuseio com API
                 */
                return $createLink;
            } else {
                // Tratar dados
            }
        } else {
            // Tratar dados
        }
    }
}
