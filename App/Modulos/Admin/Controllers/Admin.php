<?php
namespace Rocharor\Admin\Controllers;

use Rocharor\Sistema\Controller;
use Rocharor\Sistema\Sessao;
use Rocharor\Admin\Models\AdminModel;
use Rocharor\Site\Models\LoginModel;

class Admin extends Controller
{

    private $model;

    public function __construct()
    {
        $this->model = new AdminModel();
    }

    /**
     * Direciona para a tela do admin ou login
     */
    public function indexAction()
    {
        $logadoAdmin = Sessao::pegaSessao('logadoAdmin');
        
        if ($logadoAdmin) {
            $arrQtdHome = $this->model->buscaQuantidadeHome();       
            $variaveis = [
                'logadoAdmin' => $logadoAdmin,
                'dados' => $arrQtdHome,
                'escondeMenu' => true
            ];
            $this->view('homeAdmin', $variaveis,'admin');
        } else {
            if (! empty($_POST)) {
                $loginModel = new LoginModel();
                $retorno = $loginModel->validaLogin($_POST['login'], $_POST['senha']);
                if ($retorno) {
                    Sessao::setaSessao([
                        'logadoAdmin' => 1
                    ]);
                    $logadoAdmin = Sessao::pegaSessao('logadoAdmin');
                    $arrQtdHome = $this->model->buscaQuantidadeHome();
                    $variaveis = [
                        'logadoAdmin' => $logadoAdmin,
                        'dados' => $arrQtdHome,
                        'escondeMenu' => true
                    ];
                } else {
                    $variaveis = [
                        'msg'=>'Usuário ou senha inválidos',
                        'logadoAdmin' => false,
                        'escondeMenu' => true
                    ];
                }
            } else {
                $variaveis = [
                    'msg'=>'',
                    'logadoAdmin' => false,
                    'escondeMenu' => true
                ];
            }
            $this->view('homeAdmin', $variaveis,'admin');
        }
    }

    /**
     * 
     * @param unknown $tipo
     * @param unknown $valor
     */
    public function buscaDadosAction($tipo, $valor)
    {
        if($tipo == 'produto'){
            switch($valor){
                case 'todos':$where = [];
                break;
                case 'excluido':$where = ['status'=>0];
                break;
                case 'ativo':$where = ['status'=>1];
                break;
                case 'pendente':$where = ['status'=>2];
                break;
            }  
            $tabela = 'produtos';
            $view = 'produtosAdmin';
            
        }elseif($tipo == 'mensagem'){
            switch($valor){
                case 'todos':$where = [];
                break;
                case 'duvida':$where = ['tipo'=>1];
                break;
                case 'reclamacao':$where = ['tipo'=>2];
                break;
                case 'sugestao':$where = ['tipo'=>3];
                break;
                case 'elogio':$where = ['tipo'=>4];
                break;
            }
            $tabela = 'mensagens';
            $view = 'mensagensAdmin';
        }
        
        $arrDados = $this->model->buscaDados($tabela, $where);
     
        $variaveis = [
            'tipo' => $tipo,
            'valor' => $valor,
            'arrDados' => $arrDados,
            'escondeMenu' => true
        ];
        $this->view($view, $variaveis,'admin');
    }
    
    /**
     * 
     */
    public function aprovarProdutoAction()
    {
        $arrProdutos = $_POST['arrChecks'];
        
        $retorno = $this->model->aprovarProduto($arrProdutos);
        
        /* if($retorno == true){            
            $emailModel = new EmailModel();            
            $retorno = $emailModel->produtoAprovado($email);
        } */
        
        echo json_encode($retorno);
        die();
    }
}