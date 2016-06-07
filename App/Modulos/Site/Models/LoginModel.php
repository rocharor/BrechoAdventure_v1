<?php
namespace Rocharor\Site\Models;

use Rocharor\Sistema\Model;

class LoginModel extends Model
{

    public function validaLogin($email, $senha)
    {
        $email = trim($email);
        $senha = trim($senha);
        
        $sql = "SELECT id,email,senha_ext,nome_imagem FROM usuarios";
        
        $rs = $this->conn->query($sql);
        while ($row = $rs->fetch(\PDO::FETCH_ASSOC)) {
            if ($row['email'] == $email && $row['senha_ext'] == $senha) {
                return [
                    'id' => $row['id'],
                    'nome_imagem' => $row['nome_imagem']
                ];
            }
        }
        return false;
    }
}
