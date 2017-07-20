<?php
    namespace Rocharor\Sistema;

    class Conexao {

        private $host;
        private $port;
        private $name;
        private $user;
        private $pass;
        private $type;

        public function __construct($arquivo=false){

            if(file_exists($arquivo)) {
                $dados = parse_ini_file($arquivo);
            }else{
                echo 'Dados do Banco não encontrados';
                return false;
            }

            $this->host = $dados['host'];
            $this->port = $dados['port'];
            $this->name = $dados['db'];
            $this->user = $dados['user'];
            $this->pass = $dados['pass'];
            $this->type = $dados['type'];

        }

        /**
        * Faz a conexão com o banco de dados
        *
        * @param mixed $dados = informações do BD
        * @return {\PDO|\PDO_Object}
        */
        public function open(){

            //try {                
                switch($this->type) {
                    case 'mysql':
                        $conn = new \PDO('mysql:host=' . $this->host . '; port=' . $this->port . '; dbname=' . $this->name, $this->user, $this->pass , array(
                            \PDO::ATTR_PERSISTENT => true ,
                            \PDO::ATTR_TIMEOUT => 30,
                            ));
                        break;
                    case 'pgsql':
                        $conn = new \PDO('pgsql:dbname=' . $this->name . '; user=' . $this->user . '; password=' . $this->pass . '; host=' . $this->host);
                        break;
                    case 'sqlite':
                        $conn = new \PDO('sqlite:name=' . $this->name);
                        break;
                    case 'ibase':
                        $conn = new \PDO('firebird:dbname=' . $this->name, $this->user, $this->pass);
                        break;
                    case 'oci8':
                        $conn = new \PDO('oci:dbname=' . $this->name, $this->user, $this->pass);
                        break;
                    case 'mssql':
                        $conn = new \PDO('mssql:host=' . $this->host . '; dbname=' . $this->name, $this->user, $this->pass);
                        break;
                }
                //$conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                $conn->exec("SET CHARACTER SET utf8");
//               //  if(!$conn){
//                     throw new Exception('Erro ao conectar com banco de dados!');
//                 }
//             } catch (Exception $e) {               
//                 echo $e->getMessage();                                
//             }

            return $conn;
        }
    }
