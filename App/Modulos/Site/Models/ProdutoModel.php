<?php

namespace Rocharor\Site\Models;

class ProdutoModel {

    /**
    * Traz todos anuncios
    *
    */
    public function getProdutos($limit=false)
    {
        global $conn;

        $sql = "SELECT id,usuario_id,categoria,titulo,descricao,valor,estado,nm_imagem,data_cadastro,status
                FROM produtos
                WHERE status = 1
                ORDER BY status DESC, id DESC";

        if($limit){
            $sql .= " LIMIT {$limit}";
        }

        $produtos = array();
        $rs = $conn->query($sql);
        while($row = $rs->fetch(\PDO::FETCH_ASSOC)){
            $produtos[] = $row;
        }

        return $produtos;
    }

    public function getCountProduto()
    {
        global $conn;

        $sql = "SELECT count(1) as total
                FROM produtos
                WHERE status = 1";

        $totalProdutos = $conn->query($sql)->fetch(\PDO::FETCH_ASSOC);

        return $totalProdutos['total'];
    }

    /**
    * Traz dados de um produto
    *
    */
    public function getDescricaoProduto($produto_id){
        global $conn;

        $produto_id = (int)$produto_id;

        $sql = "SELECT p.categoria,p.titulo,p.descricao,p.valor,p.estado,p.nm_imagem,u.nome,u.email,u.telefone_fixo as fixo,u.telefone_cel as cel
                FROM produtos p
                inner join usuarios u
                on (u.id = p.usuario_id)
                WHERE p.id = {$produto_id}";

        $arrProduto = $conn->query($sql)->fetch(\PDO::FETCH_ASSOC);

        return $arrProduto;
    }

    public function setProduto($titulo,$categoria,$descricao,$tipo,$valor,$nome_fotos)
    {
        global $conn;

        /*$titulo = $_POST['titulo_produto'];
        $categoria = $_POST['categoria_produto'];
        $descricao = $_POST['desc_produto'];
        $tipo = $_POST['tipo_produto'];
         $foto1 = $_FILES['foto1'];
        $foto2 = $_FILES['foto2'];
        $foto3 = $_FILES['foto3'];
        $valor = $_POST['valor_produto'];
        */
        $sql = "INSERT INTO produtos (usuario_id,categoria,titulo,descricao,valor,estado,nm_imagem,data_cadastro,status) VALUES (:usuario_id,:categoria,:titulo,:descricao,:valor,:estado,:nm_imagem,NOW(),1)";

        $param = [':usuario_id'=>1,':categoria'=>$categoria,':titulo'=>$titulo,':descricao'=>$descricao,':valor'=>$valor,':estado'=>$tipo,':nm_imagem'=>$nome_fotos];

        $rs = $conn->prepare($sql);
        if($rs->execute($param)){
            return true;
        }else{
            return false;
        }
    }
    
    public function getCategoriasProduto(){
        global $conn;
    
        $sql = "SELECT cp.id, cp.categoria FROM categoria_produto cp";               
    
        $arrCategorias = $conn->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    
        return $arrCategorias;
    }

}
