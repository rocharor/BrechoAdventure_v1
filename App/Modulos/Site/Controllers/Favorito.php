<?php

namespace Rocharor\Site\Controllers;

use Rocharor\Sistema\Controller;
use Rocharor\Site\Models\FavoritoModel;

class Favorito extends Controller
{
    private $favoritoModel;
    private $params;

    public function __construct($params = false)
    {
        $favoritoModel = new FavoritoModel();
        $this->favoritoModel = $favoritoModel;
    }

    public function setFavoritoAction()
    {
        $usuario_id = $_POST['usuario_id'];
        $produto_id = $_POST['produto_id'];
        $status = $_POST['status'];

        $ret = $this->favoritoModel->setFavorito($usuario_id,$produto_id,$status);

        if($ret){
            $retorno = ['msg'=>'Favorito salvo com sucesso'];
        }else{
            $retorno = ['msg'=>'Erro ao salvar favorito'];
        }
        echo json_encode($retorno);
        die();
    }

}
