<?php
namespace Rocharor\Site\Models;

use Rocharor\Sistema\Model;

class ContatoModel extends Model
{

    public function setMensagem($dados)
    {
        $nome = trim($dados['nome']);
        $email = trim($dados['email']);
        $tipo = trim($dados['tipo']);
        $mensagem = trim($dados['mensagem']);
        
        $sql = "INSERT INTO mensagens (nome,email,tipo,mensagem,data_mensagem) VALUES (:nome,:email,:tipo,:mensagem,NOW())";
        
        $param = [
            ':nome' => $nome,
            ':email' => $email,
            ':tipo' => $tipo,
            ':mensagem' => $mensagem
        ];
        
        $rs = $this->conn->prepare($sql);
        if ($rs->execute($param)) {
            return true;
        } else {
            return false;
        }
    }
}
