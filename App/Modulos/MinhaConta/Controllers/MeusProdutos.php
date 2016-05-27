<?php
    namespace Rocharor\MinhaConta\Controllers;

    use Rocharor\Sistema\Controller;
    use Rocharor\Sistema\Sessao;
    use Rocharor\MinhaConta\Models\MeusProdutosModel;
    use Rocharor\Site\Models\ProdutoModel;
    
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
        
        public function meusProdutosEditarAction($produto_id)
        {             
            
            $objProduto = new ProdutoModel();
            $descProduto = $objProduto->getDescricaoProduto($produto_id); 
            $arrCategorias = $objProduto->getCategoriasProduto();
            
            $variaveis = ['pagina_main' => VIEWS_MC.'meusProdutosEditar.html',
                          'descProduto'=>$descProduto,
                          'arrCategorias'=>$arrCategorias
            ];
        
            $this->view('main',$variaveis);
        }
        
        public function deletarProdutoAction()
        {
            $produto_id = $_POST['produto_id'];
            $ret = $this->model->deletarProduto($produto_id);
            
            if($ret){
                $retorno = ['sucesso'=>true,'msg'=>'Produto excluído com sucesso'];
            }else{
                $retorno = ['sucesso'=>false,'msg'=>'Erro ao excluir produto'];
            }
            
            echo json_encode($retorno);
            die();
            
        }
    }
