<?php
namespace Rocharor\Admin\Models;

use Rocharor\Sistema\Model;

class HomeAdminModel extends Model
{
    
    public function buscaQuantidadeHome()
    {        
        $sql = "SELECT 
                CASE status
                WHEN 0 THEN 'Excluidos' 
                WHEN 1 THEN 'Ativos' 
                WHEN 2 THEN 'Pendentes' 
                END AS status,
                COUNT(1) as qtd,
                STATUS AS 'status_id'
                FROM produtos 
                GROUP BY STATUS";
        $rs = $this->conn->query($sql);        
        $auxSoma = 0;
        while($row = $rs->fetch(\PDO::FETCH_ASSOC)){
            $produtos[$row['status']] = ['qtd'=>(int)$row['qtd'],'status_id'=>$row['status_id']];
            $auxSoma += $row['qtd'];
        }
        $produtos['Total']['qtd'] = $auxSoma;
        
        
        $sql = "SELECT 
                CASE tipo
                WHEN 1 THEN 'Dúvida'
                WHEN 2 THEN 'Reclamação'
                WHEN 3 THEN 'Sugestão'
                WHEN 4 THEN 'Elogio'
                END AS tipo,
                COUNT(1) AS qtd,
                tipo AS 'tipo_id'
                FROM mensagens
                GROUP BY tipo;";
        $rs = $this->conn->query($sql);
        $auxSoma = 0;
        while($row = $rs->fetch(\PDO::FETCH_ASSOC)){
            $mensagens[$row['tipo']] = ['qtd'=>(int)$row['qtd'],'tipo_id'=>$row['tipo_id']];
            $auxSoma += $row['qtd'];
        }
        $mensagens['Total']['qtd'] = $auxSoma;       
        
        return ['produtos'=>array_reverse($produtos),'mensagens'=>array_reverse($mensagens)];
    }
    
    
    
    
    
    
    
    
    
    
    
    /* public function buscaDados($tipo,$valor)
    {        
        $tabela = trim($tipo);
        $valor = trim($valor);
        
        switch($valor){
            case 'cadastrados':$arrWhere = false;
            break;
            case 'pendentes':$arrWhere = ['status'=>0];
            break;
            case 'excluidos':$arrWhere = ['status'=>2];
            break;
        }
        $arrWhere = ['status'=>$valor];
        $arrDados = $this->buscar($tabela,$arrWhere);
        
        return $arrDados;
        
    } */
}
