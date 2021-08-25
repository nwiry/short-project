<?php
/**
 * @method local
 */
namespace Short\ShortProject\Validations;
/**
 * @method class Validations
 */
class Validations{
    /**
     * @var array - Dominios banidos (Não podem ser encurtados aqui)
     */
    const BanUrls = [];

    /**
     * @return bool
     */
    private function validar_url($url){
        if(filter_var($url, FILTER_VALIDATE_URL)){
            return true;
        }
        return false;
    }
}