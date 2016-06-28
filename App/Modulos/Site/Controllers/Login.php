<?php

namespace Rocharor\Site\Controllers;

use Rocharor\Sistema\Controller;
use Rocharor\Site\Models\LoginModel;
use Rocharor\Sistema\Sessao;

class Login extends Controller {
    private $email;
    private $senha;

    public function indexAction() {
        $this->email = $_POST ['email'];
        $this->senha = $_POST ['senha'];

        $this->logarAction();
    }

    public function logarAction() {
        $loginModel = new LoginModel();
        $dados = $loginModel->validaLogin($this->email, $this->senha);

        if ($dados) {
            Sessao::setaSessao(array('logado'=> $dados['id'],'nome_imagem'=> $dados['nome_imagem']));
            $retorno = ['sucesso'=>true,'msg'=>'Login efetuado com sucesso.'];
        } else {
            $retorno = ['sucesso'=>false,'msg'=>'Usuário ou senha inválidos.'];
        }
        echo json_encode($retorno);
        die();
    }

    public function deslogarAction() {
        Sessao::setaSessao(array('logado'=> 0,'nome_imagem'=> ''));        
        $logadoAdmin = Sessao::pegaSessao('logadoAdmin');       
        if($logadoAdmin){
            Sessao::excluiSessao('brechoAdventure');            
        }
        
        die();
    }
}