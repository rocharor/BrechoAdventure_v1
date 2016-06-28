<?php
namespace Rocharor\Admin\Models;

use Rocharor\Sistema\Model;
use Rocharor\Sistema\Padrao;

class AdminModel extends Model
{
    /**
     * 
     */
    public function buscaQuantidadeHome()
    {        
        $sql = "SELECT 
                CASE status
                    WHEN 0 THEN 'Excluido' 
                    WHEN 1 THEN 'Ativo' 
                    WHEN 2 THEN 'Pendente' 
                END AS status,
                COUNT(1) as qtd                
                FROM produtos 
                GROUP BY status";
        $rs = $this->conn->query($sql);        
        $auxSoma = 0;
        while($row = $rs->fetch(\PDO::FETCH_ASSOC)){
            $produtos[$row['status']] = ['qtd'=>(int)$row['qtd'],'status'=>strtolower($row['status'])];
            $auxSoma += $row['qtd'];
        }
        $produtos['Total']['qtd'] = $auxSoma;
        $produtos['Total']['status'] = 'todos';
        
        
        $sql = "SELECT 
                CASE tipo
                    WHEN 1 THEN 'Dúvida'
                    WHEN 2 THEN 'Reclamação'
                    WHEN 3 THEN 'Sugestão'
                    WHEN 4 THEN 'Elogio'
                END AS tipo,
                COUNT(1) AS qtd                
                FROM mensagens
                GROUP BY tipo;";
        $rs = $this->conn->query($sql);
        $auxSoma = 0;
        while($row = $rs->fetch(\PDO::FETCH_ASSOC)){
            
            $mensagens[$row['tipo']] = ['qtd'=>(int)$row['qtd'],'tipo'=>strtolower(Padrao::removeAcentos($row['tipo']))];
            $auxSoma += $row['qtd'];
        }
        $mensagens['Total']['qtd'] = $auxSoma;
        $mensagens['Total']['tipo'] = 'todos';
        
        return ['produtos'=>array_reverse($produtos),'mensagens'=>array_reverse($mensagens)];
    }
    
    /**
     * 
     * @param unknown $tipo
     * @param unknown $valor
     */
    public function buscaDados($tabela,$where)
    {        
        $tabela = trim($tabela);
           
        $arrDados = $this->buscar($tabela,$where);
        
        return $arrDados;
        
    }
    
    /**
     * 
     * @param unknown $arrProdutos
     * @return boolean
     */
    public function aprovarProduto($arrProdutos)
    {
        $erro = 0;
        foreach($arrProdutos as $produto_id){
            if($erro == 1){
                return false;
            }
            $parametro = [':id'=>(int)$produto_id];
            $sql = "UPDATE produtos SET status = 1 WHERE id = :id;";
            
            $rs = $this->conn->prepare($sql);
            if(!$rs->execute($parametro)){
                $erro = 1;
            }          
        }
        return true;               
    }
}
