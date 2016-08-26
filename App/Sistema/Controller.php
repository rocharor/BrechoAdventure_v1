<?php

namespace Rocharor\Sistema;

use Rocharor\Sistema\Sessao;

abstract class Controller
{
    /* public function view($arquivo, $variaveis = [])
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
    }   */
	
	/**
	 * Método padrão para abrir as VIEWS 
	 */
    public function view($arquivo, $variaveis = [], $modulo = false)
    {
    	global $smarty;
    
    	switch ($modulo) {
    		case 'minhaconta':
    			{
    				$view = VIEWS_MC . $arquivo . '.html';    				
    				break;
    			}
			case 'admin':
    			{
    				$view = VIEWS_ADMIN . $arquivo . '.html';    				
    				break;
				}
    		default:
    			{
    				$view = VIEWS . $arquivo . '.html';    			
    				break;
    			}
    	}
    
    	if (! file_exists($view)) {
    		$view = VIEWS . '404.html';
    	}
    
    	foreach ($variaveis as $nomeVar => $valorVar) {
    		$smarty->assign($nomeVar, $valorVar);
    	}
    
    	$smarty->assign('pagina_main', $view);
    	$smarty->assign('logado', Sessao::pegaSessao('logado'));
    	$smarty->assign('nome_imagem', Sessao::pegaSessao('nome_imagem'));
    	$main = 'main.html';
    	$smarty->display($main);
    }
    
    /**
     * Valida paginas que dependem de login
     */
    public function validaLogado()
    {
    	$logadoAdmin = Sessao::pegaSessao('logado');
    
    	if($logadoAdmin){
    		return true;
    	}else{
    		return false;
    	}
    }
}
