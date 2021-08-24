<?php

/**
 * @method local
 */
namespace Short\ShortProject\Operations;
use Short\ShortProject\ChangeFile\ChangeFile;
use Short\ShortProject\CreateFile\CreateFile;
/**
 * @method class CreateFile
 */
class Operations extends ChangeFile{
    /**
     * @param string
     */
    private $dirLinks;
    /**
     * @param string
     */
    private $extLinks;

    /** ---- Construtor --- */
    public function __construct(){
        /**
         * @return string
         */
        $this->dirLinks = parent::dirShorts;
        /**
         * @return string
         */
        $this->extLinks = parent::extShorts;
    }

    private function link_exist($link){
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

    private function content_link($file){
        if($this->link_exist($file)){
            return json_decode(file_get_contents($this->dirLinks . $file . $this->extLinks));
        }
        return false;
    }

    public function exist_result($link){
        return $this->link_exist($link);
    }

    public function objects_content($file){
        return $this->content_link($file);
    }
}