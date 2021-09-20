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
class Operations extends ChangeFile
{
    /**
     * @param string
     */
    private $fully;

    /** ---- Construtor --- */
    public function __construct()
    {
        /**
         * @return string
         */
        $this->fully = parent::pathLinks;
    }

    private function link_exist($link)
    {
        // Fixa o arquivo
        $filename = $this->fully;
        $filename = json_decode(file_get_contents($filename), true);
        /**
         * Verifica se o link existe ou não
         */
        if (array_key_exists($link, $filename)) {
            return true;
        }
        return false;
    }

    private function content_link($file)
    {
        if ($this->link_exist($file)) {
            return json_decode(file_get_contents($this->fully), true);
        }
        return false;
    }

    public function exist_result($link)
    {
        return $this->link_exist($link);
    }

    public function array_content($file)
    {
        return $this->content_link($file);
    }
}
