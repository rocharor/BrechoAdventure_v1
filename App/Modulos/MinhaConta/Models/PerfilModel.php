<?php

namespace Rocharor\MinhaConta\Models;

class PerfilModel {

    /**
    * Atualiza dados dos usuÃ¡rios
    *
    */
    public static function updateUsuario($user_id, $dados,$foto=false)
    {
        global $conn;

        if($foto){
            $sql = "UPDATE usuarios SET nome_imagem = :nm_foto, data_alteracao = NOW() WHERE id = {$user_id}";
            $param = array(':nm_foto'=>$foto);

            $rs = $conn->prepare($sql);
        }else{
            foreach($dados as $key=>$value){

                $valores[] = trim($key)." = :".trim($key);
                if($key == 'dt_nascimento'){
                    $dt = explode('/',$value);
                    $value = $dt[2].'-'.$dt[1].'-'.$dt[0];
                }

                $param[':'.$key] = trim($value);
            }
            $valores = implode(',',$valores);

            $sql = "UPDATE usuarios SET {$valores}, data_alteracao = NOW() WHERE id = {$user_id}";

            $rs = $conn->prepare($sql);
        }

        if($rs->execute($param))
            return true;
        else
            return false;

    }

}
