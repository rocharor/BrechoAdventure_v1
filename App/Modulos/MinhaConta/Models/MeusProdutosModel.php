<?php

namespace Rocharor\MinhaConta\Models;

class MeusProdutosModel {
    
    public function getMeusProdutos($usuario_id)
    {
        global $conn;

        $usuario_id = (int)$usuario_id;

        $sql = "SELECT * FROM produtos WHERE usuario_id = {$usuario_id}";

        $arrProdutos = array();
        $rs = $conn->query($sql);
        while($row = $rs->fetch(\PDO::FETCH_ASSOC)){
            $arrProdutos[$row['id']] = $row;
        }

        return $arrProdutos ;
    }
}
