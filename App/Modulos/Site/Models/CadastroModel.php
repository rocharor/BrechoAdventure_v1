<?php
namespace Rocharor\Site\Models;

use Rocharor\Sistema\Model;

class CadastroModel extends Model
{

    public function setUsuario($nome, $apelido, $email, $senha)
    {
        $nome = trim($nome);
        $apelido = trim($apelido);
        $email = trim($email);
        $senha = trim($senha);
        $nome_imagem = 'padrao.jpg';
        
        $sql = "INSERT INTO usuarios (nome,apelido,email,nome_imagem,senha_ext,senha_md5) VALUES (:nome,:apelido,:email,:nome_imagem,:senha_ext,:senha_md5)";
        
        $param = [
            ':nome' => $nome,
            ':apelido' => $apelido,
            ':email' => $email,
            ':nome_imagem' => $nome_imagem,
            ':senha_ext' => $senha,
            ':senha_md5' => md5($senha)
        ];
        
        $rs = $this->conn->prepare($sql);
        if ($rs->execute($param)) {
            return [
                'id' => $this->conn->lastInsertId(),
                'nome_imagem' => 'padrao.jpg'
            ];
        } else {
            return false;
        }
    }

    /**
     *
     * @param string $param
     *            = Array contendo nome do campo e valor;
     */
    public function getUsuario($param = false)
    {
        $sql = "SELECT id,
                    nome,
                    apelido,
                    email,
                    date_format(dt_nascimento,'%d/%m/%Y') as 'dt_nascimento',
                    endereco,
                    numero,
                    complemento,
                    bairro,
                    cidade,
                    uf,
                    cep,
                    telefone_fixo,
                    telefone_cel,
                    nome_imagem
                FROM usuarios WHERE 1 ";
        if ($param) {
            $where = "";
            foreach ($param as $key => $value) {
                $where .= " AND " . $key . " = '" . $value . "'";
            }
            $sql .= $where;
        }
        
        $rs = $this->conn->query($sql);
        $arrUsuario = [];
        while ($row = $rs->fetch(\PDO::FETCH_ASSOC)) {
            $arrUsuario[] = $row;
        }
        
        return $arrUsuario;
    }
}
