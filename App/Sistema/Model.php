<?php
namespace Rocharor\Sistema;

use Rocharor\Sistema\Conexao;

abstract class Model
{    
    /*
     * private $db = null;
     *
     * public function __construct()
     * {
     * try {
     *
     * $this->db = new \PDO('mysql:host=127.0.0.1;dbname=condominio;', 'root', '');
     * } catch (PDOException $e) {
     * die($e->getMessage());
     * }
     * }
     */

    protected $conn;    
    
    public function __construct()
    { 
        global $conn; 
        $this->conn = $conn;   
    }
    
    /**
     * Método para fazer busca padrões
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
     * Método para fazer inserções padrões
     * @param unknown $tabela = Nome da tabela
     * @param unknown $arrValores = Array contendo campos e valores Ex: (['nome'=>'ricardo','idade'=>29])
     * @return boolean
     */
    public function inserir($tabela, $arrValores)
    {
        $colunas = array_keys($arrValores);        
        $valores = array_values($arrValores);
        
        foreach($colunas as $values){
            $colunas_prepare[] = str_replace("$", ":", $values);
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
     * Método para fazer deleções padrões
     * @param unknown $tabela = Nome da tabela
     * @param unknown $arrValores = Array contendo apenas 1 campo e valor Ex: (['id'=>3])
     * @return boolean
     */
    public function deletar($tabela, $arrValores)
    {
        $coluna = key($arrValores);
        $coluna_prepare = str_replace('$', ':', $coluna);
        $valor = value($arrValores);
        $parametro = array_combine($coluna_prepare, $valor);
        
        $coluna = implode(',',$coluna);
        $coluna_prepare = implode(',',$coluna_prepare);
        
        $sql = "DELETE FROM $tabela WHERE $coluna = $coluna_prepare";
        
        $rs = $this->conn->prepare($sql);
        
        if ($rs->execute($parametro)) {
            return true;
        } else {
            return false;
        }
    }
}
