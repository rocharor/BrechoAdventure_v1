<?php
namespace Rocharor\Site\Models;


use Rocharor\Sistema\Model;

class HomeModel extends Model
{
    public function buscarFrases()
    {
        $arrFrases = $this->buscar('frases');
                
        return $arrFrases;         
    }
}
