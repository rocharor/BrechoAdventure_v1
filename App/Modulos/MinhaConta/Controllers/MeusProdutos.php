<?php
    namespace Rocharor\MinhaConta\Controllers;

    use Rocharor\Sistema\Controller;
    use Rocharor\Sistema\Sessao;

    class MeusProdutos extends Controller
    {
        public function indexAction()
        {
            $variaveis = ['pagina_main' => VIEWS_MC.'meusProdutos.html'];

            $this->view('main',$variaveis);
        }
    }
