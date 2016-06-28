<?php
namespace Rocharor\Site\Models;

use Rocharor\Sistema\Model;

class LoginModel extends Model
{

    /**
     * Valida login USUARIO
     * @param unknown $email
     * @param unknown $senha
     */
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
    
    /**
     * Valida o login da area do ADMIN
     * @param unknown $login
     * @param unknown $senha
     * @return boolean
     */
    public function validaLoginAdmin($login, $senha)
    {
        $login = trim($login);
        $senha = trim($senha);
        
        if($login == 'admin' && $senha == 'admin'){
            return true;                
        }else{
            return false;
        }
    }
}
