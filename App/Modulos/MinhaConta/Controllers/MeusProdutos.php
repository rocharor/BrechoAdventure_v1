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
            
            $variaveis = [
                'pagina_main' => VIEWS_MC.'meusProdutosEditar.html',
                'descProduto'=>$descProduto,
                'arrCategorias'=>$arrCategorias,
                'produto_id'=>$produto_id,
                'msg' => '',
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
                $retorno = ['sucesso'=>true,'msg'=>'Foto excluída com sucesso'];
            }else{
                $retorno = ['sucesso'=>false,'msg'=>'Erro ao excluir foto'];
            }
        
            echo json_encode($retorno);
            die();
        
        }
        
        public function alterarProdutoAction($produto_id)
        {                      
            $titulo    = $_POST['titulo_produto_update'];
            $categoria = $_POST['categoria_produto_update'];
            $descricao = $_POST['desc_produto_update'];
            $estado    = $_POST['tipo_produto_update'];
            $valor     = $_POST['valor_produto_update'];
            
            if(isset($_FILES)){
                foreach($_FILES as $foto){
                    if($foto['name'] != ''){
                        $fotos[] = $foto;
                    }                                        
                }
            }
            
            if (! $this->validaExtImagem($fotos)) {
                $msg = '<div class="alert alert-danger" align="center" style="width: 400px;">Uma ou mais fotos estão com formato não permitido</div>';
            } else {
                foreach ($fotos as $key=>$foto) {
                    $arrNomeFoto = explode('.',$foto['name']);
                    $extencao = end($arrNomeFoto);
                    $foto_nome = $this->usuario_id . '_' . $key . '_' .date('d-m-Y_h_i_s') . '.' . $extencao;
                    move_uploaded_file($foto['tmp_name'], _IMAGENS_ . 'produtos/' . $foto_nome);
                    $nome_fotos[] = $foto_nome;
                }
                $nome_fotos = implode('|', $nome_fotos);
                           
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
            
            $variaveis = [
                'pagina_main' => VIEWS_MC.'meusProdutosEditar.html',
                'descProduto'=>$descProduto,
                'arrCategorias'=>$arrCategorias,
                'produto_id'=>$produto_id,
                'msg' => $msg,
            ];     
            
            $this->view('main', $variaveis);
        
        }
        
    }
