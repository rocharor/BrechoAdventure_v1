<?php

namespace Rocharor\Site\Controllers;

use Rocharor\Sistema\Controller;
use Rocharor\Site\Models\ProdutoModel;
use Rocharor\MinhaConta\Models\MeusFavoritosModel;
use Rocharor\Sistema\Sessao;


class Produto extends Controller
{
    private $objProdutoModel;
    private $objMeusFavoritosModel;
    private $params;

    public function __construct($params = false)
    {
        $objProdutoModel = new ProdutoModel();
        $this->objProdutoModel = $objProdutoModel;

        if ($params) {
            $this->params = $params;
        }

        if(Sessao::pegaSessao('logado')){
            $objMeusFavoritos = new MeusFavoritosModel();
            $this->objMeusFavoritosModel = $objMeusFavoritos;
        }

    }

    /**
     * Abre aba Produtos listando os 12 ultimos produtos adicionados
     */
    public function indexAction()
    {
        $produtos = $this->objProdutoModel->getprodutos(9);

        $favoritos = [];
        $usuario_id = '';
        if(Sessao::pegaSessao('logado')){
            $usuario_id = Sessao::pegaSessao('logado');
            $favoritos = $this->objMeusFavoritosModel->getFavoritos($usuario_id);
        }

        foreach($produtos as $key=>$produto){
            $fotos = explode('|',$produto['nm_imagem']);
            $produtos[$key]['img_principal'] = $fotos[0];
            if(in_array($produto['id'],$favoritos)){
                $produtos[$key]['favorito'] = 1;
            }else{
                $produtos[$key]['favorito'] = 0;
            }
        }

        $variaveis = [
            'produtos'=>$produtos,
            'favoritos'=>$favoritos,
            'active_2'=>'active',
            'usuario_id'=>$usuario_id
        ];

        $this->view('produtos', $variaveis);
    }

    /**
     * Traz todos os produtos cadastrados;
     */
    public function todosProdutosAction()
    {

        $total_pagina = 9;

        if ($this->params == 1) {
            $limit = $total_pagina;
        } else {
            $limit_inicio = (($this->params - 1) * $total_pagina);
            $limit_fim = ($limit_inicio + $total_pagina);
            $limit = "{$limit_inicio},{$limit_fim}";
        }

        $totalProdutos = $this->objProdutoModel->getCountProduto();
        $paginacao = ceil($totalProdutos / $total_pagina);

        $produtos = $this->objProdutoModel->getprodutos($limit);

        $favoritos = [];
        if(Sessao::pegaSessao('logado')){
            $user_id = Sessao::pegaSessao('logado');
            $favoritos = $this->objMeusFavoritosModel->getFavoritos($user_id);
        }

        foreach($produtos as $key=>$produto){
            if(in_array($produto['id'],$favoritos)){
                $produtos[$key]['favorito'] = 1;
            }else{
                $produtos[$key]['favorito'] = 0;
            }
        }

        $variaveis = ['produtos'=>$produtos,'active_2'=>'active','pg'=>$this->params,'paginacao'=>$paginacao ];

        $this->view('todosProdutos', $variaveis);
    }

    /**
     * Traz os dados dos produto
     */
    public function getDescricaoProdutoAction()
    {
        $produto_id = $_POST['produto_id'];

        $arrProduto = $this->objProdutoModel->getDescricaoProduto($produto_id);

        echo json_encode($arrProduto);
        die();
    }


}
