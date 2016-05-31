<?php

namespace Rocharor\Sistema;

use Rocharor\Sistema\Sessao;

abstract class Controller
{
    public function view($arquivo = null, $variaveis = array())
    {
        global $start, $smarty;

        if (is_null($arquivo)) {
            $controller = strtolower(str_replace('Controller', '', $start->getController()));
            $action = strtolower(str_replace('Action', '', $start->getAction()));
            $view = VIEWS . $controller . '/' . $action . '.html';
        } else {
            $view = VIEWS . $arquivo . '.html';
        }

        if (file_exists($view)) {

            foreach ( $variaveis as $nomeVar => $valorVar ) {
                $smarty->assign($nomeVar, $valorVar);
            }
            $smarty->assign('logado', Sessao::pegaSessao('logado'));
            $smarty->assign('nome_imagem', Sessao::pegaSessao('nome_imagem'));

            if($arquivo == '404'){
                $smarty->display('404.html');
            }else{
                $smarty->display('main.html');
            }

        } else {
            $start->erro(404);
        }
    }

    public function validaExtImagem($arquivo_file)
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
