<?php

namespace Rocharor\MinhaConta\Models;

class MeusProdutosModel {
    
    public function getMeusProdutos($usuario_id)
    {
        global $conn;

        $usuario_id = (int)$usuario_id;

        $sql = "SELECT * FROM produtos WHERE usuario_id = {$usuario_id} ORDER BY status DESC, id DESC ";
   

        $arrProdutos = array();
        $rs = $conn->query($sql);
        while($row = $rs->fetch(\PDO::FETCH_ASSOC)){
            $arrProdutos[$row['id']] = $row;
        }

        return $arrProdutos ;
    }
    
    public function deletarProduto($produto_id)
    {
        global $conn;        
        
        $sql = "UPDATE produtos SET status = 0 WHERE id = :produto_id";
        
        $parametros = [':produto_id'=>$produto_id];
        
        $rs = $conn->prepare($sql);
        
        if($rs->execute($parametros)){
            return true;
        }else{
            return false;
        }
        
    }
    
    public function deletarFotoProduto($produto_id,$nm_imagem)
    {
        global $conn;
    
        $sql = "UPDATE produtos SET nm_imagem = :nm_imagem WHERE id = :produto_id";
    
        $parametros = [':produto_id'=>$produto_id,':nm_imagem'=>$nm_imagem];
    
        $rs = $conn->prepare($sql);
    
        if($rs->execute($parametros)){
            return true;
        }else{
            return false;
        }
    
    }
}
