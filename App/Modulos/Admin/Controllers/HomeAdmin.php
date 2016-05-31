<?php
    namespace Rocharor\Admin\Controllers;

    use Rocharor\Sistema\Controller;

    class HomeAdmin extends Controller
    {
        private $model;
        private $usuario_id;

        public function __construct()
        {
            /**/
            echo "<pre>";
            var_dump('aki');
            echo "</pre>";
            die();
            $this->model = new HomeModel();
            $this->usuario_id = Sessao::pegaSessao('logado');
        }

        public function indexAction()
        {
            $objProdutoModel = new ProdutoModel();
            $favoritos = $this->model->getFavoritos($this->usuario_id);
            $arrFavoritos = [];
            foreach($favoritos as $key=>$produto_id){
                $arrFavoritos[$key] = $objProdutoModel->getDescricaoProduto($produto_id);
                $arrFavoritos[$key]['produto_id'] = $produto_id;
            }

            $variaveis = ['pagina_main' => VIEWS_MC.'meusFavoritos.html', 'favoritos'=>$arrFavoritos];
            $this->view('main',$variaveis);
        }

        public function setFavoritoAction()
        {
            $usuario_id = isset($_POST['usuario_id']) ? $_POST['usuario_id'] : '' ;
            $produto_id = isset($_POST['produto_id']) ? $_POST['produto_id'] : '' ;
            $status = isset($_POST['status']) ? $_POST['status'] : '';

            $ret = $this->model->setFavorito($usuario_id,$produto_id,$status);

            if($ret){
                $retorno = ['msg'=>'Favorito salvo com sucesso'];
            }else{
                $retorno = ['msg'=>'Erro ao salvar favorito'];
            }
            echo json_encode($retorno);
            die();
        }

    }
