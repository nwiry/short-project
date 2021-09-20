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
                $createLink = [
                    "status" => "success",
                    "short" => $this->random,
                    "shortUser" => 0,
                    "shortPrivacy" => 0,
                    "shortPass" => 0,
                    "shortClicks" => 0,
                ];
                /**
                 * @return array - Retorna o resultado para manuseio com API
                 */
                return $createLink;
            } else {
                $existShort = $this->exist_short($customShort);
                // Se não existe um custom, prossiga
                if (!$existShort) {
                    $createLink = [
                        "status" => "success",
                        "short" => $customShort,
                        "shortUser" => 0,
                        "shortPrivacy" => 0,
                        "shortPass" => 0,
                        "shortClicks" => 0,
                    ];
                    return $createLink;
                } else {
                    return [
                        "status" => 'error',
                        "errorCode" => 30,
                        "response" => 'O Custom informado já está em uso!',
                    ];
                }
            }
        } else {
            return [
                "status" => 'error',
                "errorCode" => 35,
                "response" => 'Informe um endereço de URL válido!!',
            ];
        }
    }
}
