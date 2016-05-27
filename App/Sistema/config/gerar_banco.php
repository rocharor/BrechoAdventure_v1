<?php

    if(file_exists('mysql.ini')) {
        $dados = parse_ini_file('mysql.ini');
    }else{
        die('Dados do Banco não encontrados');        
    }
    
    $host = $dados['host'];
    $user = $dados['user'];
    $pass = $dados['pass'];
    $db = $dados['db'];
    
    $con = mysqli_connect($host, $user, $pass) or
    die('Não foi possível conectar');

    $query_database = "CREATE DATABASE " . $db;
    if(mysqli_query($con,$query_database)){
        echo "Banco de dados criado \n\r";
    }
    else {
        die("Erro criando o banco de dados \n\r".mysqli_error($con));
    }

    $query_tabelas = [
    "usuarios"=>
    "CREATE TABLE `usuarios` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `nome` varchar(255) DEFAULT NULL,
      `email` varchar(100) DEFAULT NULL,
      `apelido` varchar(100) DEFAULT NULL,
      `dt_nascimento` date DEFAULT NULL,
      `endereco` varchar(255) DEFAULT NULL,
      `numero` varchar(10) DEFAULT NULL,
      `complemento` varchar(150) DEFAULT NULL,
      `bairro` varchar(150) DEFAULT NULL,
      `cidade` varchar(150) DEFAULT NULL,
      `uf` varchar(2) DEFAULT NULL,
      `cep` varchar(9) DEFAULT NULL,
      `telefone_fixo` varchar(20) DEFAULT NULL,
      `telefone_cel` varchar(20) DEFAULT NULL,
      `nome_imagem` varchar(255) DEFAULT NULL,
      `senha_ext` varchar(255) DEFAULT NULL,
      `senha_md5` varchar(255) DEFAULT NULL,
      `data_cadastro` datetime DEFAULT NULL,
      `data_alteracao` datetime DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8",

    "mensagens"=>
    "CREATE TABLE `mensagens` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `nome` varchar(100) DEFAULT NULL,
      `email` varchar(100) DEFAULT NULL,
      `tipo` varchar(100) DEFAULT NULL,
      `mensagem` text,
      `status_resposta` int(11) DEFAULT '0',
      `data_mensagem` datetime DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8",

    "produtos"=>
    "CREATE TABLE `produtos` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `usuario_id` int(11) DEFAULT NULL,
      `categoria` varchar(100) DEFAULT NULL,
      `titulo` varchar(255) DEFAULT NULL,
      `descricao` text,
      `valor` decimal(10,2) DEFAULT '0.00',
      `estado` enum('Novo','Usado') DEFAULT NULL,
      `nm_imagem` varchar(255) DEFAULT NULL,
      `data_cadastro` datetime DEFAULT NULL,
      `data_alteracao` datetime DEFAULT NULL,
      `data_exclusao` datetime DEFAULT NULL,
      `status` int(11) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8",

    "favoritos"=>
    "CREATE TABLE `favoritos` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `usuario_id` int(11) DEFAULT NULL,
      `produto_id` int(11) DEFAULT NULL,
      `status` int(11) DEFAULT NULL,
      `data_cadastro` datetime DEFAULT NULL,
      `data_exclusao` datetime DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8",
        
    "categoria_produto"=>
    "CREATE TABLE `categoria_produto` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `categoria` varchar(100) DEFAULT NULL,  
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8",
        
    "Inserts"=>
    "INSERT INTO categoria_produto (categoria) VALUES ('Aquatico'),('Camping'),('Ciclismo'),('Fitnes'),('Trilha & Trekking'),('Escalada');"
        
    ];

    mysqli_select_db($con, $db);

    foreach($query_tabelas as $nm_tabela=>$query){
        if(mysqli_query($con,$query)){
            echo "Tabela " . $nm_tabela . " criada com sucesso. \n\r ";
        }
        else {
            die('Erro ao criar tabela'. $nm_tabela . mysqli_error($con));
        }
    }
    mysqli_close($con);

