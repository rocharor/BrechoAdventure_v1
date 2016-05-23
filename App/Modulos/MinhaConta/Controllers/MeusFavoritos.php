<?php
    namespace Rocharor\MinhaConta\Controllers;

    use Rocharor\Sistema\Controller;
    use Rocharor\Sistema\Sessao;

    class MeusFavoritos extends Controller
    {
        public $model;
        
        public function __construct()
        {
            $this->model = new MeusFavoritosModel();
        }
        
        public function indexAction()
        {            
            $this->model->getFavoritos();
            $variaveis = ['pagina_main' => VIEWS_MC.'meusFavoritos.html'];
            $this->view('main',$variaveis);
        }

    }
