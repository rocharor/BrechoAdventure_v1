<?php
    namespace Rocharor\MinhaConta\Controllers;

    use Rocharor\Sistema\Controller;
    use Rocharor\Sistema\Sessao;
    use Rocharor\Sistema\Padrao;
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

            $variaveis = [
                'meusProdutos'=>$meusProdutos
            ];

            $this->view('meusProdutos',$variaveis);
        }

        public function meusProdutosEditarAction($produto_id,$msg='')
        {

            $objProduto = new ProdutoModel();
            $descProduto = $objProduto->getDescricaoProduto($produto_id);
            $arrCategorias = $objProduto->getCategoriasProduto();

            $variaveis = [
                'descProduto'=>$descProduto,
                'arrCategorias'=>$arrCategorias,
                'produto_id'=>$produto_id,
                'msg' => $msg
            ];

            $this->view('meusProdutosEditar',$variaveis);
        }
        
        public function alterarProdutoAction($produto_id)
        {
            $titulo    = $_POST['titulo_produto_update'];
            $categoria = $_POST['categoria_produto_update'];
            $descricao = $_POST['desc_produto_update'];
            $estado    = $_POST['tipo_produto_update'];
            $valor     = str_replace(['R$ ','.',','], ['','','.'], $_POST['valor_produto_update']);
            $fotos = [];
        
            if (! Padrao::validaExtImagem($fotos)) {
                $msg = '<div class="alert alert-danger" align="center" style="width: 400px;">Uma ou mais fotos estão com formato não permitido</div>';
            } else {
        
                $nome_fotos = [];
                foreach ($_FILES as $key=>$foto) {
                    if($foto['name'] == ''){
                        continue;
                    }
                    $arrNomeFoto = explode('.',$foto['name']);
                    $extencao = end($arrNomeFoto);
                    $foto_nome = $this->usuario_id . '_' . $key . '_' .date('d-m-Y_h_i_s') . '.' . $extencao;
                    move_uploaded_file($foto['tmp_name'], _IMAGENS_ . 'produtos/' . $foto_nome);
                    $nome_fotos[] = $foto_nome;
                }
                $objProduto = new ProdutoModel();
                $arrProduto = $objProduto->getDescricaoProduto($produto_id);
        
                $nome_fotos = implode('|', $nome_fotos);
                if(!empty($nome_fotos)){
                    if(!empty($arrProduto['nm_imagem'])){
                        $nome_fotos = $arrProduto['nm_imagem'].'|'.$nome_fotos;
                    }
                }
        
                $retorno = $this->model->alterarProduto($titulo, $categoria, $descricao, $estado, $valor, $nome_fotos, $produto_id);
        
                if ($retorno) {
                    $msg = '<div class="alert alert-success" align="center" style="width: 400px;">Produto alterado com sucesso</div>';
                } else {
                    $msg = '<div class="alert alert-danger" align="center" style="width: 400px;">Erro ao alterar produto</div>';
                }
            }
        
            $objProduto = new ProdutoModel();
            $descProduto = $objProduto->getDescricaoProduto($produto_id);
            $arrCategorias = $objProduto->getCategoriasProduto();
        
            $this->meusProdutosEditarAction($produto_id,$msg);
        
            /* $variaveis = [
             'descProduto'=>$descProduto,
             'arrCategorias'=>$arrCategorias,
             'produto_id'=>$produto_id,
             'msg' => $msg,
             ]; */
        
            //$this->view('meusProdutosEditar', $variaveis);
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

        public function deletarFotoAction()
        {
            $produto_id = $_POST['produto_id'];
            $nm_foto = $_POST['nm_foto'];

            $objProduto = new ProdutoModel();
            $descProduto = $objProduto->getDescricaoProduto($produto_id);
            $arrFotos = explode('|',$descProduto['nm_imagem']);
            $key = array_search($nm_foto,$arrFotos);
            unset($arrFotos[$key]);
            $nm_imagem = implode('|',$arrFotos);

            $ret = $this->model->deletarFotoProduto($produto_id,$nm_imagem);

            if($ret){
                unlink(_IMAGENS_.'produtos/'.$nm_foto);
                $retorno = ['sucesso'=>true,'msg'=>'Foto excluída com sucesso'];
            }else{
                $retorno = ['sucesso'=>false,'msg'=>'Erro ao excluir foto'];
            }

            echo json_encode($retorno);
            die();

        }
       
    }
