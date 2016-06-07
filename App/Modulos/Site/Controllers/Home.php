<?php
namespace Rocharor\Site\Controllers;

use Rocharor\Sistema\Controller;
use Rocharor\Site\Models\HomeModel;

class Home extends Controller
{

    private $model;
    
    public function __construct()
    {
        $this->model = new HomeModel;
    }

    public function indexAction()
    {
        $arrFrases = $this->model->buscar('frases');        
  
        $variaveis = [
            'active_1' => 'active',
            'arrFrases' => $arrFrases[rand(0,(count($arrFrases)-1))]
        ];
        
        $this->view('index', $variaveis);
    }

    public function erroAction()
    {
        $variaveis = [];
        
        $this->view('404', $variaveis);
    }
}
