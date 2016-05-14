<?php
    namespace Rocharor\MinhaConta\Controllers;

    use Rocharor\Sistema\Controller;
    use Rocharor\Sistema\Sessao;

    class MeusFavoritos extends Controller
    {
        public function indexAction()
        {
            $variaveis = ['pagina_main' => VIEWS_MC.'favoritos.html'];

            $this->view('main',$variaveis);
        }

    }
