<?php

namespace Rocharor\Site\Controllers;

use Rocharor\Sistema\Controller;
use Rocharor\Site\Models\CadastroModel;
use Rocharor\Sistema\Sessao;

class Cadastro extends Controller {
    private $nome;
    private $apelido;
    private $email;
    private $senha;

    public function indexAction() {
        $this->nome = $_POST ['nome'];
        $this->apelido = $_POST ['apelido'];
        $this->email = $_POST ['email'];
        $this->senha = $_POST ['senha'];

        $this->cadastrarUsuarioAction();
    }

    public function cadastrarUsuarioAction() {
        $cadastroModel = new CadastroModel();
        $dados = $cadastroModel->setUsuario($this->nome, $this->apelido, $this->email, $this->senha);

        if ($dados) {
            Sessao::setaSessao(array('logado'=> $dados['id'],'nome_imagem'=> $dados['nome_imagem']));
            $retorno = ['sucesso'=>true,'msg'=>'Cadastro criado com sucesso'];
        } else {
            $retorno = ['sucesso'=>false,'msg'=>'Erro ao criar usuario'];
        }

        echo json_encode($retorno);
        die();
    }

}