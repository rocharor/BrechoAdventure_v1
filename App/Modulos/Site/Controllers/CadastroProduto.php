<?php
namespace Rocharor\Site\Controllers;

use Rocharor\Sistema\Sessao;
use Rocharor\Sistema\Controller;
use Rocharor\Site\Models\ProdutoModel;
use Rocharor\Site\Models\CadastroModel;

class CadastroProduto extends Controller
{

    public function indexAction()
    {
        $usuario_id = Sessao::pegaSessao('logado');
        
        $autorizado = true;
        
        if($usuario_id){
            $objCadastro = new CadastroModel();
            $dadosUsuario = $objCadastro->getUsuario(['id'=>$usuario_id]);            
            
            if($dadosUsuario[0]['telefone_cel'] == ''){
                $autorizado = false;
            }
            
        }
                
        $variaveis = [
            'pagina_main' => 'cadastroProduto.html',
            'msg' => '',
            'autorizado'=>$autorizado
        ];
        
        $this->view('main', $variaveis);
    }

    public function cadastrarAction()
    {
        $usuario_id = Sessao::pegaSessao('logado');
        
        $titulo = $_POST['titulo_produto'];
        $categoria = $_POST['categoria_produto'];
        $descricao = $_POST['desc_produto'];
        $tipo = $_POST['tipo_produto'];
        $valor = str_replace(['R$ ','.',','], ['','','.'], $_POST['valor_produto']);
        
        $fotos = [];
        if ($_FILES['foto1']['name'] != '') {
            $fotos[] = $_FILES['foto1'];
        }
        if ($_FILES['foto2']['name'] != '') {
            $fotos[] = $_FILES['foto2'];
        }
        if ($_FILES['foto3']['name'] != '') {
            $fotos[] = $_FILES['foto3'];
        }
    
        if (count($fotos) == 0) {
            $msg = '<div class="alert alert-danger" align="center" style="width: 400px;">Necessário escolher pelo menos 1 foto</div>';
        } else 
            if (! $this->validaExtImagem($fotos)) {
                $msg = '<div class="alert alert-danger" align="center" style="width: 400px;">Uma ou mais fotos estão com formato não permitido</div>';
            } else {        
                foreach ($fotos as $key=>$foto) {
                    $arrNomeFoto = explode('.',$foto['name']);
                    $extencao = end($arrNomeFoto);                   
                    $foto_nome = $usuario_id . '_' . $key . '_' .date('d-m-Y_h:i:s') . '.' . $extencao;           
                    move_uploaded_file($foto['tmp_name'], _IMAGENS_ . 'produtos/' . $foto_nome);
                    $nome_fotos[] = $foto_nome;
                }
                $nome_fotos = implode('|', $nome_fotos);
                
                $produtoModel = new ProdutoModel();
                $retorno = $produtoModel->setProduto($titulo, $categoria, $descricao, $tipo, $valor, $nome_fotos);
                
                if ($retorno) {
                    $msg = '<div class="alert alert-success" align="center" style="width: 400px;">Produto inserido com sucesso</div>';
                } else {
                    $msg = '<div class="alert alert-danger" align="center" style="width: 400px;">Erro ao inserir produto</div>';
                }
            }
        $variaveis = [
            'pagina_main' => 'cadastroProduto.html',
            'msg' => $msg,
            'autorizado' => true
        ];
        
        $this->view('main', $variaveis);
    }
}
