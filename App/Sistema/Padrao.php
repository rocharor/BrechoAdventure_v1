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
    
    
    public static function validaServidor()
    {
    	error_reporting(E_ALL);
    
    	if($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == '127.0.0.1:8001'){
    		ini_set('display_errors', 1);
    		$servidor = false;
    	}else{
    		ini_set('display_errors', 0);
    		$servidor = true;
    	}
    
    	return $servidor;
    }
    
    /**
     * Verifica se a url existe
     * @param $url
     * @return bool
     */
    public static function url_exists($url)
    {
    	$ch = curl_init($url);
    	curl_setopt($ch, CURLOPT_NOBODY, true);
    	curl_exec($ch);
    	$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    	curl_close($ch);
    
    	return ($code == 200);
    }
}
