<?php
namespace Rocharor\Sistema;

class Padrao
{
    
    public static function validaExtImagem($arquivo_file)
    {
        $extencoes = array('jpg','png','gif');
    
        foreach($arquivo_file as $file){
            $ext = explode('.',$file['name']);
            $ext = end($ext);
    
            if(!in_array($ext,$extencoes)){
                return false;
            }
        }
    
        return true;
    }
}
