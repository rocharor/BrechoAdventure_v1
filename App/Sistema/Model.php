<?php
namespace Rocharor\Sistema;

abstract class Model
{    
    protected $conn;    
    
    public function __construct()
    { 
        global $conn; 
        $this->conn = $conn;   
    }
    
    /**
     * Método para fazer buscas padrão
     * @param unknown $tabela = Nome da tabela
     * @param array $arrWhere = Array contendo os parametros EX:($arrWhere = ['id'=>5, 'status'=>1])
     * @param array $arrOrder = Array contendo apenas 1 os parametros para ordenação EX:($arrOrder = ['id'=>DESC])
     * @param string $tudo = Caso TRUE traz tudo, Caso FALSE traz apenas 1
     * @return Retorna os dados
     */
    public function buscar($tabela, $arrWhere = [], $tudo = true,  $arrOrder = [])
    {
        $where = ' 1 ';
        $order = '';
       
        foreach ($arrWhere as $coluna => $valor) {
            $where .= " AND " . trim($coluna) ." = " .  trim($valor);
        }
        
        if (count($arrOrder) > 0) {
            $order .= " ORDER BY " . trim(key($arrOrder[0])) . ' ' . trim(value($arrOrder[0]));
        }
        
        $sql = "SELECT * FROM $tabela WHERE $where  $order";
      
        $rs = $this->conn->query($sql);        
 
        if ($tudo) {
            return $rs->fetchAll(\PDO::FETCH_ASSOC);
        } else {
            return $rs->fetch(\PDO::FETCH_ASSOC);
        }
    }
    
    /**
     * Método para fazer inserções padrão
     * @param unknown $tabela = Nome da tabela
     * @param unknown $arrValores = Array contendo campos e valores Ex: (['nome'=>'ricardo','idade'=>29])
     * @return boolean
     */
    public function inserir($tabela, $arrValores)
    {        
        $colunas = array_keys($arrValores);        
        $valores = array_values($arrValores);
        
        foreach($colunas as $values){
            $colunas_prepare[] = ":".$values;
        }
                
        $parametros = array_combine ($colunas_prepare,$valores); 
        
        $colunas = implode(',',$colunas);
        $colunas_prepare = implode(',',$colunas_prepare);
        
        $sql = "INSERT INTO $tabela ($colunas) VALUES ($colunas_prepare)";

        $rs = $this->conn->prepare($sql);        
    
        if ($rs->execute($parametros)) {
            return true;
        } else {
            return false;
        }        
    }
    
    /**
     * Método para fazer deleções padrão
     * @param unknown $tabela = Nome da tabela
     * @param unknown $arrValores = Array contendo apenas 1 campo e valor Ex: (['id'=>3])
     * @return boolean
     */
    public function deletar($tabela, $arrWhere)
    {     
        $coluna = key($arrWhere);
        $coluna_prepare = ':'.$coluna;
        $parametro = [
            $coluna_prepare=>$arrWhere['id']
            
        ];
        
        $sql = "DELETE FROM $tabela WHERE $coluna = $coluna_prepare";
       
        $rs = $this->conn->prepare($sql);
        
        if ($rs->execute($parametro)) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Método para fazer edições padrão 
     * @param unknown $tabela = Nome da tabela
     * @param unknown $arrValores = Array contendo campos e valores Ex: (['nome'=>'ricardo','idade'=>29])
     * @param unknown $arrWhere = Array contendo campo e valor Ex: (['id'=>3,'id'=>4])
     */
    public function editar($tabela,$arrValores,$arrWhere)
    {
        try {
            $campos = [];
            $parametro = [];
            foreach($arrValores as $coluna=>$valor){            
                $coluna_prepare = ':'.$coluna;
                $campos[] = $coluna.' = '.$coluna_prepare;
                $parametro[$coluna_prepare] = $valor;
            }
            $campos = implode(',',$campos);
            
            $where = ' 1 ';
            foreach($arrWhere as $coluna=>$valor){
                $where .= " AND $coluna = $valor ";
            }
            
            $sql = "UPDATE $tabela SET $campos WHERE $where";
            
            $rs = $this->conn->prepare($sql);
            
            if($rs->execute($parametro)){
                return true;
            }else{          
                return false;
            }
        } catch(PDOException $e) {
            echo 'Error: ' . $e->getMessage();
        }
    }
    
    public function buscar2($tabela, $parametros=[],$tudo=true)
    {
    	$campos = [];
    	$where = [];
    	$order = [];
    	$limit = [];
    	 
    	foreach ($parametros as $chave=>$valor) {
    
    		if($chave=='where'){
    			 
    		}
    		if($chave=='order'){
    
    		}
    		if($chave=='limit'){
    
    		}
    	}
    
    	$sql = "SELECT $colunas FROM $tabela . $where . $order . $limit";
    
    	$rs = $this->conn->query($sql);
    
    	if ($tudo) {
    		return $rs->fetchAll(\PDO::FETCH_ASSOC);
    	} else {
    		return $rs->fetch(\PDO::FETCH_ASSOC);
    	}
    }
}
