<?php

namespace Rocharor\MinhaConta\Models;

class PerfilModel {

    /**
    * Atualiza dados dos usuÃ¡rios
    *
    */
    public static function getFavoritos()
    {
        global $conn;
        
        $sql = "SELECT * FROM favoritos";
        $favoritos = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        
        return $favoritos;        
    }

      

}
