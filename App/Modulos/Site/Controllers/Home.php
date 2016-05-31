<?php
    namespace Rocharor\Site\Controllers;

    use Rocharor\Sistema\Controller;

    class Home extends Controller{

        public function indexAction()
        {
            $variaveis = ['pagina_main' => 'index.html',
                          'active_1'=>'active',
                          'frase'=>'Prefiro carregar o peso de uma mochila nas costas do que o da consciência de não ter conhecido o mundo...'
            ];

            $this->view('main',$variaveis);
        }

        public function erroAction()
        {
            $variaveis = [];

            $this->view('404',$variaveis);
        }
    }
