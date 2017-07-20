<?php
namespace Rocharor\Site\Controllers;

use Rocharor\Sistema\Controller;
use Rocharor\Site\Models\ContatoModel;
use Rocharor\Site\Models\EmailModel;

class Contato extends Controller
{

    private $model;
    
    public function __construct()
    {
        $this->model = new ContatoModel();        
    }
    
    public function indexAction()
    {
        if (! empty($_POST)) {
            $this->salvaMensagemAction($_POST);
        } else {
            $variaveis = [
                'active_3' => 'active',
                'msg' => ''
            ];
            
            $this->view('contato', $variaveis);
        }
    }

    /**
     * Salvo os dados de contato no banco de dados e manda email
     * 
     * @param unknown $dados            
     */
    Public function salvaMensagemAction($dados)
    {        
        $retorno = $this->model->setMensagem($dados);
        
        if ($retorno) {
            $msg = '<div class="alert alert-success" align="center" style="width: 400px;">Mensagem enviada com sucesso</div>';
            $emailModel = new EmailModel();
            $emailModel->respAutomaticaContato($dados['nome'], $dados['email']);            
        } else {
            $msg = '<div class="alert alert-danger" align="center" style="width: 400px;">Erro ao enviar a mensagem</div>';
        }
        
        $variaveis = [
            'active_3' => 'active',
            'msg' => $msg
        ];
        
        $this->view('contato', $variaveis);
    }
}
