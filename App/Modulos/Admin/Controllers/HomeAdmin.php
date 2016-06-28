<?php
namespace Rocharor\Admin\Controllers;

use Rocharor\Sistema\Controller;
use Rocharor\Sistema\Sessao;
use Rocharor\Admin\Models\HomeAdminModel;

class HomeAdmin extends Controller
{

    private $model;

    public function __construct()
    {
        $this->model = new HomeAdminModel();
    }

    public function indexAction()
    {
        $logadoAdmin = Sessao::pegaSessao('logadoAdmin');
        
        if($logadoAdmin){
            //unset($_SESSION['brechoAdventure']['logadoAdmin']);
            $arrQtdHome = $this->model->buscaQuantidadeHome();      
            $variaveis = ['logadoAdmin'=>$logadoAdmin,'dados'=>$arrQtdHome];
            $this->view('homeAdmin', $variaveis);
        }else{            
            $this->validaLoginAdmin();    
        }
    }
    
    public function validaLoginAdmin()
    {
        if(!empty($_POST)){
            $login = trim($_POST['login']);
            $senha = trim($_POST['senha']);
        
            if($login == 'admin' && $senha == 'admin'){
                Sessao::setaSessao(['logadoAdmin'=>1]);
                $logadoAdmin = Sessao::pegaSessao('logadoAdmin');
                $arrQtdHome = $this->model->buscaQuantidadeHome();
                $variaveis = ['logadoAdmin'=>$logadoAdmin,'dados'=>$arrQtdHome];
            }else{
                $variaveis = ['logadoAdmin'=>false];
            }
        
        }else{
            $variaveis = ['logadoAdmin'=>false];
        }
        
        $this->view('homeAdmin', $variaveis);
        
    }

    
    
    
    
    
    
    
    
    public function buscaDadosAction($tipo, $valor)
    {
        $arrProdutos = $this->model->buscaDados($tipo, $valor);
      
        $variaveis = [
            'tipo' => $valor,
            'arrProdutos' => $arrProdutos
        ];
        $this->view('informacoesAdmin', $variaveis);
    }
}