<?php
    namespace Rocharor\Admin\Controllers;

    use Rocharor\Sistema\Controller;
    use Rocharor\Admin\Models\HomeAdminModel;
                
    class HomeAdmin extends Controller
    {
        private $model;

        public function __construct()
        {          
            $this->model = new HomeAdminModel();
        }

        public function indexAction()
        {       
            $variaveis = [];
            $this->view('homeAdmin',$variaveis);
        }
        
        public function produtoAction($tipo)
        {
            $variaveis = [];
            $this->view('produtosAdmin',$variaveis);
        }
    }