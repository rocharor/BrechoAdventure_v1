<?php

namespace Rocharor\MinhaConta\Models;

class MeusFavoritosModel {

    public function setFavorito($usuario_id,$produto_id,$status)
    {
        global $conn;

        $usuario_id = (int)$usuario_id;
        $produto_id = (int)$produto_id;
        $status = (int)$status;

        if($status == 1){
            $sql = "INSERT INTO favoritos (usuario_id,produto_id,status,data_cadastro) VALUES(:usuario_id,:produto_id,:status,NOW())";
            $params = [":usuario_id"=>$usuario_id,
                       ":produto_id"=>$produto_id,
                       ":status"=>$status];
        }else{
            $sql = "UPDATE favoritos SET status = :status, data_exclusao = NOW() WHERE produto_id = :produto_id;";
            $params = [":produto_id"=>$produto_id,
                       ":status"=>$status];
        }

        $rs = $conn->prepare($sql);

        if($rs->execute($params)){
            return true;
        }else{
            return false;
        }
    }

    public function getFavoritos($user_id)
    {
        global $conn;

        $user_id = (int)$user_id;

        $sql = "SELECT produto_id FROM favoritos WHERE usuario_id = {$user_id} AND status = 1";

        $arrFavoritos = array();
        $rs = $conn->query($sql);
        while($row = $rs->fetch(\PDO::FETCH_ASSOC)){
            $arrFavoritos[] = $row['produto_id'];
        }

        return $arrFavoritos;
    }
}
