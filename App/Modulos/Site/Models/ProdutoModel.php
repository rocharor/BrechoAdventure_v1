<?php
namespace Rocharor\Site\Models;

use Rocharor\Sistema\Model;

class ProdutoModel extends Model
{

    /**
     * Traz todos anuncios
     */
    public function getProdutos($limit = false)
    {
        $sql = "SELECT id,usuario_id,categoria,titulo,descricao,valor,estado,nm_imagem,data_cadastro,status
                FROM produtos
                WHERE status = 1
                ORDER BY status DESC, id DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        $produtos = array();
   
        $rs = $this->conn->query($sql);
        while ($row = $rs->fetch(\PDO::FETCH_ASSOC)) {
            $produtos[] = $row;
        }
        
        return $produtos;
    }

    public function getCountProduto()
    {
        $sql = "SELECT count(1) as total
                FROM produtos
                WHERE status = 1";
        
        $totalProdutos = $this->conn->query($sql)->fetch(\PDO::FETCH_ASSOC);
        
        return $totalProdutos['total'];
    }

    /**
     * Traz dados de um produto
     */
    public function getDescricaoProduto($produto_id)
    {
        $produto_id = (int) $produto_id;
        
        $sql = "SELECT p.categoria,p.titulo,p.descricao,p.valor,p.estado,p.nm_imagem,u.nome,u.email,u.telefone_fixo as fixo,u.telefone_cel as cel
                FROM produtos p
                inner join usuarios u
                on (u.id = p.usuario_id)
                WHERE p.id = {$produto_id}";
        
        $arrProduto = $this->conn->query($sql)->fetch(\PDO::FETCH_ASSOC);
        
        return $arrProduto;
    }

    public function setProduto($usuario_id, $titulo, $categoria, $descricao, $tipo, $valor, $nome_fotos)
    {
        $sql = "INSERT INTO produtos (usuario_id,categoria,titulo,descricao,valor,estado,nm_imagem,data_cadastro,status) VALUES (:usuario_id,:categoria,:titulo,:descricao,:valor,:estado,:nm_imagem,NOW(),1)";
        
        $param = [
            ':usuario_id' => $usuario_id,
            ':categoria' => $categoria,
            ':titulo' => $titulo,
            ':descricao' => $descricao,
            ':valor' => $valor,
            ':estado' => $tipo,
            ':nm_imagem' => $nome_fotos
        ];
        
        $rs = $this->conn->prepare($sql);
        if ($rs->execute($param)) {
            return true;
        } else {
            return false;
        }
    }

    public function getCategoriasProduto()
    {
        $sql = "SELECT cp.id, cp.categoria FROM categoria_produto cp";
        
        $arrCategorias = $this->conn->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
        
        return $arrCategorias;
    }
}
