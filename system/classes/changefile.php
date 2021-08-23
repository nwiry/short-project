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
                "line1" => "\"" . $line_type . "\": " . $line_content,
                "line2" => "\"" . $line_type . "\": " . $line_newValue
            ];
        }
        if($line_type == 'privateChange'){
            /**
             * @var array
             */
            $this->content = [
                "line1" => "\"private\": " . $line_content,
                "line2" => "\"private\": " . $line_newValue,
                "line3" => "\"password\": " . $extra_lineValue,
                "line4" => "\"password\": " . $extra_lineNewValue
            ];
        }
        if($line_type == 'updateClick'){
            /**
             * @var array
             */
            $this->content = [
                "line1" => "\"clicks\": "  . $line_content,
                "line2" => "\"clicks\": "  . ($line_newValue + 1)
            ];
        }
        /**
         * @return array
         */
        return $this->content;
    }
}