<?php
    namespace Rocharor\MinhaConta\Controllers;

    use Rocharor\Sistema\Controller;
    use Rocharor\Sistema\Sessao;
    use Rocharor\MinhaConta\Models\MeusFavoritosModel;
    use Rocharor\Site\Models\ProdutoModel;

    class MeusFavoritos extends Controller
    {
        private $model;
        private $usuario_id;

        public function __construct()
        {
            $this->model = new MeusFavoritosModel();
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

            $variaveis = [
                'favoritos'=>$arrFavoritos
            ];

            $this->view('meusFavoritos',$variaveis,'minhaconta');
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
