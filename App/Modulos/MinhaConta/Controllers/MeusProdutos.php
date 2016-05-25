<?php
    namespace Rocharor\MinhaConta\Controllers;

    use Rocharor\Sistema\Controller;
    use Rocharor\Sistema\Sessao;
    use Rocharor\MinhaConta\Models\MeusProdutosModel;

    class MeusProdutos extends Controller
    {
        private $model;
        private $suario_id;
        
        public function __construct()
        {
            $this->model = new MeusProdutosModel();
            $this->usuario_id = Sessao::pegaSessao('logado');
        }
        
        public function indexAction()
        {
         
            $meusProdutos = $this->model->getMeusProdutos($this->usuario_id);
            
            foreach($meusProdutos as $key=>$produto){
                $fotos = explode('|',$produto['nm_imagem']);
                $meusProdutos[$key]['img_principal'] = $fotos[0];
            }
              
            $variaveis = ['pagina_main' => VIEWS_MC.'meusProdutos.html',
                          'meusProdutos'=>$meusProdutos
            ];

            $this->view('main',$variaveis);
        }
    }
