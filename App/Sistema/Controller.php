<?php

namespace Rocharor\Sistema;

use Rocharor\Sistema\Sessao;

abstract class Controller
{
    public function view($arquivo, $variaveis = [])
    {
        global $smarty;

        if(file_exists(VIEWS . $arquivo . '.html')){
            $view = VIEWS . $arquivo . '.html';
        }elseif(file_exists(VIEWS_MC . $arquivo . '.html')){
            $view = VIEWS_MC . $arquivo . '.html';
        }elseif(file_exists(VIEWS_ADMIN . $arquivo . '.html')){
            $view = VIEWS_ADMIN . $arquivo . '.html';           
        }else{
            $view = VIEWS . '404.html';
        }
      
        foreach ( $variaveis as $nomeVar => $valorVar ) {
            $smarty->assign($nomeVar, $valorVar);
        }
        $smarty->assign('pagina_main', $view);
        $smarty->assign('logado', Sessao::pegaSessao('logado'));
        $smarty->assign('nome_imagem', Sessao::pegaSessao('nome_imagem'));
        
        $smarty->display('main.html');     
        
        /* 
         //global $start, $smarty;
         
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
        } */
    }    
}
