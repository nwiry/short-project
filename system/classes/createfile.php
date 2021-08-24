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
    }
    
}