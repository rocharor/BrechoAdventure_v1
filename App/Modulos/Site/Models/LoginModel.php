<?php

namespace Rocharor\Site\Models;

class LoginModel {

    public function validaLogin($email,$senha) {
        global $conn;

        $email = trim ( $email );
        $senha = trim ( $senha );

        $sql = "SELECT id,email,senha_ext,nome_imagem FROM usuarios";

        $rs = $conn->query ( $sql );
        while ( $row = $rs->fetch ( \PDO::FETCH_ASSOC ) ) {
            if ($row ['email'] == $email && $row ['senha_ext'] == $senha) {
                return array('id'=>$row['id'],'nome_imagem'=>$row['nome_imagem']);
            }
        }
        return false;
    }
}
