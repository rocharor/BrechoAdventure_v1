<?php
namespace Rocharor\Site\Models;

use Rocharor\Sistema\Model;

class ProdutoModel extends Model
{

    /**
     * Traz todos anuncios
     */
    public function getProdutos($limit = false, $categorias = false)
    {
        $where = ' status = 1 ';
        if($categorias){
            $where .= " AND categoria_id IN ($categorias) ";
        }       
        
        $sql = "SELECT id,usuario_id,categoria_id,titulo,descricao,valor,estado,nm_imagem,data_cadastro,status
                FROM produtos
                WHERE {$where}       
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
        
        $sql = "SELECT p.categoria_id,p.titulo,p.descricao,p.valor,p.estado,p.nm_imagem,u.nome,u.email,u.telefone_fixo as fixo,u.telefone_cel as cel
                FROM produtos p
                inner join usuarios u
                on (u.id = p.usuario_id)
                WHERE p.id = {$produto_id}";
        
        $arrProduto = $this->conn->query($sql)->fetch(\PDO::FETCH_ASSOC);
        
        return $arrProduto;
    }

    public function setProduto($usuario_id, $titulo, $categoria_id, $descricao, $tipo, $valor, $nome_fotos)
    {
        $sql = "INSERT INTO produtos (usuario_id,categoria_id,titulo,descricao,valor,estado,nm_imagem,status) VALUES (:usuario_id,:categoria_id,:titulo,:descricao,:valor,:estado,:nm_imagem,2)";
        
        $param = [
            ':usuario_id' => $usuario_id,
            ':categoria_id' => $categoria_id,
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
    
    public function buscarCategoriaFiltro($param = [])
    {
        $sql = "SELECT  cp.id, cp.categoria,COUNT(1) as 'qtd' 
                FROM produtos p
                INNER JOIN categoria_produto cp
                ON (cp.id = p.categoria_id)
                WHERE p.status = 1
                GROUP BY cp.id";
        
        $rs = $this->conn->query($sql);
        $arrCategorias = [];
        while($row = $rs->fetch(\PDO::FETCH_ASSOC)){
            $arrCategorias[$row['id']] = $row['categoria'].' ('.$row['qtd'].')';
        }        
        
        return $arrCategorias;
    }
    
    public function buscarFiltroBusca($busca)
    {
        $sql = "SELECT * FROM produtos WHERE (titulo LIKE '%{$busca}%' OR descricao LIKE '%{$busca}%') AND STATUS = 1;";
    
        $rs = $this->conn->query($sql);
        $arrProdutos = [];
        while($row = $rs->fetch(\PDO::FETCH_ASSOC)){
            $arrProdutos[] = $row;
        }
    
        return $arrProdutos;
    }
}
