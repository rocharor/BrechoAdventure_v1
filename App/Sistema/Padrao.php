<?php
namespace Rocharor\Sistema;

class Padrao
{

    public static function validaExtImagem($arquivo_file)
    {
        $extencoes = array(
            'jpg',
            'png',
            'gif'
        );
        
        foreach ($arquivo_file as $file) {
            $ext = explode('.', $file['name']);
            $ext = end($ext);
            
            if (! in_array($ext, $extencoes)) {
                return false;
            }
        }
        
        return true;
    }

    public static function escapeSql($value)
    {
        $search = array(
            "\\",
            "\x00",
            "\n",
            "\r",
            "'",
            '"',
            "\x1a"
        );
        $replace = array(
            "\\\\",
            "\\0",
            "\\n",
            "\\r",
            "\'",
            '\"',
            "\\Z"
        );
        return str_replace($search, $replace, $value);
    }

    public static function removeAcentos($string)
    {
        $string = preg_replace(array(
            "/(á|à|ã|â|ä)/",
            "/(Á|À|Ã|Â|Ä)/",
            "/(é|è|ê|ë)/",
            "/(É|È|Ê|Ë)/",
            "/(í|ì|î|ï)/",
            "/(Í|Ì|Î|Ï)/",
            "/(ó|ò|õ|ô|ö)/",
            "/(Ó|Ò|Õ|Ô|Ö)/",
            "/(ú|ù|û|ü)/",
            "/(Ú|Ù|Û|Ü)/",
            "/(ñ)/",
            "/(Ñ)/",
            "/(ç)/",
            "/(Ç)/"
        ), explode(" ", "a A e E i I o O u U n N c C"), $string);
        
        return $string;
    }
}
