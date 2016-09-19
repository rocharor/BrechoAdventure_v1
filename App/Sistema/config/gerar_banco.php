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
    
    mysqli_set_charset($con,"utf8");
    
    $query_database = "CREATE DATABASE " . $db . " DEFAULT CHARACTER SET utf8  DEFAULT COLLATE utf8_general_ci ";
    if(mysqli_query($con,$query_database)){
        echo "Banco de dados (" . $db . ") criado com sucesso\n\r";
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
      `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `data_alteracao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,      
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
      `categoria_id` int(11) DEFAULT NULL,
      `titulo` varchar(255) DEFAULT NULL,
      `descricao` text,
      `valor` decimal(10,2) DEFAULT '0.00',
      `estado` enum('Novo','Usado') DEFAULT NULL,
      `nm_imagem` varchar(255) DEFAULT NULL,
      `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `data_alteracao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,      
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

    "frases"=>
    "CREATE TABLE `frases` (
      `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
      `frase` text,
      `autor` varchar(255) DEFAULT NULL,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8",
    
        
    "Inserts Categorias"=>
    "INSERT INTO categoria_produto (categoria) VALUES ('Aquático'),('Camping'),('Ciclismo'),('Fitnes'),('Trilha & Trekking'),('Escalada');",
        
    "Inserts Frases"=>
    "INSERT INTO `frases`(`id`,`frase`,`autor`) VALUES 
    		(1,'Prefiro carregar o peso de uma mochila nas costas do que o da consciência de não ter conhecido o mundo.','Autor desconhecido'),
    		(2,'A vida começa quando acaba a sua zona de conforto.','Neale Donald Walsch'),
    		(3,'Nós viajamos, não para fugir da vida, mas para a vida não fugir de nós.','Autor desconhecido'),
    		(4,'Uma longa viagem começa com um único passo.','Lao Tsé'),
    		(5,'É graça divina começar bem. Graça maior persistir na caminhada certa. Mas graça das graças é não desistir nunca.','Dom Hélder Câmara'),
    		(6,'Toda grande caminhada começa com um simples passo.','Buda'),
    		(7,'Quando a caminhada fica dura, só os duros continuam caminhando.','Mano Brown');"    
    ];

    mysqli_select_db($con, $db);

    foreach($query_tabelas as $nm_tabela=>$query){
        if(mysqli_query($con,$query)){
            echo "Tabela (" . $nm_tabela . ") criada com sucesso. \n\r ";
        }
        else {
            die('Erro ao criar tabela'. $nm_tabela . mysqli_error($con));
        }
    }
    mysqli_close($con);

