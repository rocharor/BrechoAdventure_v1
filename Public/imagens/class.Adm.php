<?php
/**
 * Historico
 * <code>
 * = ['2012-11-16'] Tiago Antoniazi Del Guerra
 * </code>
 *
 * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
 * @version 1.0.0
 * @copyright 2012 Sodre Santoro
 *
 * @name Adm
 * @access public
 *
 * @package Adm
 */
class Adm
{

    public $teste_commit = '';
    public $is_logged = 0;
    public $perfil;
    private $servicename = "//192.168.0.14/imagens";
    private $foto_dst_dir;
    private $foto_src_dir =  "/main/wwwroot/shares/imagens";
    private $foto320x240_dst_dir;
    private $foto194x143_dst_dir;
    private $foto95x70_dst_dir;
    private $foto_existente;
    private $leilao_id;
    public $force;
    private $group;
    public $is_production = false;
    public $usuario;
    private $ips = array(
        '200.205.209.57',
        '200.205.209.56',
        '200.205.209.55',
        '192.168.0.4',
        '192.168.0.5',
        '192.168.0.210',
        '192.168.0.209',
        '192.168.0.9',
        '177.220.134.198',
        '177.220.134.197',
        '177.220.134.196',
        '177.220.134.195',
        '177.220.134.194',
        '177.220.134.193'
    );
    public $alterarEmail = array(
	    'raquelmoyses',
	    'michellecc',
	    'jessicav',
	    'rafaelga',
	    'karolvs',
	    'Ligiago',
	    "tamiresns",
	    'pamelasl',
	    'CarlosEPS',
	    'carloseps',
	    'VivianeMB',
	    'vivianemb',
	    'ligiaar',
	    'keturybcf',
	    'ricardoor',
	    'leandrofm',
	    'tiagoadg',
        'julianacb',
        'magdaal'
    );

    public $alterarApelido = array(
	    /*'raquelmoyses',
	    'ligiaar',
	    'keturybcf',
	    'carloseps',
	    'ricardoor',
	    'leandrofm',
	    'tiagoadg',*/
    );

    public $excluirDocs = array(
	    'vivianemb',
	    'karolvs',
	    'carloseps',
	    'jessicav',
	    'rafaelga',
	    'hannacsa',
	    'raquelmoyses',
	    'ligiaar',
	    'michellecc',
	    'keturybcf',
	    'ricardoor',
	    'leandrofm',
	    'tiagoadg'
    );

    public $alterarEndereco = array(
	    'raquelmoyses',
	    'rafaelga',
	    'michellecc',
	    'carloseps',
	    'ligiaar',
	    'keturybcf',
	    'ricardoor',
	    'leandrofm',
        'tiagoadg',
        'tamiresns',
        'pamelasl',
        'julianacb',
        'magdaal'
    );

    public $alterarCPF = array(
	    'raquelmoyses',
	    'ligiaar',
	    'keturybcf',
	    'ricardoor',
	    'leandrofm',
	    'tiagoadg'
    );

    public $alterarContato = array(
	    'raquelmoyses',
	    'ligiaar',
	    'keturybcf',
	    'ricardoor',
	    'leandrofm',
	    'tiagoadg',
        'julianacb',
        'magdaal'
    );

    public $PesquisaDadosNome = array(
	    'raquelmoyses',
	    'ligiaar',
	    'keturybcf',
	    'ricardoor',
	    'leandrofm',
	    'tiagoadg'
    );

    public $copiarLotes = array(
	    'raquelmoyses',
	    'ligiaar',
	    'keturybcf',
	    'ricardoor',
	    'leandrofm',
	    'tiagoadg'
    );
    /**
     * Metodo para construcao do objeto
     *
     * Historico
     * <code>
     * = ['2012-11-16'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name __construct
     * @access public
     * @return void
     */
    public function __construct($force = 0)
    {
        if (Padrao::isProducao()) {
            $this->is_production = true;
        } else {
            $this->is_production = false;
        }

        $this->force = $force;
    }


    public static function Logout()
    {
        Sessao::apagaSessao('perfil');
    }


    public function login($usuario, $senha)
    {
        $sql = new SqlServer(trim($usuario), trim($senha));

        if ($sql->link != '') {
            mssql_select_db('[Santoro]', $sql->link);
            $this->is_logged = 1;
            $this->usuario = $usuario;
            $this->group = $sql->getGrupo();
            $this->perfil = $this->getPerfil();
            Sessao::setaSessao('perfil', 'is_logged', 1);
            Sessao::setaSessao('perfil', 'group', $this->group);
            Sessao::setaSessao('perfil', 'perfil', $this->perfil);
            Sessao::setaSessao('perfil', 'usuario', $usuario);
            $erro = array(
                'erro' => '0',
                'message' => 'Login',
            );
            return array_map('htmlentities', $erro);
        } else {
            Sessao::apagaSessao('perfil');
            $erro = array(
                'erro' => '1',
                'message' => 'login inv&aacute;lido',
            );
            return array_map('htmlentities', $erro);
        }
    }

    /**
     * Metodo para pegar o perfil de acesso
     *
     * Historico
     * <code>
     * = ['2012-11-16'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name login
     * @access private
     * @return $permissao
     */
    private function getPerfil()
    {
        global $conn;
        $query = "/*Adm->getPerfil*/";
        $query .= "select * from adm_grupo where grupo  = '$this->group'";

        $rs = $conn->query($query);
        $permissao = array();
        if ($rs) {
            $permissao = $rs->fetch(PDO::FETCH_ASSOC);
        }

        $permissao_fix = array();
        foreach ($permissao as $k => $row) {
            $k = str_replace("-", "_", $k);
            $permissao_fix[$k] = $row;
        }
        return $permissao_fix;
    }

    /**
     * Metodo para pegar o publicar um leil�o
     *
     * Historico
     * <code>
     * = ['2012-11-16'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name publicar
     * @param Integer $leilao_id
     *            , String $data , String $nm_leilao , String $nu_lote , String $st_categoria
     * @access public
     * @return array(erro,message)
     */
    public function publicar($leilao_id, $data = "", $nm_leilao, $nu_lote, $st_categoria, $is_admin = false)
    {
        global $conn, $leilao_interno_conf;

        if (Sessao::pegaSessao('perfil', 'is_logged') == 0) {
            return array(
                'erro' => 1,
                'message' => htmlentities('Voc� precisa estar logado!'),
            );
        }

        $nm_responsavel = Sessao::pegaSessao('perfil', 'usuario');

        if ($leilao_id) {
            // sincroniza todas as tabelas de online e off line
            $sem_vistoria = self::sincronizaTabelas($leilao_id);
            $st_categoria = strtolower(utf8_decode($st_categoria));
            $dia = substr($data, 0, 2);
            $mes = substr($data, 3, 2);
            $resto = substr($data, 5, strlen($data) - 5);
            $data_leilao = date("Y-m-d H:i:s", strtotime($mes . "/" . $dia . $resto));
            $this->insertLeilao($leilao_id, $data_leilao);
            self::SetLote($leilao_id, $data_leilao, $nu_lote);
            // Insere log de sucesso
            self::insereLogAtualizacao($leilao_id, 'sucesso', $nm_responsavel);
            $sqlserver = new SqlServer();
            $sqlserver->atualizarLoteWeb(0, 0, $leilao_id);

            print "<span style='color:#0000FF'><b>Publica&ccedil;&atilde;o efetuada com sucesso!</b>";
        } else {
            return array(
                'erro' => 1,
                'message' => utf8_encode('Informe o c&oacute;digo do leil&atilde;o!'),
            );
        }
    }
    public function publicarTodos()
    {
        global $conn;

        $leilao_delete = array();
        $now = date("Y-m-d");
        $where = " where lei.dt_time_leilao >=  DATE_ADD('$now', INTERVAL 0 DAY)  ";
        $leiloes = self::getListaLeilao(0, $where);
        foreach ($leiloes as $leilao) {
            $dia = substr($leilao['data'], 0, 2);
            $mes = substr($leilao['data'], 3, 2);
            $resto = substr($leilao['data'], 5, strlen($leilao['data']) - 5);
            $data_leilao = date("Y-m-d H:i:s", strtotime($mes . "/" . $dia . $resto));
            $this->SetLote($leilao['leilao_id'], $data_leilao, '');
            $this->insertLeilao($leilao['leilao_id'], $data_leilao);
            $leilao_delete[] = $leilao['leilao_id'];
        }
    }

    /**
     * Metodo para carregar os menus
     *
     * Historico
     * <code>
     * = ['2012-11-26'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name loadMenu
     * @param
     *            void
     * @access public
     * @return void
     */
    public function loadMenu()
    {
        Menu::generateLeilao();
        return "Carregamento de menu concluido";
    }

    /**
     * Metodo para carregar dados do sql server segmento de veiculos
     *
     * Historico
     * <code>
     * = ['2012-11-16'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name loadVeiculo
     * @param Integer $leilao_id
     * @access public
     * @return void
     */
    public function loadVeiculo($leilao_id = 0, $carro_id_load_detalhes = false)
    {
        $sql = new SqlServer();

        $veiculos_sql = $sql->getVeiculos($leilao_id);
        $veiculos_mysql = self::getLotes("sslonline_veiculos", $leilao_id);

        $veiculos_atualizados = array();
        $veiculos_inseridos = array();

        $carro_ids = array();
        if (empty($veiculos_sql) || count($veiculos_sql) == 0 || $veiculos_sql == false) {
            self::print_erro('Retorno da procedure vazio');
            return false;
        }

        if ($leilao_id == 0) {
            $array_delete = array();
            foreach ($veiculos_mysql as $k => $mysql) {
                if (!array_key_exists($k, $veiculos_sql)) {
                    $array_delete[] = array(
                        'leilao_id' => $mysql['leilao_id'],
                        'carro_id' => $mysql['carro_id'],
                    );
                    // if ($mysql['leilao_id'] == 12926 && $mysql['lote_id'] == 1552344) {
                    //     print("DELETE: " . $k . " \n");
                    //     print("leilao_id: " . $mysql['leilao_id'] . " - lote_id: " . $mysql['leilao_id'] . " \n");
                    // }
                }
            }

            if (!empty($array_delete) && $this->is_production == true) {
                $backup = new Backup();
                $backup->bkp("sslonline_veiculos", $array_delete);
            } elseif ($this->is_production == false && !empty($array_delete)) {
                self::clearTable("sslonline_veiculos", $leilao_id, $array_delete);
            }
            // print("\n --------------------------------- \n");
        }

        foreach ($veiculos_sql as $k => $sql) {
            if (array_key_exists($k, $veiculos_mysql)) {
                $return = array_diff_assoc($sql, $veiculos_mysql[$k]);
                if (!empty($return)) {
                    $veiculos_atualizados[] = $k;
                    self::update('sslonline_veiculos', $return, array(
                        'leilao_id' => $sql['leilao_id'],
                        'carro_id' => $sql['carro_id'],
                    ), true);
                    // print("UPDATE: " . $k . " \n");
                    // print("leilao_id: " . $sql['leilao_id'] . " - lote_id: " . $sql['leilao_id'] . " \n");
                }
            } else {
                $veiculos_inseridos[] = $k;
                self::insert("sslonline_veiculos", $sql, true);
                // print("INSERT: " . $k . " \n");
                // print("leilao_id: " . $sql['leilao_id'] . " - lote_id: " . $sql['leilao_id'] . " \n");
            }
        }
        // print("\n --------------------------------- \n");

        if ($carro_id_load_detalhes != false) {
            $this->loadVeiculosDetalhes($carro_id_load_detalhes);
        }

        if ($leilao_id == 0) {
            echo "Quantidade sqlserver " . count($veiculos_sql) . "\n";
            echo "Quantidade mysql " . count($veiculos_mysql) . "\n";
            if (count($veiculos_atualizados) > 0) {
                echo "Quantidade atualizados " . count($veiculos_atualizados) . "\n";
            }
            if (count($veiculos_inseridos) > 0) {
                echo "Quantidade inseridos " . count($veiculos_inseridos) . "\n";
            }
            if (count($array_delete) > 0) {
                echo "Quantidade deletados " . count($array_delete) . "\n";
            }
            // print("\n --------------------------------- \n");
        }

        if ($this->is_production == false) {
            echo "Carregando Fotos \n";
            Adm::fixFotos('veiculos');
            // print("\n --------------------------------- \n");
        }

        if ($leilao_id == 0) {
            $this->loadVeiculosDetalhes();
        }

        return array();
    }
    private function loadVeiculosDetalhes($carro_id = 0)
    {
        global $conn;
        $sql = new SqlServer();
        $dados = $sql->getVeiculosVistoriaDetalhes($carro_id);

        if ($carro_id == 0) {
            self::clearTable('vistoria_veiculos');
        } else {
            $query = "/*Adm->loadVeiculosDetalhes*/";
            $query .= " delete from vistoria_veiculos where carro_id = $carro_id ";
            $conn->query($query);
        }

        $dados_update = array();
        if (!empty($dados)) {
            foreach ($dados as $dado) {

                if ($dado['NM_ColetorParte'] == 'Blindagem' || in_array($dado['ColetorParte_ID'], array(
                    970,
                    1097,
                    1384,
                    1709,
                    1840,
                ))) {
                    $dados_update[$dado['carro_ID']]['ST_Blindagem'] = $dado['NM_ColetorEstado'];
                    $dados_update[$dado['carro_ID']]['url_amigavel_st_blindagem'] = !empty($dado['NM_ColetorEstado']) ? Padrao::limpaValorUrlAmigavel($dado['NM_ColetorEstado']) : 'Indefinido';
                }

                if ($dado['NM_ColetorParte'] == 'Direcao Hidraulica' || in_array($dado['ColetorParte_ID'], array(
                    608,
                    784,
                    904,
                    1031,
                    1156,
                    1319,
                    1644,
                    1773,
                ))) {
                    $dados_update[$dado['carro_ID']]['ST_DirecaoHidraulica'] = $dado['NM_ColetorEstado'];
                    $dados_update[$dado['carro_ID']]['url_amigavel_st_direcaohidraulica'] = !empty($dado['NM_ColetorEstado']) ? Padrao::limpaValorUrlAmigavel($dado['NM_ColetorEstado']) : 'Indefinido';
                }

                if ($dado['NM_ColetorParte'] == 'Ar Condicionado' || in_array($dado['ColetorParte_ID'], array(
                    663,
                    783,
                    903,
                    1030,
                    1155,
                    1318,
                    1643,
                    1772,
                ))) {
                    $dados_update[$dado['carro_ID']]['ST_ArCondicionado'] = $dado['NM_ColetorEstado'];
                    $dados_update[$dado['carro_ID']]['url_amigavel_st_ar_condicionado'] = !empty($dado['NM_ColetorEstado']) ? Padrao::limpaValorUrlAmigavel($dado['NM_ColetorEstado']) : 'Indefinido';
                }

                if ($dado['NM_ColetorParte'] == 'Kit G�s' || in_array($dado['ColetorParte_ID'], array(
                    701,
                    785,
                    905,
                    1032,
                    1157,
                    1320,
                    1645,
                    1774,
                ))) {
                    $dados_update[$dado['carro_ID']]['ST_KitGas'] = $dado['NM_ColetorEstado'];
                    $dados_update[$dado['carro_ID']]['url_amigavel_st_kitgas'] = !empty($dado['NM_ColetorEstado']) ? Padrao::limpaValorUrlAmigavel($dado['NM_ColetorEstado']) : 'Indefinido';
                }

                if ($dado['NM_ColetorParte'] == 'Causa' || in_array($dado['ColetorParte_ID'], array(
                    0,
                ))) {
                    $dados_update[$dado['carro_ID']]['ST_Origem'] = $dado['NM_ColetorEstado'];
                    $dados_update[$dado['carro_ID']]['url_amigavel_st_origem'] = !empty($dado['NM_ColetorEstado']) ? Padrao::limpaValorUrlAmigavel($dado['NM_ColetorEstado']) : 'Indefinido';
                }

                if ($dado['NM_ColetorParte'] == 'Cambio' || in_array($dado['ColetorParte_ID'], array(
                    718,
                    779,
                    900,
                    1027,
                    1151,
                    1314,
                    1639,
                    1769,
                ))) {
                    $dados_update[$dado['carro_ID']]['ST_Cambio'] = $dado['NM_ColetorEstado'];
                    $dados_update[$dado['carro_ID']]['url_amigavel_st_cambio'] = !empty($dado['NM_ColetorEstado']) ? Padrao::limpaValorUrlAmigavel($dado['NM_ColetorEstado']) : 'Indefinido';
                }

                $dado['url_amigavel_nm_coletorparte'] = Padrao::limpaValorUrlAmigavel($dado['NM_ColetorParte']);
                $dado['url_amigavel_nm_coletorestado'] = Padrao::limpaValorUrlAmigavel($dado['NM_ColetorEstado']);

                Transacao::db_insert('vistoria_veiculos', $dado);
            }
        }

            foreach ($dados_update as $k => $update) {
                if (count($update) > 0) {
                    self::update('sslonline_veiculos', $update, array(
                        'carro_id' => $k,
                    ));
                }

            }
            $documentos = $sql->getVeiculosDocumentosDetalhes();
            if (!empty($documentos)) {
                self::clearTable('documentos_veiculos');
                foreach ($documentos as $dado) {
                    Transacao::db_insert('documentos_veiculos', $dado);
                }
            }
        Transacao::commit();
    }
    private function loadVeiculosDocumentos()
    {
        global $conn;
        $sql = new SqlServer();
        $dados = $sql->getVeiculosVistoriaDetalhes();
        $query = "/*Adm->loadVeiculosDocumentos*/";
        $query .= "select carro_id from sslonline_veiculos group by carro_id";
        $rs = $conn->query($query);
        while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
            $carros_id[] = $row['carro_id'];
        }

        if (!empty($dados)) {
            self::clearTable('vistoria_veiculos');
            foreach ($dados as $dado) {
                if (in_array($dado['carro_id'], $carros_id)) {
                    Transacao::db_insert('vistoria_veiculos', $dado);
                    Transacao::commit();
                }
            }
        }
    }

    /**
     * Metodo para carregar dados do sql server segmento de materiais
     *
     * Historico
     * <code>
     * = ['2012-11-16'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name loadMaterial
     * @param Integer $leilao_id
     * @access public
     * @return void
     */
    public function loadMaterial($leilao_id = 0)
    {
        $sql = new SqlServer();
        list($materiais_sql, $sem_vistoria) = $sql->getMaterial($leilao_id);
        $materiais_mysql = self::getLotes("sslonline_materiais", $leilao_id);


        $materiais_atualizados = array();
        $materiais_inseridos = array();

        $lote_ids = array();
        if (empty($materiais_sql) || count($materiais_sql) == 0 || $materiais_sql == false) {
            self::print_erro('Retorno da procedure vazio');
            return false;
        }

        if ($leilao_id == 0) {
            $array_delete = array();
            foreach ($materiais_mysql as $k => $mysql) {
                if (!array_key_exists($k, $materiais_sql)) {
                    $array_delete[] = array(
                        'leilao_id' => $mysql['leilao_id'],
                        'lote_id' => $mysql['lote_id'],
                    );
                    // print_r("DELETE: " . $k);
                    // print_r($array_delete);
                    // print("\n -------------------------------------------- \n");
                }
            }

            if (!empty($array_delete) && $this->is_production == true) {
                $backup = new Backup();
                $backup->bkp("sslonline_materiais", $array_delete);
            } elseif ($this->is_production == false && !empty($array_delete)) {
                self::clearTable("sslonline_materiais", $leilao_id, $array_delete);
            }
        }

        foreach ($materiais_sql as $k => $sql) {
            if (array_key_exists($k, $materiais_mysql)) {
                $return = array_diff_assoc($sql, $materiais_mysql[$k]);
                if (!empty($return)) {
                    $materiais_atualizados[] = $k;
                    self::update('sslonline_materiais', $return, array(
                        'leilao_id' => $sql['leilao_id'],
                        'id' => $sql['id'],
                        'lote_id' => $sql['lote_id']
                    ), true);
                    // Transacao::commit();
                }
            } else {
                // print_r("INSERT: " . $k);
                // print("\n");
                // print_r($materiais_sql[$k]);
                // print("\n -------------------------------------------- \n");

                $materiais_inseridos[] = $k;
                $sql['deposito'] = self::getDeposito($sql['deposito_id']);
                self::insert("sslonline_materiais", $sql, true);
                // Transacao::commit();
            }
        }

        if ($leilao_id == 0) {
            echo "Quantidade sqlserver " . count($materiais_sql) . "\n";
            echo "Quantidade mysql " . count($materiais_mysql) . "\n";
            if (count($array_delete) > 0) {
                echo "Quantidade deletados " . count($array_delete) . "\n";
            }
            if (count($materiais_atualizados) > 0) {
                echo "Quantidade atualizados " . count($materiais_atualizados) . "\n";
            }
            if (count($materiais_inseridos) > 0) {
                echo "Quantidade inseridos " . count($materiais_inseridos) . "\n";
            }
        }

        if ($this->is_production == false) {
            self::fixFotos('materiais');
        }

        return $sem_vistoria;
    }
    public static function getDeposito($deposito_id)
    {
        global $conn;

        if (!empty($deposito_id)) {
            $query = "select nm_patio from patios where id = $deposito_id";
            list($deposito) = $conn->query($query)->fetch(PDO::FETCH_BOTH);
            if ($deposito == 'Guarulhos-Externo') {
                $deposito = 'Outros Locais';
            }
        } else {
            $deposito = 'Consultar Edital';
        }
        return $deposito;
    }

    /**
     * Metodo para carregar dados do sql server segmento de imoveis
     *
     * Historico
     * <code>
     * = ['2012-11-16'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name loadImovel
     * @param Integer $leilao_id
     * @access public
     * @return void
     */
    public function loadImovel($leilao_id = 0)
    {
        $sql = new SqlServer();

        $imoveis_sql = $sql->getImovel($leilao_id);
        $imoveis_mysql = self::getLotes("sslonline_imoveis", $leilao_id);

        $imoveis_atualizados = array();
        $imoveis_inseridos = array();

        if (empty($imoveis_sql) || count($imoveis_sql) == 0 || $imoveis_sql == false) {
            self::print_erro('Retorno da procedure vazio');
            return false;
        }

        if ($leilao_id == 0) {
            $array_delete = array();
            foreach ($imoveis_mysql as $k => $mysql) {
                if (!array_key_exists($k, $imoveis_sql)) {
                    $array_delete[] = array(
                        'leilao_id' => $mysql['leilao_id'],
                        'lote_id' => $mysql['lote_id'],
                    );
                }
            }
            if (!empty($array_delete) && $this->is_production == true) {
                $backup = new Backup();
                $backup->bkp("sslonline_imoveis", $array_delete);
            } elseif ($this->is_production == false && !empty($array_delete)) {
                self::clearTable("sslonline_imoveis", $leilao_id, $array_delete);
            }
        }

        $lote_ids = array();
        foreach ($imoveis_sql as $k => $sql) {
            if (array_key_exists($k, $imoveis_mysql)) {
                $return = array_diff_assoc($sql, $imoveis_mysql[$k]);
                if (!empty($return)) {
                    $imoveis_atualizados[] = $k;
                    self::update('sslonline_imoveis', $return, array(
                        'leilao_id' => $sql['leilao_id'],
                        'id' => $sql['id'],
                    ));
                }
            } else {
                $imoveis_inseridos[] = $k;
                self::insert("sslonline_imoveis", $sql, true);
            }
        }

        if ($leilao_id == 0) {
            echo "Quantidade sqlserver " . count($imoveis_sql) . "\n";
            echo "Quantidade mysql " . count($imoveis_mysql) . "\n";
            if (count($imoveis_atualizados) > 0) {
                echo "Quantidade atualizados " . count($imoveis_atualizados) . "\n";
            }
            if (count($imoveis_inseridos) > 0) {
                echo "Quantidade inseridos " . count($imoveis_inseridos) . "\n";
            }
            if (count($array_delete) > 0) {
                echo "Quantidade deletados " . count($array_delete) . "\n";
            }
        }

        if ($this->is_production == false) {
            self::fixFotos('imoveis');
        }

        if ($this->leilao_id == 0) {
            $this->loadImoveisOpcionais();
        }

        return array();
    }
    private function loadImoveisOpcionais()
    {
        $sql = new SqlServer();
        $dados = $sql->getImovelVistoriaDetalhes();

        if (!empty($dados)) {
            self::clearTable('vistoria_imoveis');
            foreach ($dados as $dado) {
                Transacao::db_insert('vistoria_imoveis', $dado);
                Transacao::commit();
            }
        }
    }
    private static function getLotes($table, $leilao_id = 0)
    {
        global $conn;

        $query = "/*Adm->getLotes {$table} {$leilao_id}*/";
        $query .= "select * from $table ";
        $leilao_id > 0 ? $query .= " where leilao_id = $leilao_id " : '';

        $rs = $conn->query($query);
        $retorno = array();
        while (@$lotes = $rs->fetch(PDO::FETCH_ASSOC)) {
            if ($table == "sslonline_veiculos") {
                $key = self::makeHash(array(
                    'carro_id' => intval($lotes['Carro_ID']),
                    'leilao_id' => intval($lotes['Leilao_ID']),
                    'lote_id' => intval($lotes['Lote_ID']),
                ), 'veiculos');
            } elseif ($table == "sslonline_materiais") {
                $key = self::makeHash(array(
                    'id' => intval($lotes['id']),
                    'leilao_id' => intval($lotes['leilao_id']),
                    'lote_id' => intval($lotes['lote_id']),
                ), 'material');
            } else {
                $key = self::makeHash(array(
                    'id' => intval($lotes['id']),
                    'leilao_id' => intval($lotes['Leilao_id']),
                    'lote_id' => intval($lotes['lote_id']),
                ));
            }

            $retorno[$key] = array_change_key_case($lotes, CASE_LOWER);
        }
        return $retorno;
    }
    public static function makeHash($params, $segmento = '')
    {
        $key = '';
        switch ($segmento) {
            case 'veiculos':
                $key = sha1($params['carro_id'] . $params['leilao_id']);
                break;
            case 'lote':
                $key = sha1($params['lote_id'] . $params['leilao_id']);
                break;
			case 'material' : {
				//$key = sha1($params['lote_id'] . $params['leilao_id']);

				if(empty($params['id']) || $params['id'] == 0){
					$key = sha1($params['lote_id'] . $params['leilao_id']);
				}else{
					$key = sha1($params['id'] . $params['lote_id'] . $params['leilao_id']);
				}
			} ; break;
            default:
                $key = sha1($params['id'] . $params['leilao_id']);
                /*
         * case 'veiculos' : $key = $params['carro_id']; break; default : $key = $params['lote_id'];
         */
        }
        return $key;
    }

    /**
     * Metodo para pegar o valor do intervalo dos lotes
     *
     * Historico
     * <code>
     * = ['2012-11-16'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name getLoteIntervalo
     * @param Integer $leilao_id
     * @access public
     * @return $termino_intervalo
     */
    private static function getLoteIntervalo($leilao_id)
    {
        global $conn;
        $query = "/*Adm->getLoteIntervalo {$leilao_id}*/";
        $query .= "SELECT nu_intervalolote FROM leilao WHERE leilao_id=$leilao_id";

        $rs = $conn->query($query);
        if ($rs) {
            return $termino_intervalo = $rs->fetch(PDO::FETCH_BOTH);
        }
    }

    /**
     * Metodo para setar o lote do leil�o (incluir valores na tabela sslonline_status)
     * Obs: para tabelas innodb � necess�rio ap�s a query executar o commit
     * Historico
     * <code>
     * = ['2012-11-16'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name getLoteIntervalo
     * @param Integer $leilao_id
     *            , String $data , String $nu_lote
     * @access private
     * @return void
     */
    private function SetLote($leilao_id, $data, $nu_lote, $lote_id = 0)
    {
        global $conn;

        $publicar_foto_veiculo = false;
        $publicar_foto_material = false;
        $publicar_foto_imovel = false;
        $termino = strtotime($data);
        list($termino_intervalo) = (Adm::getLoteIntervalo($leilao_id));

		$fix_fotos_material=false;
        $lotes = array();
        $where_lote = "";
        $where_lote_nu = "";

        if (!empty($nu_lote) && $lote_id == 0) {
            $where_lote = " and lote in ('" . $nu_lote . "')";
            $where_lote_nu = " and s.nu_lote in ('" . $nu_lote . "')";
        } elseif ($lote_id > 0) {
            $where_lote = " and lt.lote_id in ('" . $lote_id . "')";
            $where_lote_nu = " and lt.lote_id in ('" . $lote_id . "')";
        }

        $query = "SELECT
        		  lt.leilao_id,
				  lt.lote_id,
				  lt.lote as nu_lote,
				  lt.endereco,
				  c.nm_categoria as categoria,
				  lt.vl_lanceminimo,
				  lt.dt_inicioleilaoonline,
				  lt.vl_lanceinicial,
				  lt.vl_incremento,
				  lt.nu_qtde,
				  lt.nm_unidade,
				  lt.vistoria as nm_vistoria ,
				  lt.st_nao_visivel,
				  lt.lote_id_principal,
				  lt.NM_MensagemTelao,
				  lt.id as segmento_id ,
				  lt.st_lote,
				  s.vl_maior_lance as vl_lance_order,
				  s.lance_id ,
				  s.nu_qtde_lance,
				  s.nu_contadorvisita ,
				  lt.nm_img,
				  3 as ST_Categoria,
				  lt.categoria as Categoria_ID,
				  0 as SubCategoria_ID ,
				  lt.id as segmento_id ,
				  lt.cliente_id
				  FROM sslonline_imoveis lt
				  LEFT JOIN sslonline_status s on lt.leilao_id = s.leilao_id and lt.lote_id = s.lote_id
				  LEFT JOIN categorias AS c ON (c.ST_Categoria = 3 AND c.ID = s.Categoria_ID )

				   WHERE lt.leilao_id = $leilao_id
						 AND lt.lote_id <> 0
						 $where_lote
						 ORDER BY lote";

        $rs = $conn->query($query);
        if ($rs) {
            while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                $indice = $row['nu_lote'] == 'Indef.' ? $row['lote_id'] : $row['nu_lote'];
                $row['descricao'] = $row['categoria'] . $row['endereco'];
                $lotes[$indice] = array_merge($row, array(
                    "tipo_segmento" => 'I',
                ));
            }
        }

        $query = "SELECT
        			    lt.leilao_id,
						lt.lote_id,
						lt.lote as nu_lote,
						group_concat(lt.marca SEPARATOR '/') as marca,
						group_concat(lt.modelo) as modelo,
						lt.nm_unidade,
						group_concat(lt.nm_tipo SEPARATOR '/') as nm_tipo,
						lt.nu_ano,
						lt.vl_lanceminimo,
						lt.dt_inicioleilaoonline,
						group_concat(lt.descricao) as descricao,
						lt.vl_lanceinicial,
						lt.vl_incremento,
						lt.nu_qtde,
						group_concat(lt.nm_vistoria) as nm_vistoria,
						lt.lote_id_principal,
						lt.st_nao_visivel,
						lt.NM_MensagemTelao ,
						lt.id as segmento_id ,
						lt.st_lote,
				  		s.vl_maior_lance as vl_lance_order,
				  		s.lance_id ,
				  		s.nu_qtde_lance,
				  		s.nu_contadorvisita ,
 					    group_concat(lt.nm_img SEPARATOR ';') as nm_img,
						2 as ST_Categoria,
						lt.categoria_id as Categoria_ID,
						lt.subcategoria_id as SubCategoria_ID,
						lt.id as segmento_id,
						lt.cliente_id
						FROM sslonline_materiais lt
						LEFT JOIN sslonline_status s on lt.leilao_id = s.leilao_id and lt.lote_id = s.lote_id
						WHERE lt.leilao_id = $leilao_id
							 AND lt.categoria_id > 0
							 AND st_loteonline=1
							 AND lt.lote_id <> 0
							 $where_lote
							 GROUP BY lt.lote_id
							 ORDER BY lote";

        $rs = $conn->query($query);
        if ($rs) {
            while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
            	$fix_fotos_material = true;
                $indice = $row['nu_lote'] == 'Indef.' || $row['nu_lote'] == '000' ? $row['lote_id'] : $row['nu_lote'];
                $lotes[$indice] = array_merge($row, array(
                    "tipo_segmento" => 'M',
                ));
            }
        }

        $query = "SELECT  lt.leilao_id,
        				  lt.lote_id,
						  lt.nu_lote,
						  lt.nm_modelo,
						  lt.nu_anoFab,
						  lt.nu_anoModelo,
						  lt.nm_enderecoexpo,
						  lt.vl_lanceminimo,
						  lt.dt_inicioleilaoonline,
						  lt.vl_lanceinicial,
						  lt.vl_incremento,
						  lt.nu_qtde,
						  lt.nm_unidade,
						  lt.nm_vistoria,
						  lt.st_nao_visivel,
						  lt.lote_id_principal,
						  lt.NM_MensagemTelao ,
						  lt.carro_id as segmento_id ,
						  lt.st_lote,
				  		  s.vl_maior_lance as vl_lance_order,
				  		  s.lance_id ,
				  		  s.nu_qtde_lance,
				  		  s.nu_contadorvisita ,
						  lt.nm_img,
						  1 as ST_Categoria,
						  lt.ST_Categoria as Categoria_ID,
						  0 as SubCategoria_ID ,
						  lt.carro_id as segmento_id,
						  lt.cliente_id
						  FROM sslonline_veiculos lt
						  LEFT JOIN sslonline_status s on lt.leilao_id = s.leilao_id and lt.lote_id = s.lote_id
							  WHERE lt.leilao_id = $leilao_id
							  AND st_loteonline=1
							  AND lt.lote_id <> 0
							  $where_lote_nu
							  ORDER BY nu_lote";

        $rs = $conn->query($query);
        if ($rs) {
            while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                $indice = $row['nu_lote'] == 'Indef.' || $row['nu_lote'] == '0000' ? $row['lote_id'] : $row['nu_lote'];
                $lotes[$indice] = array_merge($row, array(
                    "tipo_segmento" => 'V',
                ));
            }
        }

        ksort($lotes);
        Adm::clearStatus($leilao_id, $where_lote_nu);
        $lotes_publicados = array();
        $i = 0;
        foreach ($lotes as $lote_id => $lote) {
            if ($i == 0) {
                $newtime = $termino;
            } else {
                $H = date('H', $newtime);
                $i = date('i', $newtime);
                $s = date('s', $newtime) + $termino_intervalo;
                $m = date('m', $newtime);
                $D = date('d', $newtime);
                $y = date('y', $newtime);
                $newtime = mktime($H, $i, $s, $m, $D, $y);
            }

            if ($lote['vl_incremento'] == 0) {
                $lote['vl_incremento'] = 200;
            }

            $lote['vl_multiplo'] = Adm::geraMultiplo($lote['vl_incremento']);

            switch ($lote['tipo_segmento']) {
                case 'V':
					$publicar_foto_veiculo = true;
                    $lote['descricao'] = addslashes(Lote::getDescricaoVeiculos($lote['lote_id'], $lote['leilao_id'], $lote['cliente_id']));
                    break;
                case 'I':
					$publicar_foto_imovel = true;
                    $lote['descricao'] = addslashes(Lote::getDescricaoImoveis($lote['lote_id'], $lote['leilao_id']));
                    break;
                case 'M':
					$publicar_foto_material = true;
                    $lote['descricao'] = addslashes(Lote::getDescricaoMateriais($lote['lote_id'], $lote['leilao_id']));
                    break;
            }
            $newtime_insert = date('Y-m-d H:i:s', $newtime);

            $params = array(
                'leilao_id' => $leilao_id,
                'lote_id' => $lote['lote_id'],
                'st_lote' => $lote['st_lote'],
                'termino_fake' => $newtime_insert,
                'termino' => $newtime_insert,
                'avisado_fim' => 0,
                'resultado_atualizado' => 0,
                'incremento_minimo' => $lote['vl_incremento'],
                'tipo_segmento' => $lote['tipo_segmento'],
                'nu_lote' => trim($lote['nu_lote']),
                'descricao' => in_array($lote['lote_id'],array(1458296,1464813,1464817))  ? 'Sucatas Diversas' : $lote['descricao'],
                'vl_lanceminimo' => $lote['vl_lanceminimo'],
                'nm_unidade' => $lote['nm_unidade'],
                'vl_multiplo' => $lote['nm_unidade'],
                'DT_InicioLeilaoOnline' => $lote['dt_inicioleilaoonline'],
                'vl_lanceinicial' => $lote['vl_lanceinicial'],
                'nu_qtde' => $lote['nu_qtde'],
                'incremento_sistema' => $lote['vl_incremento'],
                'nm_vistoria' => $lote['nm_vistoria'],
                'lote_id_principal' => $lote['lote_id_principal'],
                'st_nao_visivel' => $lote['st_nao_visivel'],
                'mensagem' => $lote['NM_MensagemTelao'],
                'nu_contadorvisita' => intval($lote['nu_contadorvisita']),
                'nu_qtde_lance' => $lote['nu_qtde_lance'],
                'lance_id' => $lote['lance_id'],
                'nm_img' => $lote['nm_img'],
                'ST_Categoria' => $lote['ST_Categoria'],
                'Categoria_ID' => $lote['Categoria_ID'],
                'SubCategoria_ID' => $lote['SubCategoria_ID'],
                'Segmento_ID' => $lote['segmento_id'],
                'Cliente_ID' => $lote['cliente_id']
            );
            $lote['lance_id'] = (float) $lote['lance_id'];
            $lote['vl_lance_order'] = (float) $lote['vl_lance_order'];
            if($lote['lance_id'] > 0 && $lote['vl_lance_order'] > 0){
                $params['vl_maior_lance'] = $lote['vl_lance_order'];
            }
            $i++;
            Adm::insert("sslonline_status", $params, false);
        }
        Transacao::commit();

        // $this->atualizarMaiorLance($leilao_id);

		if($publicar_foto_veiculo == true)
			self::publishFotos('veiculos',$leilao_id);
		if($publicar_foto_material == true)
			self::publishFotos('materiais',$leilao_id);
		if($publicar_foto_imovel == true)
			self::publishFotos('imoveis',$leilao_id);


        if (Padrao::isProducao()) {
            $params = array(
                'lote_id' => $lote_id,
                'leilao_id' => $leilao_id,
                'descricao' => $lote_id > 0 ? "Atualiza��o do lote $lote_id leilao $leilao_id" : "Atualiza��o de leil�o $leilao_id",
                'st_tipo' => 1,
            );
            self::LogPublicacao($params);
        }
    }

    /**
     * Metodo para inserir no banco de dados
     * Historico
     * <code>
     * = ['2012-11-17'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name insert
     * @param String $table
     *            , Array $params, Boolean $innodb
     * @access private
     * @return void
     */
    private static function insert($table, $params, $innodb = true)
    {
        global $conn;

        $colunas = implode(',', array_keys($params));
        $linhas = implode("','", array_values($params));

        $query = "insert into $table ($colunas) values ('$linhas') ";

        try {
            $conn->query($query);
        } catch (PDOException $e) {
            if (!Padrao::isProducao()) {
                // print('leilao_id: ' . $params['leilao_id'] . ' -> ' . $e->getMessage());
                // print("\n");
                Padrao::print_pr($query, false);
            }
        }

        if ($innodb) {
            Transacao::commit();
        }
    }

    /**
     * Metodo para atualizar o mysql
     * Historico
     * <code>
     * = ['2013-08-06'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name insert
     * @param String $table
     *            , Array $params, Boolean $innodb
     * @access private
     * @return void
     */
    public static function update($table, $params, $colunas, $innodb = false)
    {
        global $conn;

        $where_and = array();
        foreach ($colunas as $k => $coluna) {
            $where_and[] = "{$k} = {$coluna}";
        }
        $where = " where " . implode(" and ", $where_and);

        $query = "update $table set ";
        $set = array();
        foreach ($params as $k => $dado) {
            if ($k != 'carro_id' && $k != 'leilao_id' && $k != 'id' && $k != 'nm_img') {
                $set[] = $k . "='" . ($dado) . "'";
            }
        }

        $sets = implode(",", $set);

        $query .= $sets . $where;

        try {
            $conn->query($query);
        } catch (Exception $e) {
        	echo($e->getMessage() . "\n");
            Padrao::print_pr($query,false);
        }
        if ($innodb) {
            Transacao::commit();
        }
    }

    /**
     * Metodo para atualizar o mysql
     * Historico
     * <code>
     * = ['2013-08-06'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name insert
     * @param String $table
     *            , Array $params, Boolean $innodb
     * @access private
     * @return void
     */
    private static function delete($table, $params)
    {
        global $conn;

        $query = "delete from  $table
					where " . $params;
        $conn->query($query);
        Transacao::commit();
    }

    /**
     * Metodo para limpar a tabela de status
     * Historico
     * <code>
     * = ['2012-11-17'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name clearStatus
     * @param Integer $leilao_id
     *            , String $lotes , String $where_lote_nu
     * @access private
     * @return void
     */
    private static function clearStatus($leilao_id, $where_lote_nu)
    {
        global $conn;
        $where_lote_nu = str_replace(array("lt.","s."), array('',''), $where_lote_nu);

        if ($leilao_id > 0) {
            $query = "DELETE FROM sslonline_status WHERE leilao_id in ('$leilao_id')  $where_lote_nu ";
            $conn->query($query);
            Transacao::commit();
        }
    }

    /**
     * Metodo para pegar a lista de leil�es disponiveis para publica��o
     * Historico
     * <code>
     * = ['2012-11-21'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name getListaLeilao
     * @param Integer $leilao_id
     * @access public
     * @return Array $leiloes
     */
    public function getListaLeilao($leilao_id = 0, $where = '')
    {
        global $conn;
        $query = "SELECT lei.*, date_format(lei.dt_time_leilao,'%d/%m/%Y %H:%i:%s') as fdata, lei.st_categoria as categoria,
								   lei.nm_leilao as descricao,
								(select count(*) from sslonline_status ss where ss.leilao_id = lei.leilao_id) as
								qtde_publicar
							  FROM leilao lei $where
							  group by lei.leilao_id order by lei.dt_leilao asc";

        $rs = $conn->query($query);
        $leiloes = array();
        if ($rs) {
            while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                if ($row['leilao_id'] > 0) {

                    switch ($row['categoria']) {
                        case '1':
                            $segmento = "Ve&iacute;culos";
                            break;
                        case '2':
                            $segmento = "Materiais";
                            break;
                        case '3':
                            $segmento = "Im&oacute;veis";
                            break;
                        case '1,2':
                            $segmento = "Ve&iacute;culos e Materiais";
                            break;
                        case '2,1':
                            $segmento = "Ve&iacute;culos e Materiais";
                            break;
                        case '2,3':
                            $segmento = "Materiais e Im&oacute;veis";
                            break;
                        case '3,2':
                            $segmento = "Materiais e Im&oacute;veis";
                            break;
                        case '1,3':
                            $segmento = "Ve&iacute;culos e Im&oacute;veis";
                            break;
                        case '3,1':
                            $segmento = "Ve&iacute;culos e Im&oacute;veis";
                            break;
                        case '1,2,3':
                            $segmento = "Ve&iacute;culos , Materiais e Im&oacute;veis";
                            break;
                        case '2,3,1':
                            $segmento = "Ve&iacute;culos , Materiais e Im&oacute;veis";
                            break;
                        case '3,1,2':
                            $segmento = "Ve&iacute;culos , Materiais e Im&oacute;veis";
                            break;
                    }

                    if ($row['st_leilaointerno'] == 1) {
                        $tipo = "Interno";
                    } elseif ($row['st_leilaoonline'] == 1) {
                        $tipo = "Online";
                    } else {
                        $tipo = "Simultaneo";
                    }

                    $leiloes[] = array(
                        'leilao_id' => $row['leilao_id'],
                        'deposito' => $row['deposito'],
                        'data' => $row['fdata'],
                        'nm_leilao' => $row['nm_leilao'],
                        'st_categoria' => intval($row['st_categoria']),
                        'categoria' => $segmento,
                        'tipo' => $tipo,
                        'pendentes' => Adm::formatQtdeAPublicar($row['qt_lotes_online'], $row['qtde_publicar']),
                    );
                }
            }
        }

        return $leiloes;
    }

    /**
     * Metodo para formatar a quantidade de lotes publicados
     * Historico
     * <code>
     * = ['2012-11-21'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name formatQtdeAPublicar
     * @param Integer $qt_online
     *            , Integer $qt_publicar
     * @access private
     * @return String $fqtde
     */
    private static function formatQtdeAPublicar($qt_online, $qt_publicar)
    {
        $qtde = $qt_online - $qt_publicar;

        if ($qtde == 0) {
            return "Leil&atilde;o j&aacute; Publicado";
        } elseif ($qtde < 0) {
            $fqtde = abs($qtde);
            return "Qtde a publicar $fqtde";
        } elseif ($qtde > 0) {
            $fqtde = abs($qtde);
            return "Qtde a publicar $fqtde";
        }
    }

    /**
     * Metodo para carregar as categorias de veiculos
     * Historico
     * <code>
     * = ['2012-11-21'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name formatQtdeAPublicar
     * @param Integer $qt_online
     *            , Integer $qt_publicar
     * @access private
     * @return void
     */
    public function loadCategoriaVeiculo()
    {
        global $conn;
        $sql = new SqlServer();
        $categorias = $sql->getCategoriaVeiculos();

        if (count($categorias) > 0) {
            $conn->query("delete from categorias where ST_Categoria = 1");
            foreach ($categorias as $categoria) {
                $params = array(
                    'ST_Categoria' => 1,
                    'ID' => $categoria['NU_CodigoNoSistema'],
                    'NM_Categoria' => $categoria['NM'],
                    'Url_Amigavel_NM_Categoria' => Padrao::limpaValorUrlAmigavel($categoria['NM']),
                );

                Transacao::db_insert('categorias', $params, false);
            }
            Transacao::commit();
        }
    }

    /**
     * Metodo para carregar as categorias e subcategorias de materiais
     * Historico
     * <code>
     * = ['2012-11-21'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name loadCategoriaMaterial
     * @param
     *            void
     * @access public
     * @return void
     */
    public function loadCategoriaMaterial()
    {
        global $conn;
        $sql = new SqlServer();
        $categorias = $sql->getCategoriaMaterial();
        $conn->query("delete from categorias where ST_Categoria = 2");

        foreach ($categorias['categoria'] as $k => $categoria) {

            $categoria = str_replace(array(
                "'",
                "ÃÆÃâÃÂ¢Ã¢âÂ¬Ãâ",
                "ÃÆÃâÃÂ¢Ã¢âÂ¬ÃÂ",
            ), array(
                "",
                "O",
                "O",
            ), $categoria);
            $url_amigavel = Padrao::limpaValorUrlAmigavel($categoria);

            $params = array(
                'ST_Categoria' => 2,
                'ID' => $k,
                'NM_Categoria' => $categoria,
                'Url_Amigavel_NM_Categoria' => $url_amigavel,
            );

            Transacao::db_insert('categorias', $params, false);
        }

        $conn->query("delete from subcategorias where ST_Categoria = 2");

        foreach ($categorias['sub_categoria'] as $k => $subcategoria) {
            $nome = str_replace(array(
                "'",
                "ÃÆÃâÃÂ¢Ã¢âÂ¬Ãâ",
                "ÃÆÃâÃÂ¢Ã¢âÂ¬ÃÂ",
            ), array(
                "",
                "O",
                "O",
            ), $subcategoria['NM']);
            $id = $subcategoria['categoria_id'];
            $url_amigavel = Padrao::limpaValorUrlAmigavel($nome);

            $params = array(
                'ST_Categoria' => 2,
                'ID' => $k,
                'Categoria_ID' => $id,
                'NM_SubCategoria' => $nome,
                'Url_Amigavel_NM_SubCategoria' => $url_amigavel,
            );

            Transacao::db_insert('subcategorias', $params, false);
        }
        Transacao::commit();
    }

    /**
     * Metodo para carregar as categorias de imoveis
     * Historico
     * <code>
     * = ['2012-11-21'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name loadCategoriaMaterial
     * @param
     *            void
     * @access public
     * @return void
     */
    public function loadCategoriaImovel()
    {
        global $conn;
        $sql = new SqlServer();
        $categorias = $sql->getCategoriaImoveis();

        if (count($categorias) > 0) {
            $conn->query("delete from categorias where ST_Categoria = 3");

            foreach ($categorias as $categoria) {
                $codigo = $categoria['ID'];
                $nome = str_replace(array(
                    "'",
                    "ÃÆÃâÃÂ¢Ã¢âÂ¬Ãâ",
                    "ÃÆÃâÃÂ¢Ã¢âÂ¬ÃÂ",
                    "ÃÆÃâÃâÃÂ",
                    "ÃÆÃâÃâÃÂ¡",
                ), array(
                    "",
                    "O",
                    "O",
                    "A",
                    "a",
                ), $categoria['NM']);
                $url = Padrao::limpaValorUrlAmigavel($categoria['NM']);

                $params = array(
                    'ST_Categoria' => 3,
                    'ID' => $codigo,
                    'NM_Categoria' => $nome,
                    'Url_Amigavel_NM_Categoria' => $url,
                );

                Transacao::db_insert('categorias', $params, false);
            }
            Transacao::commit();
        }
    }

    /**
     * Metodo para carregar o catalogo de leil�es (tabela leilao)
     * Historico
     * <code>
     * = ['2012-11-21'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name loadCatalogo
     * @param
     *            void
     * @access public
     * @return void
     */
    public function loadCatalogo($modo='leilao')
    {
    	global $conn;
		if($modo == 'pdf'){
			$query = " select leilao_id from leilao where st_leilao not in ('E','O') ";
			$rs = $conn->query($query);
			$catalogos=array();
			while(@$row = $rs->fetch(PDO::FETCH_ASSOC)){
				$catalogos[] = $row['leilao_id'];
			}
	        if (count($catalogos) > 0) {
 	            Adm::limparPasta('editais');
            	Adm::limparPasta('catalogos');
	            foreach ($catalogos as $catalogo) {
	                Adm::loadCatalogos($catalogo);
	                Adm::loadEditais($catalogo);
	            }
			}
		}else{
	        $sql = new SqlServer();
	        $catalogos = $sql->getCatalogo();

            $leilao_interno = array();
	        if (count($catalogos) > 0) {
	            $leilao = self::clearTableLeilao();

                $leilao_insert = array();
	            foreach ($catalogos as $catalogo) {
	                if ($catalogo['st_leilaointerno'] == 1) {
	                    if (!array_key_exists($catalogo['leilao_id'], $leilao_interno)) {
	                        $leilao_interno[$catalogo['leilao_id']] = $catalogo;
	                    }
	                }
	                $query = " SELECT 1 FROM leilao_encerrado WHERE leilao_id = {$catalogo['leilao_id']}";
	                $existe = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
	                if(!$existe){
    	                if (!in_array($catalogo['leilao_id'], $leilao)) {
    	                    if (!array_key_exists($catalogo['leilao_id'], $leilao_insert)) {
    	                        Adm::insert('leilao', $catalogo, true);
                                // Transacao::commit();
    	                    }
    	                }
	                }
	            }
	            if (count($leilao_interno) > 0) {
	                self::loadLeilaoInterno($leilao_interno);
	            }

	        }
        }
        return "Publica&ccedil;&atilde;o de catalogos concluida";
    }
    private static function fixInsertCatalogo($array)
    {
    }
    private static function loadLeilaoInterno($internos)
    {
        global $conn;

        $query = "select leilao_id from leilao_interno group by leilao_id ";
        $rs = $conn->query($query);
        $olds = array();
        if ($rs) {
            while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                $olds[] = $row['leilao_id'];
            }
        }

        foreach ($internos as $interno) {
            if (!empty($interno['cliente_id'])) {
                $cliente = strtolower(Padrao::getData("clientes", "nm_cliente", "id={$interno['cliente_id']}"));
                $params = array(
                    'leilao_id' => $interno['leilao_id'],
                    'cliente_id' => $interno['cliente_id'],
                    'bloquear_lance' => 1,
                    'bloquear_apelido' => 1,
                    'bloquear_total' => 0,
                    'scroll_foto' => 0,
                    'intervalo_lote' => 60,
                    'intervalo_status' => 20,
                    'intervalo_status_pregao' => 60,
                    'intervalo_status_uma' => 40,
                    'intervalo_status_duas' => 20,
                    'tipo_impressao' => 4,
                    'path' => "interno/{$cliente}",
                    'data_inicio' => "NOW()",
                    'data_fim' => $interno['dt_time_leilao'],
                    'ativo' => 1,
                );

                if (!in_array($params['leilao_id'], $olds)) {
                    Transacao::db_insert("leilao_interno", $params);
                }
            }
        }
        Transacao::commit();
    }
    private static function clearTableLeilao()
    {
        global $conn;

        $leilao = array();

        $query = " SELECT leilao_id FROM leilao_interno WHERE ativo = 1 ";
        $rs = $conn->query($query);
        $leilao = array();
        while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
            $leilao[] = $row['leilao_id'];
        }

        /**
         * DELETA OS LEILOES ABERTOS E ENCERRADOS DA TABELA LEILAO
         */
        if (count($leilao) > 0) {
            $where_not = " WHERE leilao_id NOT IN (" . implode(",", $leilao) . ") AND st_leilao <> ('O') ";
        } else {
            $where_not = " WHERE st_leilao <> ('O') ";
        }

        $query = "DELETE FROM leilao $where_not";
        $conn->query($query);



        /* if (count($leilao) > 0) {
		  $query = " DELETE FROM leilao WHERE leilao_id NOT IN(" . implode(",", $leilao) . ") AND st_leilao = 'E' " ;
        } else {
          $query = " DELETE FROM leilao WHERE st_leilao = 'E' " ;
        }

		$conn->query($query); */

		Transacao::commit();
        return $leilao;
    }

    /**
     * Metodo para carregar os leil�es judiciais (tabela judicial)
     * Historico
     * <code>
     * = ['2012-11-21'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name loadJudicial
     * @param
     *            void
     * @access public
     * @return void
     */
    public function loadJudicial()
    {
        $sql = new SqlServer();
        $judiciais = $sql->getJudicial();

        if (!empty($judiciais)) {
            Adm::clearTable('judicial');
            foreach ($judiciais as $judicial) {
                Adm::insert('judicial', $judicial, true);
            }
            return "Publicando Tabela de Judicial\n";
        }
    }

    /**
     * Metodo para limpar pasta de catalogo ou edital
     * Historico
     * <code>
     * = ['2012-11-21'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name limparPasta
     * @param String $pasta
     * @access private
     * @return void
     */
    private static function limparPasta($pasta)
    {
        $rm_editais = sprintf('rm -f %s/%s/*', _PDF_DIR_, $pasta);
        system($rm_editais);
    }

    /**
     * Metodo para limpar uma determinada tabela
     * Historico
     * <code>
     * = ['2012-11-21'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name clearTable
     * @param String $table,
     *            Integer $leilao_id
     * @access private
     * @return void
     */
    private static function clearTable($table, $leilao_id = 0, $array_delete = array())
    {
        global $conn;

        $where = $leilao_id > 0 ? " and leilao_id = $leilao_id" : '';

        if (!empty($array_delete)) {
            $wheres = array();
            foreach ($array_delete as $row) {
                $tmp = array();
                foreach ($row as $indice => $valor) {
                    $tmp[] = "{$indice} = '{$valor}'";
                }
                $wheres[] = '(' . implode(' AND ', $tmp) . ')';
            }

            $where .= " and " . implode(" or ", $wheres);
        }

        $query = "delete from $table where 1 $where ";
        $conn->query($query);
        Transacao::commit();
    }

    /**
     * Metodo para carregar editais disponiveis
     * Historico
     * <code>
     * = ['2012-11-21'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name loadEditais
     * @param Integer $leilao_id
     * @access private
     * @return void
     */
    private static function loadEditais($leilao_id)
    {
        $file_leilao_id = "leilao" . $leilao_id . ".pdf";
        if (file_exists("/wwwroot/shares/editais/strtoupper" . strtoupper($file_leilao_id))) {
            $system = sprintf('cp /wwwroot/shares/editais/%s %s/editais/', strtolower($file_leilao_id), _PDF_DIR_);
            system($system);
            // echo ("Copiando Edital {$leilao_id} \n");
        } elseif (file_exists("/wwwroot/shares/editais/" . strtolower($file_leilao_id))) {
            $system = sprintf('cp /wwwroot/shares/editais/%s %s/editais/', strtolower($file_leilao_id), _PDF_DIR_);
            system($system);
            // echo ("Copiando Edital {$leilao_id} \n");
        }
    }

    /**
     * Metodo para carregar catalogos disponiveis
     * Historico
     * <code>
     * = ['2012-11-21'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name loadCatalogos
     * @param Integer $leilao_id
     * @access private
     * @return void
     */
    private static function loadCatalogos($leilao_id)
    {
        $file_leilao_id = "leilao" . $leilao_id . ".pdf";

        if (file_exists("/wwwroot/shares/catalogos/" . strtoupper($file_leilao_id))) {
            $system = sprintf('cp /wwwroot/shares/catalogos/%s %s/catalogos/', strtolower($file_leilao_id), _PDF_DIR_);
            system($system);
            // echo ("Copiando Catalogo {$leilao_id} \n");
        } elseif (file_exists("/wwwroot/shares/catalogos/" . strtolower($file_leilao_id))) {
            $system = sprintf('cp /wwwroot/shares/catalogos/%s %s/catalogos/', strtolower($file_leilao_id), _PDF_DIR_);
            system($system);
            // echo ("Copiando Catalogo {$leilao_id} \n");
        }
    }

    /**
     * Metodo para gerar multiplo do lote
     * Historico
     * <code>
     * = ['2012-11-21'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name geraMultiplo
     * @param Float $vl_incremento_minimo
     * @access private
     * @return Integer $multiplo
     */
    public static function geraMultiplo($incremento)
    {
        $incremento = (float) $incremento;

        if ($incremento < 1) {
            $multiplo = 0.01;
        } elseif ($incremento < 10) {
            $multiplo = 0.10;
        } elseif ($incremento < 50) {
            $multiplo = 1;
        } elseif ($incremento < 200) {
            $multiplo = 50;
        } elseif ($incremento < 10000) {
            $multiplo = 100;
        } elseif ($incremento < 20000) {
            $multiplo = 500;
        } elseif ($incremento < 100000) {
            $multiplo = 1000;
        } elseif ($incremento < 200000) {
            $multiplo = 5000;
        } elseif ($incremento < 1000000) {
            $multiplo = 10000;
        } else {
            $multiplo = 50000;
        }

        // if ($incremento <= 0.10) {
        // $multiplo = 0.01;
        // } elseif ($incremento <= 1) {
        // $multiplo = 0.10;
        // } elseif ($incremento <= 5) {
        // $multiplo = 1;
        // } elseif ($incremento <= 20) {
        // $multiplo = 5;
        // } elseif ($incremento <= 50) {
        // $multiplo = 10;
        // } elseif ($incremento <= 500) {
        // $multiplo = 50;
        // } elseif ($incremento <= 5000) {
        // $multiplo = 500;
        // } elseif ($incremento <= 50000) {
        // $multiplo = 5000;
        // } else {
        // $multiplo = 50000;
        // }

        return $multiplo;
    }

    /**
     * Metodo para atualizar o maior lance de cada lote
     * Obs: precisa considerar o leil�o do bradesco que � diferente
     * Historico
     * <code>
     * = ['2012-11-23'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name atualizarMaiorLance
     * @param Integer $leilao_id
     * @access private
     * @return void
     */
    private static function atualizarMaiorLance($leilao_id)
    {
        global $conn;
        /*$query = "update sslonline_status ss,
					(select sl3.sslonline_lance_id, ss2.lote_id
					  from sslonline_lance sl3
					  inner join (select max(vl_lance) as maior_lance,
								   lote_id
									from sslonline_lance
									 where leilao_id = $leilao_id
											   group by lote_id) as sl2 on sl2.maior_lance = sl3.vl_lance and sl2.lote_id = sl3.lote_id
											  inner join sslonline_status ss2 on ss2.lote_id = sl3.lote_id) as sl
				   set lance_id = sl.sslonline_lance_id
				 where ss.lote_id = sl.lote_id";*/

        $query = "UPDATE
         santoro_dev.sslonline_status a
         LEFT JOIN (
          SELECT b.leilao_id, b.lote_id, MAX(sslonline_lance_id) AS max_lance_id, MAX(vl_lance) AS max_vl_lance, COUNT(1) AS qtde_lance
          FROM santoro_dev.sslonline_lance b
          WHERE b.leilao_id = $leilao_id
          GROUP BY b.lote_id
        ) c ON (c.leilao_id = a.leilao_id AND c.lote_id = a.lote_id)
        SET
         a.lance_id = c.max_lance_id
         , a.vl_maior_lance = c.max_vl_lance
         , a.nu_qtde_lance = qtde_lance
        WHERE a.leilao_id = $leilao_id;";
        $conn->query($query);
        Transacao::commit();
    }

    /**
     * Metodo para buscar os leiloes_ids personalizados
     * Obs: controlados por xml 'clientes.xml'
     * Historico
     * <code>
     * = ['2012-11-23'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name getEmpresasArray
     * @param Integer $leilao_id
     * @access public
     * @return Array $leiloes
     */
    public static function getEmpresasArray($empresa)
    {
        global $conn;

        $leiloes = array();
        $cliente_id = Adm::getEmpresas($empresa);

        if ($cliente_id) {
            $query = "select leilao_id from leilao where cliente_id in ($cliente_id)";
            $rs = $conn->query($query);
            if ($rs) {
                while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                    $leiloes[] = $row['leilao_id'];
                }
            }
        }
        return $leiloes;


        // $query = " select leilao_id from leilao_cliente where empresa = '$empresa' group by leilao_id ";
        // $leiloes = array();
        // $rs = $conn->query($query);
        // if ($rs) {
        //     while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
        //         $leiloes[] = $row['leilao_id'];
        //     }
        // }
        // return $leiloes;
    }

    public static function getEmpresasFotoArray($empresa)
    {
        global $conn;

        $query = " select leilao_id from leilao_cliente_foto where empresa = '$empresa' group by leilao_id ";
        $leiloes = array();
        $rs = $conn->query($query);
        if ($rs) {
            while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                $leiloes[] = $row['leilao_id'];
            }
        }
        return $leiloes;
    }

    /**
     * Metodo para carregar os clientes_ids personalizados
     * Historico
     * <code>
     * = ['2012-11-23'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name getEmpresas
     * @param String $empresa
     * @access private
     * @return String $retorno
     */
    // Alterado para public em 10/11/2015 R
    //private static function getEmpresas($empresa)
    public static function getEmpresas($empresa)
    {
        $path = _XMLDIR_ . "/clientes.xml";
        $xml = simplexml_load_file($path);
        $retorno = array();

        if (count($xml) > 0) {
            foreach ($xml->$empresa->cliente_id as $row) {
                $retorno[] = $row;
            }
            return implode(',', $retorno);
        } else {
            return $retorno;
        }
    }

    /**
     * Metodo para pegar o campo de observa��o
     * Historico
     * <code>
     * = ['2012-11-23'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name getObs
     * @param String $segmento
     *            , Integer $deposito
     * @access public
     * @return String $retorno
     */
    public static function getObs($segmento, $deposito = 0)
    {
        global $conn;

        $query = "select obs from obs_padrao where segmento = '$segmento' ";
        if ($deposito > 0) {
            $query .= " and ( deposito_id = $deposito  or deposito_id is null )";
        }

        $obs = '';
        $rs = $conn->query($query);
        if ($rs) {
            while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                $obs .= $row['obs'] . ' ';
            }
        }
        return $obs;
    }

    /**
     * Metodo para apagar as fotos n�o usadas do site
     * Historico
     * <code>
     * = ['2012-11-23'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name clearPhotos
     * @param
     *            void
     * @access private
     * @return void
     */
    private function clearPhotos()
    {

		$arquivos =  array();
    	if (is_dir($this->foto_dst_dir)) {
            if ($dh = opendir($this->foto_dst_dir)) {
                while (($file = readdir($dh)) !== false) {
					if(preg_match('/(c|l|m|i)[0-9]+[a-z]\.jpg/i', $file)) {
                		$arquivos[] = $file;
					}
                }
                closedir($dh);
            }
			$diff = array_diff($arquivos,$this->fotos_utilizadas);

			if(count($diff) > 0){
				foreach($diff as $file){
	            	printf("Apagando o arquivo %s. Foto 640x420 nao utilizada.\n", $this->foto_dst_dir . '/' . $file);
	                $s = sprintf('rm -f %s', $this->foto_dst_dir . '/' . $file);
	                system($s);
	                printf("Apagando o arquivo %s. Foto 320x240 nao utilizada.\n", $this->foto_dst_dir . '/320x240/' . $file);
	                $s = sprintf('rm -f %s', $this->foto_dst_dir . '/320x240/' . $file);
	                system($s);
	                printf("Apagando o arquivo %s. Foto 194x143 nao utilizada.\n", $this->foto_dst_dir . '/194x143/' . $file);
	                $s = sprintf('rm -f %s', $this->foto_dst_dir . '/194x143/' . $file);
	                system($s);
	                printf("Apagando o arquivo %s. Foto 95x70 nao utilizada.\n", $this->foto_dst_dir . '/95x70/' . $file);
	                $s = sprintf('rm -f %s', $this->foto_dst_dir . '/95x70/' . $file);
	                system($s);
	            }

				echo("Apagado " . count($diff) . " fotos \n");
			}
		}
    }

    /**
     * Metodo para copiar as fotos
     * Historico
     * <code>
     * = ['2012-11-23'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name copyPhotos
     * @param String $p
     *            , String $id , String $nm_caminhoimagem
     * @access private
     * @return Array $foto_existente , $array_return;
     */
    private function copyPhotos($p, $id, $nm_caminhoimagem, $leilao_id , $nu_lote)
    {
        $array_return = array();
        $filecontrole = $this->foto_dst_dir . "/controle.txt";

        $ultima_atualizacao = date("Y m d His", mktime(0, 0, 0, 7, 1, 2000));
        $aux_array_fotos = array();
        if (file_exists($filecontrole)) {
            $ultima_atualizacao = date("YmdHis", filemtime($filecontrole) - 600);
        }

        if (intval($id) == 0) {
            return;
        }

        $this->foto_existente = 0;
        if ($nm_caminhoimagem != '') {
            for ($a = 'A'; $a <= _CHECK_LETTER_; $a++) {
                $foto_filename = sprintf("%s%d%s.JPG", $p, $id, $a);
                $foto_filename_lower = sprintf("%s%d%s.JPG", strtolower($p), $id, $a);
                if ($nm_caminhoimagem) {
                	$array_path=array();
                    $caminho = explode('\\', $nm_caminhoimagem);
					foreach($caminho as $row){
						if(!empty($row))
							$array_path[] = $row;
					}
                    $imgpath = $array_path[2] . '/' . $array_path[3] . '/';
                }

                $foto_path_upper = $this->foto_src_dir . "/" . $imgpath . $foto_filename;
                $foto_path_lower = $this->foto_src_dir . "/" . $imgpath . $foto_filename_lower;

                $foto_path_dst = $this->foto_dst_dir . '/' . $foto_filename;
                $foto320x240 = $this->foto320x240_dst_dir . '/' . $foto_filename;

                $foto194x143 = $this->foto194x143_dst_dir . '/' . $foto_filename;
                $foto95x70 = $this->foto95x70_dst_dir . '/' . $foto_filename;

                if (file_exists($foto_path_dst)) {

                    if (file_exists($foto_path_upper)) {
                        $this->alterFoto($foto_filename, $ultima_atualizacao, $foto_path_upper, $foto_path_dst, $foto320x240, $foto194x143, $foto95x70 , $leilao_id , $nu_lote);
                        if (!in_array($foto_filename, $array_return)) {
                        	array_push($this->fotos_utilizadas, $foto_filename);
                            array_push($array_return, $foto_filename);
                        }
                    }
                    if (file_exists($foto_path_lower)) {
                        $this->alterFoto($foto_filename, $ultima_atualizacao, $foto_path_lower, $foto_path_dst, $foto320x240, $foto194x143, $foto95x70 , $leilao_id , $nu_lote);
                        if (!in_array($foto_filename, $array_return)) {
                        	array_push($this->fotos_utilizadas, $foto_filename);
                            array_push($array_return, $foto_filename);
                        }
                    }
                } else {
                    if (file_exists($foto_path_upper)) {
                        $this->copyFormatPhoto($foto_path_upper, $foto_path_dst, $foto320x240, $foto194x143, $foto95x70,$leilao_id , $nu_lote);
                        if (!in_array($foto_filename, $array_return)) {
                        	array_push($this->fotos_utilizadas, $foto_filename);
                            array_push($array_return, $foto_filename);
                        }

                    }
                    if (file_exists($foto_path_lower)) {
                        $this->copyFormatPhoto($foto_path_lower, $foto_path_dst, $foto320x240, $foto194x143, $foto95x70,$leilao_id , $nu_lote);

                        if (!in_array($foto_filename, $array_return)) {
                            array_push($this->fotos_utilizadas, $foto_filename);
                            array_push($array_return, $foto_filename);
                        }


                    }
                }
            }
        }

        return array(
            $this->foto_existente,
            $array_return,
        );
    }

    /**
     * Metodo para validar a necessidade de copiar a foto
     * Historico
     * <code>
     * = ['2012-11-23'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name alterFoto
     * @param String $foto_filename,
     *            String $ultima_atualizacao, String $foto_path, String $foto_path_dst, String $foto320x240, String $foto194x143, String $foto95x70
     * @access private
     * @return void
     */
    private function alterFoto($foto_filename, $ultima_atualizacao, $foto_path, $foto_path_dst, $foto320x240, $foto194x143, $foto95x70, $leilao_id , $nu_lote)
    {
        $this->foto_existente = 1;

        if(date("YmdHis", filemtime($foto_path_dst))  < date("YmdHis", filemtime($foto_path))){
	         printf ("Atualizando o arquivo %s \n", $foto_path_dst);
        }

        if (date("YmdHis", filemtime($foto_path_dst))  < date("YmdHis", filemtime($foto_path)) || $this->force == 1 ) {
            //printf ("Apagando o arquivo %s \n", $foto_path_dst);
            $s = sprintf('rm -f %s', $foto_path_dst);
            system($s);

            //printf ("Apagando o arquivo %s \n", $foto320x240);
            $s = sprintf('rm -f %s', $foto320x240);
            system($s);

            //printf ("Apagando o arquivo %s \n", $foto194x143);
            $s = sprintf('rm -f %s', $foto194x143);
            system($s);

            //printf ("Apagando o arquivo %s \n", $foto95x70);
            $s = sprintf('rm -f %s', $foto95x70);
            system($s);

            $this->copyFormatPhoto($foto_path, $foto_path_dst, $foto320x240, $foto194x143, $foto95x70,$leilao_id , $nu_lote);
        } else {

            //printf ($foto_filename . " - Arquivo igual. n�o � necess�rio apaga-lo! \n");
        }
    }

    /**
     * Metodo para copiar a foto colocando a marca dagua e formantando a foto no tamanho correto
     * Historico
     * <code>
     * = ['2012-11-23'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name copyFormatPhoto
     * @param String $foto_path,
     *            String $foto_path_dst, String $foto320x240, String $foto194x143, String $foto95x70
     * @access private
     * @return void
     */
    private function copyFormatPhoto($foto_path, $foto_path_dst, $foto320x240, $foto194x143, $foto95x70 , $leilao_id, $nu_lote)
    {
        if (file_exists($foto_path)) {
            $this->foto_existente = 1;

            // printf ("Copiando o arquivo %s \n", $foto_path);
            $s = sprintf('cp %s %s', $foto_path, $foto_path_dst);
            system($s);

            $img_detalhes = getimagesize($foto_path_dst);
            $tamanho_padrao_1 = "320x240";
            $tamanho_padrao_2 = "194x143";
            $tamanho_padrao_3 = "95x70";

            if ($img_detalhes[0] != 640 || $img_detalhes[1] != 480) {
                $s = sprintf('/usr/bin/composite -geometry +187+0 %s /wwwroot/sodresantoro/http/imagens/marca_dagua/canvas.gif %s', $foto_path_dst, $foto_path_dst);
                system($s);
                //printf ("Resizing para 640x480 em %s \n", $foto_path_dst);
                $s = sprintf('/main/bin/resize_image %s 640x480', $foto_path_dst);
                system($s);
            }
			/*
			 * removido 28/04/2016, será necessário mudar a chave das imagens para leilao e lote para evitar os problemas de fotos antigas em lotes novos. e a remoção de fotos dos encerrados.
			 * */

            /*if(!empty($leilao_id) && !empty($nu_lote)){
            	$texto = utf8_encode(sprintf('Leilão %s - Lote: %s' , $leilao_id , trim($nu_lote))) ;
	            $s = '/usr/bin/convert ' . $foto_path_dst . ' -pointsize 15 -draw ' ;
	            $s .= '"fill black text 471,15 ' . "'$texto' ";
				$s .= '		       text 469,15 ' . "'$texto' ";
				$s .= '		       text 471,16 ' . "'$texto' ";
				$s .= '		       text 471,14 ' . "'$texto' ";
				$s .= '		       text 469,16 ' . "'$texto' ";
				$s .= '		       text 469,14 ' . "'$texto' ";
	            $s .= 'fill white  text 470,15 ' . "'$texto' ";
			    //$s .= "'".utf8_encode(sprintf('Leil�o %s - Lote: %s' , $leilao_id , trim($nu_lote))) . "'";
	            $s .= '" ' . $foto_path_dst;
				system($s);
            }*/

            if (!in_array($this->leilao_id, Adm::getEmpresasArray('bradesco')) && !in_array($this->leilao_id, Adm::getEmpresasArray('trt'))) {
                //printf ("Marca D'Agua em %s \n", $foto_path_dst);
                $s = sprintf('/usr/bin/composite -dissolve 50 /wwwroot/sodresantoro/http/imagens/marca_dagua/textologo_480.gif %s %s', $foto_path_dst, $foto_path_dst);
                system($s);
            }

            //printf ("Diminui a Qualidade em %s \n", $foto_path_dst);
            $s = sprintf('/usr/bin/convert -quality 30 %s %s', $foto_path_dst, $foto_path_dst);
            system($s);

            //printf ("Resizing %s to 320x240\n", $foto320x240);
            $s = sprintf('cp %s %s', $foto_path_dst, $foto320x240);
            system($s);

            $s = sprintf('/main/bin/resize_image %s %s', $foto320x240, $tamanho_padrao_1);
            system($s);

            //printf ("Diminui a Qualidade em %s \n", $foto320x240);
            $s = sprintf('/usr/bin/convert -quality 30 %s %s', $foto320x240, $foto320x240);
            system($s);

            //printf ("Resizing %s to 194x143\n", $foto194x143);
            $s = sprintf('cp %s %s', $foto_path_dst, $foto194x143);
            system($s);

            $s = sprintf('/main/bin/resize_image %s %s', $foto194x143, $tamanho_padrao_2);
            system($s);

            //printf ("Diminui a Qualidade em %s \n", $foto194x143);
            $s = sprintf('/usr/bin/convert -quality 30 %s %s', $foto194x143, $foto194x143);
            system($s);

            //printf ("Resizing %s to 95x70\n", $foto95x70);
            $s = sprintf('cp %s %s', $foto_path_dst, $foto95x70);
            system($s);

            $s = sprintf('/main/bin/resize_image %s %s', $foto95x70, $tamanho_padrao_3);
            system($s);

            //printf ("Diminui a Qualidade em %s \n", $foto95x70);
            $s = sprintf('/usr/bin/convert -quality 30 %s %s', $foto95x70, $foto95x70);
            system($s);
        }
        return;
    }

    /**
     * Metodo para copia de fotos de veiculos usado no cron
     * Historico
     * <code>
     * = ['2012-11-23'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name loadFotosVeiculos
     * @param Integer $lote_id
     * @access public
     * @return void
     */
    public function loadFotosVeiculos($params=array())
    {
        global $conn;

 		$this->foto_dst_dir = "/wwwroot/Fotos/fotos.veiculos";
        $this->foto320x240_dst_dir = "/wwwroot/Fotos/fotos.veiculos/320x240";
        $this->foto194x143_dst_dir = "/wwwroot/Fotos/fotos.veiculos/194x143";
        $this->foto95x70_dst_dir = "/wwwroot/Fotos/fotos.veiculos/95x70";
        $this->fotos_utilizadas = array();

		if(isset($params['modo'])){
	    	switch ($params['modo']) {
				case 'leilao': {
					$where  = " where leilao_id in ('{$params['leilao_id']}') " ;
				}break;
				case 'lote': {
					$where  = " where lote_id in ('{$params['lote_id']}') " ;
				}break;
			}
		}else{
			$where = '';
		}

		$aux_fotos = array();
        $query = "SELECT carro_id, lote_id, leilao_id, nu_lote , nm_caminhoimagem FROM sslonline_veiculos $where";
		$rs = $conn->query($query);
        if ($rs) {
            while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
	            $carro_id = $row["carro_id"];
                $lote_id_fix = $row["lote_id"];
                $nm_caminhoimagem = $row["nm_caminhoimagem"];
                $leilao_id = $row['leilao_id'];
                $nu_lote = $row['nu_lote'];

				if($this->force == 1){
					$sqlserver = new SqlServer();
					$nm_caminhoimagem_update = $sqlserver->getCaminhoImagem(array('lote_id'=>$lote_id_fix , 'carro_id' => $carro_id));
			   	    if(($nm_caminhoimagem_update != $row['nm_caminhoimagem'])){
                		$nm_caminhoimagem=$nm_caminhoimagem_update;
				 	}
				}

                $this->leilao_id = $row['leilao_id'];
                $aux_array_fotos_delete[] = $carro_id;
                $array_fotos1 = array();
                $array_fotos2 = array();
                $fotos = '';
                $foto_existente1=false;
				$foto_existente2=false;
                list($foto_existente1, $array_fotos1) = $this->copyPhotos("C", $carro_id, $nm_caminhoimagem, $leilao_id , $nu_lote );

                if ($lote_id_fix > 0) {
                    list($foto_existente2, $array_fotos2) = $this->copyPhotos("L", $lote_id_fix, $nm_caminhoimagem, $leilao_id , $nu_lote );
                }

                if (is_array($array_fotos1) && count($array_fotos1) > 0) {
                    $fotos = implode(";", $array_fotos1);
                }

                if (is_array($array_fotos2) && count($array_fotos2) > 0) {
                    $fotos .= implode(";", $array_fotos2);
                }

                if ($foto_existente1 == 1 or $foto_existente2 == 1) {
                	$nm_caminhoimagem = addslashes($nm_caminhoimagem);
                	$query = "UPDATE sslonline_veiculos SET nm_img='$fotos' , nm_caminhoimagem='$nm_caminhoimagem' WHERE (leilao_id=$this->leilao_id and lote_id=$lote_id_fix and carro_id = $carro_id);";
                    $conn->query($query);
                }
            }

			Transacao::commit();
			if(Padrao::isProducao()){
				if($params['lote_id'] > 0){
					self::publishFotos('veiculos',$this->leilao_id,$params['lote_id']);
				}elseif($params['leilao_id'] > 0 ){
					self::publishFotos('veiculos',$params['leilao_id']);
				}else{
 					self::publishFotos('veiculos');
				}
			}

            if (empty($params)) {
                echo "\n\nApagando fotos nao utilizadas pelo site...!!!\n";
                $this->clearPhotos();
                $fp = fopen($this->foto_dst_dir . "/controle.txt", "w");
                fputs($fp, "Arquivo de controle da atualizacao de fotos\n");
                fclose($fp);
                print("\nEnviando veiculos sem foto.\n");
                $email = new Email();
                $email->sendLotesSemFotos('veiculos');
            }
        }
    }
    public function getFileSizesFotos($segmento)
    {
        global $conn;

        $query = " select nome,filesize from aux_fotos where st_categoria = '{$segmento}'";
        $aux_fotos = array();
        $rs = $conn->query($query);
        if ($rs) {
            while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                $aux_fotos[$row['nome']] = $row['filesize'];
            }
        }
        $query = " delete from aux_fotos where st_categoria = '{$segmento}'";
        $conn->query($query);

        return $aux_fotos;
    }
    private static function InserirAuxFotos($data)
    {
        global $conn;
        $query = "";
        foreach ($data as $row) {
            $query = "/* InserirAuxFotos */ ";
            $query .= "insert into aux_fotos (st_categoria,segmento_id,nome,filesize) values  ";
            $query .= "('{$row['segmento']}','{$row['segmento_id']}','{$row['nome']}','{$row['filesize']}')";
            $conn->query($query);
        }
    }

    /**
     * Metodo para copia de fotos de materiais usado no cron
     * Historico
     * <code>
     * = ['2012-11-23'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name loadFotosMateriais
     * @param Integer $lote_id
     * @access public
     * @return void
     */
    public function loadFotosMateriais($params=array())
    {
        global $conn;
        $this->foto_dst_dir = "/wwwroot/Fotos/fotos.materiais";
        $this->foto320x240_dst_dir = "/wwwroot/Fotos/fotos.materiais/320x240";
        $this->foto194x143_dst_dir = "/wwwroot/Fotos/fotos.materiais/194x143";
        $this->foto95x70_dst_dir = "/wwwroot/Fotos/fotos.materiais/95x70";
        $this->fotos_utilizadas = array();

        $aux_fotos = array();

		if(isset($params['modo'])){
	    	switch ($params['modo']) {
				case 'leilao': {
					$where  = " where leilao_id in ('{$params['leilao_id']}') " ;
				}break;
				case 'lote': {
					$where  = " where lote_id in ('{$params['lote_id']}') " ;
				}break;
			}
		}else{
			$where = '';
		}
        $query = "SELECT id, lote_id, leilao_id , nm_caminhoimagem , lote as nu_lote  FROM sslonline_materiais $where";
        $rs = $conn->query($query);
        if ($rs) {
            while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                $material_id = $row["id"];
                $lote_id_fix = $row["lote_id"];
                $nm_caminhoimagem = $row["nm_caminhoimagem"];
                $leilao_id = $row['leilao_id'];
                $nu_lote = $row['nu_lote'];
        	    $this->leilao_id = $row['leilao_id'];

				if($this->force == 1){
					$sqlserver = new SqlServer();
					$nm_caminhoimagem_update = $sqlserver->getCaminhoImagem(array('lote_id'=>$lote_id_fix , 'material_id' => $material_id));
                    if(($nm_caminhoimagem_update != $row['nm_caminhoimagem'])){
                		$nm_caminhoimagem=$nm_caminhoimagem_update;
				 	}
				}


                $array_fotos1 = array();
                $array_fotos2 = array();
                $fotos = '';
                list($foto_existente1, $array_fotos1) = $this->copyPhotos("M", $material_id, $nm_caminhoimagem, $leilao_id, $nu_lote);

			    if ($lote_id_fix > 0) {
                    list($foto_existente2, $array_fotos2) = $this->copyPhotos("L", $lote_id_fix, $nm_caminhoimagem, $leilao_id, $nu_lote);
				}

                if (is_array($array_fotos1)) {
                    $fotos = implode(";", $array_fotos1);
                }

                if (is_array($array_fotos2)) {
                    $fotos .= implode(";", $array_fotos2);
                }

           	 	if ($foto_existente1 == 1 or $foto_existente2 == 1) {
           	 		$nm_caminhoimagem = addslashes($nm_caminhoimagem);
                    $query = "UPDATE sslonline_materiais SET nm_img='$fotos', nm_caminhoimagem='$nm_caminhoimagem' WHERE (leilao_id=$this->leilao_id and lote_id=$lote_id_fix and id = $material_id);";
                    $conn->query($query);
                    Transacao::commit();
                }
            }

			if(Padrao::isProducao()){
				if($params['lote_id'] > 0){
					self::publishFotos('materiais',$this->leilao_id,$params['lote_id']);
				}elseif($params['leilao_id'] > 0){
					self::publishFotos('materiais',$params['leilao_id']);
				}else{
					self::publishFotos('materiais');
				}
			}


            if (empty($params)) {
                echo "\n\nApagando fotos nao utilizadas pelo site...!!!\n";
                $this->clearPhotos();
                $fp = fopen($this->foto_dst_dir . "/controle.txt", "w");
                fputs($fp, "Arquivo de controle da atualizacao de fotos\n");
                fclose($fp);
                print("\nEnviando materiais sem foto.\n");
                $email = new Email();
                $email->sendLotesSemFotos('materiais');
            }
        }
    }

    public static function publishFotos($segmento,$leilao_id=0,$lote_id=0){
    	global $conn;
		switch ($segmento) {
			case 'veiculos':{
			    	$query = "
							update sslonline_status s
							inner join (
							select nm_img ,lote_id,leilao_id from sslonline_veiculos where nm_img <> ''
							) v on s.leilao_id = v.leilao_id and s.lote_id = v.lote_id
							set s.nm_img = v.nm_img
						";
                        $where = array();
				    	if($leilao_id > 0 )
                            $where[] = " s.leilao_id = $leilao_id ";
						if($lote_id > 0 )
                            $where[] = " s.lote_id = $lote_id ";
                        if(count($where) > 0)
                            $query .= " where " . implode(' AND ', $where);
                 		$conn->query($query);
						Transacao::commit();

				};break;
				case 'materiais':{
				    	$query = "
							update sslonline_status s
							inner join (
							select group_concat(nm_img SEPARATOR ';') as nm_img,lote_id,leilao_id from sslonline_materiais where nm_img <> '' group by lote_id
							) m on s.leilao_id = m.leilao_id and s.lote_id = m.lote_id
							set s.nm_img = m.nm_img
						";
                         $where = array();
				    	if($leilao_id > 0 )
                            $where[] = " s.leilao_id = $leilao_id ";
						if($lote_id > 0 )
                            $where[] = " s.lote_id = $lote_id ";
                        if(count($where) > 0)
                            $query .= " where " . implode(' AND ', $where);
                 		$conn->query($query);
						Transacao::commit();
				};break;

				case 'imoveis':{
						$query = "
							update sslonline_status s
							inner join (
							select nm_img ,lote_id,leilao_id from sslonline_imoveis where nm_img <> ''
							) i on s.leilao_id = i.leilao_id and s.lote_id = i.lote_id
							set s.nm_img = i.nm_img
						";
                        $where = array();
				    	if($leilao_id > 0 )
                            $where[] = " s.leilao_id = $leilao_id ";
						if($lote_id > 0 )
                            $where[] = " s.lote_id = $lote_id ";
                        if(count($where) > 0)
                            $query .= " where " . implode(' AND ', $where);

                 		$conn->query($query);
						Transacao::commit();

				};break;

		}
    }

    /**
     * Metodo para copia de fotos de materiais usado no cron
     * Historico
     * <code>
     * = ['2012-11-23'] Tiago Antoniazi Del Guerra
     * </code>
     *
     * @author Tiago Antoniazi Del Guerra <tiago.antoniazi@gmail.com>
     * @version 1.0.0
     *
     * @name loadFotosImoveis
     * @param Integer $lote_id
     * @access public
     * @return void
     */
    public function loadFotosImoveis($params=array())
    {
        global $conn;
        $this->foto_dst_dir = "/wwwroot/Fotos/fotos.imoveis";
        $this->foto320x240_dst_dir = "/wwwroot/Fotos/fotos.imoveis/320x240";
        $this->foto194x143_dst_dir = "/wwwroot/Fotos/fotos.imoveis/194x143";
        $this->foto95x70_dst_dir = "/wwwroot/Fotos/fotos.imoveis/95x70";
        $this->fotos_utilizadas = array();


        $aux_fotos = array();
    	if(isset($params['modo'])){
	    	switch ($params['modo']) {
				case 'leilao': {
					$where  = " where leilao_id in ('{$params['leilao_id']}') " ;
				}break;
				case 'lote': {
					$where  = " where lote_id in ('{$params['lote_id']}') " ;
				}break;
			}
		}else{
			$where = "";
		}


        $query = "SELECT id, lote_id, leilao_id , nm_caminhoimagem , lote as nu_lote FROM sslonline_imoveis $where";

        $rs = $conn->query($query);
        if ($rs) {
            while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                $id = $row["id"];
                $lote_id_fix = $row["lote_id"];
                $nm_caminhoimagem = $row["nm_caminhoimagem"];
                $leilao_id = $row['leilao_id'];
                $nu_lote = $row['nu_lote'];
                $this->leilao_id = $row['leilao_id'];

				if($this->force == 1){
					$sqlserver = new SqlServer();
				    $nm_caminhoimagem_update = $sqlserver->getCaminhoImagem(array('lote_id'=>$lote_id , 'imovel_id' => $id));
				    if(($nm_caminhoimagem_update != $row['nm_caminhoimagem'])){
                		$nm_caminhoimagem=$nm_caminhoimagem_update;
				 	}
				}

                if($this->force == 1 && ($nm_caminhoimagem_update != $row['nm_caminhoimagem']))
                	$nm_caminhoimagem=$nm_caminhoimagem_update;

                $array_fotos1 = array();
                $array_fotos2 = array();
                $fotos = '';
                list($foto_existente1, $array_fotos1) = $this->copyPhotos("I", $id, $nm_caminhoimagem,  $leilao_id, $nu_lote);

                if ($lote_id_fix > 0) {
                    list($foto_existente2, $array_fotos2) = $this->copyPhotos("L", $lote_id_fix, $nm_caminhoimagem,  $leilao_id, $nu_lote);
                }

                if (is_array($array_fotos1)) {
                    $fotos = implode(";", $array_fotos1);
                }

                if (is_array($array_fotos2)) {
                    $fotos .= implode(";", $array_fotos2);
                }

                $nm_caminhoimagem = addslashes($nm_caminhoimagem);
                if ($foto_existente1 == 1 or $foto_existente2 == 1) {
                	$nm_caminhoimagem = addslashes($nm_caminhoimagem);
                	$query = "UPDATE sslonline_imoveis SET nm_img='$fotos' , nm_caminhoimagem='$nm_caminhoimagem' WHERE (leilao_id=$this->leilao_id and lote_id=$lote_id_fix and id = $id);";
                    $conn->query($query);
                }
            }
			Transacao::commit();
			if(Padrao::isProducao()){
				if($params['lote_id'] > 0){
					self::publishFotos('imoveis',$this->leilao_id,$params['lote_id']);
				}elseif($params['leilao_id'] > 0){
					self::publishFotos('imoveis',$params['leilao_id']);
				}else{
					self::publishFotos('imoveis');
				}
			}

            if (empty($params)) {
                echo "\n\nApagando fotos nao utilizadas pelo site...!!!\n";
                $this->clearPhotos();
                $fp = fopen($this->foto_dst_dir . "/controle.txt", "w");
                fputs($fp, "Arquivo de controle da atualizacao de fotos\n");
                fclose($fp);
                print("\nEnviando materiais sem foto.\n");
                $email = new Email();
                $email->sendLotesSemFotos('imoveis');
            }
        }
    }
    /*public static function loadPatios()
    {
        global $conn;

        $query = "truncate patios";
        $conn->query($query);

        $sql = new SqlServer();
        $patios = $sql->getPatios();
        foreach ($patios as $k => $patio) {
            $query = "insert into patios (id,nm_patio,nm_endereco,ativo) values ('$patio[ID]' , '$patio[NM]' , '$patio[NM_Endereco]',0)";
            $conn->query($query);
        }
        Transacao::commit();
        self::getXmlPatios();
    }
    public function cadastrarPatio($params)
    {
        global $conn;
        $params = array_map('utf8_decode', $params);
        $colunas = implode(',', array_keys($params));
        $linha = implode("','", array_values($params));
        $query = "INSERT INTO patios (" . $colunas . ", ativo) VALUES ('" . $linha . "', '1');";
        $conn->query($query);
        Transacao::commit();
    }
    public function updatePatio($params, $id){
        global $conn;

        foreach ($params as $k => $value) {
            $sets[] = $k . " = '" . utf8_decode($value) . "'";
        }

        $query = "UPDATE patios SET " . implode(",", $sets) . " WHERE id = " . $id . ";";
        $conn->query($query);
        Transacao::commit();
    }
    public function deletePatio($id)
    {
        global $conn;
        $query = "UPDATE patios SET ativo = '0' WHERE id = " . $id . ";";
        $conn->query($query);
        Transacao::commit();
    }*/
    public static function verificaPermissaoBanner()
    {
        // echo 'verificando...<hr>';
        // $diretorios_anexo = array();
        // $diretorios_anexo[] = 'imagens/banners_antigo/';
        // $diretorios_anexo[] = 'imagens/banners/';
        // $diretorios_anexo[] = '/wwwroot/sodresantoro-132/http/imagens/banners/';
        // $diretorios_anexo[] = '/wwwroot/sodresantoro-133/http/imagens/banners/';
        // $diretorios_anexo[] = '/wwwroot/sodresantoro-135/http/imagens/banners/';
        // $diretorios_anexo[] = '/wwwroot/sodre-132/http/imagens/v3/';
        // $diretorios_anexo[] = '/wwwroot/sodre-133/http/imagens/v3/';
        // $diretorios_anexo[] = '/wwwroot/sodre-135/http/imagens/v3/';

        // foreach($diretorios_anexo as $dir) {
        // echo 'dir: ' . $dir . '<br>';
        // $comando = shell_exec('ls -lart ' . $dir . '../');
        // if($comando) {
        // Padrao::print_pr($comando, FALSE);
        // }
        // else {
        // echo 'erro<hr>';
        // }
        // try {
        // $novo_arquivo = fopen($dir . '/arquivo_teste.txt', 'w');
        // fwrite($novo_arquivo, 'teste');
        // fclose($novo_arquivo);

        // echo 'dir: ' . $dir;
        // echo '<hr>';
        // }
        // catch(Exception $e) {
        // echo "Exce��o pega: ", $e->getMessage(), "\n";
        // }
        // }
    }

    private function insertLeilao($leilao_id, $data_leilao)
    {
        global $conn, $leilao_interno_conf;

        // $query = "select *, st_categoria from leilao where leilao_id = " . $leilao_id . " group by leilao_id";
        $query = "
        SELECT
          leilao_id,
          dt_leilao,
          deposito,
          deposito_ID,
          st_categoria,
          mm_edital,
          st_leilaofisico,
          st_leilaoonline,
          st_leilaointerno,
          st_montagem,
          qt_lotes,
          horario_leilao,
          nm_endereco,
          nm_leilao,
          nm_junta,
          nm_leiloeiro,
          st_aeronave,
          nu_diaspropostas,
          qt_lotes_online,
          bn_enviocomprova,
          comissao,
          st_leilao,
          dt_time_leilao,
          bn_fechamentosequencial,
          nu_intervalolote,
          nm_tipoleilao,
          cliente_id,
          BN_BloqueioBoleto,
          MM_FormaPagto,
          dt_encerramento,
          url_amigavel_i_deposito,
          url_amigavel_j_nm_tipoleilao
        FROM
          leilao
        WHERE leilao_id = " . $leilao_id . "
        GROUP BY leilao_id;";
        $dados = $conn->query($query)->fetch(PDO::FETCH_ASSOC);

        $atraso = 45;
        $finalizar = "S";
        $st_categoria = $dados['st_categoria'];
        $bn_simultaneo = 1;

        if ($st_categoria == 1) {
            $finalizar = "N";
            $categoria = "V";
        } else if ($st_categoria == 2) {
            $categoria = "M";
        } else if ($st_categoria == 3) {
            $categoria = "I";
        }

        $tempo_atraso_lote = $dados['nu_intervalolote'];
        if ($dados['st_leilaointerno'] == 1) {
            $st_leilaofisico = 0;
            if (array_key_exists($leilao_id, $leilao_interno_conf)) {
                $tempo_atraso_lote = $leilao_interno_conf[$leilao_id]['intervalo_lote'];
            } else {
                $tempo_atraso_lote = 120;
            }
        }

        $atraso = $tempo_atraso_lote;

        $conn->query("DELETE FROM sslonline_leilao WHERE leilao_id=$leilao_id");

        $params = array(
            'sslonline_leilao_id' => 'NULL',
            'leilao_id' => $leilao_id,
            'finalizar' => $finalizar,
            'tempo_atraso_lote' => $tempo_atraso_lote,
            'st_leilaointerno' => $dados['st_leilaointerno'],
            'st_leilaofisico' => $dados['st_leilaofisico'],
            'dt_leilao' => $data_leilao,
            'nm_leilao' => $dados['nm_leilao'],
            'termino_lote_interval' => $atraso,
            'categ_id' => $st_categoria,
            'tipo' => $categoria,
            'bn_simultaneopatios' => $bn_simultaneo,
            'bn_fechamentosequencial' => $dados['bn_fechamentosequencial'],
        );

        Transacao::db_insert("sslonline_leilao", $params);

        if (Padrao::isProducao()) {
            $params = array(
                'leilao_id' => $leilao_id,
                'descricao' => "Publica��o do leil�o $leilao_id",
            );
            Adm::LogPublicacao($params);
        }
    }
    private static function insereLogAtualizacao($leilao_id, $mensagem, $nm_responsavel)
    {
        global $conn;
        Transacao::db_insert(" santoro_bkp.logatualizacao", array(
            "leilao_id" => $leilao_id,
            "mensagem" => $mensagem,
            "data_acao" => "NOW()",
            "nm_responsavel" => $nm_responsavel,
        ));
    }
    public static function LogPublicacao($params)
    {
        global $conn;

        $params['ip'] = Padrao::getIp();
        $usuario = Sessao::pegaSessao('perfil', 'usuario');
        if (!isset($usuario)) {
            $usuario = 'cron';
        }
        $params['usuario'] = $usuario;
        $params['data_acao'] = "NOW()";

        Transacao::db_insert("santoro_bkp.LogPublicacao", $params);
    }
    private static function getXmlPatios()
    {
        $path = _XMLDIR_ . "/patios.xml";
        $xml = simplexml_load_file($path);
        $patios_xml = array();

        foreach ($xml as $row) {
            $id = $row->id->__toString();
            $patios_xml[$id] = array(
                'nm_cidade' => utf8_decode($row->nm_cidade->__toString()),
                'nm_telefone' => $row->nm_telefone->__toString(),
                'nm_email' => $row->nm_email->__toString(),
                'img' => $row->img->__toString(),
                'nm_texto' => self::fixTexto($row->nm_texto->__toString()),
                'mapa' => $row->mapa->__toString(),
                'horario_atendimento' => utf8_decode($row->horario_atendimento->__toString()),
                'ativo' => $row->ativo->__toString(),
            );
        }

        foreach ($patios_xml as $k => $patio) {
            self::update("patios", $patio, array(
                "id" => $k,
            ), false);
        }

        self::setMapas();
        self::setCasaSodreSantoro(999);
    }
    private static function fixTexto($string)
    {
        $string = str_replace("\n", "<BR/>", $string);
        return addslashes(utf8_decode(trim($string)));
    }
    private static function setCasaSodreSantoro($id)
    {
        global $conn;

        $query = "update patios set nm_endereco='Av. Brasil, 478 - Jardim Paulista / SP' where id={$id} ";
        $conn->query($query);

        Transacao::commit();
    }
    public static function getXmlFaqs()
    {
        global $conn;

        $path = _XMLDIR_ . "/faqs.xml";
        $xml = simplexml_load_file($path);
        $patios_xml = array();

        foreach ($xml as $row) {
            $id = utf8_decode($row->titulo->__toString());
            $perguntas[$id][] = array(
                'pergunta' => utf8_decode($row->pergunta->__toString()),
                'resposta' => Padrao::escapeSql(trim(utf8_decode($row->resposta->__toString()))),
                'titulo' => utf8_decode($row->titulo->__toString()),
            );
        }

        $conn->query("truncate adm_faqs");

        foreach ($perguntas as $segmento) {
            foreach ($segmento as $pergunta) {
                $pergunta["data_cadastro"] = 'NOW()';
                $pergunta['status'] = 1;

                Transacao::db_insert("adm_faqs", $pergunta);
            }
        }
        Transacao::commit();
    }
    private static function setMapas()
    {
        global $conn;
        $mapas = array(
            16 => "https://www.google.com.br/maps/place/Sodr%C3%A9+Santoro+Leil%C3%B5es+-+Bauru/@-22.315758,-49.056188,17z/data=!3m1!4b1!4m2!3m1!1s0x94bf6790d208f287:0x43ca4fdae31078d3",
            12 => "https://www.google.com.br/maps/place/Sodr%C3%A9+Santoro+Leil%C3%B5es+-+Curitiba/@-25.522263,-49.104093,17z/data=!4m2!3m1!1s0x94dcf4128b65c743:0x55cca952efd874c0",
            999 => "https://www.google.com.br/maps/place/Casa+Sodr%C3%A9+Santoro+-+S%C3%A3o+Paulo/@-23.575738,-46.66481,17z/data=!3m1!4b1!4m2!3m1!1s0x94ce59ddcf3dce1f:0x7dcc15c05e46c916",
            27 => "https://www.google.com/maps/place/Sodre+Santoro+Leil%C3%B5es+-+Monte+Mor/@-22.945115,-47.280912,17z/data=!3m1!4b1!4m2!3m1!1s0x0:0x8ddea1af3997598d?hl=pt-PT",
            3 => "https://www.google.com.br/maps/place/Sodr%C3%A9+Santoro+Leil%C3%B5es/@-23.4542494,-46.5337925,12z/data=!4m5!1m2!2m1!1ssodre+santoro+guarulhos!3m1!1s0x94ce5ff322bcb66d:0x3d7f471cb180ae56",
            8 => "https://www.google.com.br/maps/place/Sodre+Santoro+Leil%C3%B5es+-+Ribeir%C3%A3o+Preto/@-21.21687,-47.758339,17z/data=!3m1!4b1!4m2!3m1!1s0x94b9c766b03668e1:0x9767ccf2ef865348",
        );

        foreach ($mapas as $k => $mapa) {
            $query = "update patios set mapa='{$mapa}' where id = $k";
            $conn->query($query);
        }
        Transacao::commit();
    }
    private static function fixFotos($segmento)
    {
       /* global $conn;

        $query = '';
        switch ($segmento) {
            case 'veiculos':
            	echo "Atualizando fotos de veiculos \n";

            	$query = " update sslonline_veiculos s
					inner join (
						select leilao_id,lote_id,nm_img from santoro.sslonline_veiculos
					) as ps ON ps.leilao_id = s.leilao_id and ps.lote_id = s.lote_id
					set s.nm_img = ps.nm_img
		         ";
                break;
            case 'materiais':

            	echo "Atualizando fotos de materiais \n";
                $query = " update sslonline_materiais s
					inner join (
						select leilao_id,lote_id,nm_img from santoro.sslonline_materiais
					) as ps ON ps.leilao_id = s.leilao_id and ps.lote_id = s.lote_id
					set s.nm_img = ps.nm_img
		         ";    break;
            case 'imoveis':

            	echo "Atualizando fotos de imoveis \n";
                $query = " update sslonline_imoveis s
					inner join (
						select leilao_id,lote_id,nm_img from santoro.sslonline_imoveis
					) as ps ON ps.leilao_id = s.leilao_id and ps.lote_id = s.lote_id
					set s.nm_img = ps.nm_img ";
		        break;
        }

        if (!empty($query)) {
            $conn->query($query);
            Transacao::commit();
        }

        echo "Publicando fotos na sslonline_status \n";
        $query = " update sslonline_status s
					inner join (
						select leilao_id,lote_id,nm_img from santoro.sslonline_status
					) as ps ON ps.leilao_id = s.leilao_id and ps.lote_id = s.lote_id
					set s.nm_img = ps.nm_img
		         ";
        $conn->query($query);
        Transacao::commit();*/
    }
    public function getFaqs()
    {
        global $conn;
        $query = " SELECT * FROM adm_faqs WHERE status = 1 ORDER BY titulo, pergunta, resposta, faqs_id;";
        $rs = $conn->query($query);
        $faqs = array();
        if ($rs) {
            while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                $faqs[] = $row;
            }
        }
        return $faqs;
    }
    public static function getFaq($id)
    {
        global $conn;
        $query = "SELECT * FROM adm_faqs WHERE faqs_id = " . $id . " AND status = 1;";
        $faq = array();
        $rs = $conn->query($query);
        if ($rs) {
            $faq = $rs->fetch(PDO::FETCH_ASSOC);
        }
        return array_map('utf8_encode', $faq);
    }
    public static function cadastrarFaqs($params)
    {
        global $conn;
        $params = array_map('utf8_decode', $params);
        $colunas = implode(',', array_keys($params));
        $linha = implode("','", array_values($params));
        $query = "INSERT INTO adm_faqs (" . $colunas . ", data_cadastro, status) VALUES ('" . $linha . "', '" . date('Y-m-d H:i:s') . "', 1);";
        $conn->query($query);
    }
    public static function updateFaqs($params, $id)
    {
        global $conn;

        foreach ($params as $k => $value) {
            $sets[] = $k . " = '" . utf8_decode($value) . "'";
        }

        $query = "UPDATE adm_faqs SET " . implode(",", $sets) . ", data_alteracao = '" . date('Y-m-d H:i:s') . "' WHERE faqs_id = " . $id . ";";
        $conn->query($query);
    }
    public static function deleteFaq($id)
    {
        global $conn;
        // $query = "delete from faqs where faqs_id = $id ";
        $query = "UPDATE adm_faqs SET data_exclusao = '" . date('Y-m-d H:i:s') . "', status = 'inativo' WHERE faqs_id = " . $id . ";";
        $conn->query($query);
    }
    public function getUsuariosChat()
    {
        return Chat::getUsuariosChat();
    }
    public function getAssuntosChat()
    {
        return Chat::getAssuntosChat();
    }
    public function getChatUsuarioAssuntos($idUsuario)
    {
        return Chat::getChatUsuarioAssuntos($idUsuario);
    }
    public static function validaUsuarioChat($login)
    {
        return Chat::validaUsuarioChat($login);
    }
    public function cadastrarChatUsuario($params)
    {
        return Chat::cadastrarChatUsuario($params);
    }
    public function getIDChatUsuario($params)
    {
        return Chat::getIDChatUsuario($params);
    }
    public function cadastrarChatAssunto($params)
    {
        return Chat::cadastrarChatAssunto($params);
    }
    public function updateChatAssunto($params, $id)
    {
        return Chat::updateChatAssunto($params, $id);
    }
    public function updateChatUsuario($params, $id)
    {
        return Chat::updateChatUsuario($params, $id);
    }
    public function cadastrarChatUsuarioAssunto($assuntos, $idUsuario)
    {
        return Chat::cadastrarChatUsuarioAssunto($assuntos, $idUsuario);
    }
    public function deleteChatUsuario($id)
    {
        return Chat::deleteChatUsuario($id);
    }
    public function deleteChatAssunto($id)
    {
        return Chat::deleteChatAssunto($id);
    }
    public function cadastrarChatNovoExpediente($data, $idUsuario, $hora_entrada, $hora_saida, $tem_expediente, $chat_novo_expediente_id = null)
    {
        return Chat::cadastrarChatNovoExpediente($data, $idUsuario, $hora_entrada, $hora_saida, $tem_expediente, $chat_novo_expediente_id = null);
    }
    public function deleteChatNovoExpediente($id)
    {
        return Chat::deleteChatNovoExpediente($id);
    }
    public static function getNameOperador($user_id)
    {
        global $conn;

        $query = " select nome,email from adm_operadores where user_id = $user_id ";

        list($nome, $email) = $conn->query($query)->fetch(PDO::FETCH_BOTH);

        if (!empty($nome)) {
            return $nome;
        } else {
            return $email;
        }
    }
    public static function getLoteId($leilao_id, $nu_lote)
    {
        global $conn;
        $tmp = explode(",", $nu_lote);
        $nu_lote = strtoupper(implode("','", $tmp));

        $query = " select lote_id from sslonline_status where leilao_id ='{$leilao_id}' and nu_lote in ('{$nu_lote}')";

        $rs = $conn->query($query);
        $lotes = array();
        while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
            $lotes[] = $row['lote_id'];
        }

        if (count($lotes) > 0) {
            return $lotes;
        } else {
            return false;
        }
    }
    private function ifRedeInterna($cron = false)
    {
        if ($cron == false) {
            $ip = Padrao::getIp();
            $pattern = "/(" . implode('|', $this->ips) . ")/i";
            if (!preg_match($pattern, $ip)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    private static function verificaLeilao($leilao_id)
    {
        global $conn;

        $query = "SELECT count(*)
			FROM sslonline_status
		   WHERE leilao_id = $leilao_id";

        list($qtde_lotes) = $conn->query($query)->fetch(PDO::FETCH_BOTH);
        return $qtde_lotes;
    }
    private static function verificaLeilaoIniciado($leilao_id, $lote_id)
    {
        global $conn;

        $where = " WHERE leilao_id = $leilao_id ";
        if ($lote_id > 0) {
            $where = " WHERE lote_id = $lote_id ";
        }

        $query = "SELECT count(*)
			FROM sslonline_status
		   $where
		   and st_lote not in (1,9)";

        list($qtde_lotes) = $conn->query($query)->fetch(PDO::FETCH_BOTH);
        return $qtde_lotes;
    }
    private function sincronizaTabelas($leilao_id, $carro_id = 0)
    {
        global $conn;

        self::print_erro('Atualizando Leil&otilde;es...', false);
        $this->loadCatalogo();

        $query = "select  st_categoria as segmento from leilao where leilao_id = $leilao_id ";
        list($tmp) = $conn->query($query)->fetch(PDO::FETCH_BOTH);
        $segmentos = explode(",", $tmp);

        $sem_vistoria = array();

        foreach ($segmentos as $segmento) {
            if ($segmento == 1) {
                self::print_erro('Atualizando Veiculos...', false);
                $sem_vistoria_veiculos = $this->loadVeiculo($leilao_id, $carro_id);
                $sem_vistoria = is_array($sem_vistoria) ? $sem_vistoria : array();
                $sem_vistoria_veiculos = is_array($sem_vistoria_veiculos) ? $sem_vistoria_veiculos : array();
                $sem_vistoria = array_merge($sem_vistoria, $sem_vistoria_veiculos);
                $this->loadCacheVeiculos(1);
            }
            if ($segmento == 2) {
                self::print_erro('Atualizando Materiais...', false);
                $sem_vistoria_materais = $this->loadMaterial($leilao_id);
                $sem_vistoria = array_merge($sem_vistoria, $sem_vistoria_materais);
                $this->loadCacheMateriais(1);
            }
            if ($segmento == 3) {
                self::print_erro('Atualizando Imoveis...', false);
                $sem_vistoria_imoveis = $this->loadImovel($leilao_id);
                $sem_vistoria = array_merge($sem_vistoria, $sem_vistoria_imoveis);
                $this->loadCacheImoveis(1);
            }
        }
        $this->loadJudicial();

        return $sem_vistoria;
    }
    private static function getTotalOnline($leilao_id)
    {
        global $conn;

        $query = "select count(*) from sslonline_status where leilao_id = " . $leilao_id . " and dt_inicioleilaoonline <> '0000-00-00'";
        list($total_lotes) = $conn->query($query)->fetch(PDO::FETCH_BOTH);

        return $total_lotes;
    }
    public function sendLeilaoSistema($leilao_id, $data = "", $nm_leilao, $st_categoria, $total_online_sistema)
    {
        if ($this->ifRedeInterna()) {
            print "<span style=''>Acesso negado.</span>";
            exit();
        }
        $qtde_lotes = self::verificaLeilao($leilao_id);

        $nm_responsavel = Sessao::pegaSessao('perfil', 'usuario');
        if ($qtde_lotes > 0) {
            if (self::verificaLeilaoIniciado($leilao_id, 0) > 0) {
                self::insereLogAtualizacao($leilao_id, 'tentativa', $nm_responsavel);
                print "<span style='color:#FF0000'><b>Leil&atilde;o j&aacute; come&ccedil;ou, favor entrar em contato com o CPD.</b></span>";
                exit();
            }
        }

        $bn_simultaneo = 0;
        $atraso = 0;




        $st_categoria = strtolower($st_categoria);

        $indice = substr($st_categoria, 0, 1);

        if ($indice == "v") {
            $bn_simultaneo = 1;
            $categoria = "V";
            $categoria_id = 1;
        } else if ($indice == "m") {
            $categoria = "M";
            $categoria_id = 2;
        } else if ($indice == "i") {
            $categoria = "I";
            $categoria_id = 3;
        } else {
            $bn_simultaneo = 1;
            $categoria = "V";
            $categoria_id = 1;
        }









        $interno = 0;

        global $leilao_interno_conf;

        $leiloes_internos = array();
        foreach ($leilao_interno_conf as $leilao_interno) {
            $leiloes_internos[] = $leilao_interno['leilao_id'];
        }

        if (in_array($leilao_id, $leiloes_internos)) {
            $bn_simultaneo = 0;
            $interno = 1;
        }

        if ($leilao_id) {

            // sincroniza todas as tabelas de online e off line
            $sem_vistoria = self::sincronizaTabelas($leilao_id);

            if (!self::validarLeilaoDisponiovel($leilao_id)) {
                print "<br><span style='color:#FF0000'><b>Esse leil&atilde;o j&aacute; passou.</b>";
                exit();
            }

            $dia = substr($data, 0, 2);
            $mes = substr($data, 3, 2);
            $resto = substr($data, 5, strlen($data) - 5);
            $data_leilao = date("Y-m-d H:i:s", strtotime($mes . "/" . $dia . $resto));

            $nm_leilao = utf8_decode($nm_leilao);

            $this->insertLeilao($leilao_id, $data_leilao);

            print "<span style='color:#0000FF'>OK </span> <br>Setando lotes...";
            ob_flush();
            flush();
            self::SetLote($leilao_id, $data_leilao, false);

            print "<span style='color:#0000FF'>OK </span><br>";
            ob_flush();
            flush();

            $retorno_erro = Adm::erroPublicacao($leilao_id, $total_online_sistema, $sem_vistoria);
            $sqlserver = new SqlServer();
            $sqlserver->atualizarLoteWeb(0, 0, $leilao_id);
            $nm_responsavel = Sessao::pegaSessao('perfil', 'usuario');

            if ($retorno_erro['erro'] == 0) {
                // Insere log de sucesso
                self::insereLogAtualizacao($leilao_id, 'sucesso', $nm_responsavel);

                print "<span style='color:#0000FF'><b>Publica&ccedil;&atilde;o efetuada com sucesso!</b>";
                print "<br>Total online: " . $total_online_sistema . "<br></span>";
            } else {
                if (is_array($retorno_erro)) {
                    foreach ($retorno_erro as $lote) {
                        $sqlserver->atualizarLoteWeb($lote['lote_id'], 1, 0);
                    }
                    // Insere log de sucesso
                    self::insereLogAtualizacao($leilao_id, $retorno_erro['mensagem'], $nm_responsavel);
                }
            }
            ob_flush();
            flush();
            exit();
        } else {
            print "<span style='color:#FF0000'>Erro de processamento</span>";
            exit();
        }
    }
    private static function validarLeilaoDisponiovel($leilao_id)
    {
        global $conn;

        $query = "select leilao_id from leilao where leilao_id = $leilao_id ";
        list($existe) = $conn->query($query)->fetch(PDO::FETCH_BOTH);

        if ($existe > 0) {
            return true;
        } else {
            return false;
        }
    }
    private static function erroPublicacao($leilao_id, $total_online_sistema, $sem_vistoria = array())
    {
        global $conn;

        $erro = 0;

        $array_lote_id = array();
        $mensagem = array();

        // Adm::print_erro('Testando Lotes sem data de inicio do online / lotes offline');

        $query = " select * from (
					select lote_id,NM_Vistoria, NU_Lote  , 'Veiculos' as segmento from sslonline_veiculos where DT_InicioLeilaoOnline = '0000-00-00' and leilao_id = $leilao_id
					union
					select lote_id , NM_Vistoria as NM_Vistoria, Lote  as NU_Lote  , 'Materiais' as segmento from sslonline_materiais where DT_InicioLeilaoOnline = '0000-00-00' and leilao_id = $leilao_id
					union
					select lote_id,Vistoria as NM_Vistoria, Lote as NU_Lote  , 'Imoveis' as segmento from sslonline_imoveis where DT_InicioLeilaoOnline = '0000-00-00' and leilao_id = $leilao_id
					)
					as vistoria
					order by NU_Lote";

        $rs = $conn->query($query);
        $erro_dt = array();
        if ($rs) {
            while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                $erro_dt[] = $row;
                if (!in_array($row['lote_id'], $array_lote_id)) {




                    $array_lote_id[] = $row['lote_id'];
                }
            }
        }

        if (!empty($erro_dt)) {
            print "<br><span style='color:#FF0000'><b>Online com erro, data de inicio do online n&atilde;o setada.<br>Caso os lotes abaixo sejam OFFLINE(sem possibilidade de receber lances Online), desconsidere essa mensagem.</b>";
            print "<table>";
            print "<tr><th>Vistoria</th><th>Lote</th><th>Segmento</th></tr>";
            foreach ($erro_dt as $row) {
                print "<tr><td>{$row['NM_Vistoria']}</td><td>{$row['NU_Lote']}</td><td>{$row['segmento']}</td></tr>";
            }
            print "</table>";
            $mensagem[] = " Existem lotes offline / sem inicio de leil&atilde;o online";
            $erro = 0;
        }

        // Adm::print_erro('Testando Lotes repetidos ');

        $query = " select * from (
					select lote_id,NM_Vistoria, NU_Lote  , 'Veiculos' as segmento  from sslonline_veiculos where leilao_id = $leilao_id  group by nu_lote having count(1) > 1
					union
					select lote_id,NM_Vistoria as NM_Vistoria, Lote  as NU_Lote  , 'Materiais' as segmento from sslonline_materiais where  leilao_id = $leilao_id  group by nu_lote having count(1) > 1
					union
					select lote_id,Vistoria as NM_Vistoria, Lote as NU_Lote  , 'Imoveis' as segmento   from sslonline_imoveis where leilao_id =  $leilao_id  group by nu_lote having count(1) > 1
					) as vistoria
					order by NU_lote
		        ";

        $rs = $conn->query($query);
        $erro_repetido = array();
        if ($rs) {
            while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                $erro_repetido[] = $row;
                if (!in_array($row['lote_id'], $array_lote_id)) {
                    $array_lote_id[] = $row['lote_id'];
                }
            }
        }



        if (!empty($erro_repetido)) {
            print "<br><span style='color:#FF0000'><b>Online com erro, existem lotes com numera��o repetidas.</b>";
            print "<table>";
            print "<tr><th>Vistoria</th><th>Lote</th><th>Segmento</th></tr>";
            foreach ($erro_repetido as $row) {
                print "<tr><td>{$row['NM_Vistoria']}</td><td>{$row['NU_Lote']}</td><td>{$row['segmento']}</td></tr>";
            }
            print "</table>";
            $mensagem[] = "Existem Lotes Repetidos";
            $erro = 0;
        }

        // Adm::print_erro('Testando Lotes sem vistoria ');
        if (!empty($sem_vistoria)) {
            print "<br><span style='color:#FF0000'><b>Online com erro, existem lotes sem vistoria que n&atilde;o foram publicados.</b>";
            print "<table>";
            print "<tr><th>Leil&atilde;o</th><th>Lote</th><th>Segmento</th></tr>";
            foreach ($sem_vistoria as $row) {
                print "<tr><td>{$row['leilao_id']}</td><td>{$row['NU_Lote']}</td><td>{$row['segmento']}</td></tr>";
            }
            print "</table>";
            $mensagem[] = " Existem Lotes Sem Vistoria ";
            $erro = 1;
        }

        // Adm::print_erro('Testando subLotes ');

        $total_online = self::getTotalOnline($leilao_id);

        if (($total_online_sistema > $total_online) or ($total_online_sistema < $total_online)) {
            print "<br><span style='color:#FF0000'><b>Online com erro, favor verificar</b>";
            print "<br>Total online: " . $total_online;
            print "<br>Total online Sistema: " . $total_online_sistema . "<br></span>";
            $erro = 1;
        }

        return array(
            'erro' => $erro,
            'mensagem' => implode(",", $mensagem),
            'array_lote_id' => $array_lote_id,
        );
    }
    private static function getCompradoresLote($lote_id)
    {
        global $conn;

        $query = "SELECT au.id, au.email, au.nome , p.st_sucata, au.sexo
						FROM apss_users au
						INNER JOIN sslonline_lance sl on au.id = sl.user_id
						INNER JOIN apss_perfil p on au.comprador_id = p.comprador_id
						WHERE sl.lote_id = $lote_id
						group by au.id";

        $dados_usuario = array();
        $rs = $conn->query($query);
        while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
            $dados_usuario[] = $row;
        }
        return $dados_usuario;
    }
    private function excluirLances($lote_id, $responsavel)
    {
        global $conn;

        $ip = Padrao::getIp();

        if (!Padrao::isProducao()) {
            self::print_erro('Ambiente de desenvolvimento , log n&atilde;o efetuado', false, true);
        } else {
            $query = "INSERT santoro_bkp.logsslonline_lance
						  (leilao_id , lote_id , user_id , vl_lance , dt_lance , st_lance , ip , acao, responsavel, dt_acao,nm_identificacao , formapagamento_id)
							SELECT 		leilao_id,
									lote_id,
									user_id,
									vl_lance,
									dt_lance,
									st_lance,
									'$ip',
									'Exclus�o Autom�tica',
									'$responsavel',
									NOW(),
									nm_identificacao,
									formapagamento_id
									FROM sslonline_lance
									WHERE lote_id = $lote_id";

            $conn->query($query);
            Transacao::commit();
            // self::print_erro("Log Lances do lote $lote_id efetuado com sucesso",false,true);
        }

        $query = "delete from sslonline_lance where lote_id = $lote_id";
        $conn->query($query);
        Transacao::commit();

        // self::print_erro("Lances $lote_id excluidos com sucesso",false,true);

        if (!Padrao::isProducao()) {
            self::print_erro('Ambiente de desenvolvimento , log n&atilde;o efetuado', false, true);
        } else {
            // Exclui automatico
            $querylog = "INSERT santoro_bkp.logsslonline_lanceauto
					 (leilao_id , lote_id , user_id , vl_lancef , dt_lanceauto , ip , acao, responsavel, dt_acao)
					 SELECT leilao_id,
							lote_id,
							user_id,
							vl_lancef,
							dt_lanceauto,
							'$ip',
							'Exclus�o Autom�tica',
							'$responsavel',
							NOW()
					 FROM sslonline_lanceauto
					 WHERE lote_id = $lote_id";
            $conn->query($querylog);
            Transacao::commit();
            // self::print_erro("Log Lances automaticos do lote $lote_id efetuado com sucesso",false,true);
        }

        $query = "delete from sslonline_lanceauto where lote_id = $lote_id";
        $conn->query($query);
        Transacao::commit();

        // self::print_erro("Lances automaticos $lote_id excluidos com sucesso",false,true);
    }
    private function atualizaLoteOnline($lote_id, $leilao_id, $acao = 0)
    {
        global $conn;

        $query = "
				select * from (
				select 'V' as tipo_segmento , nm_vistoria , nu_lote , carro_id as segmento_id from sslonline_veiculos where leilao_id = $leilao_id and lote_id = $lote_id
				UNION
				select 'M' as tipo_segmento , nm_vistoria as nm_vistoria , lote as nu_lote , id as segmento_id from sslonline_materiais where leilao_id = $leilao_id and lote_id = $lote_id
				UNION
				select 'I' as tipo_segmento , vistoria as nm_vistoria, lote as nu_lote , id as segmento_id from sslonline_imoveis where leilao_id = $leilao_id and lote_id = $lote_id
				) as lote ";

        $data = $conn->query($query)->fetch(PDO::FETCH_ASSOC);

        if (empty($data) && $acao != 4) {
            self::print_erro("Lote n&atilde;o encontrado!");
            exit();
        }

        if ($data['tipo_segmento'] == 'V') {
            $this->loadVeiculo($leilao_id, $data['segmento_id']);
        } elseif ($data['tipo_segmento'] == 'M') {
            $this->loadMaterial($leilao_id);
        } elseif ($data['tipo_segmento'] == 'I') {
            $this->loadImovel($leilao_id);
        }

        if ($acao == 4 && empty($data)) {
            $this->loadCatalogos($leilao_id);
            $this->sincronizaTabelas($leilao_id, $data['segmento_id']);

            $query = "
        	select * from (
        	select 'V' as tipo_segmento , nm_vistoria , nu_lote from sslonline_veiculos where leilao_id = $leilao_id and lote_id = $lote_id
        	UNION
        	select 'M' as tipo_segmento , nm_vistoria as nm_vistoria , lote as nu_lote from sslonline_materiais where leilao_id = $leilao_id and lote_id = $lote_id
        	UNION
        	select 'I' as tipo_segmento , vistoria as nm_vistoria, lote as nu_lote from sslonline_imoveis where leilao_id = $leilao_id and lote_id = $lote_id
        	) as lote ";

            $data = $conn->query($query)->fetch(PDO::FETCH_ASSOC);
        }

        $query = "select dt_leilao
							from sslonline_leilao
						   where leilao_id = $leilao_id";

        list($data_leilao) = $conn->query($query)->fetch(PDO::FETCH_BOTH);

        self::print_erro("Publicando Leil&atilde;o: $leilao_id - Lote: {$data['nu_lote']} / Vistoria: {$data['nm_vistoria']} ", false);

        // Atualiza tabela de status
        $this->SetLote($leilao_id, $data_leilao, $data['nu_lote'], $lote_id);

        return $data;
    }
    public function escreverMensagem($mensagem, $lote_id)
    {
        global $conn;

        $query = "UPDATE sslonline_status
						 SET mensagem='$mensagem'
					   WHERE lote_id=$lote_id";

        $conn->query($query);

        $ip = Padrao::getIp();

        $adm_id = Sessao::pegaSessao('perfil', 'usuario');
        if (empty($adm_id)) {
            $adm_id = Usuario::verificaLogin();
        }

        $params = array(
            'lote_id' => $lote_id,
            'mensagem_id' => null,
            'nm_mensagem' => $mensagem,
            'user_id' => $adm_id,
            'ip' => $ip,
            'dt_mensagem' => 'NOW()',
        );
        Transacao::db_insert("mensagem_lote", $params);

        Transacao::commit();
        return true;
    }
    public function atualizarFotos($params)
    {
        global $conn;
        if (Padrao::isProducao()) {

            $force = 1;
            $adm = new Adm($force);
            foreach ($params as $row) {

                switch ($row['tipo_segmento']) {
                    case 'V':
                        {
                            $adm->loadFotosVeiculos(array('modo'=>'lote' , 'lote_id'=>$row['lote_id']));
                        }
                        ;
                        break;
                    case 'M':
                        {
                            $adm->loadFotosMateriais(array('modo'=>'lote' , 'lote_id'=>$row['lote_id']));
                        }
                        ;
                        break;
                    case 'I':
                        {
                            $adm->loadFotosImoveis(array('modo'=>'lote' , 'lote_id'=>$row['lote_id']));
                        }
                        ;
                        break;
                }
            }

            // atualizar cache

            //$exec = "/usr/bin/find /wwwroot/shares/ngnix/cache/ -type f -delete";
            //shell_exec($exec);
        }
    }
    public function excluiLanceLoteAlterado($lote_id, $leilao_id, $responsavel, $acao, $mensagem = '', $sucata = false, $exclusao_lote = false)
    {
        if ($sucata) {
            $cron = true;
        } else {
            $cron = false;
        }

        if ($this->ifRedeInterna($cron)) {
            self::print_erro('Acesso Negado!');
            exit();
        }

        // Verifica se lote ja foi iniciado
        $lote_iniciado = self::verificaLeilaoIniciado($leilao_id, $lote_id);
        if ($lote_iniciado > 0) {
            self::print_erro("<b>Lote ja iniciado, favor entrar em contato com o CPD.</b>");
            exit();
        }

        if (empty($mensagem)) {
            // Escreve mensagem padrao
            $data_atual = date("d/m/Y H:i:s");
            $mensagem = "Devido a alteraï¿½ï¿½es no descritivo deste lote, todos os lances enviados atï¿½ $data_atual foram excluÃ­dos.";
        }

        if ($sucata) {
            $mensagem = "De acordo com o comunicado do Detran nï¿½ 07/2014 os lances desse lote foram excluï¿½dos. Se habilite para enviar lances novamente!";
        }

        if (($lote_id > 0) and ($mensagem)) {

            switch ($acao) {
                // eliminar lote do leilÃ£o
                /*
                 * excluir lance enviar email tirar de produÃ§Ã£o
                 */
                case 1:
                    {
                        self::print_erro('Iniciando...', false);

                        // Gera dados dos arrematantes que ofertaram lance
                        $dados_usuario = self::getCompradoresLote($lote_id);
                        self::excluirLances($lote_id, $responsavel);
                        self::print_erro('Exclus&atilde;o dos lances efetuada com sucesso!', false);

                        self::print_erro('Enviando Emails de exclus&atilde;o!', false);
                        $email = new Email();
                        $email->sendEmailAvisoExclusao($lote_id, $mensagem, $dados_usuario, $sucata);

                        $array_delete[] = array(
                            'leilao_id' => $leilao_id,
                            'lote_id' => $lote_id,
                        );
                        $backup = new Backup();
                        $backup->rotateProducao($array_delete, $exclusao_lote);

                        self::print_erro('Lote eliminado com sucesso!', false);
                    }
                    ;
                    break;

                // modificar
                /**
                 * atualizar o lote
                 * excluir lances
                 * enviar email
                 * atualizar foto
                 */
                case 2:
                    {
                        self::print_erro('Iniciando...', false);
                        // Atualizar o lote
                        $dados_lote = $this->atualizaLoteOnline($lote_id, $leilao_id);
                        $this->escreverMensagem($mensagem, $lote_id);

                        // Gera dados dos arrematantes que ofertaram lance
                        $dados_usuario = self::getCompradoresLote($lote_id);
                        self::excluirLances($lote_id, $responsavel);
                        self::print_erro('Exclus&atilde;o dos lances efetuada com sucesso!', false);

                        self::print_erro('Enviando Emails de exclus&atilde;o!', false);
                        $email = new Email();
                        $email->sendEmailAvisoExclusao($lote_id, $mensagem, $dados_usuario, $sucata);

                        self::print_erro('Atualizando as fotos!', false);
                        $array_update[] = array(
                            'leilao_id' => $leilao_id,
                            'lote_id' => $lote_id,
                            'nm_responsavel' => $responsavel,
                            'nm_mensagem' => $mensagem,
                            'dt' => 'NOW()',
                            'tipo_segmento' => $dados_lote['tipo_segmento'],
                            'st_atualizado' => 0,
                        );
                        $this->atualizarFotos($array_update);

                        if ($sucata == false) {
                            $sqlserver = new SqlServer();
                            $sqlserver->atualizarLoteWeb($lote_id);
                        }

                        self::print_erro('Lote modificado com sucesso!', false);
                    }
                    ;
                    break;

                // retirado
                /*
                 * atualizar o lote excluir lances enviar email
                 */
                case 3:
                    {
                        self::print_erro('Iniciando...', false);
                        // Atualizar o lote
                        $dados_lote = $this->atualizaLoteOnline($lote_id, $leilao_id);
                        $this->escreverMensagem('LOTE RETIRADO', $lote_id);

                        // Gera dados dos arrematantes que ofertaram lance
                        $dados_usuario = self::getCompradoresLote($lote_id);
                        self::excluirLances($lote_id, $responsavel);
                        self::print_erro('Exclus&atilde;o dos lances efetuada com sucesso!', false);

                        self::print_erro('Enviando Emails de exclus&atilde;o!', false);
                        $email = new Email();
                        $email->sendEmailAvisoExclusao($lote_id, 'LOTE RETIRADO', $dados_usuario, $sucata);

                        if ($sucata == false) {
                            $sqlserver = new SqlServer();
                            $sqlserver->atualizarLoteWeb($lote_id);
                        }

                        self::print_erro('Lote retirado com sucesso!', false);
                    }
                    ;
                    break;

                // atualizar lote sem excluir lances
                /**
                 * atualizar o lote
                 * atualizar foto
                 */
                case 4:
                    {
                        self::print_erro('Iniciando...', false);

                        $dados_lote = $this->atualizaLoteOnline($lote_id, $leilao_id, $acao);
                        $array_update[] = array(
                            'leilao_id' => $leilao_id,
                            'lote_id' => $lote_id,
                            'nm_responsavel' => $responsavel,
                            'nm_mensagem' => $mensagem,
                            'dt' => 'NOW()',
                            'tipo_segmento' => $dados_lote['tipo_segmento'],
                            'st_atualizado' => 0,
                        );

                        self::print_erro('Atualizando as fotos!', false);
                        $this->atualizarFotos($array_update);

                        if ($sucata == false) {
                            $sqlserver = new SqlServer();
                            $sqlserver->atualizarLoteWeb($lote_id);
                        }

                        self::print_erro('Lote Atualizado com sucesso!', false);
                    }
                    ;
                    break;

                // publicar todos lotes pendentes em desenvolvimento
                case 5:
                    {
                    }
                    ;
                    break;

                default:
                    {
                        self::print_erro('AÃ§&atilde;o desconhecida!');
                    }
                    ;
            }
        } else {
            self::print_erro('Erro lote n&atilde;o idenfificado!', false);
        }
    }
    public function buscaLotesAnexo($leilao_id)
    {
        global $conn;
        $retorno = array();

        $query = "
		SELECT lote_id, TRIM(NU_lote) AS nu_lote FROM sslonline_veiculos WHERE  leilao_id = '" . $leilao_id . "'
		UNION
		SELECT lote_id, TRIM(lote) AS nu_lote FROM sslonline_materiais WHERE leilao_id = '" . $leilao_id . "'
		UNION
		SELECT lote_id, TRIM(Lote) AS nu_lote FROM sslonline_imoveis WHERE Leilao_id = '" . $leilao_id . "'
		ORDER BY NU_Lote;";
        $rs = $conn->query($query);
        if ($rs) {
            while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                $retorno[] = $row;
            }
        }
        return $retorno;
    }
    public function selecionarLoteAnexo($leilao_id, $lote_id)
    {
        global $conn;
        $retorno = array(
            'leilao_id' => $leilao_id,
            'lote_id' => $lote_id,
            'lote' => '',
            'descricao' => '',
            'modelo' => '',
            'nm_leilao' => '',
            'deposito' => '',
            'dt_time_leilao' => '',
            'anexos' => array(),
        );

        $query = "
		SELECT lote_id, NU_lote as lote, descricao, NM_Modelo as modelo FROM sslonline_veiculos WHERE lote_id = '" . $lote_id . "' AND leilao_id = '" . $leilao_id . "'
		UNION
		SELECT lote_id, lote as lote, descricao, '' as modelo FROM sslonline_materiais WHERE lote_id = '" . $lote_id . "' AND leilao_id = '" . $leilao_id . "'
		UNION
		SELECT lote_id, lote as lote, descricao, '' as modelo FROM sslonline_imoveis WHERE lote_id = '" . $lote_id . "' AND Leilao_id = '" . $leilao_id . "';";
        $rs = $conn->query($query);
        if ($rs) {
            while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                // $row = array_map('utf8_encode', $row);
                $retorno = array_merge($retorno, $row);
            }
        }

        $query = "
		SELECT nm_leilao, deposito, date_format(dt_time_leilao,'%d/%m/%Y - %H:%i') AS dt_time_leilao
		FROM leilao
		WHERE leilao_id = '" . $leilao_id . "' LIMIT 1;";
        $rs = $conn->query($query);
        if ($rs) {
            while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                // $row = array_map('utf8_encode', $row);
                $retorno = array_merge($retorno, $row);
            }
        }

        $query = "
		SELECT *
		FROM aux_anexo
		WHERE leilao_id = '" . $leilao_id . "' AND lote_id = '" . $retorno['lote_id'] . "';";
        $anexos = array();
        $rs = $conn->query($query);
        if ($rs) {
            while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                // $row = array_map('utf8_encode', $row);
                $anexos[] = $row;
            }
        }
        $retorno['anexos'] = $anexos;

        return $retorno;
    }
    public function selecionarLeilaoAnexo($leilao_id)
    {
        global $conn;
        $retorno = array(
            'leilao_id' => $leilao_id,
            'lote_id' => 'Sem Lote',
            'lote' => 'Sem Lote',
            'nm_leilao' => '',
            'deposito' => '',
            'dt_time_leilao' => '',
            'anexos' => array(),
        );

        $query = "
		SELECT nm_leilao, deposito, date_format(dt_time_leilao,'%d/%m/%Y - %H:%i') AS dt_time_leilao
		FROM leilao
		WHERE leilao_id = '" . $leilao_id . "' LIMIT 1;";
        $rs = $conn->query($query);
        if ($rs) {
            while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                // $row = array_map('utf8_encode', $row);
                $retorno = array_merge($retorno, $row);
            }
        }

        $query = "
		SELECT *
		FROM aux_anexo
		WHERE leilao_id = '" . $leilao_id . "' AND (lote_id IS NULL OR TRIM(lote_id) = '');";
        $anexos = array();
        $rs = $conn->query($query);
        if ($rs) {
            while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                // $row = array_map('utf8_encode', $row);
                $anexos[] = $row;
            }
        }
        $retorno['anexos'] = $anexos;

        return $retorno;
    }
    public function excluirAnexo($anexo_id)
    {
        global $conn;

        $query = "
		SELECT * FROM aux_anexo
		WHERE id = '" . $anexo_id . "' LIMIT 1;";
        $rs = $conn->query($query);
        if ($rs) {
            if (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {

                $arquivo = 'anexo/' . $row['nome_arquivo'];
                if (file_exists($arquivo)) {
                    @unlink($arquivo);
                    if (Padrao::isProducao()) {
                        $servers = array(
                            '/wwwroot/sodre/http/anexo/',
                            '/wwwroot/sodre-132/http/anexo/',
                            '/wwwroot/sodre-133/http/anexo/', /*,
                        '/wwwroot/sodre-135/http/anexo/'*/
                        );
                        foreach ($servers as $s) {
                            @unlink($s . $row['nome_arquivo']);
                        }
                    }
                }

                $query = "DELETE FROM aux_anexo WHERE id = '" . $anexo_id . "' LIMIT 1;";
                $rs2 = $conn->query($query);
                Transacao::commit();
                return '1';
            }
        }

        return '0';
    }
    public function editarAnexo($anexo_id, $nome)
    {
        global $conn;

        $query = "
		UPDATE aux_anexo
		SET descricao = '" . Padrao::escapeSql(utf8_decode($nome)) . "'
		WHERE id = '" . $anexo_id . "' LIMIT 1;";
        $rs = $conn->query($query);
        if ($rs) {
            return '1';
        }

        return '0';
    }
    public function verificaHashAnexo($hash)
    {
        global $conn;

        $query = "SELECT id FROM aux_anexo WHERE hash = '" . $hash . "'";
        $rs = $conn->query($query);
        if ($rs) {
            if (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                return false;
            }
        }
        return true;
    }
    public function salvaAnexo($leilao_id, $lote_id, $nome, $arquivo, $hash)
    {
        if ((int) $leilao_id > 0) {
            $leilao_id = (int) $leilao_id;

            if ($lote_id == 'Sem Lote') {
                $lote_id = 'NULL';
            } else {
                $lote_id = (int) $lote_id;
            }

            global $conn;

            if ($lote_id == 'NULL') {
                $query = "
				SELECT * FROM aux_anexo
				WHERE leilao_id = " . $leilao_id . "
				AND lote_id IS NULL
				AND LOWER(descricao) = LOWER('" . Padrao::escapeSql($nome) . "')
				LIMIT 1;";
            } else {
                $query = "
				SELECT * FROM aux_anexo
				WHERE leilao_id = " . $leilao_id . "
				AND lote_id = " . $lote_id . "
				AND LOWER(descricao) = LOWER('" . Padrao::escapeSql($nome) . "')
				LIMIT 1;";
            }
            $rs = $conn->query($query);
            if ($rs) {
                if (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                    return 'J&aacute; existe um anexo com esse nome. Por favor, insira outro nome.';
                }
            }

            if ($lote_id == 'NULL') {
                $query = "
				SELECT GROUP_CONCAT(CASE WHEN st_categoria = 1 THEN 'v' WHEN st_categoria = 2 THEN 'm' WHEN st_categoria = 3 THEN 'i' END) as 'tipo_segmento'
				FROM leilao
				WHERE leilao_id = '" . $leilao_id . "'";
            } else {
                $query = "
				SELECT LOWER(tipo_segmento) AS tipo_segmento FROM sslonline_status
				WHERE leilao_id = '" . $leilao_id . "'
				AND lote_id = '" . $lote_id . "'
				LIMIT 1;";
            }
            $rs = $conn->query($query);
            if ($rs) {
                if ($row = $rs->fetch(PDO::FETCH_ASSOC)) {
                    $tipo = $row['tipo_segmento'];

                    $nome_tmp_arquivo = $arquivo['tmp_name'];
                    $nome_arquivo = $arquivo['name'];

                    $query = "
					INSERT INTO aux_anexo (leilao_id, lote_id, hash, tipo, nome_arquivo, descricao)
					VALUES ('" . $leilao_id . "', " . $lote_id . ", '" . $hash . "', '" . $tipo . "', '" . $nome_arquivo . "', '" . $nome . "');";
                    $conn->query($query);
                    $anexo_id = (int) $conn->lastInsertId();
                    Transacao::commit();

                    if ($anexo_id > 0) {
                        $diretorio_anexo = 'anexo/';
                        $extensao = array_reverse(explode('.', $nome_arquivo));
                        $extensao = strtolower($extensao[0]);
                        $novo_nome = $leilao_id . '_' . $lote_id . '_' . $anexo_id . '.' . $extensao;

                        if (@move_uploaded_file($nome_tmp_arquivo, $diretorio_anexo . $novo_nome)) {
                            $mover_servers = true;
                            $codigo_erro = array();
                            if (Padrao::isProducao()) {
                                // $servers = array(
                                //     '/wwwroot/sodre-132/http/anexo/',
                                //     '/wwwroot/sodre-133/http/anexo/',
                                //     '/wwwroot/sodre-135/http/anexo/'
                                // );

                                // foreach ($servers as $k_server => $s) {
                                //     if (!@copy($diretorio_anexo . $novo_nome, $s . $novo_nome)) {
                                //         $mover_servers = false;
                                //         $codigo_erro[] = $k_server;
                                //     }
                                // }
                            }

                            if ($mover_servers) {
                                $query = "
								UPDATE aux_anexo
								SET nome_arquivo = '" . $novo_nome . "'
								WHERE id = '" . $anexo_id . "' LIMIT 1;";
                                $conn->query($query);
                                Transacao::commit();

                                return true;
                            } else {
                                @unlink($diretorio_anexo . $novo_nome);

                                foreach ($servers as $s) {
                                    @unlink($s . $novo_nome);
                                }
                            }
                        } else {
                            $codigo_erro[] = 'No such file or directory';
                        }

                        $query = "
						DELETE FROM aux_anexo WHERE id = '" . $anexo_id . "' LIMIT 1;";
                        $conn->query($query);
                        Transacao::commit();

                        return 'Erro ao copiar os arquivos, tente novamente. [Erro: ' . implode(', ', $codigo_erro) . ']';
                    }

                    return 'Escolha um arquivo para anexar!';
                }
            }
        }
        return 'Escolha um leil&atilde;o e tente novamente.';
    }
    public static function getSugestoes()
    {
        global $conn;

        $query = "
		SELECT *, DATE_FORMAT(data_cadastro, '%d/%m/%Y %H:%i:%s') AS 'data'
		FROM adm_sugestao
		WHERE status = 1 /* AND finalizado = 'nao'*/
		ORDER BY DATA_CADASTRO DESC, sugestao_id DESC;";
        $rs = $conn->query($query);
        $lista_sugestoes = array();
        if ($rs) {
            while ($row = $rs->fetch(PDO::FETCH_ASSOC)) {
                $lista_sugestoes[] = $row;
            }
        }

        return $lista_sugestoes;
    }
    public static function concluiSugestao($sugestao_id)
    {
        global $conn;
        $query = "UPDATE adm_sugestao SET finalizado = 'sim' WHERE sugestao_id = '" . $sugestao_id . "';";
        $conn->query($query);
        Transacao::commit();
    }
    public static function getUserPerfil($comprador_ID)
    {
        global $conn;

        $retorno = array();
        if (!empty($comprador_ID)) {
            //alterado para procurar por comprador_id, antes era CompradorWeb_ID
            $query = "SELECT BN_ListaNegra,ST_Documentacao,DT_ConfirmacaoDados,ST_ISO,ST_Sucata,Qtde_LotesV,Qtde_LotesI,Qtde_LotesM
                      FROM apss_perfil AS u WHERE u.Comprador_ID = '" . $comprador_ID . "'";
            $rs = $conn->query($query);

            if ($rs) {
                $retorno = $rs->fetch(PDO::FETCH_ASSOC);
                $retorno = json_encode($retorno);
            }
        }

        return $retorno;
    }
    public static function limparSenhasAnteriores($user_id)
    {
        global $conn;

        if (!empty($user_id)) {
            $senha = Padrao::getData("apss_users", "senha", "id={$user_id}");

            $query = "DELETE FROM apss_users_senha WHERE user_id = '" . $user_id . "'";
            $conn->query($query);
            Transacao::commit();
        }
    }
    public static function getSenhasAnteriores($user_id)
    {
        global $conn;

        $senhas = array();
        if (!empty($user_id)) {
            $query = "SELECT DATE_FORMAT(dt_acao, '%d/%m/%Y %H:%i:%s') AS dt_acao FROM apss_users_senha AS u WHERE u.user_id = '" . $user_id . "'";
            $rs2 = $conn->query($query);

            if ($rs2) {
                $count = 0;
                while ($row2 = $rs2->fetch(PDO::FETCH_ASSOC)) {
                    $senhas[$count]['num'] = $count + 1;
                    $senhas[$count]['data'] = $row2['dt_acao'];
                    $count++;
                }
            }
            $senhas = json_encode($senhas);
        }

        return $senhas;
    }
    public static function getTentativasLogin($user_id)
    {
        global $conn;

        $logins = array();
        if (!empty($user_id)) {
            $query = "SELECT id, email, ip, DATE_FORMAT(dt_acao, '%d/%m/%Y %H:%i:%s') AS dt_acao FROM apss_users_login AS u WHERE u.user_id = '" . $user_id . "'";
            $rs2 = $conn->query($query);

            if ($rs2) {
                while ($row2 = $rs2->fetch(PDO::FETCH_ASSOC)) {
                    $logins[] = $row2;
                }
            }
            $logins = json_encode($logins);
        }

        return $logins;
    }
    public static function getCondicoesVenda($user_id)
    {
        global $conn;

        $condicoes = array();
        if (!empty($user_id)) {
            $query = "SELECT GROUP_CONCAT(leilao_id) as leilao_id FROM (
            SELECT CONCAT(leilao_id, '|',data) as leilao_id FROM (
            SELECT leilao_id, DATE_FORMAT(date(data_hora), '%d/%m/%Y %H:%i') as data FROM sslonline_cond_venda cv WHERE cv.apss_users_id = '" . $user_id . "'
            UNION ALL
            SELECT leilao_id, DATE_FORMAT(date(data_hora), '%d/%m/%Y %H:%i') as data FROM santoro_bkp.sslonline_cond_venda cv WHERE cv.apss_users_id = '" . $user_id . "'
            ) t GROUP BY leilao_id) t";

            $rs2 = $conn->query($query);
            if ($rs2) {
                if ($row2 = $rs2->fetch(PDO::FETCH_ASSOC)) {
                    $condicoes = $row2['leilao_id'];
                }
            }
        }

        return $condicoes;
    }
    public static function filtroBuscaUsuario($valor, $tipo)
    {
        global $conn;

        $campos_busca = array();
        $ordem = '';

        //if ($tipo == 'todos' || $tipo == 'nome') {
        if ($tipo == 'todos') {
            $tipo = 'cpf';
        }

        if ($tipo == '' || $tipo == 'todos' || $tipo == 'nome') {
            $tmp_campos_busca = array();
            foreach (explode(' ', $valor) as $v) {
                $tmp_campos_busca[] = "u.nome LIKE '%" . Padrao::escapeSql($v) . "%'";
            }

            $campos_busca[] = '(' . implode(' AND ', $tmp_campos_busca) . ')';
            $ordem = 'u.nome';
        }
        if ($tipo == '' || $tipo == 'todos' || $tipo == 'apelido') {
            $tmp_campos_busca = array();
            foreach (explode(' ', $valor) as $v) {
                $tmp_campos_busca[] = "u.apelido LIKE '%" . Padrao::escapeSql($v) . "%'";
            }

            $campos_busca[] = '(' . implode(' AND ', $tmp_campos_busca) . ')';
            $ordem = 'u.apelido';
        }
        if ($tipo == '' || $tipo == 'todos' || $tipo == 'email') {
            $tmp_campos_busca = array();
            foreach (explode(' ', $valor) as $v) {
                $tmp_campos_busca[] = "u.email LIKE '%" . Padrao::escapeSql($v) . "%'";
            }

            $campos_busca[] = '(' . implode(' AND ', $tmp_campos_busca) . ')';
            $ordem = 'u.email';
        }
        if ($tipo == '' || $tipo == 'todos' || $tipo == 'cpf') {
            $tmp_campos_busca = array();
            foreach (explode(' ', $valor) as $v) {
                $tmp_campos_busca[] = "u.cpf LIKE '%" . Padrao::escapeSql($v) . "%'";
            }

            $campos_busca[] = '(' . implode(' AND ', $tmp_campos_busca) . ')';
            $ordem = 'u.cpf';
        }
        if ($tipo == '' || $tipo == 'todos' || $tipo == 'id_web') {
            $tmp_campos_busca = array();
            foreach (explode(' ', $valor) as $v) {
                $tmp_campos_busca[] = "u.id = '" . Padrao::escapeSql($v) . "'";
            }

            $campos_busca[] = '(' . implode(' AND ', $tmp_campos_busca) . ')';
            $ordem = 'u.id';
        }
        if ($tipo == '' || $tipo == 'todos' || $tipo == 'id_sistema') {
            $tmp_campos_busca = array();
            foreach (explode(' ', $valor) as $v) {
                $tmp_campos_busca[] = "u.comprador_id = '" . Padrao::escapeSql($v) . "'";
            }

            $campos_busca[] = '(' . implode(' AND ', $tmp_campos_busca) . ')';
            $ordem = 'u.comprador_id';
        }

        $query = "SELECT
        u.id, u.nome, u.contato, u.apelido, u.tipo_logradouro, u.endereco, u.numero, u.bairro, u.cep, u.cidade, u.estado, u.email, u.senha, u.rg, u.cpf, u.tmpsenha,
        u.telefone, u.tempsenha, u.st_cadastro, u.dica, u.nascimento, u.comprador_id, u.compl, u.ST_LeilaoOnline, u.datacad, u.fone_coml, u.fone_celular, u.fone_fax,
        u.fone_celular_2, u.cod_func, u.cliente_id, u.leilao_inverso, u.ramo1, u.ramo2, u.st_apelido, u.dt_liberacao_apelido, u.hash, u.controle_hash, u.sexo,
        u.apelido_trt, u.solicitacao_alteracao, u.solicitacao_senha, u.solicitacao_cpf, u.solicitacao_rg, u.solicitacao_nome, u.solicitacao_tipo_logradouro,
        u.solicitacao_endereco, u.solicitacao_numero, u.solicitacao_complemento, u.solicitacao_bairro, u.solicitacao_cidade, u.solicitacao_uf, u.solicitacao_cep,
        u.observacao_geral_documentacao, u.solicitacao_email, u.solicitacao_apelido, u.hash_migracao, u.st_migracao
        FROM apss_users u";
        if (count($campos_busca)) {
            $query .= " WHERE " . implode(' OR ', $campos_busca);
        }
        if ($ordem != '') {
            $query .= " ORDER BY " . $ordem;
        }
        $query .= " LIMIT 100";

        $rs = $conn->query($query);
        $dados_usuario = array();
        $user_id_arquivos = array();
        if ($rs) {
            while ($row = $rs->fetch(PDO::FETCH_ASSOC)) {

                $queryLibeTemp = "SELECT DATE_FORMAT(dt_cadastro, '%d/%m/%Y %H:%i:%s') as dt_cadastro FROM liberacao_temporaria WHERE apss_user_id = '" . $row['id'] . "'";
                $qlt = $conn->query($queryLibeTemp);

                if ($qlt) {
                    $libeTemp = $qlt->fetch(PDO::FETCH_ASSOC);
                    $row['dt_liberacao_hash'] = $libeTemp['dt_cadastro'];
                } else {
                    $row['dt_liberacao_hash'] = '';
                }

                $tmp = array(
                    'dados' => $row,
                );
                $tmp['dados']['condicoes'] = '';
                $tmp['dados']['data_senha'] = '';
                $tmp['dados']['logins'] = '';

                $query = "SELECT DATE_FORMAT(dt_acao, '%d/%m/%Y %H:%i:%s') as dt_acao from apss_users_senha as u where u.user_id = '" . $row['id'] . "' order by id desc limit 1";
                $rs2 = $conn->query($query);

                if ($rs2) {
                    if ($row2 = $rs2->fetch(PDO::FETCH_ASSOC)) {
                        $tmp['dados']['data_senha'] = $row2['dt_acao'];
                    }
                }

                $logins = array();

                $tmp['dados']['logins'] = json_encode($logins);

                $dados_usuario[] = $tmp;
            }
        }
        return $dados_usuario;
    }

    public static function atualizaUsuarioBox1($id, $nome = null, $cpf = null, $apelido = null, $rg = null, $email = null, $senha = null, $emails = array(), $padrao = 0, $excluir = array(), $emailPadrao = null, $perguntaAlteracaoEmail = array(),$contato,$tel1 = null,$tel2 = null,$cel1 = null,$cel2 = null)
    {
        global $conn;

        // email padrao
        // $padrao = (int)$padrao;
        // if($padrao > 0) {
        // busca o email que sera alterado para padrao
        // $query = "SELECT * FROM apss_users_email WHERE id = '" . $padrao . "' LIMIT 1;";
        // $rs = $conn->query($query);
        // if($rs) {
        // if($row = $rs->fetch(PDO::FETCH_ASSOC)) {
        // $campos[] = "email = '" . $row['email'] . "'";
        // $query = "UPDATE apss_users_email SET padrao = 'nao' WHERE apss_users_id = '" . $row['apss_users_id'] . "' AND status = 'ativo';";
        // $conn->query($query);
        // Transacao::commit();

        // $query = "UPDATE apss_users_email SET padrao = 'sim' WHERE id = '" . $row['id'] . "';";
        // $conn->query($query);
        // Transacao::commit();
        // }
        // }
        // }

        // emails excluidos
        // if(is_array($excluir)) {
        // foreach($excluir as $e) {
        // $query = "UPDATE apss_users_email SET status = 'inativo', data_exclusao = NOW() WHERE id = '" . $e . "' AND status = 'ativo' AND padrao != 'sim';";
        // $conn->query($query);
        // Transacao::commit();
        // }
        // }

        $crypt_salt = Usuario::$crypt_salt_static;

        $campos = array();
        $campos_valores = array();
        $log = array();

        if (trim($nome) != '') {
            $log[] = "nome = '" . $nome . "'";
            $campos['nome'] = "nome = :nome";
            $campos_valores[':nome'] = $nome;
        }
        if (trim($cpf) != '') {
            $log[] = "cpf = '" . $cpf . "'";
            $campos['cpf'] = "cpf = :cpf";
            $campos_valores[':cpf'] = $cpf;
        }
        if (trim($senha) != '') {
            $log[] = "senha = SHA('" . $senha . $crypt_salt . "')";
            $campos['senha'] = "senha = SHA(:senha)";
            $campos_valores[':senha'] = $senha . $crypt_salt;
        }

        if (!empty($emailPadrao)) {
            $log[] = "email = '" . $emailPadrao . "'";
            $campos['email'] = "email = :email";
            $campos_valores[':email'] = $emailPadrao;
        }

        $log[] = "apelido = '" . $apelido . "'";
        $campos['apelido'] = "apelido = :apelido";
        $campos_valores[':apelido'] = $apelido;

        $log[] = "rg = '" . $rg . "'";
        $campos['rg'] = "rg = :rg";
        $campos_valores[':rg'] = $rg;

        $log[] = "solicitacao_email = '" . $email . "'";
        $campos['solicitacao_email'] = "solicitacao_email = :solicitacao_email";
        $campos_valores[':solicitacao_email'] = $email;

        $log[] = "contato = '" . $contato . "'";
        $campos['contato'] = "contato = :contato";
        $campos_valores[':contato'] = $contato;

        $log[] = "telefone = '" . $tel1 . "'";
        $campos['telefone'] = "telefone = :telefone";
        $campos_valores[':telefone'] = $tel1;

        $log[] = "fone_coml = '" . $tel2 . "'";
        $campos['fone_coml'] = "fone_coml = :fone_coml";
        $campos_valores[':fone_coml'] = $tel2;

        $log[] = "fone_celular = '" . $cel1 . "'";
        $campos['fone_celular'] = "fone_celular = :fone_celular";
        $campos_valores[':fone_celular'] = $cel1;

        $log[] = "fone_celular_2 = '" . $cel2 . "'";
        $campos['fone_celular_2'] = "fone_celular_2 = :fone_celular_2";
        $campos_valores[':fone_celular_2'] = $cel2;

        if (trim($email) != '') {

            $query = "SELECT email, hash FROM apss_users WHERE id = '" . $id . "' LIMIT 1;";
            $rs = $conn->query($query);
            if ($row = $rs->fetch(PDO::FETCH_ASSOC)) {
                $hash = empty($row['hash']) ? md5(date('Y-m-d H:i:s') . $email) : $row['hash'];
                $emailBase = $row['email'];
            }

            // echo $emailBase .' - '. $email;
            if ($emailBase != $emailPadrao && !empty($emailPadrao)) {
                $campos_valores[':solicitacao_email'] = '';
            }

            $log[] = "hash = '" . $hash . "'";
            $campos['hash'] = "hash = :hash";
            $campos_valores[':hash'] = $hash;

            // enviamos o email
            $objEmail = new Email();
            $objEmail->sendHashNovoEmail($email, $hash);
        }

        // print_r($campos);
        // print_r($campos_valores);
        // exit();

        // antes de atualizar, verifico se o email foi alterado. se sim, enviamos o email para confirmacao
        // $query = "SELECT email FROM apss_users WHERE id = '" . $id . "' LIMIT 1;";
        // $rs = $conn->query($query);
        // if($row = $rs->fetch(PDO::FETCH_ASSOC)) {
        // $email_atual = $row['email'];

        // #if($email_atual != $email) {
        // // alteramos a hash do usuario
        // $hash = md5(date('Y-m-d H:i:s') . $email);
        // $campos[] = "hash = '" . $hash . "'";
        // $campos[] = "controle_hash = '0'";

        // // enviamos o email
        // $objEmail = new Email();
        // $objEmail->sendHash($email, $hash);
        // #}
        // }

        LogAdm::setIdUsuario($id);
        LogAdm::setTabela('apss_users');
        $returnLog = LogAdm::saveAlteracaoUsuario($log, 'Altera&ccedil;&atilde;o Box "Dados do Comprador"');
        if ($returnLog) {
            $query = "UPDATE apss_users SET " . implode(', ', $campos) . " WHERE id = '" . $id . "';";
            // $conn->query($query);

            $rs = $conn->prepare($query, array(
                PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY,
            ));
            $rs->execute($campos_valores);
            Transacao::commit();

            if (count($perguntaAlteracaoEmail) > 0) {
                foreach ($perguntaAlteracaoEmail as $value) {
                    $pergunta = Adm::getPerguntasValidaEmail($value);
                    $params['descricao'] = 'Confirma&ccedil;&atilde;o de altera&ccedil;&atilde;o de e-mail - ' . $pergunta[0]['descricao'];
                    LogAdm::setIdUsuario($id);
                    LogAdm::AddLogSimples($params);
                }
            }
        }

        // if(is_array($emails)) {
        // foreach($emails as $e) {
        // $query = "INSERT INTO apss_users_email (apss_users_id, email, padrao, data_cadastro, status) VALUE ('" . $id . "', '" . $e . "', 'nao', NOW(), 'ativo');";
        // $conn->exec($query);
        // Transacao::commit();
        // }
        // }
    }
    public static function atualizaControleHash($id)
    {
        global $conn;

        $query = "SELECT id FROM liberacao_temporaria WHERE apss_user_id = '" . $id . "';";
        $rs = $conn->query($query);
        if ($rs->rowCount() == 0) {
            $query = "UPDATE apss_users SET controle_hash = '2' WHERE id = '" . $id . "';";
            $conn->query($query);
            Transacao::commit();

            $query = "INSERT INTO liberacao_temporaria (apss_user_id, nm_responsavel) VALUES ('" . $id . "','" . $_SESSION['perfil']['usuario'] . "');";
            $conn->query($query);
            Transacao::commit();

            $params['descricao'] = 'Libera&ccedil;&atilde;o Temporaria realizada com sucesso';
            LogAdm::setIdUsuario($id);
            LogAdm::AddLogSimples($params);

            return 'LiberaÃ§Ã£o Temporaria realizada com sucesso';
        } else {

            $params['descricao'] = 'A Libera&ccedil;&atilde;o Temporaria j&aacute; foi realizada anteriormente';
            LogAdm::setIdUsuario($id);
            LogAdm::AddLogSimples($params);

            return 'A LiberaÃ§Ã£o Temporaria jÃ¡ foi realizada anteriormente';
        }
    }
    public static function atualizaUsuarioBox3($id, $cep, $tipo_logradouro, $endereco, $numero, $complemento, $bairro, $cidade, $uf, $solicitacao_alteracao, $tipoAcao = 'SalvarBloquear')
    {
        global $conn;

        if ($tipoAcao == 'Rejeitar') {
            $campos = array();
            $campos[] = "solicitacao_alteracao = 0";
            $campos[] = "solicitacao_cep = ''";
            $campos[] = "solicitacao_tipo_logradouro = ''";
            $campos[] = "solicitacao_endereco = ''";
            $campos[] = "solicitacao_numero = ''";
            $campos[] = "solicitacao_complemento = ''";
            $campos[] = "solicitacao_bairro = ''";
            $campos[] = "solicitacao_cidade = ''";
            $campos[] = "solicitacao_uf = ''";

            // Padrao::insereNotificacaoUsuario(array(
            //     'user_id' => $id,
            //     'tipo_id' => 1,
            //     'descricao' => utf8_encode('Solicita&ccedil;&atilde;o de altera&ccedil;&atilde;o dos dados cadastrais rejeitada.'),
            //     'url' => '/minhaconta/dados-cadastrais/',
            // ));

            $query = "UPDATE adm_notificacao SET lido = 'sim', data_respondido = NOW() WHERE lido = 'nao' AND apss_user_id = '" . $id . "' AND tipo = 'cadastro';";
            $conn->query($query);
            Transacao::commit();

            $query = "UPDATE apss_users SET " . implode(', ', $campos) . " WHERE id = '" . $id . "';";
            $conn->query($query);
            Transacao::commit();

            $params['descricao'] = 'Rejeitou altera&ccedil;&atilde;o de endere&ccedil;o';
            LogAdm::setIdUsuario($id);
            LogAdm::AddLogSimples($params);
        } else {

            $crypt_salt = Usuario::$crypt_salt_static;

            $log = array();
            $campos = array();
            $campos_valores = array();
            if (!is_null($cep)) {
                $log[] = "cep = '" . $cep . "'";
                $campos[] = "cep = ?";
                $campos_valores[] = $cep;
            }
            if (!is_null($tipo_logradouro)) {
                $log[] = "tipo_logradouro = '" . $tipo_logradouro . "'";
                $campos[] = "tipo_logradouro = ?";
                $campos_valores[] = $tipo_logradouro;
            }
            if (!is_null($endereco)) {
                $log[] = "endereco = '" . $endereco . "'";
                $campos[] = "endereco = ?";
                $campos_valores[] = $endereco;
            }
            if (!is_null($numero)) {
                $log[] = "numero = '" . $numero . "'";
                $campos[] = "numero = ?";
                $campos_valores[] = $numero;
            }
            if (!is_null($complemento)) {
                $log[] = "compl = '" . $complemento . "'";
                $campos[] = "compl = ?";
                $campos_valores[] = $complemento;
            }
            if (!is_null($bairro)) {
                $log[] = "bairro = '" . $bairro . "'";
                $campos[] = "bairro = ?";
                $campos_valores[] = $bairro;
            }
            if (!is_null($cidade)) {
                $log[] = "cidade = '" . $cidade . "'";
                $campos[] = "cidade = ?";
                $campos_valores[] = $cidade;
            }
            if (!is_null($uf)) {
                $log[] = "estado = '" . $uf . "'";
                $campos[] = "estado = ?";
                $campos_valores[] = $uf;
            }

            LogAdm::setIdUsuario($id);
            LogAdm::setTabela('apss_users');

            $txtExtra = '';
            if ($solicitacao_alteracao == 1) {
                $txtExtra = $tipoAcao == 'SalvarBloquear' ? '(Salvar e Bloquear)' : '(Salvar e N&atilde;o Bloquear)';
            }
            $returnLog = LogAdm::saveAlteracaoUsuario($log, "Altera&ccedil;&atilde;o Box EndereÃ§o " . $txtExtra);

            if ($returnLog) {

                if ($solicitacao_alteracao == 1) {
                    // limpa os campos de solicitacao de alteracao de endereco

                     if ($tipoAcao == 'SalvarBloquear') {
	                    $campos[] = "solicitacao_alteracao = '2'";
	                    $msgNotificacao = 'Solicita&ccedil;&atilde;o de altera&ccedil;&atilde;o dos dados cadastrais aprovada. Por favor, reenvie os documentos necess&aacute;rios';
                     }elseif ($tipoAcao == 'SalvarNaoBloquear') {
                        $campos[] = "solicitacao_alteracao = '0'";
                        $msgNotificacao = 'Solicita&ccedil;&atilde;o de altera&ccedil;&atilde;o dos dados cadastrais aprovada.';
                    }
                    $campos[] = "solicitacao_cep = ''";
                    $campos[] = "solicitacao_tipo_logradouro = ''";
                    $campos[] = "solicitacao_endereco = ''";
                    $campos[] = "solicitacao_numero = ''";
                    $campos[] = "solicitacao_complemento = ''";
                    $campos[] = "solicitacao_bairro = ''";
                    $campos[] = "solicitacao_cidade = ''";
                    $campos[] = "solicitacao_uf = ''";

                    // insere uma notificao para o usuario
                    // Padrao::insereNotificacaoUsuario(array(
                    //     'user_id' => $id,
                    //     'tipo_id' => 1,
                    //     'descricao' => utf8_encode($msgNotificacao),
                    //     'url' => '/minhaconta/dados-cadastrais/',
                    // ));

                    // seta como lida a notificacao do admin
                    $query = "UPDATE adm_notificacao SET lido = 'sim', data_respondido = NOW() WHERE lido = 'nao' AND apss_user_id = '" . $id . "' AND tipo = 'cadastro';";
                    $conn->query($query);
                    Transacao::commit();
                }

                $query = "UPDATE apss_users SET " . implode(', ', $campos) . " WHERE id = '" . $id . "';";

                $rs = $conn->prepare($query);
                $rs->execute($campos_valores);
                Transacao::commit();

/*
                Conforme pedido da raquel dia 17/04/2015 nÃ£o reporvar documentos apÃ³s o bloqueio.

                if ($tipoAcao == 'SalvarBloquear') {
                    // bloqueio dos arquivos
                    $query = "SELECT GROUP_CONCAT(a.id) AS 'ids' FROM apss_users_arquivos a, apss_users_arquivo_tipo t WHERE a.tipo_id = t.id AND a.status = 'ativo' AND a.user_id = '" . $id . "';";
                    $rs = $conn->query($query);
                    if ($rs) {
                        if ($row = $rs->fetch(PDO::FETCH_ASSOC)) {
                            $query = "UPDATE apss_users_arquivos SET situacao = 'reprovado' WHERE id IN (" . $row['ids'] . ");";
                            $conn->query($query);
                            Transacao::commit();
                        }
                    }
                }
*/
            }
        }
    }

    public static function atualizaUsuarioBox4($fields, $delete = array(), $nm_responsavel)
    {
        global $conn;

        $id = $fields['id'];
        $campos = array();
        $querys_documentos = array();

        $obs_geral = '';
        $comprador_id = '';
        $query = '';
        $montaLog = array();

        foreach ($fields as $key => $value) {

            if ($key != 'id') {
                $valAux = explode('|', $value);

                if(empty($valAux[3]))
                    $valAux[3] = '';

                $novo_doc = $valAux[3] == 'undefined' ? '' : utf8_decode($valAux[3]) ;


                if (count($valAux) > 2) {
                    $valAux[0] = trim($valAux[0]);
                    $valAux[1] = (int)$valAux[1];

                    $dtReprovado = '';
                    if($valAux[0] != 'aprovado' && $valAux[0] != 'aguardando_aprovacao')
                        $dtReprovado = ', data_reprovacao = NOW() ';

                    if($valAux[2] == 'doc_lote'){//faz atualizaï¿½ï¿½es para arquivos dos LOTES

                        //para nï¿½o salvar o nome do ultimo operador como responsavel
                        $sql =  "SELECT situacao FROM apss_users_documento_lote WHERE id = {$valAux[1]};";
                        $ret = $conn->query($sql)->fetch(PDO::FETCH_ASSOC);
                        $nm_responsavel_lote = '';
                        if($ret['situacao'] != $valAux[0]){
                            $nm_responsavel_lote = ", nm_responsavel='".$nm_responsavel."'";
                        }

                        $query .= "UPDATE apss_users_documento_lote SET situacao = '{$valAux[0]}' {$nm_responsavel_lote}, tipo_manual='{$novo_doc}' {$dtReprovado} WHERE id = {$valAux[1]}; ";
                        $tipo = Usuario::getDadosArquivoDocumento($valAux[1],$valAux[2]);
                        $montaLog[$valAux[1]] = array(
                            "tipo = '" . $tipo['tipo'] . "'",
                            "situacao = '" . $valAux[0] . "'",
                            "{$valAux[2]}",
                        );

                    }else{//faz atualizaï¿½es para arquivos PESSOAIS
                        if(strtotime(str_replace('/','-',$valAux[4])) > 0)
                            $data_validade =  date('Y-m-d',strtotime(str_replace('/','-',$valAux[4])));
                        else
                            $data_validade = '';

                        //para nï¿½o salvar o nome do ultimo operador como responsavel
                        $sql =  "SELECT situacao FROM apss_users_arquivos WHERE id = {$valAux[1]};";
                        $ret = $conn->query($sql)->fetch(PDO::FETCH_ASSOC);
                        $nm_responsavel_pessoais = '';
                        if($ret['situacao'] != $valAux[0]){
                            $nm_responsavel_pessoais = ", nm_responsavel='".$nm_responsavel."'";
                        }

                        $query .= "UPDATE apss_users_arquivos SET situacao = '{$valAux[0]}' {$nm_responsavel_pessoais},  tipo_manual='{$novo_doc}',  data_validade='{$data_validade}' {$dtReprovado} WHERE id = {$valAux[1]}; ";
                        $tipo = Usuario::getDadosArquivoDocumento($valAux[1],$valAux[2]);
                        $montaLog[$valAux[1]] = array(
                            "tipo = '" . $tipo['tipo'] . "'",
                            "situacao = '" . $valAux[0] . "'",
                            "{$valAux[2]}",
                        );
                    }


                    if ($valAux[0] != 'aguardando_envio') {
                        $situacao = '';
                        switch ($valAux[0]) {
                            case 'aprovado':
                                $situacao = 'aprovado';
                                break;
                            case 'reprovado':
                                $situacao = 'reprovado';
                                break;
                            case 'incompleto':
                                $situacao = 'definido como incompleto';
                                break;
                            case 'ilegivel':
                                $situacao = 'definido como ilegï¿½vel';
                                break;
                            case 'divergente':
                                $situacao = 'definido como divergente';
                                break;
                            default:
                                $situacao = false;
                        }
                        if ($situacao) {
                            if (Usuario::getStatusArquivoDocumento($valAux[1], $valAux[0])) {
                                // insere uma notificao para o usuario
                                $dadoArquivo = self::getDadosUsuarioArquivo($valAux[1], $valAux[0]);
                                // Padrao::insereNotificacaoUsuario(array(
                                //     'user_id' => $id,
                                //     'tipo_id' => '2',
                                //     'descricao' => utf8_encode('O documento "' . $dadoArquivo['nm_tipo'] . '" foi ' . $situacao),
                                //     'url' => '/minhaconta/dados-cadastrais/',
                                // ));
                            }
                        }

                        if($valAux[0] == 'reprovado' || $valAux[0] == 'cortado'){
                            $sql = "SELECT caminho FROM apss_users_arquivos WHERE id = {$valAux[1]}";
                            $caminho_arquivo = $conn->query($sql)->fetch(PDO::FETCH_ASSOC);

                            $dest = str_replace("/upload/", "/upload/excluir/", $caminho_arquivo['caminho']);
                            if (file_exists($caminho_arquivo['caminho'])) {
                                if (copy($caminho_arquivo['caminho'], $dest)) {
                                    unlink($caminho_arquivo['caminho']);
                                }
                            }
                        }
                    }
                } else {
                    if ($key == 'iptIdSistema') {
                        $comprador_id = utf8_decode($value);
                        $query .= "UPDATE apss_users SET comprador_id = '" . $value . "' WHERE id = '" . $id . "';";
                        $query .= "UPDATE apss_users_arquivos SET comprador_id = '" . $value . "' WHERE user_id = '" . $id . "';";
                        $query .= "UPDATE apss_users_documento_lote SET comprador_id = '" . $value . "' WHERE user_id = '" . $id . "';";

                        LogAdm::setIdUsuario($id);
                        LogAdm::setTabela('apss_users');
                        LogAdm::saveAlteracaoUsuario(array(
                            "comprador_id = '" . $comprador_id . "'",
                        ), "Altera&ccedil;&atilde;o Box \"Sistema\"");
                    }
                    if ($key == 'observacao') {
                        $obs_geral = utf8_decode($value);
                        $query .= "UPDATE apss_users SET observacao_geral_documentacao = '" . $obs_geral . "' WHERE id = '" . $id . "';";
                    }
                }
            }
        }

        LogAdm::setIdUsuario($id);
        LogAdm::setTabela('apss_users');
        LogAdm::saveAlteracaoUsuario(array(
            "observacao_geral_documentacao = '" . $obs_geral . "'",
        ), "Alteraï¿½ï¿½o Box \"Sistema\"");

        foreach ($montaLog as $key => $value) {
            LogAdm::setIdUsuario($id);
            if($value[2] == 'doc_lote')
                LogAdm::setTabela('apss_users_documento_lote');
            else
                LogAdm::setTabela('apss_users_arquivos');

            unset($value[2]);
            LogAdm::setIdTabelaCampo($key);
            LogAdm::saveAlteracaoUsuario($value, "Alteraï¿½ï¿½o Box \"Sistema\"", true);
        }

        $conn->query($query);
        Transacao::commit();

        $arrDelPessoal = array();
        $arrDelLote = array();
        foreach($delete as $key=>$value){
            $key = str_replace('delete-','',$key);
            $dados = explode('|',$key);
            if($dados[0] == 'doc_pessoal'){
                $arrDelPessoal[] =  (int)$value;
            }else{
                $arrDelLote[] = (int)$value ;
            }
        }


        $arquivos_historico = array();

        if (count($delete) > 0) {
            if (count($arrDelPessoal) > 0) {
                $arquivos_historico = $arrDelPessoal;
                $query = "SELECT status_copia,ID_SqlServer,caminho,tipo,id from apss_users_arquivos WHERE user_id = '" . $id . "' AND id IN (" . implode(",", $arrDelPessoal) . ");";
                $arquivos = array();
                $montaLog = array();
                $rs = $conn->query($query);
                if ($rs) {
                    while ($row = $rs->fetch(PDO::FETCH_ASSOC)) {
                        $sqlserver = new SqlServer();
                        if ($row['status_copia'] == 1) {
                            $sqlserver->desassociarFax($row['ID_SqlServer']);
                        }
                        if (preg_match('/(admin)/i', $row['caminho'])) {
                            $path_fix = str_replace("/http", "/admin/", $_SERVER['DOCUMENT_ROOT']);
                            $row['caminho'] = $path_fix . str_replace("../admin/", "", $row['caminho']);
                        }
                        $dest = str_replace("/upload/", "/upload/excluir/", $row['caminho']);
                        if (file_exists($row['caminho'])) {
                            if (copy($row['caminho'], $dest)) {
                                unlink($row['caminho']);
                            }
                        }
                        $arquivos[] = $row['tipo'];
                        $montaLog[$row['id']] = array(
                            "tipo = '" . $row['tipo'] . "'",
                            "status = 'inativo'",
                        );
                    }
                    LogAdm::setIdUsuario($id);
                    LogAdm::setTabela('apss_users');
                    LogAdm::saveAlteracaoUsuario(array(
                        "observacao_geral_documentacao = '" . $obs_geral . "'",
                    ), "Altera&ccedil;&atilde;o Box \"Sistema\"");

                    foreach ($montaLog as $key => $value) {
                        LogAdm::setIdUsuario($id);
                        LogAdm::setTabela('apss_users_arquivos');
                        LogAdm::setIdTabelaCampo($key);
                        LogAdm::saveAlteracaoUsuario($value, "Altera&ccedil;&atilde;o Box \"Sistema\"", true);
                    }
                }
            }
            if (count($arrDelLote) > 0) {
                $arquivos_historico = $arrDelLote;
                $query = "SELECT status_copia,ID_SqlServer,caminho,tipo,id from apss_users_documento_lote WHERE user_id = '" . $id . "' AND id IN (" . implode(",", $arrDelLote) . ");";
                $arquivos = array();
                $montaLog = array();
                $rs = $conn->query($query);
                if ($rs) {
                    while ($row = $rs->fetch(PDO::FETCH_ASSOC)) {
                        $sqlserver = new SqlServer();
                        if ($row['status_copia'] == 1) {
                            $sqlserver->desassociarFax($row['ID_SqlServer']);
                        }
                        if (preg_match('/(admin)/i', $row['caminho'])) {
                            $path_fix = str_replace("/http", "/admin/", $_SERVER['DOCUMENT_ROOT']);
                            $row['caminho'] = $path_fix . str_replace("../admin/", "", $row['caminho']);
                        }
                        $dest = str_replace("/upload/", "/upload/excluir/", $row['caminho']);

                        if (file_exists($row['caminho'])) {
                            if (copy($row['caminho'], $dest)) {
                                unlink($row['caminho']);
                            }
                        }
                        $arquivos[] = $row['tipo'];
                        $montaLog[$row['id']] = array(
                            "tipo = '" . $row['tipo'] . "'",
                            "status = 'inativo'",
                        );
                    }
                    LogAdm::setIdUsuario($id);
                    LogAdm::setTabela('apss_users');
                    LogAdm::saveAlteracaoUsuario(array(
                        "observacao_geral_documentacao = '" . $obs_geral . "'",
                    ), "Altera&ccedil;&atilde;o Box \"Sistema\"");

                    foreach ($montaLog as $key => $value) {
                        LogAdm::setIdUsuario($id);
                        LogAdm::setTabela('apss_users_documento_lote');
                        LogAdm::setIdTabelaCampo($key);
                        LogAdm::saveAlteracaoUsuario($value, "Altera&ccedil;&atilde;o Box \"Sistema\"", true);
                    }
                }
            }

            Adm::gravarHistorico(array(
                'NM_Responsavel' => $nm_responsavel,
                'comprador_id' => Usuario::getCompradorId($id),
                'NM_Obs' => empty($obs_geral) ? implode(",", $arquivos_historico) : implode(",", $arquivos_historico) . " - " . SqlServer::encodeToSqlServer($obs_geral),
                'NM_Assunto' => 'Desassociar Documentos',
            ), $id);

/*
            28/11/2014 raquel pediu para retirar esse recurso

            $sqlserver->setStatusUploadFax(array( 'comprador_id' => Usuario::getCompradorId($id), 'status' => 6, 'NM_Responsavel' => $nm_responsavel, ));
*/
            if(count($arrDelPessoal) > 0){
                $query = "UPDATE apss_users_arquivos SET status = 0, data_exclusao = NOW() WHERE user_id = '" . $id . "' AND id IN (" . implode(",", $arrDelPessoal) . ");";
                $conn->query($query);
                Transacao::commit();
            }
            if(count($arrDelLote) > 0){
                $query = "UPDATE apss_users_documento_lote SET status = 0, data_exclusao = NOW() WHERE user_id = '" . $id . "' AND id IN (" . implode(",", $arrDelLote) . ");";
                $conn->query($query);
                Transacao::commit();
            }
        }

        //para setar notificaï¿½ï¿½es como lidas caso nï¿½o haja nenhum doc aguardando aprovaï¿½ï¿½o
        $arquivos = Adm::getDocsSalvos_arquivo_lote($id);
        $arrArquivos = isset($arquivos['pessoais']) ? $arquivos['pessoais'] : '';
        $arrLotes    = isset($arquivos['lotes']) ? $arquivos['lotes'] : '';

        $delMsgArq = false;
        foreach($arrArquivos as $valor){
            if($valor['situacao'] == 'aguardando_aprovacao' || $valor['status'] == 0)
                $delMsgArq = true;
        }
        if(!$delMsgArq){
            $query = "UPDATE adm_notificacao SET lido = 'sim', data_respondido = NOW() WHERE lido = 'nao' AND apss_user_id = '" . $id . "' AND tipo = 'cadastro';";
            $conn->query($query);
            Transacao::commit();
        }

        $delMsgLote = false;
        foreach($arrLotes as $valor){
            if($valor['situacao'] == 'aguardando_aprovacao' || $valor['status'] == 0)
                $delMsgLote= true;
        }
        if(!$delMsgLote){
            $query = "UPDATE adm_notificacao SET lido = 'sim', data_respondido = NOW() WHERE lido = 'nao' AND apss_user_id = '" . $id . "' AND tipo = 'documento_lote';";
            $conn->query($query);
            Transacao::commit();
        }
    }
    public static function getDadosUsuarioArquivo($id)
    {
        global $conn;

        $retorno = false;
        if (!empty($id)) {
            $query = "SELECT a.id, a.user_id, c.nome as nm_conjunto, a.conjunto_id, g.nome as nm_grupo, a.grupo_id, t.nome as nm_tipo, t.rotulo, a.tipo_id, ";
            $query .= "gt.obrigatorio, a.situacao, a.nome_enviado, a.nome_criado, a.caminho, a.status, ";
            $query .= "DATE_FORMAT(a.data_solicitacao, '%d/%m/%Y %H:%i:%s') as data_solicitacao ";
            $query .= "FROM apss_users_arquivos AS a ";
            $query .= "INNER JOIN apss_users_arquivo_conjunto AS c ON c.id = a.conjunto_id ";
            $query .= "INNER JOIN apss_users_arquivo_grupo AS g ON g.id = a.grupo_id ";
            $query .= "INNER JOIN apss_users_arquivo_tipo AS t ON t.id = a.tipo_id ";
            $query .= "INNER JOIN apss_users_arquivo_grupo_tipo AS gt ON gt.grupo_id = a.grupo_id AND gt.tipo_id = a.tipo_id ";
            $query .= "WHERE c.status = 1 AND a.id = " . $id . " ORDER BY c.id ASC, g.id ASC, gt.ordem ASC ";

            $rs = $conn->query($query);
            if ($row = $rs->fetch(PDO::FETCH_ASSOC)) {
                return $row;
            }
        }
        return $retorno;
    }
    public static function buscaArquivoUsuario($arquivo_id,$tipo_doc=false)
    {
        global $conn;

        if($tipo_doc == 'Documentos'){
            $query = "
            SELECT *
            FROM apss_users_documento_lote
            WHERE id = '" . $arquivo_id . "' AND status = 1;";
            $arquivo = null;

            $rs = $conn->query($query);
            if ($row = $rs->fetch(PDO::FETCH_ASSOC)) {
                $arquivo = $row;
            }
        }else{
            $query = "
            SELECT a.*
            FROM apss_users_arquivos a
            WHERE a.id = '" . $arquivo_id . "' AND status = 1;";
            $arquivo = null;
            $rs = $conn->query($query);
            if ($row = $rs->fetch(PDO::FETCH_ASSOC)) {
                $arquivo = $row;
            }
        }

        return $arquivo;
    }
    public static function validarAcesso($acao, $usuario = '')
    {
        global $array_usuario_relatorio;
        global $array_chat_relatorio;
        global $acessoChatPainel;
        global $acessoBradescoRelatorio;

        $usuario = strtolower($usuario);

        $acao = str_replace("-", "_", $acao);
        $permissao = Sessao::pegaSessao('perfil', 'perfil', $acao);
        $grupo = Sessao::pegaSessao('perfil', 'group');

        if (strpos($_SERVER['REDIRECT_URL'], 'chat-painel') > 0) {
            $user = strtolower(Sessao::pegaSessao('perfil', 'usuario'));
            if (in_array($user, $acessoChatPainel)) {
                return true;
            }
        }

        if (strpos($_SERVER['REDIRECT_URL'], 'chat-relatorio') > 0) {
            $user = strtolower(Sessao::pegaSessao('perfil', 'usuario'));
            if (in_array($user, $array_chat_relatorio)) {
                return true;
            }
        }

        if (strpos($_SERVER['REDIRECT_URL'], 'bradesco-relatorio') > 0) {
            $user = strtolower(Sessao::pegaSessao('perfil', 'usuario'));
            if (in_array($user, $acessoBradescoRelatorio)) {
                return true;
            }
        }

        if ($acao == 'relatorio' && $permissao == false) {
            if (isset($usuario) && in_array($usuario, $array_usuario_relatorio)) {

                return true;
            } else {

                return false;
            }
        } elseif ($acao == 'chat-relatorio' && ($grupo == 'CallCenterGerencia' || in_array($usuario, $array_chat_relatorio))) {
            return true;
        } elseif (in_array($acao, array(
            'bradesco_atendimento',
            'bradesco_proposta',
        )) && ($grupo == 'CallCenterGerencia' || $grupo == 'CallCenter' || $grupo == 'Diretores')) {
            // || $grupo == 'ConsultaGerencial'
            return true;
        } elseif (in_array($acao, array(
            'bradesco_atendimento',
            'bradesco_proposta',
            'bradesco_relatorio',
            'bradesco_mapa',
        )) && ($grupo == 'CallCenterGerencia' || $grupo == 'Administrator' || $grupo == 'ConsultaGerencial' || $grupo == 'Diretores')) {
            // || $grupo == 'ConsultaGerencial'
            return true;
        } elseif ($permissao == 1) {
            return true;
        } else {
            return false;
        }
    }
    public function getVideoTransmissao()
    {
        global $conn;
        $query = "select * from fms_config order by status desc";
        $rs = $conn->query($query);

        $dados = array();
        while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
            $dados[] = array(
                'url' => $row['url'],
                'instancia' => $row['instancia'],
                'label' => $row['url'] . "/" . $row['instancia'],
                'id' => $row['id'],
                'status' => $row['status'],
                'ativo' => $row['status'] == 1 ? 'Sim' : 'N&atilde;o',
            );
        }

        return $dados;
    }
    public static function saveVideoTransmissao($params)
    {
        global $conn;

        if ($params['status'] == 1) {
            $query = " update fms_config set status = 0";
            $conn->query($query);
            $params['data_ativacao'] = 'NOW()';
        }

        Transacao::db_insert("fms_config", $params);

        return true;
    }
    public static function updateVideoTransmissao($colunas, $id)
    {
        global $conn;
        if ($colunas['status'] == 1) {
            $query = " update fms_config set status = 0";
            $conn->query($query);
            $colunas['data_ativacao'] = 'NOW()';
        }

        Adm::update("fms_config", $colunas, array(
            "id" => $id,
        ), true);

        return true;
    }
    public static function deleteVideoTransmissao($id)
    {
        global $conn;
        if (!isset($id)) {
            return false;
        }

        $query = " delete from fms_config where id=$id ";
        $conn->query($query);
        Transacao::commit();

        return true;
    }
    public function getLeiloesInternos()
    {
        global $conn;
        $query = "select * from leilao_interno";
        $rs = $conn->query($query);

        $dados = array();
        while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
            $dados[] = array(
                'leilao_id' => $row['leilao_id'],
                'data_inicio' => $row['data_inicio'],
                'data_fim' => $row['data_fim'],
                'cliente_id' => $row['cliente_id'],
                'ativo' => $row['ativo'] == 1 ? 'Sim' : 'N&atilde;o',
            );
        }

        return $dados;
    }
    public function getClientesInterno()
    {
        global $conn;

        $query = " select c.id,c.nm_cliente from leilao_interno i
					inner join clientes c
					on i.cliente_id = c.id
					group by c.id ";
        $clientes = array();
        $rs = $conn->query($query);

        if ($rs) {
            while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                $clientes[] = $row;
            }
        }
        return $clientes;
    }
    public function updateCallCenter()
    {
        $sql_server = new SqlServer();
        $chat_sql = $sql_server->getCallCenter();

        foreach ($chat_sql as $sql) {
            self::getMysqlCallCenter($sql);
        }

        self::removerCallCenter($chat_sql);
    }
    private static function removerCallCenter($usuarios)
    {
        global $conn;
        $where = "where login not in ('" . implode("','", $usuarios) . "')";

        $query = "update chat_novo_usuario set data_exclusao=now(),status='inativo' $where ";

        $conn->query($query);

        Transacao::commit();
    }
    private static function getMysqlCallCenter($usuario)
    {
        global $conn;

        $query = " select chat_novo_usuario_id from chat_novo_usuario where login = '$usuario' ";
        list($login) = $conn->query($query)->fetch(PDO::FETCH_BOTH);

        if (empty($login)) {
            $params = array(
                'login' => $usuario,
                'data_cadastro' => "NOW()",
                'status' => 1,
            );
            Transacao::db_insert("chat_novo_usuario", $params);
        }
    }
    public function loadAutorizados($cliente_id, $liberados)
    {
        global $conn;

        switch ($cliente_id) {
            case '275':
                $tabela = 'compradores_gm';
                break;
            case '3375':
                $tabela = 'compradores_ecolab';
                break;
        }

        $conn->query("truncate $tabela ");

        $i = 0;
        $cpf_in = array();

        for ($i = 1; $i < count($liberados); $i++) {
            if ($cliente_id == '275') {
                $params = array(
                    'empresa_id' => "{$cliente_id}",
                    'planta' => "{$liberados[$i][0]}",
                    'registro' => "{$liberados[$i][1]}",
                    'nome' => "{$liberados[$i][2]}",
                    'secao' => "{$liberados[$i][3]}",
                    'dt_nascimento' => "{$liberados[$i][4]}",
                    'admissao' => "{$liberados[$i][5]}",
                    'rg' => "{$liberados[$i][6]}",
                    'cpf' => "{$liberados[$i][7]}",
                );
            } elseif ($cliente_id == '3375') {
                $params = array(
                    'empresa_id' => $cliente_id,
                    'matricula' => $liberados[$i][0],
                    'nome' => $liberados[$i][1],
                    'cpf' => "{$liberados[$i][2]}",
                    'rg' => $liberados[$i][3],
                );
            }
            if ($cliente_id == '3375' and (empty($params['cpf']) || empty($params['rg']))) {
                continue;
            }

            Transacao::db_insert($tabela, $params);

            if (count($cpf_in) == 1000) {
                self::fixCadastroInterno(implode("','", $cpf_in), $cliente_id);
                $cpf_in = array();
            } else {
                if (trim($params['cpf']) == '') {
                    $cpf_in[] = trim($params['cpf']);
                }
            }
        }

        return array(
            'erro' => 0,
            'msg' => "Quantidade de Inseridos " . count($liberados),
        );
    }
    private static function fixCadastroInterno($cpf_in, $cliente_id)
    {
        global $conn;

        $query = " update apss_users set cliente_id = $cliente_id where cpf in ('$cpf_in') ";
        $conn->query($query);
        Transacao::commit();
    }
    public static function fixOutrosLocais($local)
    {
        if ($local == "Guarulhos-Externo") {
            $local = "Outros Locais";
        }
        return $local;
    }
    public static function getOperadores($status = '')
    {
        global $conn;

        $query = "select
		 case segmento
			when 'I' then 'Imoveis'
			when 'M' then 'Materiais'
			when 'V' then 'Veiculos'
			else
				'Indefinido'
			end as segmento ,
			case status
				when 1 then 'Ativo'
				else
				'Inativo'
			end as status ,
			sigla,
			nome,
			email,
			patio ,
			user_id as id,
            SUBSTR(email, 1, INSTR(email, '@') - 1) AS ordem

		 from adm_operadores ";

        if (!empty($status)) {
            $query .= " where status = $status ";
        }

        $query .= " order by ordem; ";

        $rs = $conn->query($query);
        $operadores = array();
        if ($rs) {
            while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                $operadores[] = $row;
            }
        }

        return $operadores;
    }
    public static function updateOperador($params, $id)
    {
        global $conn;

        $query = "update adm_operadores set
					nome='{$params['nome']}'  ,
					status=1,
					data_inclusao=now()
					where user_id = $id ";
        $conn->query($query);

        if (!empty($params['senha'])) {
            $validate = Padrao::validateSenha($params['senha'], $id);

            if (isset($validate) && $validate['erro'] == 1) {
                return $validate;
            }
            $senha = $params['senha'] . Usuario::$crypt_salt_static;
            $query = "update apss_users set
						senha=SHA('$senha') where id = $id ";
            $conn->query($query);
            Usuario::LogSenha($id);
        }

        Transacao::commit();
        return array(
            'erro' => 0,
            'msg' => 'Operador Atualizado com sucesso!',
        );
    }
    public static function excluirOperador($id)
    {
        global $conn;

        $query = "update adm_operadores set
					nome='',
					status=0,
					data_exclusao=now()
					where user_id = $id ";
        if ($conn->query($query)) {
            Transacao::commit();
            return array(
                'erro' => 0,
                'msg' => '',
            );
        } else {
            return array(
                'erro' => 1,
                'msg' => 'Erro ao excluir o Operador',
            );
        }
    }
    public static function getDepartamentos()
    {
        global $conn;

        $query = " select * from adm_departamentos ";

        $departamentos = Transacao::getQueryArray($query);

        return $departamentos;
    }
    public static function getFuncionarios($ativo = true, $id = false)
    {
        global $conn;

        $where = "where status = $ativo ";

        if (isset($id) && $id > 0) {
            $where .= " and f.id = $id ";
        }

        $query = " select
			f.*
			,
			d.departamento
		from adm_funcionarios f
		inner join  adm_departamentos d on f.departamento_id = d.id
		$where  ";

        return Transacao::getQueryArray($query);
    }
    public static function saveFuncionario($params)
    {
        global $conn;

        Transacao::db_insert("adm_funcionarios", $params);
        Transacao::commit();
		echo json_encode(array('erro'=>0,'msg'=>utf8_encode('Crachï¿½ criado com sucesso!')));
    }

	public static function cadastrarDepartamento($nome_departamento){
		global $conn;
		if(!empty($nome_departamento)){
			$query  = " insert into adm_departamentos (departamento) values ('$nome_departamento');";
			$conn->query($query);
			Transacao::commit();
			echo json_encode(array('erro'=>0,'msg'=>'Departamento criado com sucesso!'));
		}else{
			echo json_encode(array('erro'=>1,'msg'=>'Preencha o departamento corretamente!'));
		}
	}
    public static function deletarFuncionario($id)
    {
        global $conn;
		$query = "delete from adm_funcionarios where id = $id ";
		$conn->query($query);
		Transacao::commit();
	}

    public function loadBuscas()
    {
        global $conn;

        $query = "select stringbusca from apss_users_maisbuscados where stringbusca <> '' and stringbusca not REGEXP '^[0-9]+$' and length(stringbusca)  > 3 group by stringbusca";
        $buscas = array();
        $rs = $conn->query($query);
        if ($rs) {
            while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                // $query_maisbuscados = Busca::getQuery($row['stringbusca'], TRUE);
                $query = Busca::getQuery($row['stringbusca'], true);
                $parametros = $query[1];
                $rs = $conn->prepare($query[0]);
                $rs->execute($parametros);
                $maisbuscados = array();
                $maisbuscados = $rs->fetch(PDO::FETCH_ASSOC);
                // $maisbuscados=array();
                // $maisbuscados=$conn->query($query_maisbuscados)->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($maisbuscados) && count($maisbuscados) > 0) {
                    $query_update = "update apss_users_maisbuscados set ativo = 1 where stringbusca = '{$row['stringbusca']}' ";
                    $conn->query($query_update);
                }
            }
            $query_delete = " delete from apss_users_maisbuscados where ativo = 0 or ativo is null or stringbusca is null ";
            $conn->query($query_delete);
            Transacao::commit();
        }

        Busca::setMoreSearchCache();
    }
    public function getDestaques()
    {
        global $conn;

        $query = "select * from destaques d
					inner join destaque_lines dl on d.destaque_id = dl.destaque_id
					order by d.segmento, d.prioridade, d.destaque_id, dl.coluna1";

        $destaques = array();
        $rs = $conn->query($query);

        while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
            $destaques[$row['destaque_id']]['destaque_id'] = $row['destaque_id'];
            $destaques[$row['destaque_id']]['segmento'] = $row['segmento'];
            $destaques[$row['destaque_id']]['prioridade'] = $row['prioridade'];
            $destaques[$row['destaque_id']]['itens'][] = array(
                'coluna1' => $row['coluna1'],
                'operacao' => $row['operacao'],
                'coluna2' => $row['coluna2'],
            );
        }
        return $destaques;
    }
    public function excluirLancesSucata()
    {
        global $conn;

        $query = "
		SELECT S.lote_id , V.leilao_id
		FROM sslonline_veiculos V
		INNER JOIN sslonline_status S ON (V.lote_id = S.lote_id)
		INNER JOIN leilao LE ON (V.leilao_id = LE.leilao_id)
		INNER JOIN sslonline_lance L ON (S.lance_id = L.sslonline_lance_id)
		INNER JOIN apss_users AU ON (L.user_id = AU.id)
		INNER JOIN apss_perfil P ON (AU.comprador_id = P.comprador_id)
		WHERE V.ST_EstadoGeral = 'S'
		AND LE.Deposito_ID != 12
		AND P.ST_Sucata = 0
        AND LE.DT_leilao  = DATE_FORMAT(NOW(),'%Y-%m-%d');";

        $lista_ids = array();
        $rs = $conn->query($query);
        while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
            $this->excluiLanceLoteAlterado($row['lote_id'], $row['leilao_id'], 'sistema', 2, '', 1);
        }
    }
    public static function getImpressoras()
    {
        global $conn;

        $lista_impressoras = array();

        $query = "
		SELECT *
		FROM adm_impressoras
		WHERE status = 1
		ORDER BY nome;";
        $rs = $conn->query($query);
        while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
            $lista_impressoras[] = $row;
        }

        return $lista_impressoras;
    }
    public static function salvaImpressoras($nome, $url)
    {
        Adm::insert('adm_impressoras', array(
            'nome' => $nome,
            'url' => $url,
            'data_cadastro' => date('Y-m-d H:i:s'),
            'status' => 1,
        ));
    }
    public static function excluirImpressoras($id)
    {
        Adm::update('adm_impressoras', array(
            'data_exclusao' => date('Y-m-d H:i:s'),
            'status' => 'inativo',
        ), array(
            'impressoras_id' => $id,
        ));
    }
    private static function getLeiloesAtivos()
    {
        global $conn;

        $query = "select leilao_id from leilao where dt_leilao = DATE_FORMAT(now(),'%Y-%m-%d') and st_leilao not in ('E','C','S') group by leilao_id";
        $leiloes = Transacao::getQueryArray($query);

        return $leiloes;
    }
    public static function gravarHistorico($documentos, $user_id)
    {
        global $conn;
        if (!empty($documentos)) {
            $sqlserver = new SqlServer();
            $sqlserver->gravaHistorico($documentos);
        }
    }
    public static function gravarNotificacaoAdm($user_id, $tipo)
    {
        global $conn;

        if (empty($user_id) || empty($tipo)) {
            return false;
        }

        // insere a notificacao do admin
        $ja_tem_notificacao_admin = false;
        $query = "		SELECT adm_notificacao_id
						FROM adm_notificacao
						WHERE lido = 'nao'
						AND apss_user_id = $user_id
						AND tipo = '$tipo' ";

        list($tem_notificacao) = $conn->query($query)->fetch(PDO::FETCH_BOTH);
        if ($tem_notificacao > 0) {
            $ja_tem_notificacao_admin = true;
        }

        if (!$ja_tem_notificacao_admin) {
            $nome = Padrao::getData('apss_users', 'nome', "id=$user_id");
            $query = "		INSERT INTO adm_notificacao (apss_user_id, tipo, lido, descricao, link, data_cadastro)
							VALUES (?, ?, ?, ?, ?, now());";
            // $conn->query($query);
            $rs = $conn->prepare($query);
            $rs->execute(array(
                $user_id,
                $tipo,
                'nao',
                $nome,
                '/adm/usuario/cadastro/filtro/' . $user_id . '/tipo/id_web',
            ));
            Transacao::commit();
        }
    }
    public static function chatVerificaInatividade()
    {
        global $conn;

        // $query = "SELECT chat_novo_requisicao_id AS requisicao_id, TIMESTAMPDIFF(SECOND, data_cadastro, NOW()) AS data, apss_user_id FROM (SELECT cnr.apss_user_id, cnm.data_cadastro, cnm.chat_novo_requisicao_id
        // FROM chat_novo_mensagem AS cnm RIGHT JOIN chat_novo_requisicao AS cnr ON cnr.chat_novo_requisicao_id = cnm.chat_novo_requisicao_id
        // WHERE cnr.encerrado = 'nao' ORDER BY cnm.chat_novo_mensagem_id DESC ) as tab GROUP BY chat_novo_requisicao_id ASC";

        // busca as requisicoes abertas
        $query = "
		SELECT
		 r.chat_novo_requisicao_id
		 , r.data_entrada_fila
		 , r.data_entrada_callcenter
		 , TIMESTAMPDIFF(SECOND, r.data_entrada_fila, r.data_entrada_callcenter) AS 'tempo_na_fila'
		 , TIMESTAMPDIFF(SECOND, r.data_entrada_callcenter, NOW()) AS 'tempo_atendimento'
		 , (
		  SELECT TIMESTAMPDIFF(SECOND, MAX(data_cadastro), NOW()) AS 'tempo_ultima_mensagem'
		  FROM chat_novo_mensagem m
		  WHERE m.chat_novo_requisicao_id = r.chat_novo_requisicao_id
		 ) AS 'data_mensagem'
		FROM chat_novo_requisicao r
		WHERE r.encerrado = 0
		AND r.chat_novo_usuario_id > 0
		GROUP BY r.chat_novo_requisicao_id
		ORDER BY r.chat_novo_requisicao_id DESC;";
        $rs = $conn->query($query);
        if ($rs->rowCount() > 0) {
            while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                if ($row['data_mensagem'] > 600) {
                    $retorno['segundos'] = $row['data_mensagem'];
                    $query = "
					UPDATE chat_novo_requisicao
					SET motivo_encerramento = 'inatividade', encerrado = 2, data_encerramento = NOW()
					WHERE chat_novo_requisicao_id = '" . $row['chat_novo_requisicao_id'] . "';";
                    $conn->exec($query);

                    $mensagem = "Conversa encerrada por Inatividade!";
                    $data_cadastro = date('Y-m-d H:i:s');

                    /*
                    $query = "
                    INSERT INTO chat_novo_mensagem (chat_novo_requisicao_id, apss_user_id, mensagem, data_cadastro, status)
                    VALUES ('" . $row['chat_novo_requisicao_id'] . "', '" . $row['apss_user_id'] . "', '" . $mensagem . "', '" . $data_cadastro . "', 1);";
                    */
                    $query = "
					INSERT INTO chat_novo_mensagem (chat_novo_requisicao_id, apss_user_id, mensagem, data_cadastro, status)
					VALUES ('" . $row['chat_novo_requisicao_id'] . "', '" . $row['apss_user_id'] . "', '" . $mensagem . "', NOW(), 1);";
                    $conn->exec($query);
                }
            }
        }
        Transacao::commit();
    }
    public static function listaDocumentosUpload($arquivos, $tipo_cadastro, $campanha = false)
    {
        global $conn;

        if ($tipo_cadastro == 'juridica') {
            $query_complemento = 'cnpj';
        } else {
            $query_complemento = 'cpf';
        }

        $retorno = array();
        if ($tipo_cadastro == 'fisica') {
            $tipo = 'CPF';
        } else {
            $tipo = 'CNPJ';
        }

        $query = "
		SELECT
		 c.id 'conjunto_id'
		 , g.id 'grupo_id'
		 , t.id 'tipo_id'
		 , c.nome 'conjunto'
		 , c.descricao 'c_descricao'
		 , g.nome 'grupo'
		 , g.rotulo 'g_rotulo'
		 , g.descricao 'g_descricao'
		 , t.nome 'tipo'
		 , t.rotulo 't_rotulo'
		 , gt.obrigatorio
		FROM
		 apss_users_arquivo_conjunto c
		 , apss_users_arquivo_grupo g
		 , apss_users_arquivo_grupo_tipo gt
		 , apss_users_arquivo_tipo t
		WHERE
		 c.id = g.conjunto_id
		 AND g.id = gt.grupo_id
		 AND gt.tipo_id = t.id
		 AND c.status = 1
		 AND g.status = 1
		 AND gt.status = 1
		 AND t.status = 1
		 AND c.visualizacao IN('" . $query_complemento . "', 'ambos')";
		if($campanha) {
			$query .= " AND c.campanha = '" . $campanha . "'";
		}
		$query .= " ORDER BY c.ordem, c.id, g.ordem, g.id, gt.ordem, gt.id;";
        $rs = $conn->query($query);
        while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
            $conjunto_id = $row['conjunto_id'];
            $conjunto = $row['conjunto'];
            $c_descricao = $row['c_descricao'];

            $grupo_id = $row['grupo_id'];
            $grupo = $row['grupo'];
            $g_rotulo = $row['g_rotulo'];
            $g_descricao = $row['g_descricao'];

            $tipo_id = $row['tipo_id'];
            $tipo = $row['tipo'];
            $t_rotulo = $row['t_rotulo'];
            $obrigatorio = $row['obrigatorio'];

            if (!isset($retorno[$conjunto_id])) {
                $aberto = 'sim';
                if (isset($arquivos[$conjunto_id])) {
                    $aberto = 'nao';
                }

                $retorno[$conjunto_id] = array(
                    'conjunto_id' => $conjunto_id,
                    'nome' => $conjunto,
                    'descricao' => $c_descricao,
                    'aberto' => $aberto,
                    'grupos' => array(),
                );
            }

            if (!isset($retorno[$conjunto_id]['grupos'][$grupo_id])) {
                $aberto = 'nao';
                if (isset($arquivos[$conjunto_id][$grupo_id])) {
                    $aberto = 'sim';
                }

                $retorno[$conjunto_id]['grupos'][$grupo_id] = array(
                    'grupo_id' => $grupo_id,
                    'nome' => $grupo,
                    'descricao' => $g_descricao,
                    'rotulo' => $g_rotulo,
                    'aberto' => $aberto,
                    'tipos' => array(),
                );
            }

            if (!isset($retorno[$conjunto_id]['grupos'][$grupo_id]['tipos'][$tipo_id])) {
                $documento = 'nao';
                if (isset($arquivos[$conjunto_id][$grupo_id][$tipo_id])) {
                    $documento = $arquivos[$conjunto_id][$grupo_id][$tipo_id];
                }

                $retorno[$conjunto_id]['grupos'][$grupo_id]['tipos'][$tipo_id] = array(
                    'tipo_id' => $tipo_id,
                    'nome' => $tipo,
                    'rotulo' => $t_rotulo,
                    'obrigatorio' => $obrigatorio,
                    'documento' => $documento,
                );
            }
        }

        return $retorno;
    }
    public static function validarEnvioObs($obs, $user_id)
    {
        global $conn;

        $query = " select ID from apss_users_obs where User_ID = $user_id and NM_Obs like '%$obs%' ";

        list($id) = $conn->query($query)->fetch(PDO::FETCH_BOTH);

        if ($id > 0) {
            return true;
        } else {
            return false;
        }
    }
    public static function extrairShopInvestBradesco($params)
    {
        global $conn;
        $texto = $params['texto'];

        $texto = self::formataTexto($texto);

        $texto = str_ireplace("<", "", $texto);
        $texto = rawurlencode($texto);
        $texto = str_ireplace("%26%238211%3B", "-", $texto);
        $texto = str_ireplace("%96", "-", $texto);
        $texto = utf8_decode(rawurldecode($texto));
        $texto = explode(";", preg_replace("/\n/i", ";", $texto));

        $dados['Lote'] = self::searcherNovo($texto[0], '/Lote ([0-9]+)/');
        $dados['Nome'] = self::searcherNovo($texto[4], '/(Nome ?)([ ]+?)(:)([ ]+?)([^?]+)/');
        $dados['CPF'] = self::searcherNovo($texto[5], '/(CPF)([ ]+?)(:)([ ]+?)([^?]+)/');
        $dados['RG'] = self::searcherNovo($texto[6], '/(RG)([ ]+?)(:)([ ]+?)([^?]+)/');
        $dados['Profissao'] = self::searcherNovo($texto[7], '/(Profissao)([ ]+?)(:)([ ]+?)([^?]+)/');
        $dados['EstadoCivil'] = self::searcherNovo($texto[8], '/(Estado Civil)([ ]+)?(:)([ ]+)?([^?]+)/');
        $dados['Conjuge'] = self::searcherNovo($texto[9], '/(Conjuge)([ ]+)?(:)([ ]+)?([^?]+)/');
        $dados['CPFConjuge'] = self::searcherNovo($texto[10], '/(Conjuge CPF)([ ]+)?(:)([ ]+)?([^?]+)/');
        $dados['RGConjuge'] = self::searcherNovo($texto[11], '/(Conjuge RG)([ ]+)?(:)([ ]+)?([^?]+)/');

        $numero = self::searcherNovo($texto[13], '/(Numero)([ ]+)?(:)([ ]+)?([^?]+)/');
        $complemento = self::searcherNovo($texto[14], '/(Complemento)([ ]+)?(:)([ ]+)?([^?]+)/');
        $endereco = self::searcherNovo($texto[12], '/(Endereco)([ ]+)?(:)([ ]+)?([^?]+)/');

        $dados['Endereco'] = "{$endereco} , n {$numero} {$complemento}";
        $dados['Cidade'] = self::searcherNovo($texto[16], '/(Cidade)([ ]+)?(:)([ ]+)?([^?]+)/');
        $dados['Estado'] = self::searcherNovo($texto[17], '/(Estado)([ ]+)?(:)([ ]+)?([^?]+)/');
        $dados['Cep'] = self::searcherNovo($texto[18], '/(CEP)([ ]+)?(:)([ ]+)?([^?]+)/');
        $dados['Telefone'] = self::searcherNovo($texto[19], '/(Telefone)([ ]+)?(:)([ ]+)?([^?]+)/');
        $dados['Fax'] = self::searcherNovo($texto[20], '/(Fax)([ ]+)?(:)([ ]+)?([^?]+)/');
        $dados['Email'] = self::searcherNovo($texto[21], '/(E-mail)([ ]+)?(:)([ ]+)?([^?]+)/');
        $dados['Proposta'] = self::searcherNovo($texto[22], '/(Proposta)([ ]+)?(:)([ ]+)?([^?]+)?/');
        $dados['FormaPagto'] = self::searcherNovo($texto[24], '/(Forma)([ ]+)?(:)([ ]+)?([^?]+)?/');
        $dados['FormaPagto'] = utf8_encode($dados['FormaPagto']);
        $dados['Leilao_ID'] = $params['leilao_id'];

        return $dados;
    }
    public function formataTexto($str)
    {
        return strtr(utf8_decode($str), utf8_decode('Ãâ¦ÃÂ Ãâ¦Ã¢â¬â¢Ãâ¦ÃÂ½Ãâ¦ÃÂ¡Ãâ¦Ã¢â¬ÅÃâ¦ÃÂ¾Ãâ¦ÃÂ¸ÃâÃÂ¥ÃâÃÂµÃÆÃ¢âÂ¬ÃÆÃÂÃÆÃ¢â¬Å¡ÃÆÃâÃÆÃ¢â¬Å¾ÃÆÃ¢â¬Â¦ÃÆÃ¢â¬Â ÃÆÃ¢â¬Â¡ÃÆÃâ ÃÆÃ¢â¬Â°ÃÆÃÂ ÃÆÃ¢â¬Â¹ÃÆÃâÃÆÃÂÃÆÃÂ½ÃÆÃÂÃÆÃÂÃÆÃ¢â¬ËÃÆÃ¢â¬â¢ÃÆÃ¢â¬ÅÃÆÃ¢â¬ÂÃÆÃ¢â¬Â¢ÃÆÃ¢â¬âÃÆÃÅÃÆÃ¢âÂ¢ÃÆÃÂ¡ÃÆÃ¢â¬ÂºÃÆÃâÃÆÃÂÃÆÃÂ¸ÃÆÃÂ ÃÆÃÂ¡ÃÆÃÂ¢ÃÆÃÂ£ÃÆÃÂ¤ÃÆÃÂ¥ÃÆÃÂ¦ÃÆÃÂ§ÃÆÃÂ¨ÃÆÃÂ©ÃÆÃÂªÃÆÃÂ«ÃÆÃÂ¬ÃÆÃÂ­ÃÆÃÂ®ÃÆÃÂ¯ÃÆÃÂ°ÃÆÃÂ±ÃÆÃÂ²ÃÆÃÂ³ÃÆÃÂ´ÃÆÃÂµÃÆÃÂ¶ÃÆÃÂ¸ÃÆÃÂ¹ÃÆÃÂºÃÆÃÂ»ÃÆÃÂ¼ÃÆÃÂ½ÃÆÃÂ¿'), 'SOZsozYYuAAAAAAACEEEEIIIIDNOOOOOOUUUUYsaaaaaaaceeeeiiiionoooooouuuuyy');
    }
    private static function searcherNovo($texto, $reg)
    {
        preg_match($reg, $texto, $tmp);
        $tmp = array_reverse($tmp);
        if (isset($tmp[0])) {
            return self::replaceBR($tmp[0]);
        }
    }
    private static function replaceBR($str)
    {
        return trim(str_replace(array(
            '<br>',
            'br>',
        ), array(
            '',
            '',
        ), $str));
    }
    /*public function validarPermissaoDesassociar($usuario)
    {
        $usuario = strtolower($usuario);
        if (in_array($usuario, $this->excluirDocs) || $this->group == 'CallCenterGerencia') {
            return true;
        } else {
            return false;
        }
    } */
    public static function savePropostaBradesco($params, $leilao)
    {
        global $conn;

        $retorno = false;
        if (count($params) > 0) {

            $st_from = 4; // lance via email (bradesco@ssol.com.br)
            $leilao_id = $leilao['leilao_id']; // leilao_id

            $dados['user_id_shop'] = 135361; // Fixa user_id do SHOPINVEST
            $dados['formaPagto'] = utf8_decode($leilao['formaPagto']);

            // select p/ ver se cpf jah cadastrado!
            $query_sel = "SELECT id FROM imoveis_users WHERE cpfcnpj='" . $params['cpfcnpj'] . "'";
            $retorno = Transacao::getQueryArray($query_sel);
            $user_id = $retorno[0]['id'];

            // select p/ lote_id
            $query = "SELECT lote_id FROM sslonline_imoveis WHERE lote = " . $leilao['lote'] . " and leilao_id = $leilao_id";
            $retorno = Transacao::getQueryArray($query);
            $lote_id = isset($retorno[0]) ? $retorno[0]['lote_id'] : '';

            $lance_info = array(
                'leilao_id' => $leilao_id,
                'lote_id' => $lote_id,
                'vl_lance' => $leilao['proposta'],
            );

            // Identifica plaqueta:
            $query = " SELECT plaqueta FROM imoveis_lanceonline WHERE leilao_id = " . $leilao_id . " AND user_id = " . $dados['user_id_shop'] . "";
            $auxPlaqueta = Transacao::getQueryArray($query);
            $plaqueta = array();
            foreach ($auxPlaqueta as $item) {
                $plaqueta[] = $item['plaqueta'];
            }

            $plaqueta = count($plaqueta) > 0 ? max($plaqueta) : 0;
            $plaqueta = $plaqueta['plaqueta'] + 1;
            if ($plaqueta < 10) {
                $plaqueta = 0 . $plaqueta;
            }

            if (empty($user_id)) {
                $campos = array_keys($params);
                $valores = array_values($params);

                $query = "INSERT INTO imoveis_users (" . implode(', ', $campos) . ") VALUES ('" . implode("', '", $valores) . "')";
                $conn->query($query);
                Transacao::commit();

                $retorno = Transacao::getQueryArray($query_sel);
                $user_id = $retorno[0]['id'];
            }

            // ENVIA LANCE
            $params['user_id_shop'] = 135361; // Fixa user_id do SHOPINVEST
            $params['formaPagto'] = utf8_decode($leilao['formaPagto']);
            $dados_lance = self::insertLanceBradesco($lance_info, $leilao_id, $params, $plaqueta);

            if (empty($user_id)) {
                $erro = "Erro: por favor tente novamente.";
                $retorno = false;
            } else {
                if (!empty($lote_id)) {
                    // checkar lance minimo
                    $query = "SELECT vl_lanceminimo FROM sslonline_imoveis WHERE lote_id = '$lote_id' AND leilao_id = '$leilao_id'";
                    $lance_minimo = Transacao::getQueryArray($query);
                    $lance_minimo = $lance_minimo[0]['vl_lanceminimo'];

                    if ($lance_minimo > $leilao['proposta']) {
                        $erro = "O Lance minimo para esse lote &eacute; de R$ $lance_minimo";
                        $retorno = false;
                    } else {
                        // INSERIR IMOVEIS_LANCEONLINE
                        $leilao['formaPagto'] = utf8_decode($leilao['formaPagto']);
                        $leilao['proposta'] = str_replace('R$ ', '', $leilao['proposta']);
                        $query = "INSERT INTO imoveis_lanceonline VALUES (NULL,'" . $user_id . "'," . $dados['user_id_shop'] . ",'" . $st_from . "', '" . $leilao['leilao_id'] . "','" . $leilao['lote'] . "','" . $leilao['proposta'] . "','" . date('Y-m-d') . "', '" . date('H:i:s') . "','','107','', '" . $leilao['formaPagto'] . "', '" . $plaqueta . "')";
                        $conn->query($query);
                        Transacao::commit();
                        $erro = "Lance adicionado com sucesso";
                        $retorno = true;
                    }
                } else {
                    $erro = "Lote n&atilde;o encontrado no sistema";
                    $retorno = false;
                }
            }
        }

        return array(
            'retorno' => $retorno,
            'msg' => utf8_encode($erro),
        );
    }
    public function insertLanceBradesco($lance_info, $leilao_id, $dados, $plaqueta)
    {
        global $conn;
        // Ip do usuÃÂ¡rio:
        $ip = $_SERVER["REMOTE_ADDR"];

        $lote_info = array(
            'leilao_id' => $lance_info['leilao_id'],
            'lote_id' => $lance_info['lote_id'],
        );

        $sslonline_lances = self::sslOnlineUltimosLancesSelectBradesco($lote_info);
        if (is_array($sslonline_lances)) {
            $sslonline_lances = self::sslOnlineUltimosLancesFormatBradesco($lote_info, $sslonline_lances);
        }

        /* status e tÃÂ©rmino do lote */
        $sslonline_statuslote = array(
            "st_lote" => $sslonline_lances[0]['st_lote'],
            "termino" => $sslonline_lances[0]['termino'],
            "incremento_minimo" => $sslonline_lances[0]["incremento_minimo"],
            'leilao_id' => $lance_info['leilao_id'],
            'lote_id' => $lance_info['lote_id'],
        );

        $sslonline_statuslote = self::sslonlineStatusloteFormat($sslonline_statuslote, '');
        self::sslonlineLanceExcessaoBradesco($lance_info, $sslonline_statuslote, $dados['user_id_shop'], $plaqueta, $dados['formaPagto']);
    }
    public function sslonlineLanceMax($lote_info)
    {
        global $conn;
        extract($lote_info);

        $query = "SELECT user_id, vl_lance, dt_lance FROM sslonline_lance WHERE lote_id = $lote_id ORDER BY vl_lance DESC,dt_lance DESC, sslonline_lance_id ASC LIMIT 1";

        $sslonline_lance = Transacao::getQueryArray($query);
        return ($sslonline_lance);
    }
    public function sslonlineLanceMin($lote_info)
    {
        global $conn;
        extract($lote_info);

        $query = "SELECT user_id,vl_lance, dt_lance FROM sslonline_lance WHERE lote_id=$lote_id ORDER BY vl_lance ASC,dt_lance DESC, sslonline_lance_id ASC LIMIT 1";
        // INDICE Lote_id leilao_id=$leilao_id

        $sslonline_lance = Transacao::getQueryArray($query);
        return ($sslonline_lance);
    }
    public function sslonlineLanceautoMax($lote_info, $LIMIT = 1)
    {
        global $conn;
        extract($lote_info);

        $query = "SELECT user_id, vl_lancef, vl_bonus, sslonline_lanceauto_id FROM sslonline_lanceauto WHERE lote_id = $lote_id ORDER BY vl_lancef DESC, dt_lanceauto ASC, sslonline_lanceauto_id ASC LIMIT $LIMIT";

        $sslonline_lanceauto = Transacao::getQueryArray($query);
        return ($sslonline_lanceauto);
    }
    public function sslonlineStatusloteSet($lote_info, $st_lote, $contra_oferta = false)
    {
        global $conn, $smarty;

        $ST_LOTE_ENCERRADO = 0;
        $ST_LOTE_ANDAMENTO = 1;
        $ST_LOTE_DOULHE_UMA = 2;
        $ST_LOTE_DOULHE_DUAS = 3;
        $ST_LOTE_PREGAO = 4;
        $ST_LOTE_VENDIDO = 5;
        $ST_LOTE_NAO_VENDIDO = 6;
        $ST_LOTE_CONDICIONAL = 7;
        $ST_LOTE_REPASSE = 8;
        $ST_LOTE_RETIRADO = 9;

        extract($lote_info);

        if ($leilao_id <= 0 || $lote_id <= 0) {
            return false;
        }

        $encerrado = "";
        $where_aux = " ";

        // Verifica se esta jogando lote para encerrado
        if ($st_lote == $ST_LOTE_VENDIDO || $st_lote == $ST_LOTE_NAO_VENDIDO || $st_lote == $ST_LOTE_CONDICIONAL) {
            $encerrado = ", encerrado = 1 ";
            if ($st_lote != $ST_LOTE_NAO_VENDIDO && !$contra_oferta) {
                $where_aux = " AND st_lote = 3";
            }

            if ($st_lote == $ST_LOTE_NAO_VENDIDO && $contra_oferta) {
                $encerrado = "";
            }
        }

        $query = 'SELECT st_lote FROM sslonline_status WHERE lote_id = ' . $lote_id;
        $retorno = Transacao::getQueryArray($query);
        $retorno = $retorno[0]['st_lote'];
        list($status_old) = $retorno;

        if (($status_old == $ST_LOTE_PREGAO) and ($st_lote == $ST_LOTE_VENDIDO or $st_lote == $ST_LOTE_CONDICIONAL)) {
            return false;
        }

        if ($status_old == $ST_LOTE_ANDAMENTO) {
            echo $query = 'SELECT au.email, au.nome, slei.tipo, DATE_FORMAT(slei.dt_leilao, "%d/%m/%Y") as dia, DATE_FORMAT(slei.dt_leilao, "%H:%i:%s") as horario, slei.leilao_id, ss.nu_lote FROM sslonline_lance sl
                    inner join apss_users au on au.id = sl.user_id and au.comprador_id <> 0
                    inner join sslonline_leilao slei on  slei.leilao_id = sl.leilao_id
                    inner join sslonline_status ss on  ss.lote_id = sl.lote_id
                    WHERE sl.lote_id = ' . $lote_id . ' GROUP by user_id';
            $usuarios_lance_lote = Transacao::getQueryArray($query);

            foreach ($usuarios_lance_lote as $mail_dados) {
                if ($mail_dados['tipo'] == 'M') {
                    $mail_dados['tipo'] = 'materiais';
                } else if ($mail_dados['tipo'] == 'I') {
                    $mail_dados['tipo'] = 'imoveis';
                } else if ($mail_dados['tipo'] == 'V') {
                    $mail_dados['tipo'] = 'veiculos';
                } else {
                    $mail_dados['tipo'] = 'bens';
                }

                $smarty->assign("mail_dados", $mail_dados);
                $mail['body'] = $smarty->fetch("email/mail_inicio_lote.html");
                $mail['subject'] = "[Sodre Santoro] - Lote iniciado";
                $mail['email'] = $mail_dados['email'];
                $mail['lote_id'] = $lote_id;
                $mail['leilao_id'] = $mail_dados['leilao_id'];
                $mail['aguardar_envio'] = 0;
                $mail['tipo_envio'] = 2;

                $query = "SELECT bn_enviocomprova FROM leilao WHERE leilao_id = " . $mail['leilao_id'];
                $retorno = Transacao::getQueryArray($query);
                $retorno = $retorno[0]['bn_enviocomprova'];
                list($is_comprova) = $retorno;

                $envia_comprova = 0;
                if ($mail['tipo_envio'] == 3) {
                    $envia_comprova = $is_comprova;
                } else {
                    $envia_comprova = 0;
                }

                unset($mail['leilao_id']);

                $mail['st_comprova'] = $envia_comprova;
                $mail['st_envio'] = 0;
                $mail['dt_acao'] = "NOW()";

                if ($is_comprova) {
                    self::insert("mail_cron", $mail);
                }
            }
        }

        // Zera status sql server caso esteja encerrado e seja colocado em preg???o
        if (($status_old == $ST_LOTE_VENDIDO || $status_old == $ST_LOTE_NAO_VENDIDO || $status_old == $ST_LOTE_CONDICIONAL) && ($st_lote == $ST_LOTE_PREGAO)) {
            if (Padrao::isProducao()) {
                $SqlServer = new SqlServer();
                $SqlServer->DesassociaLoteXComprador($lote_id);
                $conn->query("DELETE FROM mail_cron WHERE lote_id = $lote_id and aguardar_envio = 1");
            }
        }

        $query = "SELECT count(*) as total FROM sslonline_status WHERE lote_id_principal = $lote_id";
        $retorno = Transacao::getQueryArray($query);
        $count_lote_id = $retorno[0]['total'];

        if (($count_lote_id >= 1) and ($st_lote == $ST_LOTE_NAO_VENDIDO)) {
            $query = "UPDATE sslonline_status SET st_nao_visivel = 0 WHERE lote_id_principal = $lote_id";
            $conn->query($query);
        }

        // repasse no leilao interno
        $interno = Padrao::makeWhereNotIn();
        $leilao_interno_conf = explode(',', $interno);
        if (key_exists($leilao_id, $leilao_interno_conf)) {
            // robot repasse jogar lote em repasse para depois do ultimo lote
            if ($st_lote == $ST_LOTE_REPASSE) {
                // pegar o ultimo horario do leilao
                $query = "SELECT max(s.termino) as termino_repasse , l.nu_intervalolote as termino_intervalo from sslonline_status s inner join leilao  l on s.leilao_id = l.leilao_id where s.leilao_id = $leilao_id";
                $retorno = Transacao::getQueryArray($query);
                $termino_repasse = $retorno[0]['termino_repasse'];
                $termino_intervalo = $retorno[0]['termino_intervalo'];

                $termino_time_repasse = selft::timestampGetTime($termino_repasse);
                $termino_repasse = $termino_time_repasse + $termino_intervalo;
                $termino = date("YmdHis", $termino_repasse);
                $where_termino = ", termino = '$termino' ";
            } else {
                $where_termino = '';
            }
        }

        $query = "UPDATE sslonline_status SET st_lote=" . $st_lote . " $where_termino $encerrado WHERE lote_id= $lote_id $where_aux AND leilao_id=$leilao_id";
        $conn->query($query);

        // Log
        $ip = getenv("REMOTE_ADDR");
        $ip_lan = $_SERVER['HTTP_X_FORWARDED_FOR'];
        if ($ip_lan != null) {
            $ip .= " / $ip_lan";
        }

        global $user_id;

        if (Padrao::isProducao()) {
            $querylog = "INSERT santoro_bkp.logsslonline_status
            SELECT '', leilao_id, lote_id, $st_lote, termino_fake, termino, avisado_fim, resultado_atualizado, incremento_minimo, tipo_segmento, nu_lote, descricao, vl_lanceinicial, nm_unidade, vl_multiplo, mensagem,
            DT_InicioLeilaoOnline, Categ_ID, interesse, disputa, 'UPDATE', '$user_id', NOW(), '$ip', lote_id_principal, st_nao_visivel FROM sslonline_status WHERE lote_id = $lote_id AND leilao_id=$leilao_id";
            $conn->query($querylog);
        }

        if ($st_lote == $ST_LOTE_VENDIDO || $st_lote == $ST_LOTE_NAO_VENDIDO || $st_lote == $ST_LOTE_CONDICIONAL) {

            echo $query = "SELECT AP.id, case
                                    when SL.plaqueta_id <> 0 then P.comprador_id
                                else
                                    AP.comprador_id end as comprador_id,
                                case
                                    when SL.plaqueta_id <> 0 then  P.nm_comprador
                                else
                                    AP.nome end as nome, SL.vl_lance, AP.cpf, AP.telefone
            FROM sslonline_status ST
            INNER JOIN sslonline_lance SL on ST.lance_id = SL.sslonline_lance_id
            INNER JOIN apss_users AP on SL.user_id = AP.id
            LEFT  JOIN apss_users_plaquetas P on SL.plaqueta_id = P.id
            WHERE ST.lote_id = $lote_id";
            exit();
            $retorno = Transacao::getQueryArray($query);
            $retorno = $retorno[0];
            $retorno = array_values($retorno);
            list($User_ID_Vencedor, $comprador_id, $nm_comprador, $vl_lance, $cpf, $telefone) = $retorno;

            if (empty($comprador_id)) {
                $comprador_id = 'null';
            } else {
                // Envia email lote encerrado: Venda e Condicional
                // if ($st_lote==ST_LOTE_VENDIDO || $st_lote==ST_LOTE_CONDICIONAL){
                if ($st_lote == $ST_LOTE_VENDIDO) {
                    global $array_leilao_trt;
                    if (!in_array($leilao_id, $array_leilao_trt)) {
                        sendMailFimLote($User_ID_Vencedor, $lote_id, $vl_lance);
                    }
                }
            }

            switch ($st_lote) {
                case $ST_LOTE_VENDIDO:
                    $st_lote = 3;
                    break;
                case $ST_LOTE_NAO_VENDIDO:
                    $st_lote = 4;
                    break;
                case $ST_LOTE_CONDICIONAL:
                    $st_lote = 1;
                    break;
                case $ST_LOTE_REPASSE:
                    $st_lote = 0;
                    break;
            }

            if ($vl_lance == '') {
                $vl_lance = 0;
            }

            // inclui valor total multiplicado pelo qtde
            if ($lote_info['nu_qtde'] > 1) {
                $vl_lance = ($vl_lance * $lote_info['nu_qtde']);
            }

            if ($st_lote == 4) {
                $vl_lance = 0;
            }

            // Repasse
            if ($st_lote == 0) {
                $vl_lance = 'null';
                $comprador_id = 'null';
                $nm_comprador = '';
            }

            global $USUARIOSFISICO;

            if (array_key_exists($User_ID_Vencedor, $USUARIOSFISICO)) {
                $ST_CompradorOnline = $USUARIOSFISICO[$User_ID_Vencedor];
            } else {
                $ST_CompradorOnline = -1;
            }

            $nm_comprador = str_replace("\'", "", $nm_comprador);
            $syb_query = "EXEC spU_LotesSTLoteOnline @ST_Lote='$st_lote', @Comprador_ID=" . $comprador_id . ", @NM_Comprador='" . $nm_comprador . "', @Lote_ID=" . $lote_info['lote_id'] . ", @VL_Venda=" . $vl_lance . ",@ST_CompradorOnline=" . $ST_CompradorOnline . ",@Leilao_ID=" . $leilao_id;
            $log_syb_query = trim(mysql_real_escape_string($syb_query));
            $query_robot = "insert into robot_leilao (leilao_id,lote_id,comprador_id,log_proc,st_data,st_lote,status) values ('$leilao_id','$lote_id','$comprador_id','$log_syb_query',now(),'$st_lote',0)";
            $d->query($query_robot);

            // $syb = connect_sodre05();

            global $dbsets;
            if ($dbsets["database"] == "santoro") {
                $query_log = "INSERT santoro_bkp.logsslonline_sqlserver (lote_id, leilao_id, vl_lance, st_lote, comprador_id, nm_comprador, sql_retorno, data, descricao) VALUES ($lote_id, $leilao_id, $vl_lance, $st_lote,
                    $comprador_id, '$nm_comprador', '', NOW(), '$log_syb_query ')";

                $d->query($query_log);
            }
        }

        return true;
    }
    public function sslonlineFisiconlineAtualizaTermino($lote_info, $st_lote)
    {
        global $conn;

        $sslonline_leilao = self::sslonline_leilao_select_bradesco($lote_info['leilao_id']);

        $ST_LOTE_ENCERRADO = 0;
        $ST_LOTE_ANDAMENTO = 1;
        $ST_LOTE_DOULHE_UMA = 2;
        $ST_LOTE_DOULHE_DUAS = 3;
        $ST_LOTE_PREGAO = 4;
        $ST_LOTE_VENDIDO = 5;
        $ST_LOTE_NAO_VENDIDO = 6;
        $ST_LOTE_CONDICIONAL = 7;
        $ST_LOTE_REPASSE = 8;
        $ST_LOTE_RETIRADO = 9;

        extract($lote_info);

        if ($st_lote != $ST_LOTE_DOULHE_UMA && $st_lote != $ST_LOTE_DOULHE_DUAS && $st_lote != $ST_LOTE_PREGAO && $st_lote != $ST_LOTE_VENDIDO) {
            return;
        }

        $termino_time = time();
        $termino = date("YmdHis", $termino_time);

        $q = "UPDATE sslonline_status SET termino = '$termino' WHERE lote_id = $lote_id AND leilao_id = $leilao_id";
        $conn->query($q);

        if ($leilao_id != 5991) {
            $termino_lote_interval = $sslonline_leilao['termino_lote_interval'];

            $sqlserver = new SqlServer();
            $retorno = $sqlserver->bradescoSslonlineTermino($leilao_id, $termino_lote_interval);
        }

        return true;
    }
    public function sslonlineStatusloteSelect($lote_info)
    {
        global $conn;
        extract($lote_info);

        if ($leilao_id <= 0 || $lote_id <= 0) {
            return false;
        }

        $query = "SELECT st_lote, termino FROM sslonline_status WHERE lote_id = $lote_id ";
        // INDICE Lote_id leilao_id=$leilao_id

        $result = Transacao::getQueryArray($query);
        if (count($result) == 1) {
            $result = $result[0];
        }
        return ($result);
    }
    public function sslonlineFisiconlineStDoulheChk($lote_info)
    {
        global $conn;

        $ST_LOTE_ENCERRADO = 0;
        $ST_LOTE_ANDAMENTO = 1;
        $ST_LOTE_DOULHE_UMA = 2;
        $ST_LOTE_DOULHE_DUAS = 3;
        $ST_LOTE_PREGAO = 4;
        $ST_LOTE_VENDIDO = 5;
        $ST_LOTE_NAO_VENDIDO = 6;
        $ST_LOTE_CONDICIONAL = 7;
        $ST_LOTE_REPASSE = 8;
        $ST_LOTE_RETIRADO = 9;

        $sslonline_statuslote = self::sslonlineStatusloteSelect($lote_info);
        extract($sslonline_statuslote);

        if ($st_lote == $ST_LOTE_DOULHE_DUAS || $st_lote == $ST_LOTE_DOULHE_UMA || $st_lote == $ST_LOTE_REPASSE) {
            if (($lote_info['leilao_id'] == 6436) and ($st_lote == $ST_LOTE_REPASSE)) {
                // Envia email informando novo lance no lote em repasse
                $mail['body'] = "Novo lance no leilao";
                $mail['subject'] = "[Sodre Santoro] - Novo lance no leilao Artes";
                $mail['email'] = "ronald@ssol.com.br";
                $mail['lote_id'] = $lote_info['lote_id'];
                $mail['leilao_id'] = $lote_info['leilao_id'];
                $mail['aguardar_envio'] = 0;
                $mail['tipo_envio'] = 4;

                self::insertMailCron($mail);
            }

            self::sslonlineStatusloteSet($lote_info, $ST_LOTE_PREGAO);
            self::sslonlineFisiconlineAtualizaTermino($lote_info, $ST_LOTE_PREGAO);
        }

        return true;
    }
    public static function sslonlineStatusTempoSelect($leilao_id)
    {
        global $conn;

        if ($leilao_id <= 0) {
            return false;
        }

        $query = "SELECT st_lote, tempo FROM sslonline_status_tempo WHERE leilao_id = $leilao_id";
        $result = Transacao::getQueryArray($query);

        $sslonline_status_tempo = array();
        foreach ($result as $value) {
            $sslonline_status_tempo[$value['st_lote']] = $value['tempo'];
        }

        return ($sslonline_status_tempo);
    }
    public function sslonlineStatusDoulheChk($lote_info)
    {
        global $conn;
        extract($lote_info);

        $ST_LOTE_ENCERRADO = 0;
        $ST_LOTE_ANDAMENTO = 1;
        $ST_LOTE_DOULHE_UMA = 2;
        $ST_LOTE_DOULHE_DUAS = 3;
        $ST_LOTE_PREGAO = 4;
        $ST_LOTE_VENDIDO = 5;
        $ST_LOTE_NAO_VENDIDO = 6;
        $ST_LOTE_CONDICIONAL = 7;
        $ST_LOTE_REPASSE = 8;
        $ST_LOTE_RETIRADO = 9;

        $sslonline_leilao = self::sslonlineLeilaoSelectBradesco($leilao_id);

        $interno = Padrao::makeWhereNotIn();
        $leilao_interno_conf = explode(',', $interno);

        $sslonline_statuslote = self::sslonlineStatusloteSelect($lote_info);
        extract($sslonline_statuslote);

        if ($st_lote == $ST_LOTE_DOULHE_DUAS || $st_lote == $ST_LOTE_DOULHE_UMA || $st_lote == $ST_LOTE_PREGAO) {
            $termino_time = self::timestampGetTime($termino);
            $tempo_restante = $termino_time - time();
            $sslonline_status_tempo = self::sslonlineStatusTempoSelect($leilao_id);

            $query = "SELECT sl.tempo_atraso_lote, l.bn_enviocomprova, sl.bn_fechamentosequencial FROM sslonline_leilao sl inner join leilao l on l.leilao_id = sl.leilao_id WHERE sl.leilao_id = $leilao_id group by sl.leilao_id";
            $retorno = Transacao::getQueryArray($query);
            $tempo_atraso = $retorno[0]['tempo_atraso_lote'];
            $bn_enviocomprova = $retorno[0]['bn_enviocomprova'];
            $bn_fechamentosequencial = $retorno[0]['bn_fechamentosequencial'];

            if (key_exists($leilao_id, $leilao_interno_conf)) {
                $tempo_atraso = $leilao_interno_conf[$leilao_id]['intervalo_lote'];
            }

            $query = "SELECT nu_intervalolote from leilao where leilao_id = $leilao_id";
            $retorno = Transacao::getQueryArray($query);
            $nu_intervalolote = $retorno[0]['nu_intervalolote'];

            if ($nu_intervalolote == 0) {
                $tempo_atraso_s = 180 - $tempo_restante;
            } elseif ($nu_intervalolote < 180) {
                $tempo_atraso_s = ($tempo_atraso) - $tempo_restante;
            } else {
                $tempo_atraso_s = 180 - $tempo_restante;
            }

            $query = "SELECT termino, lote_id, st_lote FROM sslonline_status WHERE leilao_id = $leilao_id AND st_lote > 0";
            $result = Transacao::getQueryArray($query);

            $sslonline_status_finish = array(
                $ST_LOTE_VENDIDO,
                $ST_LOTE_ENCERRADO,
                $ST_LOTE_NAO_VENDIDO,
                $ST_LOTE_REPASSE,
                $ST_LOTE_CONDICIONAL,
                $ST_LOTE_RETIRADO,
            );
            $time_dt_leilao = self::timestampGetTime($sslonline_leilao['dt_leilao']);
            $termino_time = $time_dt_leilao;
            $time_dt_leilao_restante = $time_dt_leilao - time();

            foreach ($result as $row) {
                $lote_id = $row['lote_id'];
                $termino_time = self::timestampGetTime($row['termino']);

                // TJ com encerramento nao sequencial
                if ($bn_fechamentosequencial != 1) {
                    if ($lote_info['lote_id'] != $row['lote_id']) {
                        continue;
                    }
                }

                $termino_time = $termino_time + $tempo_atraso_s;
                $termino = date("YmdHis", $termino_time);
                $tempo_restante = $termino_time - time();
                $st_lote = 0;

                if (($tempo_restante > $sslonline_status_tempo[$ST_LOTE_DOULHE_UMA]) and !(in_array($row['st_lote'], $sslonline_status_finish))) {
                    $st_lote = $ST_LOTE_ANDAMENTO;
                } elseif (($tempo_atraso > $sslonline_status_tempo[$ST_LOTE_DOULHE_DUAS]) and !(in_array($row['st_lote'], $sslonline_status_finish))) {
                    $st_lote = $ST_LOTE_DOULHE_UMA;
                } elseif (!in_array($row['st_lote'], $sslonline_status_finish)) {
                    $st_lote = $ST_LOTE_DOULHE_DUAS;
                }

                $query = "UPDATE sslonline_status SET ";
                if ($st_lote > 0) {
                    $query .= " st_lote = $st_lote, ";
                }

                $query .= " termino = '$termino' WHERE (lote_id=$lote_id AND leilao_id=$leilao_id)";
                $conn->query($query);

                // Log
                if (Padrao::isProducao()) {
                    $ip = getenv("REMOTE_ADDR");
                    $querylog = "INSERT santoro_bkp.logsslonline_status
                        SELECT '', leilao_id, lote_id, st_lote, termino_fake, termino, avisado_fim, resultado_atualizado, incremento_minimo, tipo_segmento, nu_lote, descricao, vl_lanceinicial, nm_unidade, vl_multiplo, mensagem,
                        DT_InicioLeilaoOnline, Categ_ID, interesse, disputa, 'UPDATE_INICIAL', '$user_id', NOW(), '$ip', lote_id_principal, st_nao_visivel FROM sslonline_status WHERE lote_id = $lote_id AND leilao_id=$leilao_id";

                    $conn->query($querylog);
                }
            }
            self::sslonlineLeilaoAtualizaDtTermino($leilao_id);
        }
        return true;
    }
    public function sslonlineLeilaoAtualizaDtTermino($leilao_id)
    {
        global $conn;

        if ($leilao_id <= 0) {
            return false;
        }

        $query = "SELECT MAX(termino) as termino FROM sslonline_status WHERE leilao_id = $leilao_id";
        $retorno = Transacao::getQueryArray($query);
        $max_termino = $retorno[0]['termino'];

        if (strlen($max_termino)) {
            // $query = "UPDATE sslonline_leilao SET dt_termino = '$max_termino'";
            // $conn->query($query);

        }

        return true;
    }
    public function getLoteUser($leilao_id, $lote_id, $user_id)
    {
        global $conn;
        global $leilao_interno_conf;

        foreach ($leilao_interno_conf as $leilao_interno) {
            if ($leilao_interno['bloquear_lance'] == 1) {
                $leiloes_bloquear[] = $leilao_interno['leilao_id'];
            }
        }

        $bloquear_lance = 0;

        foreach ($leiloes_bloquear as $leilao_bloquear) {
            $query = "SELECT a.user_id, a.lote_id from sslonline_lance a inner join sslonline_status b On a.lote_id=b.lote_id and a.leilao_id=b.leilao_id inner join apss_users c On a.user_id=c.id
            where   a.leilao_id=" . $leilao_bloquear . " and a.vl_lance=( SELECT max(d.vl_lance) from sslonline_lance d where d.leilao_id=" . $leilao_bloquear . " and d.lote_id=a.lote_id group by d.lote_id )
            group by a.lote_id order by b.nu_lote";

            $rs = Transacao::getQueryArray($query);

            foreach ($rs as $row) {
                if (($row['user_id'] == $user_id) and ($row['lote_id'] != $lote_id)) {
                    $bloquear_lance = $bloquear_lance + 1;
                }
            }

            if ($bloquear_lance != 0) {
                break;
            }
        }

        return $bloquear_lance;
    }
    public function sslonlineLanceInsert($lote_info, $vl_lance, $user_id, $st_lote = '', $nm_identificacao = '', $formapagamento_id = 0, $forcar_lance_contra_oferta = false)
    {
        global $d;
        global $sslonline_leilao;
        global $leilao_interno_conf;
        $USUARIO_DE_AUDITORIO = Padrao::pegaOperadores('usuario_de_auditorio');

        $ST_LOTE_ENCERRADO = 0;
        $ST_LOTE_ANDAMENTO = 1;
        $ST_LOTE_DOULHE_UMA = 2;
        $ST_LOTE_DOULHE_DUAS = 3;
        $ST_LOTE_PREGAO = 4;
        $ST_LOTE_VENDIDO = 5;
        $ST_LOTE_NAO_VENDIDO = 6;
        $ST_LOTE_CONDICIONAL = 7;
        $ST_LOTE_REPASSE = 8;
        $ST_LOTE_RETIRADO = 9;

        extract($lote_info);
        if ($leilao_id <= 0 || $lote_id <= 0 || $user_id <= 0) {
            return 3;
        }

        if (!is_numeric($vl_lance) || $vl_lance <= 0) {
            return 3;
        }

        foreach ($leilao_interno_conf as $leilao_interno) {
            if ($leilao_interno['bloquear_lance'] == 1) {
                $leiloes_bloquear[] = $leilao_interno['leilao_id'];
            }
        }

        if (in_array($leilao_id, $leiloes_bloquear)) {
            $bloquear_lance = self::getLoteUser($leilao_id, $lote_id, $user_id);
            // Se o lote id que o usu???rio esta vencendo n???o for o atual, bloquear este lance do usu???rio
            if ($bloquear_lance > 0) {
                return 2;
            }
        }

        $ip = getenv("REMOTE_ADDR");
        $ip_lan = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : null;
        if ($ip_lan != null) {
            $ip .= " / $ip_lan";
        }

        if ($sslonline_leilao['st_leilaofisico'] == 1) {
            self::sslonlineFisiconlineStDoulheChk($lote_info);
        } else {
            self::sslonlineStatusDoulheChk($lote_info);
        }

        $envia_mail_superado = false;

        $query = "SELECT st_lote from sslonline_status where lote_id = " . $lote_id;
        $retorno = Transacao::getQueryArray($query);
        list($st_lote_verifica) = $retorno[0]['st_lote'];

        if (($st_lote_verifica == $ST_LOTE_CONDICIONAL && !$forcar_lance_contra_oferta) or $st_lote_verifica == $ST_LOTE_VENDIDO or $st_lote_verifica == $ST_LOTE_NAO_VENDIDO) {
            return 3;
        }

        if ($st_lote_verifica == $ST_LOTE_ANDAMENTO) {

            $query = "SELECT user_id, vl_lance FROM sslonline_lance WHERE lote_id = $lote_id and vl_lance = (select max(vl_lance) as vl_lance from sslonline_lance where lote_id = $lote_id)";
            $retorno = Transacao::getQueryArray($query);

            $usuario_superado = $retorno[0]['user_id'];
            $vl_lance_superado = $retorno[0]['vl_lance'];

            if (($usuario_superado > 0) and (!in_array($usuario_superado, $USUARIO_DE_AUDITORIO) and ($usuario_superado != $user_id))) {
                $envia_mail_superado = true;
            }
        }

        $result = self::insert("sslonline_lance", array(
            "lote_id" => (integer) $lote_id,
            "leilao_id" => (integer) $leilao_id,
            "user_id" => (integer) $user_id,
            "vl_lance" => (double) $vl_lance,
            "dt_lance" => date('Y-m-d H:i:s'),
            "ip" => $ip,
            "nm_identificacao" => $nm_identificacao,
            "formapagamento_id" => $formapagamento_id,
        ));

        if (strstr($result[1], 'Duplicate')) {
            return 3;
        }

        if ($envia_mail_superado) {
            self::sendMailUsuarioSuperado($usuario_superado, $lote_id, $vl_lance_superado);
        }

        if (Padrao::isProducao()) {
            $resultlog = self::insert("santoro_bkp.logsslonline_lance", array(
                "lote_id" => (integer) $lote_id,
                "leilao_id" => (integer) $leilao_id,
                "user_id" => (integer) $user_id,
                "vl_lance" => (double) $vl_lance,
                "st_lance" => $st_lote,
                "dt_lance" => "NOW()",
                "ip" => $ip,
                "dt_acao" => "NOW()",
                "acao" => "INSERT",
                "responsavel" => (integer) $user_id,
                "nm_identificacao" => $nm_identificacao,
                "formapagamento_id" => $formapagamento_id,
            ));
        }

        return 1;
    }
    public function sendMailUsuarioSuperado()
    {
        global $d, $smarty;

        $mail_dados = array();

        $query = "SELECT nome, email from apss_users where id = $usuario_superado";
        $retorno = Transacao::getQueryArray($query);
        $mail_dados['nome'] = $retorno[0]['nome'];
        $mail_dados['email'] = $retorno[0]['email'];

        $query = "SELECT ss.nu_lote, ss.descricao, sl.dt_leilao, sl.nm_leilao, sl.leilao_id from sslonline_status ss inner join sslonline_leilao sl on sl.leilao_id = ss.leilao_id where ss.lote_id = $lote_id";
        $retorno = Transacao::getQueryArray($query);
        $mail_dados['nu_lote'] = $retorno[0]['nu_lote'];
        $mail_dados['descricao'] = $retorno[0]['descricao'];
        $mail_dados['dt_leilao'] = $retorno[0]['dt_leilao'];
        $mail_dados['nm_leilao'] = $retorno[0]['nm_leilao'];
        $mail_dados['leilao_id'] = $retorno[0]['leilao_id'];

        $data_leilao = strtotime($mail_dados['dt_leilao']);
        $mail_dados['dt_leilao'] = date("d/m/Y H:i", $data_leilao);
        $mail_dados['vl_lance_superado'] = number_format($vl_lance_superado, 2, ',', '.');

        $smarty->assign("mail_dados", $mail_dados);
        $mail['body'] = $smarty->fetch("sslonline/mail_superado.html");
        $mail['subject'] = "[Sodre Santoro] - Seu lance foi superado";
        $mail['email'] = $mail_dados['email'];
        $mail['lote_id'] = $lote_id;
        $mail['leilao_id'] = $mail_dados['leilao_id'];
        $mail['aguardar_envio'] = 0;
        $mail['tipo_envio'] = 1;

        self::insertMailCron($mail);
    }
    public function insertMailCron($dados)
    {
        global $conn;

        /*
         * tipo_envio 1 = Email superado 2 = Email de lote iniciado 3 = Email de lote encerrado 4 = Log Marcio alteraÃÂ§ÃÂ£o valores lote
         */

        $query = "SELECT bn_enviocomprova FROM leilao WHERE leilao_id = " . $dados['leilao_id'];
        $retorno = Transacao::getQueryArray($query);
        $retorno = $retorno[0]['bn_enviocomprova'];
        list($is_comprova) = $retorno;

        $envia_comprova = 0;
        if ($dados['tipo_envio'] == 3) {
            $envia_comprova = $is_comprova;
        } else {
            $envia_comprova = 0;
        }

        unset($dados['leilao_id']);

        $dados['st_comprova'] = $envia_comprova;
        $dados['st_envio'] = 0;
        $dados['dt_acao'] = "NOW()";

        if ($is_comprova) {
            $result = self::insert("mail_cron", $dados);
        } else if ($dados['tipo_envio'] == 4) {
            $result = self::insert("mail_cron", $dados);
        }
    }
    public function sslonlineLanceautoChkInverso($lote_info, $vl_lance, $user_id = 0, $incremento_minimo = INCREMENTO_MINIMO)
    {
        global $conn;

        extract($lote_info);
        $query = "SELECT user_id, MIN(vl_lancef) as vl_lancef FROM sslonline_lanceauto WHERE lote_id=$lote_id AND leilao_id=$leilao_id GROUP BY user_id HAVING MIN(vl_lancef) <= $vl_lance ORDER BY vl_lancef ASC, dt_lanceauto LIMIT 2";

        $sslonline_lanceauto = Transacao::getQueryArray($query);

        $lanceauto_count = count($sslonline_lanceauto);
        if ($lanceauto_count == 0) {
            return (false);
        }

        $lanceauto_user_id = $sslonline_lanceauto[0]["user_id"];
        if ($user_id > 0) {
            if ($user_id == $lanceauto_user_id) {
                return (false);
            }
        }

        $vl_lancef = $sslonline_lanceauto[0]["vl_lancef"]; // Valor minimo cadastrado no lance auto
        $vl_lanceauto = $vl_lance; // valor do lance auto

        if ($lanceauto_count == 2) {
            $vl_lancef_1 = $sslonline_lanceauto[1]["vl_lancef"];
            if ($vl_lancef == $vl_lancef_1) {
                $vl_lanceauto = $sslonline_lanceauto[0]["vl_lancef"] - $lanceauto_incremento;
            } else {
                $vl_lanceauto = $sslonline_lanceauto[1]["vl_lancef"];

                sslonline_lance_insert($lote_info, $sslonline_lanceauto[1]["vl_lancef"], $sslonline_lanceauto[1]['user_id']);
            }
        }

        $vl_lanceauto = $vl_lanceauto - $incremento_minimo;

        if ($vl_lanceauto < $vl_lancef) {
            $vl_lanceauto = $vl_lancef;
        }

        self::sslonlineLanceInsert($lote_info, $vl_lanceauto, $lanceauto_user_id);

        return (true);
    }
    public function sslonlineLanceautoChk($lote_info, $vl_lance, $user_id = 0, $incremento_minimo = INCREMENTO_MINIMO, $vl_lanceinicialf = '', $nu_user_lance = 0)
    {
        global $conn;

        extract($lote_info);

        if (!$vl_lance) {
            $vl_lance = 0;
        }

        $query = "SELECT  sl.user_id, vl_lancef, sl.vl_bonus, sl.formapagamento_id FROM sslonline_lanceauto sl WHERE sl.lote_id = $lote_id and sl.vl_lancef > $vl_lance GROUP BY sl.user_id ORDER BY sl.vl_lancef DESC, sl.sslonline_lanceauto_id asc limit 2";

        $sslonline_lanceauto = Transacao::getQueryArray($query);

        // Verifica se o maior lance realmente ??? o do usu???rio vencedor, n???o enviar lance real nesse caso
        if ($user_id > 0 && count($sslonline_lanceauto) > 0) {
            if ($user_id == $sslonline_lanceauto[0]["user_id"]) {
                return (false);
            }
        }

        // Verifica quantos lances autom???ticos existem para o lote
        if (count($sslonline_lanceauto) == 2) {
            // Mais de um autom???tico maior que o lance atual
            // Maior lance auto
            $user_id_maior = $sslonline_lanceauto[0]["user_id"];
            $vl_lanceauto_maior = $sslonline_lanceauto[0]["vl_lancef"];
            $vl_bonus_maior = $sslonline_lanceauto[0]["vl_bonus"];
            $formapagamento_id_maior = $sslonline_lanceauto[0]["formapagamento_id"];

            // Verifica se existe bonus cadastrado
            if ($vl_bonus_maior != 0) {
                if ($incremento_minimo < $vl_bonus_maior) {
                    $vl_bonus_maior = $incremento_minimo;
                }
                $vl_lanceauto_maior = $vl_lanceauto_maior + $vl_bonus_maior;
            }

            // 2??? lance auto
            $user_id_menor = $sslonline_lanceauto[1]["user_id"];
            $vl_lanceauto_menor = $sslonline_lanceauto[1]["vl_lancef"];
            $vl_bonus_menor = $sslonline_lanceauto[1]["vl_bonus"];
            $formapagamento_id_menor = $sslonline_lanceauto[1]["formapagamento_id"];

            if ($vl_bonus_menor != 0) {
                if ($incremento_minimo < $vl_bonus_menor) {
                    $vl_bonus_menor = $incremento_minimo;
                }
                $vl_lanceauto_menor = $vl_lanceauto_menor + $vl_bonus_menor;
            }

            // Caso valor seja igual ao cadastrado, implementar regra de um incremento a menos pro 2??? lance auto
            if ($vl_lanceauto_maior == $vl_lanceauto_menor) {
                $vl_lance_enviar = $vl_lanceauto_menor - $incremento_minimo;
                // Verifica se lance ??? menor que o atual, ent???o nao enviar o 2??? melhor lance auto
                if ($vl_lance_enviar > $vl_lance) {
                    self::sslonlineLanceInsert($lote_info, $vl_lance_enviar, $user_id_menor, '', '', $formapagamento_id_menor);
                }

                // Envia o autom???tico do maior lance
                $vl_lance_enviar = $vl_lanceauto_maior;
                self::sslonlineLanceInsert($lote_info, $vl_lance_enviar, $user_id_maior, '', '', $formapagamento_id_maior);
            } else {
                // Maior lance real do lote
                $vl_lance_enviar = $vl_lance;

                // Rodar regra enquanto maior lance real do lote for menor que o 2??? lance auto cadastrado
                while ($vl_lance_enviar < $vl_lanceauto_menor) {
                    // Pr???ximo lance do automatico j??? registrado
                    $vl_lance_enviar = $vl_lance_enviar + $incremento_minimo;

                    if ($vl_lance_enviar <= $vl_lanceauto_menor) {
                        $ultimo_user_insert = $user_id_menor;
                        self::sslonlineLanceInsert($lote_info, $vl_lance_enviar, $user_id_menor, '', '', $formapagamento_id_menor);
                    }

                    // Pr???ximo lance do atual automatico
                    $vl_lance_enviar = $vl_lance_enviar + $incremento_minimo;
                    if ($vl_lance_enviar <= $vl_lanceauto_maior) {
                        $ultimo_user_insert = $user_id_maior;
                        self::sslonlineLanceInsert($lote_info, $vl_lance_enviar, $user_id_maior, '', '', $formapagamento_id_maior);
                    }
                }

                $vl_lance_enviar = $vl_lance_enviar + $incremento_minimo;
                if ($ultimo_user_insert != $user_id_maior) {
                    if ($vl_lance_enviar <= $vl_lanceauto_maior) {
                        self::sslonlineLanceInsert($lote_info, $vl_lance_enviar, $user_id_maior, '', '', $formapagamento_id_maior);
                    } else {
                        return false;
                    }
                }

                return (true);
            }
        } elseif (count($sslonline_lanceauto) != 0) {

            // Apenas um lance autom???tico maior que o lance atual
            $lanceauto_user_id = $sslonline_lanceauto[0]["user_id"];

            $vl_lancef = $sslonline_lanceauto[0]["vl_lancef"];
            $vl_lanceauto = $vl_lance;

            $vl_lanceauto = $vl_lanceauto + $incremento_minimo;

            if ($vl_lanceinicialf > $vl_lanceauto) {
                $vl_lanceauto = $vl_lanceinicialf;
            }

            if ($vl_lanceauto > $vl_lancef) {
                $vl_lanceauto = $vl_lancef;
            }

            $formapagamento_id = $sslonline_lanceauto[0]["formapagamento_id"];

            // Verifica qual maior lance cadastrado
            $query = "SELECT  user_id, vl_lance
                            FROM sslonline_lance
                           WHERE lote_id = $lote_id
                           ORDER BY vl_lance desc
                           LIMIT 1";

            list($user_id_maior_lance_aux, $vl_lance_maior_lance_aux) = $d->sselect($query);

            $user_id_maior_lance = 0;
            if ($user_id_maior_lance_aux > 0) {
                $user_id_maior_lance = $user_id_maior_lance_aux;
            }

            $vl_lance_maior_lance = 0;
            if ($vl_lance_maior_lance_aux > 0) {
                $vl_lance_maior_lance = $vl_lance_maior_lance_aux;
            }

            // Bonus
            if ($vl_lanceauto == $vl_lance && ($user_id != $lanceauto_user_id) && ($user_id_maior_lance != $lanceauto_user_id)) {
                if ($sslonline_lanceauto[0]["vl_bonus"] != 0) {
                    $vl_bonus = $sslonline_lanceauto[0]["vl_bonus"];
                    if ($incremento_minimo < $vl_bonus) {
                        $vl_bonus = $incremento_minimo;
                    }
                    // insere o lance do usuario atual e depois supera com o bonus
                    sslonline_lance_insert($lote_info, $vl_lance, $user_id, '', '', $formapagamento_id);
                    $vl_lanceauto = $vl_lanceauto + $vl_bonus;
                } else {
                    // insere apenas o valor do lance
                    $vl_lanceauto = $vl_lance;
                }
            }

            // Insere lance
            if (($nu_user_lance == 0) and ($vl_lanceauto < $vl_lanceinicialf)) {
                return false;
            } else if ($user_id_maior_lance == $lanceauto_user_id) {
                if ($vl_lanceinicialf > $vl_lance_maior_lance) {
                    sslonline_lance_insert($lote_info, $vl_lanceinicialf, $lanceauto_user_id, '', '', $formapagamento_id);
                }

                /*
                 * else if ($vl_lance == $vl_lanceauto) sslonline_lance_insert($lote_info,$vl_lanceauto,$lanceauto_user_id);
                 */
                // LANCE_AUTO_FIX
                return true;
            } else {
                sslonline_lance_insert($lote_info, $vl_lanceauto, $lanceauto_user_id, '', '', $formapagamento_id);
            }

            return (true);
        } else {
            return false;
        }
    }
    public function sslonlineLanceExcessaoBradesco($lance_info, $sslonline_statuslote, $user_id, $nm_identificacao = "", $formapagamento_id = 0)
    {
        global $leilao_interno_conf;

        $interno = Padrao::makeWhereNotIn();
        $leilao_interno_conf = explode(',', $interno);
        $message = array();
        $leilao_inverso = false;

        $lance_insert = true;
        $lanceauto_chk = true;

        extract($lance_info);
        if ($leilao_id <= 0 || $lote_id <= 0 || $user_id <= 0) {
            return false;
        }

        $lote_info = array(
            "lote_id" => $lote_id,
            "leilao_id" => $leilao_id,
        );

        $first_vl_lance = 0;

        // formata casas decimais
        $vl_lance = str_replace('R$', '.', $vl_lance);
        $vl_lance = trim($vl_lance);
        $vl_lance = str_replace('.', '', $vl_lance);
        $vl_lance = str_replace(',', '.', $vl_lance);

        if (!is_numeric($vl_lance) /* || !is_int($vl_lance)*/) {
            $message[] = "Lance Inv&aacute;lido. Digite apenas n&uacute;meros.";
            return (false);
        }

        if ($vl_lance <= 0) {
            $message[] = "Lance Inv&aacute;lido. O valor do lance n&atilde;o pode ser vazio.";
            return (false);
        }

        $nu = self::sslonlineLanceNumBradesco($lance_info);

        /* checar incremento minimo */
        if ($nu > 0) {
            if ($leilao_inverso) {
                $lance_max = self::sslonlineLanceMin($lote_info);
            } else {
                $lance_max = self::sslonlineLanceMax($lote_info);
            }

            $vl_lanceatual = $lance_max[0]['vl_lance'];
            $check_incremento_minimo = true;
        } else {
            $vl_lanceatual = $first_vl_lance;
            if ($vl_lanceatual == 0) {
                $check_incremento_minimo = true;
            } else {
                $check_incremento_minimo = false;
            }
        }

        $alterar_incremento = false;

        $DEPOSITOS_DE_AUDITORIO = array(
            3 => 25702,
            8 => 25703,
            10 => 25704,
            12 => 25705,
            16 => 25706,
            17 => 25707,
            18 => 27701,
            18 => 130154,
            20 => 48487,
            19 => 57265,
            26 => 97663,
            21 => 112072,
        );
        $USUARIOS_DUTRA = array(
            25702,
            131642,
            44967,
            44968,
            45068,
            45069,
            50652,
            50653,
            50654,
            107145,
            107146,
            107147,
            107148,
            107149,
            107150,
            107151,
            113394,
            116201,
            123616,
            130185,
            130186,
            130187,
            130188,
            130189,
            130190,
            130756,
            135360,
            135361,
        );
        $USUARIOS_RIBEIRAO = array(
            25703,
            120275,
            123614,
        );
        $USUARIOS_CAMPINAS = array(
            123615,
        );
        $USUARIOS_BAURU = array(
            123617,
        );
        $USUARIO_ADMINISTRADOR = array(
            131642,
        );
        $USUARIOS_MARANHAO = array(
            135360,
            135361,
            25707,
        );
        $sslonline_leilao = self::sslonlineLeilaoSelectBradesco($leilao_id);

        if ($check_incremento_minimo) {
            // if ($leilao_inverso){
            // $l_diff = sprintf ("%.2f", ($vl_lanceatual - $vl_lance));
            // }else{
            $l_diff = sprintf("%.2f", ($vl_lance - $vl_lanceatual));
            // }

            if ($l_diff < $sslonline_statuslote["incremento_minimo"]) {

                $user_id_deposito = $DEPOSITOS_DE_AUDITORIO[$sslonline_leilao['deposito_id']];

                // Usuarios da dutra
                if ($sslonline_leilao['deposito_id'] == 3 and in_array($user_id, $USUARIOS_DUTRA)) {
                    $user_id_deposito = $user_id;
                }

                // Usuarios da ribeirao
                if ($sslonline_leilao['deposito_id'] == 8 and in_array($user_id, $USUARIOS_RIBEIRAO)) {
                    $user_id_deposito = $user_id;
                }
                // UsuÃÂ¡rios de Campina
                if ($sslonline_leilao['deposito_id'] == 10 and in_array($user_id, $USUARIOS_CAMPINAS)) {
                    $user_id_deposito = $user_id;
                }

                // UsuÃÂ¡rios de Bauru
                if ($sslonline_leilao['deposito_id'] == 16 and in_array($user_id, $USUARIOS_CAMPINAS)) {
                    $user_id_deposito = $user_id;
                }

                // UsuÃÂ¡rios de MaranhÃÂ£o
                if ($sslonline_leilao['deposito_id'] == 17 and in_array($user_id, $USUARIOS_MARANHAO)) {
                    $user_id_deposito = $user_id;
                }

                $params['bradesco'] = 1;
                $relatorio = new Relatorio($params);
                $auxBradesco = $relatorio->getLeiloesEmpresa('bradesco');

                $array_leilao_bradesco = array();
                foreach ($auxBradesco as $item) {
                    $array_leilao_bradesco[$item['leilao_id']] = $item['leilao_id'];
                }

                if (!in_array($leilao_id, $array_leilao_bradesco)) {
                    // NÃÂ£o considerar multiplo para enviar lance via fax leilÃÂ£o do bradesco
                    if ($user_id_deposito == $user_id) {
                        $alterar_incremento = true;
                    } else {
                        $message[] = "O lance enviado est&aacute; abaixo do incremento m&iacute;nimo do lote.";
                        $message[] = "O incremento m&iacute;nimo do lote &eacute; de R$ " . number_format($sslonline_statuslote["incremento_minimo"], 2, ',', '.');
                        return false;
                    }
                }
            }
        }

        /* checar se temos um lance automÃÂ¡tico com o mesmo valor */
        $sslonline_lanceauto = self::sslonlineLanceautoMax($lance_info);

        $insere_bonus = false;

        if (!empty($sslonline_lanceauto)) {
            $sslonline_lanceauto = $sslonline_lanceauto[0];
            if ($sslonline_lanceauto["user_id"] != $user_id && $vl_lance == $sslonline_lanceauto["vl_lancef"]) {
                // $lauto_chk = true; // LANCE_AUTO_FIX
                // $lanceauto_chk = true; // LANCE_AUTO_FIX
                // $lance_insert = false; // LANCE_AUTO_FIX
                $lauto_chk = false;
                $lanceauto_chk = false;

                if ($sslonline_lanceauto["vl_bonus"] > 0) {
                    $insere_bonus = true;
                }
            }
        }

        $lance_inserido = false;

        if ($lance_insert) {

            // LeilÃÂ£o Interno
            // Bloquear Lance
            // NÃÂ£o aceitar lance se vencedor ja estiver arrematando outro lote - LeilÃÂ£o interno
            foreach ($leilao_interno_conf as $leilao_interno) {
                if ($leilao_interno['bloquear_lance'] == 1) {
                    $leiloes_bloquear[] = $leilao_interno['leilao_id'];
                }
            }

            if (in_array($leilao_id, $leiloes_bloquear)) {
                $inseriu = self::sslonlineLanceInsert($lote_info, $vl_lance, $user_id, $sslonline_statuslote['st_lote'], $nm_identificacao, $formapagamento_id);
                if ($inseriu == 2) {
                    $message[] = "Lance n&atilde;o foi aceito, j&aacute; existe um lote com seu lance vencendo!.";
                    return true;
                } else if ($inseriu == 3) {
                    $message[] = "Lance n&atilde;o foi aceito, favor tentar novamente.";
                    return false;
                }
            } else {
                $inseriu = self::sslonlineLanceInsert($lote_info, $vl_lance, $user_id, $sslonline_statuslote['st_lote'], $nm_identificacao, $formapagamento_id);
                // print_pr($inseriu);
                if ($inseriu == 3) {
                    $message[] = "Lance n&atilde;o foi aceito, favor tentar novamente.";
                    return false;
                }
            }

            $lance_inserido = true;

            if ($insere_bonus) {
                $incremento_bonus = $sslonline_lanceauto["vl_bonus"];
                if ($sslonline_lanceauto["vl_bonus"] > $sslonline_statuslote["incremento_minimo"]) {
                    $incremento_bonus = $sslonline_statuslote["incremento_minimo"];
                }

                $sslonline_lanceauto["vl_lancef"] = $sslonline_lanceauto["vl_lancef"] + $incremento_bonus;
                self::sslonlineLanceInsert($lote_info, $sslonline_lanceauto["vl_lancef"], $sslonline_lanceauto["user_id"], $sslonline_statuslote['st_lote'], '', $formapagamento_id);
                $lauto_chk = true;
            }
        }

        if ($lanceauto_chk) {
            $incremento_minimo = $sslonline_statuslote["incremento_minimo"];
            if ($leilao_inverso) {
                $lauto_chk = self::sslonlineLanceautoChkInverso($lote_info, $vl_lance, $user_id, $incremento_minimo);
            } else {
                $lauto_chk = self::sslonlineLanceautoChk($lote_info, $vl_lance, $user_id, $incremento_minimo);
            }

            // $lance_inserido = true; //LANCE_AUTO_FIX
        }

        if ($lance_inserido) {
            if ($lauto_chk) {
                if ($leilao_inverso) {
                    $message[] = "Recebemos seu lance, mas j&aacute; havia um lance autom&aacute;tico cadastrado anteriormente por outro usu&aacute;rio com um valor menor. Se desejar, envie um novo lance.";
                } else {
                    $message[] = "Recebemos seu lance, mas j&aacute; havia um lance autom&aacute;tico cadastrado anteriormente por outro usu&aacute;rio com um valor maior. Se desejar, envie um novo lance.";
                }
            }
        }

        return true;
    }
    public function sslonlineStLoteColor($st_lote)
    {
        $ST_LOTE_ENCERRADO = 0;
        $ST_LOTE_ANDAMENTO = 1;
        $ST_LOTE_DOULHE_UMA = 2;
        $ST_LOTE_DOULHE_DUAS = 3;
        $ST_LOTE_PREGAO = 4;
        $ST_LOTE_VENDIDO = 5;
        $ST_LOTE_NAO_VENDIDO = 6;
        $ST_LOTE_CONDICIONAL = 7;
        $ST_LOTE_REPASSE = 8;
        $ST_LOTE_RETIRADO = 9;
        $c = '';
        switch ($st_lote) {
            case $ST_LOTE_DOULHE_UMA:
                $c = '#FADF00';
                break;

            case $ST_LOTE_DOULHE_DUAS:
                $c = '#FF9A00';
                break;

            case $ST_LOTE_PREGAO:
                $c = '#12AD2B';
                break;

            case $ST_LOTE_ENCERRADO:
            case $ST_LOTE_VENDIDO:
                $c = '#0099FF';
                break;

            case $ST_LOTE_NAO_VENDIDO:
                $c = '#FC3D32';
                break;

            case $ST_LOTE_CONDICIONAL:
            case $ST_LOTE_RETIRADO:
                $c = '#BABBBC';
                break;

            case $ST_LOTE_ANDAMENTO:
                $c = '#FFFFFF';
                break;

            case $ST_LOTE_REPASSE:
                $c = '#9A96DE';
                break;
        }

        return ($c);
    }
    public function sslonlineStatusloteColor($status_id, $status_n)
    {
        $ST_LOTE_ENCERRADO = 0;
        $ST_LOTE_ANDAMENTO = 1;
        $ST_LOTE_DOULHE_UMA = 2;
        $ST_LOTE_DOULHE_DUAS = 3;
        $ST_LOTE_PREGAO = 4;
        $ST_LOTE_VENDIDO = 5;
        $ST_LOTE_NAO_VENDIDO = 6;
        $ST_LOTE_CONDICIONAL = 7;
        $ST_LOTE_REPASSE = 8;
        $ST_LOTE_RETIRADO = 9;

        switch ($status_id) {
            case 1:
            /* Vendido - Vermelho */
                $color = self::sslonlineStLoteColor($ST_LOTE_VENDIDO);
                break;

            case 2:
            /* Encerrado - Vermelho */
                $color = self::sslonlineStLoteColor($ST_LOTE_ENCERRADO);
                break;

            case 3:
            /* Em Andamento - Azul*/
                $color = "#0064B0";
                break;

            case 4:
            /* Superado - Laranja */
                $color = "#FF6600";
                break;

            case 5:
            /* Vencendo - Verde */
                $color = "#035803";
                break;

            case 6:
            /* em pregao */
                $color = self::sslonlineStLoteColor($ST_LOTE_PREGAO);
                break;

            case 7:
            /* doulhe 1 */
                $color = self::sslonlineStLoteColor($ST_LOTE_DOULHE_UMA);
                break;

            case 8:
            /* doulhe 2 */
                $color = self::sslonlineStLoteColor($ST_LOTE_DOULHE_DUAS);
                break;

            case 9:
            /* Repasse */
                $color = self::sslonlineStLoteColor($ST_LOTE_REPASSE);
                break;
            default:
                $color = "#000";
                break;
        }

        $fstatus = '<font color="' . $color . '">' . $status_n . '</font>';
        return ($fstatus);
    }
    public function timestampGetTime($timestamp)
    {
        if (empty($timestamp)) {
            return false;
        }

        if (strlen($timestamp) == 14) {
            $y = substr($timestamp, 0, 4);
            $m = substr($timestamp, 4, 2);
            $d = substr($timestamp, 6, 2);
            $h = substr($timestamp, 8, 2);
            $i = substr($timestamp, 10, 2);
            $s = substr($timestamp, 12, 2);
        } elseif (strlen($timestamp) == 19) {
            $y = substr($timestamp, 0, 4);
            $m = substr($timestamp, 5, 2);
            $d = substr($timestamp, 8, 2);
            $h = substr($timestamp, 11, 2);
            $i = substr($timestamp, 14, 2);
            $s = substr($timestamp, 17, 2);
        } else {
            return false;
        }

        return (mktime($h, $i, $s, $m, $d, $y));
    }
    public function formatSeconds($seconds)
    {
        if ($seconds <= 0) {
            return false;
        }

        $t = $seconds;

        $dias = intval($t / (60 * 60 * 24));
        $t %= (60 * 60 * 24);

        $horas = intval($t / (60 * 60));
        $t %= (60 * 60);

        $minutos = intval($t / 60);
        $t %= 60;

        $segundos = $t;

        if ($dias > 0) {
            return (sprintf("%2d dias", $dias));
        }

        return (sprintf("%02d:%02d:%02d", $horas, $minutos, $segundos));
    }
    public function sslonlineStatusloteFormat($sslonline_statuslote, $user_id = 0, $lance_vencedor = array(), $lote_info = array(), $finalizar = 'N')
    {
        extract($sslonline_statuslote);

        $ST_LOTE_ENCERRADO = 0;
        $ST_LOTE_ANDAMENTO = 1;
        $ST_LOTE_DOULHE_UMA = 2;
        $ST_LOTE_DOULHE_DUAS = 3;
        $ST_LOTE_PREGAO = 4;
        $ST_LOTE_VENDIDO = 5;
        $ST_LOTE_NAO_VENDIDO = 6;
        $ST_LOTE_CONDICIONAL = 7;
        $ST_LOTE_REPASSE = 8;
        $ST_LOTE_RETIRADO = 9;

        $status_encerrado_lote = array(
            $ST_LOTE_VENDIDO,
            $ST_LOTE_ENCERRADO,
            $ST_LOTE_NAO_VENDIDO,
            $ST_LOTE_CONDICIONAL,
            $ST_LOTE_RETIRADO,
        );

        $sslonline_leilao = self::sslonlineLeilaoSelectBradesco($leilao_id);

        if ($sslonline_leilao['st_leilaofisico'] == 1) {
            if ($st_lote == $ST_LOTE_PREGAO) {
                $termino = self::sslonlineStatusloteColor(6, "Em Preg&atilde;o");
            } elseif ($st_lote == $ST_LOTE_DOULHE_UMA) {
                $termino = self::sslonlineStatusloteColor(7, "Dou-lhe Uma");
            } elseif ($st_lote == $ST_LOTE_DOULHE_DUAS) {
                $termino = self::sslonlineStatusloteColor(8, "Dou-lhe Duas");
            } elseif ($st_lote == $ST_LOTE_REPASSE) {
                $termino = self::sslonlineStatusloteColor(9, "Repasse");
            } elseif ($st_lote == $ST_LOTE_RETIRADO) {
                $termino = self::sslonlineStatusloteColor(10, "Retirado");
            } elseif ($st_lote == $ST_LOTE_CONDICIONAL) {
                $termino = self::sslonlineStatusloteColor(0, "Condicional");
            } elseif ($st_lote == $ST_LOTE_NAO_VENDIDO) {
                $termino = self::sslonlineStatusloteColor(0, "N&atilde;o Vendido");
            } elseif ($st_lote == $ST_LOTE_VENDIDO) {
                $termino = self::sslonlineStatusloteColor(0, "Vendido");
            }
            $sslonline_statuslote['tempo_restante'] = $termino;
        } else {
            $termino_time = self::timestampGetTime($termino);

            $sslonline_statuslote['tempo_restante'] = self::formatSeconds(($termino_time - time()));
            $sslonline_statuslote['tempo_restante_r'] = date('Y_m_d_H_i_s', $termino_time);
        }

        $VENDIDO = 1;
        $STATUS[$VENDIDO] = "Vencedor";

        $ENCERRADO = 2;
        $STATUS[$ENCERRADO] = "Encerrado";

        $ANDAMENTO = 3;
        $STATUS[$ANDAMENTO] = "Em Andamento";

        $SUPERADO = 4;
        $STATUS[$SUPERADO] = "Superado";

        $VENCENDO = 5;
        $STATUS[$VENCENDO] = "Vencendo";

        $NAO_VENDIDO = 11;
        $STATUS[$NAO_VENDIDO] = "Nao Vendido";

        $CONDICIONAL = 0;
        $STATUS[$CONDICIONAL] = "Vencedor";

        $RETIRADO = 10;
        $STATUS[$RETIRADO] = "<b>Retirado</b>";

        if ($user_id > 0) {
            global $d;

            extract($lote_info);
            if ($leilao_id <= 0 || $lote_id <= 0) {
                return (self::sslonlinestatusloteformat($sslonline_statuslote));
            }

            if ($st_lote == $ST_LOTE_ENCERRADO || $st_lote == $ST_LOTE_NAO_VENDIDO || $st_lote == $ST_LOTE_VENDIDO || $st_lote == $ST_LOTE_CONDICIONAL) {
                $sslonline_statuslote["status_id"] = $ENCERRADO;
                $encerrar_ok = true;
                if (!empty($lance_vencedor)) {
                    if ($user_id == $lance_vencedor["user_id"]) {
                        if ($finalizar == 'S') {
                            if ($st_lote == $ST_LOTE_VENDIDO) {
                                $sslonline_statuslote["status_id"] = $VENDIDO;
                            } elseif ($st_lote == $ST_LOTE_NAO_VENDIDO) {
                                $sslonline_statuslote["status_id"] = $NAO_VENDIDO;
                            } elseif ($st_lote == $ST_LOTE_CONDICIONAL) {
                                $sslonline_statuslote["status_id"] = $CONDICIONAL;
                            }
                            $encerrar_ok = false;
                        }
                    }
                }
                if ($encerrar_ok) {
                    $sslonline_statuslote["status_id"] = $ENCERRADO;
                }
            } else {

                $query = "SELECT count(*) as total FROM sslonline_lance WHERE leilao_id = $leilao_id AND lote_id = $lote_id AND user_id = $user_id";
                $c = Transacao::getQueryArray($query);
                $c = $c[0]['total'];

                if (in_array($st_lote, $status_encerrado_lote)) {
                    $sslonline_statuslote["status_id"] = $ENCERRADO;
                } elseif ($c > 0) {
                    if ($user_id == $lance_vencedor["user_id"]) {
                        $sslonline_statuslote["status_id"] = $VENCENDO;
                    } else {
                        $sslonline_statuslote["status_id"] = $SUPERADO;
                    }
                } else {
                    $sslonline_statuslote["status_id"] = $ANDAMENTO;
                }
            }
        } else {
            if (in_array($st_lote, $status_encerrado_lote)) {
                $sslonline_statuslote["status_id"] = $ENCERRADO;
            } else {
                $sslonline_statuslote["status_id"] = $ANDAMENTO;
            }
        }

        if ($st_lote == $ST_LOTE_RETIRADO) {
            $sslonline_statuslote["status_id"] = $RETIRADO;
        }

        $sslonline_statuslote["status_n"] = $STATUS[$sslonline_statuslote["status_id"]];
        $sslonline_statuslote["fstatus"] = self::sslonlineStatusloteColor($sslonline_statuslote["status_id"], $sslonline_statuslote["status_n"]);

        if ($incremento_minimo <= 0) {
            $incremento_minimo = 100;
        }

        $sslonline_statuslote['incremento_minimo'] = $incremento_minimo;
        $sslonline_statuslote['fincremento_minimo'] = self::ssloFormatVlLanceBradesco($incremento_minimo);

        return ($sslonline_statuslote);
    }
    public function onlyNumbers($value)
    {
        $pattern = "/[0-9]*/";
        preg_match($pattern, $value, $is_numeric);
        return $is_numeric[0];
    }
    public function sslOnlineLeilaoSelectBradesco($leilao_id = 0, $categ_id = 0)
    {
        global $conn;

        $leilao_id = self::onlyNumbers($leilao_id);
        $categ_id = self::onlyNumbers($categ_id);

        $query = "SELECT sl.*, DATE_FORMAT(sl.dt_leilao,'%d/%m/%Y') as f_dt_leilao, l.deposito_id, DAYOFWEEK(l.dt_leilao) as dia_semana, l.comissao, l.bn_enviocomprova, l.deposito, l.nm_tipoleilao
        FROM sslonline_leilao sl left join leilao l on l.leilao_id = sl.leilao_id";
        if ($categ_id > 0) {
            $query .= " WHERE sl.categ_id = $categ_id group by sl.categ_id limit 1";
        } elseif ($leilao_id > 0) {
            $query .= " WHERE sl.leilao_id = $leilao_id limit 1";
        }

        $sslonline_leilao = Transacao::getQueryArray($query);

        if (!count($sslonline_leilao)) {
            return false;
        }

        if (count($sslonline_leilao) == 1) {
            $sslonline_leilao = $sslonline_leilao[0];
        }

        return ($sslonline_leilao);
    }
    public function ssloFormatVlLanceBradesco($vl_lance)
    {
        $f_vl_lance = round($vl_lance);

        $milhar = ".";
        $decimal = ",";

        if ($f_vl_lance != $vl_lance) {
            $f_vl_lance = number_format($vl_lance, 2, $decimal, $milhar);
        } else {
            $f_vl_lance = number_format($vl_lance, 0, "", $milhar);
        }

        return ($f_vl_lance);
    }
    public function replacecaracterivalidoBradesco($texto)
    {
        $texto = str_replace("'", ".", $texto);
        $texto = str_replace("\\\"", ".", $texto);
        $texto = str_replace("\"", ".", $texto);
        $texto = str_replace("<", ".", $texto);
        $texto = str_replace(">", ".", $texto);
        $texto = str_replace("&", ".", $texto);
        return $texto;
    }
    public function sslOnlineUltimosLancesFormatBradesco($lote_info, $sslonline_lances, $LIMIT = 3)
    {
        global $conn;

        $leilao_id = $lote_info['leilao_id'];
        $lote_id = $lote_info['lote_id'];

        $sslonline_leilao = self::sslOnlineLeilaoSelectBradesco($leilao_id);

        $params['bradesco'] = 1;
        $relatorio = new Relatorio($params);
        $auxBradesco = $relatorio->getLeiloesEmpresa('bradesco');

        $array_leilao_bradesco = array();
        foreach ($auxBradesco as $item) {
            $array_leilao_bradesco[$item['leilao_id']] = $item['leilao_id'];
        }

        $interno = Padrao::makeWhereNotIn();
        $leilao_interno_conf = explode(',', $interno);

        extract($lote_info);

        if (array_key_exists($leilao_id, $leilao_interno_conf)) {
            if ($leilao_interno_conf[$leilao_id]['cliente_id'] == 275) {
                $LIMIT = count($sslonline_lances);
                if ($LIMIT < 8) {
                    $LIMIT = 8;
                }
            }
        }

        if (!is_array($sslonline_lances)) {
            if ($LIMIT <= 0) {
                return false;
            }

            $sslonline_lances = array();
        }

        if (count($sslonline_lances) > 0) {
            for ($i = 0; $i < $LIMIT; $i++) {
                if (!isset($sslonline_lances[$i])) {
                    $sslonline_lances[$i] = array();
                }
            }
        }

        $nu = self::sslonlineLanceNumBradesco($lote_info);
        for ($i = 0; $i < $LIMIT; $i++) {

            if (empty($sslonline_lances[$i]['vl_lance'])) {
                $sslonline_lances[$i]['vl_lance'] = 0;
            }

            if ($nu < 0) {
                $nu = 0;
            }

            if ($leilao_id == 76361) {
                $sslonline_lances[$i]['fvl_lance'] = $moeda . " " . self::ssloFormatVlLanceBradesco($sslonline_lances[$i]['vl_lance']);
            } else {
                $sslonline_lances[$i]['fvl_lance'] = self::ssloFormatVlLanceBradesco($sslonline_lances[$i]['vl_lance']);
            }

            if (isset($sslonline_lances[$i]['vl_lanceinicial'])) {
                $sslonline_lances[$i]['vl_lanceinicial'] = self::ssloFormatVlLanceBradesco($sslonline_lances[$i]['vl_lanceinicial']);
            }

            $sslonline_lances[$i]['nu'] = $nu--;
            if (!$sslonline_lances[$i]['nu']) {
                $sslonline_lances[$i]['nu'] = '-';
            }

            if (!empty($sslonline_lances[$i]['apelido'])) {
                $sslonline_lances[$i]['usuario'] = $sslonline_lances[$i]['apelido'];
            } elseif (!empty($sslonline_lances[$i]['nome'])) {
                // Inicias do sobrenome
                $nome_user = explode(" ", $sslonline_lances[$i]['nome']);
                $result = "";
                foreach ($nome_user as $nome) {
                    if (!$result) {
                        $result = $nome;
                    } elseif (strlen($nome) > 2) {
                        $result .= " " . substr($nome, 0, 1) . ".";
                    }
                }

                $sslonline_lances[$i]['usuario'] = $result;
                // list($sslonline_lances[$i]['usuario']) = explode(" ",$sslonline_lances[$i]['nome']);
            } else {
                $sslonline_lances[$i]['usuario'] = '-';
            }

            $sslonline_lances[$i]['usuario'] = substr($sslonline_lances[$i]['usuario'], 0, 13);

            $sslonline_lances[$i]['usuario'] = self::replacecaracterivalidoBradesco($sslonline_lances[$i]['usuario']);

            if (isset($sslonline_lances[$i]['nm_identificacao'])) {
                if ($sslonline_lances[$i]['nm_identificacao']) {
                    $sslonline_lances[$i]['usuario'] = $sslonline_lances[$i]['usuario'] . "  " . $sslonline_lances[$i]['nm_identificacao'];
                }
            }

            $comissao = 1 + ($sslonline_leilao['comissao'] / 100);

            if (isset($sslonline_lances[$i]['nu_qtde'])) {
                if ($sslonline_lances[$i]['nu_qtde'] > 1) {
                    $vl_total = (($sslonline_lances[$i]['vl_lance'] * $sslonline_lances[$i]['nu_qtde']) * $comissao);
                } else {
                    $vl_total = $sslonline_lances[$i]['vl_lance'] * $comissao;
                }
            } else {
                $vl_total = $sslonline_lances[$i]['vl_lance'] * $comissao;
            }

            if ($leilao_id == 76361) {
                $sslonline_lances[$i]['vl_total'] = $moeda . " " . self::ssloFormatVlLanceBradesco($vl_total);
            } else {
                $sslonline_lances[$i]['vl_total'] = self::ssloFormatVlLanceBradesco($vl_total);
            }
        }

        return ($sslonline_lances);
    }
    public function sslOnlineUltimosLancesSelectBradesco($lote_info, $LIMIT = 3)
    {
        global $conn;

        $no_limit = false;
        $leilao_id = $lote_info['leilao_id'];
        $lote_id = $lote_info['lote_id'];

        $params['bradesco'] = 1;
        $relatorio = new Relatorio($params);
        $auxBradesco = $relatorio->getLeiloesEmpresa('bradesco');

        $array_leilao_bradesco = array();
        foreach ($auxBradesco as $item) {
            $array_leilao_bradesco[$item['leilao_id']] = $item['leilao_id'];
        }

        $interno = Padrao::makeWhereNotIn();
        $leilao_interno_conf = explode(',', $interno);

        if (in_array($leilao_id, $array_leilao_bradesco)) {
            $order_by = 'ORDER BY  sl.vl_lance DESC, sl.dt_lance, sl.sslonline_lance_id DESC';
        } elseif (array_key_exists($leilao_id, $leilao_interno_conf)) {
            if ($leilao_interno_conf[$leilao_id]['cliente_id'] == 275) {
                $order_by = 'ORDER BY sl.nm_identificacao asc';
                $no_limit = true;
            }
        } else {
            $order_by = 'ORDER BY sl.vl_lance DESC, sl.dt_lance DESC, sl.sslonline_lance_id DESC';
        }

        if ($lote_id <= 0 || $leilao_id <= 0) {
            return false;
        }

        $query = "SELECT sl.vl_lance, au.apelido, au.apelido_trt, au.nome, sl.lote_id, sl.leilao_id, user_id, st.termino, DATE_FORMAT(st.termino,'%d/%m - %H:%i') as termino3, st.st_lote, st.incremento_minimo,
        st.vl_lanceinicial, st.nu_qtde, st.nm_unidade, st.mensagem, sl.nm_identificacao FROM sslonline_status st LEFT JOIN sslonline_lance sl ON sl.lote_id = st.lote_id LEFT JOIN apss_users au ON au.id = sl.user_id
        WHERE st.lote_id = $lote_id $order_by ";
        if (!$no_limit) {
            $query .= " LIMIT $LIMIT";
        }

        $sslonline_lance = Transacao::getQueryArray($query);
        return ($sslonline_lance);
    }
    public static function cargaTiposDocumentos()
    {
        global $conn;

        $retorno = array();

        $query = "
		SELECT
		 a.id
		 /*
		 , a.conjunto_id
		 , a.grupo_id
		 , a.tipo_id
		 */
		 , a.user_id
		 , a.situacao
		 , a.tipo
		 , u.cpf
		FROM santoro.apss_users_arquivos a, santoro.apss_users u
		WHERE a.user_id = u.id
		AND a.user_id > 0
		AND a.conjunto_id = 0
		AND a.grupo_id = 0
		AND a.tipo_id = 0
		ORDER BY a.user_id, a.tipo
		LIMIT 10000;";
        $rs = $conn->query($query);
        while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
            $arquivo_id = $row['id'];
            // $conjunto_id = $row['conjunto_id'];
            // $grupo_id = $row['grupo_id'];
            // $tipo_id = $row['tipo_id'];
            $user_id = $row['user_id'];
            $situacao = $row['situacao'];
            $tipo = $row['tipo'];
            $cpf = $row['cpf'];
            $tipo_cpf = (Padrao::validaCpf($cpf) ? 'f' : (Padrao::validaCnpj($cpf) ? 'j' : 'f'));

            if (!isset($retorno[$user_id])) {
                $retorno[$user_id] = array(
                    'cpf' => $cpf,
                    'tipo_cpf' => $tipo_cpf,
                    'arquivos' => array(),
                );
            }

            $retorno[$user_id]['arquivos'][$tipo] = $row;
        }

        foreach ($retorno as $user_id => &$v) {
            $cpf = $v['cpf'];
            $tipo_cpf = $v['tipo_cpf'];
            $arquivos = $v['arquivos'];

            // echo "user_id: $user_id<br>";
            // echo "cpf: $cpf<br>";
            // echo "tipo_cpf: $tipo_cpf<br>";

            if ($tipo_cpf == 'f') {

                // grupo CPF + RG
                // conjunto_id: 1
                // grupo_id: 1
                // tipo_id: 1 - cpf
                // tipo_id: 13 - cpf-verso
                // tipo_id: 2 - rg
                // tipo_id: 7 - rg-verso
                if (isset($arquivos['cpf_1']) || isset($arquivos['cpf_2'])) {
                    if (isset($arquivos['cpf_1'])) {
                        $v['arquivos']['cpf_1']['conjunto_id'] = '1';
                        $v['arquivos']['cpf_1']['grupo_id'] = '1';
                        $v['arquivos']['cpf_1']['tipo_id'] = '1';
                    }

                    if (isset($arquivos['cpf_2'])) {
                        $v['arquivos']['cpf_2']['conjunto_id'] = '1';
                        $v['arquivos']['cpf_2']['grupo_id'] = '1';
                        $v['arquivos']['cpf_2']['tipo_id'] = '13';
                    }

                    if (isset($arquivos['rg_1'])) {
                        $v['arquivos']['rg_1']['conjunto_id'] = '1';
                        $v['arquivos']['rg_1']['grupo_id'] = '1';
                        $v['arquivos']['rg_1']['tipo_id'] = '2';
                    }

                    if (isset($arquivos['rg_2'])) {
                        $v['arquivos']['rg_2']['conjunto_id'] = '1';
                        $v['arquivos']['rg_2']['grupo_id'] = '1';
                        $v['arquivos']['rg_2']['tipo_id'] = '7';
                    }
                } // grupo RG
                // conjunto_id: 1
                // grupo_id: 2
                // tipo_id: 2 - rg
                // tipo_id: 7 - rg-verso
                else {
                    if (isset($arquivos['rg_1'])) {
                        $v['arquivos']['rg_1']['conjunto_id'] = '1';
                        $v['arquivos']['rg_1']['grupo_id'] = '2';
                        $v['arquivos']['rg_1']['tipo_id'] = '2';
                    }

                    if (isset($arquivos['rg_2'])) {
                        $v['arquivos']['rg_2']['conjunto_id'] = '1';
                        $v['arquivos']['rg_2']['grupo_id'] = '2';
                        $v['arquivos']['rg_2']['tipo_id'] = '7';
                    }
                }

                // grupo ENDERECO + DECLARACAO
                // conjunto_id: 2
                // grupo_id: 5
                // tipo_id: 4 - comprovante-endereco-frente
                // tipo_id: 9 - comprovante-endereco-verso
                // tipo_id: 5 - declaracao-endereco
                if (isset($arquivos['declaracao_endereco'])) {
                    $v['arquivos']['declaracao_endereco']['conjunto_id'] = '2';
                    $v['arquivos']['declaracao_endereco']['grupo_id'] = '5';
                    $v['arquivos']['declaracao_endereco']['tipo_id'] = '5';

                    if (isset($arquivos['endereco_1'])) {
                        $v['arquivos']['endereco_1']['conjunto_id'] = '2';
                        $v['arquivos']['endereco_1']['grupo_id'] = '5';
                        $v['arquivos']['endereco_1']['tipo_id'] = '4';
                    }

                    if (isset($arquivos['endereco_2'])) {
                        $v['arquivos']['endereco_2']['conjunto_id'] = '2';
                        $v['arquivos']['endereco_2']['grupo_id'] = '5';
                        $v['arquivos']['endereco_2']['tipo_id'] = '9';
                    }
                } // grupo ENDERECO
                // conjunto_id: 2
                // grupo_id: 4
                // tipo_id: 4 - comprovante-endereco-frente
                // tipo_id: 9 - comprovante-endereco-verso
                else {
                    if (isset($arquivos['endereco_1'])) {
                        $v['arquivos']['endereco_1']['conjunto_id'] = '2';
                        $v['arquivos']['endereco_1']['grupo_id'] = '4';
                        $v['arquivos']['endereco_1']['tipo_id'] = '4';
                    }

                    if (isset($arquivos['endereco_2'])) {
                        $v['arquivos']['endereco_2']['conjunto_id'] = '2';
                        $v['arquivos']['endereco_2']['grupo_id'] = '4';
                        $v['arquivos']['endereco_2']['tipo_id'] = '9';
                    }
                }
            }

            if ($tipo_cpf == 'j') {

                // grupo CONTRATO SOCIAL
                // conjunto_id: 5
                // grupo_id: 7
                // tipo_id: 10 - contrato-social-1-folha
                // tipo_id: 11 - contrato-social-ultima-folha
                // tipo_id: 12 - cartao-cnpj
                if (isset($arquivos['contrato_social_1']) || isset($arquivos['contrato_social_2']) || isset($arquivos['contrato_social_3'])) {
                    if (isset($arquivos['contrato_social_1'])) {
                        $v['arquivos']['contrato_social_1']['conjunto_id'] = '5';
                        $v['arquivos']['contrato_social_1']['grupo_id'] = '7';
                        $v['arquivos']['contrato_social_1']['tipo_id'] = '10';
                    }

                    if (isset($arquivos['contrato_social_2'])) {
                        $v['arquivos']['contrato_social_2']['conjunto_id'] = '5';
                        $v['arquivos']['contrato_social_2']['grupo_id'] = '7';
                        $v['arquivos']['contrato_social_2']['tipo_id'] = '11';
                    }

                    if (isset($arquivos['contrato_social_3'])) {
                        $v['arquivos']['contrato_social_3']['conjunto_id'] = '5';
                        $v['arquivos']['contrato_social_3']['grupo_id'] = '7';
                        $v['arquivos']['contrato_social_3']['tipo_id'] = '12';
                    }
                }

                // grupo CPF + RG
                // conjunto_id: 6
                // grupo_id: 8
                // tipo_id: 1 - cpf
                // tipo_id: 13 - cpf-verso
                // tipo_id: 2 - rg
                // tipo_id: 7 - rg-verso
                if (isset($arquivos['cpf_1']) || isset($arquivos['cpf_2'])) {
                    if (isset($arquivos['cpf_1'])) {
                        $v['arquivos']['cpf_1']['conjunto_id'] = '6';
                        $v['arquivos']['cpf_1']['grupo_id'] = '8';
                        $v['arquivos']['cpf_1']['tipo_id'] = '1';
                    }

                    if (isset($arquivos['cpf_2'])) {
                        $v['arquivos']['cpf_2']['conjunto_id'] = '6';
                        $v['arquivos']['cpf_2']['grupo_id'] = '8';
                        $v['arquivos']['cpf_2']['tipo_id'] = '13';
                    }

                    if (isset($arquivos['rg_1'])) {
                        $v['arquivos']['rg_1']['conjunto_id'] = '6';
                        $v['arquivos']['rg_1']['grupo_id'] = '8';
                        $v['arquivos']['rg_1']['tipo_id'] = '2';
                    }

                    if (isset($arquivos['rg_2'])) {
                        $v['arquivos']['rg_2']['conjunto_id'] = '6';
                        $v['arquivos']['rg_2']['grupo_id'] = '8';
                        $v['arquivos']['rg_2']['tipo_id'] = '7';
                    }
                } // grupo RG
                // conjunto_id: 6
                // grupo_id: 9
                // tipo_id: 2 - rg
                // tipo_id: 7 - rg-verso
                else {
                    if (isset($arquivos['rg_1'])) {
                        $v['arquivos']['rg_1']['conjunto_id'] = '6';
                        $v['arquivos']['rg_1']['grupo_id'] = '9';
                        $v['arquivos']['rg_1']['tipo_id'] = '2';
                    }

                    if (isset($arquivos['rg_2'])) {
                        $v['arquivos']['rg_2']['conjunto_id'] = '6';
                        $v['arquivos']['rg_2']['grupo_id'] = '9';
                        $v['arquivos']['rg_2']['tipo_id'] = '7';
                    }
                }

                // grupo ENDERECO + DECLARACAO
                // conjunto_id: 2
                // grupo_id: 5
                // tipo_id: 4 - comprovante-endereco-frente
                // tipo_id: 9 - comprovante-endereco-verso
                // tipo_id: 5 - declaracao-endereco
                if (isset($arquivos['declaracao_endereco'])) {
                    $v['arquivos']['declaracao_endereco']['conjunto_id'] = '2';
                    $v['arquivos']['declaracao_endereco']['grupo_id'] = '5';
                    $v['arquivos']['declaracao_endereco']['tipo_id'] = '5';

                    if (isset($arquivos['endereco_1'])) {
                        $v['arquivos']['endereco_1']['conjunto_id'] = '2';
                        $v['arquivos']['endereco_1']['grupo_id'] = '5';
                        $v['arquivos']['endereco_1']['tipo_id'] = '4';
                    }

                    if (isset($arquivos['endereco_2'])) {
                        $v['arquivos']['endereco_2']['conjunto_id'] = '2';
                        $v['arquivos']['endereco_2']['grupo_id'] = '5';
                        $v['arquivos']['endereco_2']['tipo_id'] = '9';
                    }
                } // grupo ENDERECO
                // conjunto_id: 2
                // grupo_id: 4
                // tipo_id: 4 - comprovante-endereco-frente
                // tipo_id: 9 - comprovante-endereco-verso
                else {
                    if (isset($arquivos['endereco_1'])) {
                        $v['arquivos']['endereco_1']['conjunto_id'] = '2';
                        $v['arquivos']['endereco_1']['grupo_id'] = '4';
                        $v['arquivos']['endereco_1']['tipo_id'] = '4';
                    }

                    if (isset($arquivos['endereco_2'])) {
                        $v['arquivos']['endereco_2']['conjunto_id'] = '2';
                        $v['arquivos']['endereco_2']['grupo_id'] = '4';
                        $v['arquivos']['endereco_2']['tipo_id'] = '9';
                    }
                }
            }
        }

        foreach ($retorno as $user_id => &$v) {
            $cpf = $v['cpf'];
            $tipo_cpf = $v['tipo_cpf'];
            $arquivos = $v['arquivos'];

            foreach ($arquivos as $a) {
                if (isset($a['conjunto_id'])) {
                    $query = "
				UPDATE santoro.apss_users_arquivos
				SET conjunto_id = '" . $a['conjunto_id'] . "'
				, grupo_id = '" . $a['grupo_id'] . "'
				, tipo_id = '" . $a['tipo_id'] . "'
				WHERE id = '" . $a['id'] . "' LIMIT 1;";
                    echo $query . '<br>';
                }
            }
        }
    }
    public static function adicionaAtendimentoBradesco($parms)
    {
        self::insert("imoveis_ab_from", $parms, false);
    }
    public static function getLoteIdInitBradesco($leilao_id)
    {
        global $conn;

        list($nu_lote, $lote_id_aovivo) = self::getLoteAovivoBradesco($leilao_id);

        if ($lote_id_aovivo != 0) {
            return $lote_id_aovivo;
        }

        $query = "SELECT lote_id FROM sslonline_status WHERE leilao_id = $leilao_id AND (st_nao_visivel IS NULL OR st_nao_visivel = 0) ORDER BY st_lote DESC, termino LIMIT 1";
        $rs = $conn->query($query);
        $row = $rs->fetch(PDO::FETCH_ASSOC);

        $lote_id = $row['lote_id'];

        return $lote_id;
    }
    public function getLoteAovivoBradesco($leilao_id)
    {
        global $conn;

        $query = "SELECT ss.nu_lote, sl.lote_id_aovivo FROM sslonline_leilao sl INNER JOIN sslonline_status ss on ss.lote_id = sl.lote_id_aovivo WHERE sl.leilao_id = $leilao_id";
        $rs = $conn->query($query);
        $row = $rs->fetch(PDO::FETCH_ASSOC);

        $nu_lote = isset($row['nu_lote']) ? $row['nu_lote'] : '';
        $lote_id_aovivo = isset($row['lote_id_aovivo']) ? $row['lote_id_aovivo'] : '';

        return array(
            $nu_lote,
            $lote_id_aovivo,
        );
    }
    public function getImovelIdByLoteIdOnlineBradesco($lote_id)
    {
        global $conn;

        $imovel_id = '';
        if (!empty($lote_id)) {
            $query = "SELECT id FROM sslonline_imoveis WHERE lote_id = $lote_id";

            $rs = $conn->query($query);
            $row = $rs->fetch(PDO::FETCH_ASSOC);
            $imovel_id = $row['id'];
        }

        return $imovel_id;
    }
    public function getFotosPopBradesco($id = 0, $lote_id = 0)
    {
        global $imoveis_photo_dir, $imoveis_photo_link, $imoveis_photo_dir_320x240, $imoveis_photo_link_320x240, $conn;

        if (empty($imoveis_img_dir)) {
            $imoveis_img_dir = $imoveis_photo_dir;
        }

        if (empty($imoveis_img_link)) {
            $imoveis_img_link = $imoveis_photo_link;
        }

        if (empty($imoveis_img_dir_320x240)) {
            $imoveis_img_dir_320x240 = $imoveis_photo_dir_320x240;
        }

        if (empty($imoveis_img_link_320x240)) {
            $imoveis_img_link_320x240 = $imoveis_photo_link_320x240;
        }

        $photos = array();
        if (!empty($lote_id)) {
            if ($lote_id > 0) {
                $query = "select nm_img from sslonline_imoveis where lote_id = $lote_id";
            } else {
                $query = "select nm_img from sslonline_imoveis where id = $id";
            }

            $rs = $conn->query($query);
            $row = $rs->fetch(PDO::FETCH_ASSOC);
            $tmp = $row['nm_img'];

            if (isset($tmp)) {
                $array_photos = explode(";", $tmp);
            }

            foreach ($array_photos as $fotos) {
                if (!in_array($fotos, $photos)) {
                    $photos[] = $fotos;
                }
            }
        }

        return $photos;
    }
    public function sslonlineLanceNumBradesco($lote_info, $user_id = 0)
    {
        global $conn;

        extract($lote_info);
        if ($leilao_id <= 0 || $lote_id <= 0) {
            return false;
        }

        $query = "SELECT count(*) as nu FROM sslonline_lance WHERE lote_id = $lote_id ";
        if ($user_id > 0) {
            $query .= " AND user_id = $user_id";
        }

        $rs = $conn->query($query);
        $row = $rs->fetch(PDO::FETCH_ASSOC);
        $nu = $row['nu'];
        return ($nu);
    }
    public function getProxAntBradesco($leilao_id)
    {
        global $conn;

        $query = "SELECT lote_id, CASE WHEN nu_lote = 'Indef' THEN nm_vistoria ELSE nu_lote END nu_lote FROM sslonline_status WHERE leilao_id = " . $leilao_id . " AND (st_nao_visivel IS NULL OR st_nao_visivel = 0) order by nu_lote";
        $sslo_prox_ant = array();
        $rs = $conn->query($query);
        while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
            $sslo_prox_ant[] = $row;
        }

        return $sslo_prox_ant;
    }
    public function getAllLancesBradesco($lote_id)
    {
        global $conn;

        $query = "select user_id from sslonline_lance where lote_id = $lote_id";
        $rs = $conn->query($query);
        $user_id = array();
        if ($rs->rowCount() > 0) {
            while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                $user_id[] = $row['user_id'];
            }
        }

        return $user_id;
    }
    public static function getInfoTelaoBradesco($leilao_id, $params)
    {
        global $conn;

        $USUARIO_DE_AUDITORIO = Padrao::pegaOperadores('usuario_de_auditorio');
        $retorno = array();
        $lote_id = $params["lote_id"];
        $fl_aovivo = !empty($params["fl_aovivo"]) ? $params["fl_aovivo"] : 'false';
        $fix_pagina = !empty($params["pagina"]) ? $params["pagina"] : 1;
        $mapa_leilao = !empty($params["mapa_leilao"]) ? $params["mapa_leilao"] : 'false';
        $base_url = $params['base_url'];
        $prox_lote_id = '';
        $ant_lote_id = '';
        $mapa_lote = '';
        $descricao_lote = array();
        $descricao_topo = '';
        $mapa_lote['qtde_presencial'] = 0;
        $mapa_lote['qtde_online'] = 0;

        if ($lote_id <= 0) {
            $lote_id = Adm::getLoteIdInitBradesco($leilao_id);
        }

        if ($fl_aovivo === 'true') {
            list($nu_lote, $lote_id_aovivo) = self::getLoteAovivoBradesco($leilao_id);
            $lote_id = !empty($lote_id_aovivo) ? $lote_id_aovivo : '';
        }

        $fotos = array();
        $query = "select tipo from sslonline_leilao where leilao_id = $leilao_id limit 1";
        $rs = $conn->query($query);
        $row = $rs->fetch(PDO::FETCH_ASSOC);
        $tipo_segmento = $row['tipo'];

        switch ($tipo_segmento) {
            case 'V':
                // require_once (LIBDIR . "/veiculos/veiculos.php");
                // $fotos = get_Fotos (NULL, $lote_id, NULL, NULL, TRUE);
                break;
            case 'M':
                // require_once (LIBDIR . "/materiais/materiais.php");
                // $material_id = get_material_id_by_lote_id($lote_id);
                // $fotos = get_Fotos ($material_id, $lote_id, NULL, NULL, TRUE);
                break;
            default:
                $imovel_id = self::getImovelIdByLoteIdOnlineBradesco($lote_id);
                $fotos = self::getFotosPopBradesco($imovel_id, $lote_id);
        }

        $total_reg = "11";
        $pc = $fix_pagina;

        $inicio = $pc - 1;
        $inicio = $inicio * $total_reg;

        $query = "SELECT qtde_lance, st.nu_lote, (
					select descricao from sslonline_materiais where lote_id = st.lote_id and id > 0 group by lote_id
					union
					select descricao from sslonline_imoveis where lote_id = st.lote_id group by lote_id
					union
					select descricao from sslonline_veiculos where lote_id = st.lote_id group by lote_id
				) as descricao, st.vl_lanceinicial, st.vl_lanceminimo, st.st_lote, au.apelido, sl.nm_identificacao, au.nome, sl.vl_lance AS valor_maximo, st.tipo_segmento, st.lote_id
				from sslonline_status st
				LEFT join sslonline_lance sl ON st.lance_id = sl.sslonline_lance_id
				LEFT join apss_users au ON au.id = sl.user_id
				LEFT join (SELECT lote_id, COUNT(*) AS qtde_lance from sslonline_lance sl2 WHERE leilao_id=$leilao_id GROUP by lote_id) AS x ON x.lote_id=st.lote_id
				WHERE st.leilao_id = $leilao_id and st.st_nao_visivel = 0
				ORDER BY st.nu_lote
				LIMIT $inicio,$total_reg";
        $result = $conn->query($query);

        $query = "SELECT qtde_lance, st.nu_lote, st.descricao, st.vl_lanceinicial, st.vl_lanceminimo, st.st_lote, au.apelido, sl.nm_identificacao, au.nome, sl.vl_lance AS valor_maximo, st.tipo_segmento, st.lote_id
				from sslonline_status st
				LEFT join sslonline_lance sl ON st.lance_id = sl.sslonline_lance_id
				LEFT join apss_users au ON au.id = sl.user_id
				LEFT join (SELECT lote_id, COUNT(*) AS qtde_lance from sslonline_lance sl2 WHERE leilao_id=$leilao_id GROUP by lote_id) AS x ON x.lote_id=st.lote_id
				WHERE st.leilao_id = $leilao_id  and st.st_nao_visivel = 0
				ORDER BY st.nu_lote";
        $todos = $conn->query($query);

        $tr = $todos->rowCount(); // verifica o nÃÆÃÂºmero total de registros
        $tp = $tr / $total_reg; // verifica o nÃÆÃÂºmero total de pÃÆÃÂ¡ginas

        // vamos criar a visualizaÃÆÃÂ§ÃÆÃÂ£o

        // agora vamos criar os botÃÆÃÂµes "Anterior e prÃÆÃÂ³ximo"
        $anterior = $pc - 1;
        $proximo = $pc + 1;

        $anterior2 = '';
        $proximo2 = '';
        if ($pc > 1) {
            $anterior2 = " <a href='" . $base_url . "/lote_id/$lote_id/fl_aovivo/$fl_aovivo/pagina/$anterior/mapa_leilao/$mapa_leilao/'>Anterior <img src=\"/imagens/relat_leilao/nav_lote-esq.png\"/ class=\"left\" alt=\"proxima pagina\"></a> ";
        }

        if ($pc < $tp) {
            $proximo2 = "<a href='" . $base_url . "/lote_id/$lote_id/fl_aovivo/$fl_aovivo/pagina/$proximo/mapa_leilao/$mapa_leilao/'>PrÃÆÃÂ³xima <img src=\"/imagens/relat_leilao/nav_lote-dir.png\"/ class=\"right\" alt=\"proxima pagina\"></a>";
        }

        $retorno["anterior"] = $anterior2;
        $retorno["proximo"] = $proximo2;
        $retorno["pagina"] = $pc;

        /* Formatar campos */
        $vl_maximo = 0;
        $vl_minimo = 0;
        $qtde_lance = 0;
        $resultado = array();
        while (@$relatorio = $result->fetch(PDO::FETCH_ASSOC)) {
            preg_match("/(R E T I R A D O|RETIRADO)/", trim($relatorio['descricao']), $retirado);
            if ($retirado) {
                $relatorio['st_lote'] = 9;
            }

            $relatorio['link'] = "" . $base_url . "/lote_id/$relatorio[lote_id]/fl_aovivo/false/pagina/$fix_pagina/mapa_leilao/false/";

            if ($relatorio['qtde_lance'] == 0) {
                $relatorio['agio'] = '-';
            } else {
                $relatorio['agio'] = round(((($relatorio['valor_maximo'] - $relatorio['vl_lanceminimo']) * 100) / $relatorio['vl_lanceminimo'])) . "%";
            }

            /* Status do Lote */
            if ($relatorio['st_lote'] == 1) {
                $relatorio['st_lote'] = 'Em Andamento';
            } elseif ($relatorio['st_lote'] == 2) {
                $relatorio['st_lote'] = 'Dou-lhe Uma';
            } elseif ($relatorio['st_lote'] == 3) {
                $relatorio['st_lote'] = 'Dou-lhe Duas';
            } elseif ($relatorio['st_lote'] == 4) {
                $relatorio['st_lote'] = 'Preg&atilde;o';
            } elseif ($relatorio['st_lote'] == 5) {
                $relatorio['st_lote'] = 'Vendido';
            } elseif ($relatorio['st_lote'] == 6) {
                $relatorio['st_lote'] = 'N&atilde;o Vendido';
            } elseif ($relatorio['st_lote'] == 7) {
                $relatorio['st_lote'] = 'Condicional';
            } elseif ($relatorio['st_lote'] == 8) {
                $relatorio['st_lote'] = 'Repasse';
            } elseif ($relatorio['st_lote'] == 9) {
                $relatorio['st_lote'] = 'Retirado';
            } elseif ($relatorio['st_lote'] == 0) {
                $relatorio['st_lote'] = 'Encerrado';
            }

            $vl_maximo = $relatorio['valor_maximo'] + $vl_maximo;
            $vl_minimo = $relatorio['vl_lanceminimo'] + $vl_minimo;
            $qtde_lance = $relatorio['qtde_lance'] + $qtde_lance;

            $relatorio['vl_lanceminimo'] = "R$ " . number_format($relatorio['vl_lanceminimo'], 2, ',', '.');
            $relatorio['valor_maximo'] = "R$ " . number_format($relatorio['valor_maximo'], 2, ',', '.');

            array_push($resultado, $relatorio);
        }

        $vl_maximo = number_format($vl_maximo, 2, ',', '.');
        $vl_minimo = number_format($vl_minimo, 2, ',', '.');

        /* RELATORIOS TOTAIS */
        $query = " SELECT COUNT(*) AS total_st, ss.st_lote, SUM(ss.vl_lanceminimo) AS avaliacao, SUM(sl.vl_lance) AS lance, ((SUM(sl.vl_lance) - SUM(ss.vl_lanceminimo))*100 /  SUM(ss.vl_lanceminimo))AS agio, lotes,
			 	 ((COUNT(*) * 100)/lotes) AS porc
			 	 from sslonline_status ss
				 LEFT join sslonline_lance sl ON ss.lance_id = sl.sslonline_lance_id, (SELECT COUNT(*)AS lotes from sslonline_status WHERE leilao_id = $leilao_id and st_lote NOT IN (0, 7, 9)) AS perc
				 WHERE ss.leilao_id = $leilao_id and ss.st_lote NOT IN (0,  7, 9) GROUP by st_lote ORDER BY st_lote DESC";
        $totais = $conn->query($query);

        $totais_format = array();
        $linha_total = array();

        $retirados = array();
        $arrSeparados = array(
            2,
            3,
            4,
        );
        $auxSeparados = array();
        $auxStPregao = array();
        $listaSeparados = array();

        $linha_total['avaliacao'] = '';
        $linha_total['lance'] = '';
        $linha_total['lances'] = '';
        $linha_total['porcentagem'] = '';

        while (@$linha = $totais->fetch(PDO::FETCH_ASSOC)) {
            if (in_array($linha['st_lote'], $arrSeparados)) {
                $auxSeparados[] = $linha;
            } else {
                $linha_total['avaliacao'] = $linha['avaliacao'] + $linha_total['avaliacao'];
                $linha_total['lance'] = $linha['lance'] + $linha_total['lance'];

                $linha['porc'] = round($linha['porc']);
                $linha['agio'] = round($linha['agio']);

                $linha['lances'] = $linha['total_st'];
                $linha['porcentagem'] = $linha['porc'] . "%";
                $linha['avaliacao'] = "R$ " . number_format($linha['avaliacao'], 2, ',', '.');
                $linha['lance'] = "R$ " . number_format($linha['lance'], 2, ',', '.');
                $linha['agio'] = ($linha['agio']) . "%";

                $linha_total['lances'] = $linha['lances'] + $linha_total['lances'];
                $linha_total['porcentagem'] = $linha['porcentagem'] + $linha_total['porcentagem'];
                $totais_format[$linha['st_lote']] = $linha;
            }
        }

        if (count($auxSeparados) > 0) {

            $auxStPregao['total_st'] = '';
            $auxStPregao['st_lote'] = '';
            $auxStPregao['avaliacao'] = '';
            $auxStPregao['lance'] = '';
            $auxStPregao['agio'] = '';
            $auxStPregao['lotes'] = '';
            $auxStPregao['porc'] = '';

            $temPregao = false;
            foreach ($auxSeparados as $key => $value) {
                $temPregao = true;

                $value['porc'] = round($value['porc']);
                $value['agio'] = round($value['agio']);

                $linha_total['avaliacao'] = $value['avaliacao'] + $linha_total['avaliacao'];
                $linha_total['lance'] = $value['lance'] + $linha_total['lance'];

                $auxStPregao['total_st'] = $auxStPregao['total_st'] + $value['total_st'];
                $auxStPregao['st_lote'] = 4;
                $auxStPregao['avaliacao'] = $auxStPregao['avaliacao'] + $value['avaliacao'];
                $auxStPregao['lance'] = $auxStPregao['lance'] + $value['lance'];
                $auxStPregao['agio'] = $auxStPregao['agio'] + $value['agio'];
                $auxStPregao['lotes'] = $auxStPregao['lotes'] + $value['lotes'];
                $auxStPregao['porc'] = $auxStPregao['porc'] + $value['porc'];

                $linha_total['lances'] = $value['total_st'] + $linha_total['lances'];
                $linha_total['porcentagem'] = $value['porc'] + $linha_total['porcentagem'];
            }

            if ($temPregao) {
                $auxStPregao['lances'] = $auxStPregao['total_st'];
                $auxStPregao['st_lote'] = 4;
                $auxStPregao['avaliacao'] = "R$ " . number_format($auxStPregao['avaliacao'], 2, ',', '.');
                $auxStPregao['lance'] = "R$ " . number_format($auxStPregao['lance'], 2, ',', '.');
                $auxStPregao['agio'] = ($auxStPregao['agio']) . "%";
                $auxStPregao['lotes'] = $auxStPregao['lotes'] + $value['lotes'];
                $auxStPregao['porcentagem'] = ($auxStPregao['porc']) . "%";

                $totais_format[4] = $auxStPregao;
            }
        }

        ksort($totais_format);

        $linha_total['agio'] = ($linha_total['lance'] - $linha_total['avaliacao']) * 100 / $linha_total['avaliacao'];
        $linha_total['agio'] = round($linha_total['agio']) . "%";
        $linha_total['avaliacao'] = "R$ " . number_format($linha_total['avaliacao'], 2, ',', '.');
        $linha_total['lance'] = "R$ " . number_format($linha_total['lance'], 2, ',', '.');
        $linha_total['porcentagem'] = round($linha_total['porcentagem']) . '%';

        $query = "SELECT COUNT(*) AS total_st, ss.st_lote, SUM(ss.vl_lanceminimo) AS avaliacao, SUM(sl.vl_lance) AS lance, ((SUM(sl.vl_lance) - SUM(ss.vl_lanceminimo))*100 /  SUM(ss.vl_lanceminimo))AS agio, lotes,
			 	 ((COUNT(*) * 100)/lotes) AS porc
			 	 from sslonline_status ss
				 LEFT join sslonline_lance sl ON ss.lance_id = sl.sslonline_lance_id, (SELECT COUNT(*)AS lotes from sslonline_status WHERE leilao_id = $leilao_id and st_lote IN (0, 7, 9)) AS perc
				 WHERE ss.leilao_id = $leilao_id and ss.st_lote IN (0, 7, 9) GROUP BY st_lote ORDER BY st_lote DESC";
        $extra = $conn->query($query);
        while (@$linha = $extra->fetch(PDO::FETCH_ASSOC)) {

            $linha['lances'] = $linha['total_st'];
            $linha['porcentagem'] = '-';
            $linha['avaliacao'] = "R$ " . number_format($linha['avaliacao'], 2, ',', '.');
            $linha['lance'] = "R$ " . number_format($linha['lance'], 2, ',', '.');
            $linha['agio'] = round($linha['agio']) . "%";

            $listaSeparados[$linha['st_lote']] = $linha;
        }

        ksort($listaSeparados);

        $query = "SELECT count(1) as qtde_aceitou from sslonline_cond_venda where leilao_id = $leilao_id";
        $rs = $conn->query($query);
        $row = $rs->fetch(PDO::FETCH_ASSOC);
        $retorno['qtde_aceitou'] = $row['qtde_aceitou'];

        $query = "SELECT count(1) as qtde from santoro.contador_sslonline WHERE  leilao_id = $leilao_id and data >= date_sub(now() ,interval 360 second)";
        $rs = $conn->query($query);
        $row = $rs->fetch(PDO::FETCH_ASSOC);
        $retorno['ativos'] = $row['qtde'];

        if (!empty($lote_id)) {
            /* Lote a Lote */
            $query = "		(Select st.nu_lote, st.descricao AS titulo_lote, si.descricao, si.vl_lanceminimo, st.st_lote
							from sslonline_imoveis si
							INNER join sslonline_status st ON si.lote_id = st.lote_id WHERE si.lote_id = $lote_id )
						union
							(select st.nu_lote, st.descricao AS titulo_lote, si.descricao, si.vl_lanceminimo, st.st_lote
							from sslonline_materiais si
							INNER join sslonline_status st ON si.lote_id = st.lote_id WHERE si.lote_id = $lote_id and si.id > 0)
						union
							(select st.nu_lote, st.descricao AS titulo_lote, si.descricao, si.vl_lanceminimo, st.st_lote
							from sslonline_veiculos si
							INNER join sslonline_status st ON si.lote_id = st.lote_id WHERE si.lote_id = $lote_id)
						order by nu_lote asc ";
            $descricao_topo = $conn->query($query);
            $descricao_topo = $descricao_topo->fetch(PDO::FETCH_ASSOC);
            $descricao_topo['vl_lanceminimo'] = "R$ " . number_format($descricao_topo['vl_lanceminimo'], 2, ',', '.');

            $query = "SELECT sl.vl_lance, ss.vl_lanceminimo, au.nome, au.apelido, sl.nm_identificacao, ((sl.vl_lance - ss.vl_lanceminimo)*100 /  ss.vl_lanceminimo) AS agio, CASE WHEN iu.cidade<>'' THEN iu.cidade ELSE au.cidade END AS Cidade,
					CASE WHEN iu.estado <> '' THEN CASE when e2.UF is null then e.UF else e2.UF end ELSE e.UF END AS Estado
					from sslonline_lance sl
					LEFT join apss_users au ON sl.user_id = au.id
					LEFT join estados e ON au.estado = e.id
					LEFT join imoveis_lanceonline il ON sl.user_id = il.user_id AND sl.nm_identificacao = il.plaqueta and sl.leilao_id = il.leilao_id
					LEFT join imoveis_users iu ON il.imoveis_user_id = iu.id
					LEFT join estados e2 ON iu.estado=e2.uf
					LEFT join sslonline_status ss ON sl.lote_id = ss.lote_id
					WHERE ss.lote_id = $lote_id  and ss.st_lote not in (0,7)
					ORDER BY sl.vl_lance DESC
					limit 3";
            $aux = $conn->query($query);

            /* numero de lances */
            $lote_info = array(
                "leilao_id" => $leilao_id,
                "lote_id" => $lote_id,
            );
            $lance_num = self::sslonlineLanceNumBradesco($lote_info);

            $conta_lances = array();
            $conta_lances[0] = $lance_num;
            while ($lance_num > 1) {
                $lance_num = $lance_num;
                $lance_num--;
                $conta_lances[] = $lance_num;
            }

            $descricao_lote = array();
            $i = 0;
            while (@$lotes = $aux->fetch(PDO::FETCH_ASSOC)) {
                $lotes['vl_lance'] = "R$ " . number_format($lotes['vl_lance'], 2, ',', '.');
                $lotes['agio'] = round($lotes['agio']) . "%";
                $lotes['conta_lance'] = $conta_lances[$i];
                $lotes['Cidade'] = strtoupper($lotes['Cidade']);
                $lotes['Estado'] = strtoupper($lotes['Estado']);

                $i++;
                $descricao_lote[] = $lotes;
            }

            $sslo_prox_ant = self::getProxAntBradesco($leilao_id);

            $aux = 0;
            $first_lote_id = "";
            $last_lote_id = "";
            foreach ($sslo_prox_ant as $vetor) {
                if (empty($first_lote_id)) {
                    $first_lote_id = $vetor['lote_id'];
                }

                if ($aux == 1) {
                    $prox_lote_id = $vetor['lote_id'];
                    $aux = 0;
                }

                if ($vetor['lote_id'] == $lote_id) {
                    $aux = 1;
                    if ($vetor['lote_id'] == $first_lote_id) {
                        $ant_lote_id = "";
                    } else {
                        $ant_lote_id = $ant;
                    }
                }

                $ant = $vetor['lote_id'];
            }

            $last_lote_id = $ant;

            if ($prox_lote_id == 0) {
                $prox_lote_id = $first_lote_id;
            }

            if ($ant_lote_id == 0) {
                $ant_lote_id = $last_lote_id;
            }

            /* MAPA */
            $qtde_presencial = 0;
            $qtde_online = 0;

            if ($mapa_leilao === "false") {
                // LOTE A LOTE
                $mapa_lote = array();
                $query = "SELECT COUNT(*) as total_lance, e.UF AS Estado
						from sslonline_lance sl
						LEFT join apss_users au ON sl.user_id = au.id
						LEFT join estados e ON au.estado = e.id
						LEFT join imoveis_lanceonline il ON sl.user_id = il.user_id AND sl.nm_identificacao = il.plaqueta  and sl.leilao_id = il.leilao_id
						LEFT join imoveis_users iu ON il.imoveis_user_id = iu.id
						LEFT join estados e2 ON iu.estado=e2.uf
						WHERE lote_id = $lote_id
						GROUP by estado";
                $rs = $conn->query($query);
                while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                    $mapa_lote[] = $row;
                }

                $lances = self::getAllLancesBradesco($lote_id);
                foreach ($lances as $lance) {
                    if ($lance == 135361) {
                        $qtde_online = $qtde_online + 1;
                    } elseif ($lance == 135360) {
                        $qtde_online = $qtde_online + 1;
                    } else {
                        if (in_array($lance, $USUARIO_DE_AUDITORIO)) {
                            $qtde_presencial = $qtde_presencial + 1;
                        } else {
                            $qtde_online = $qtde_online + 1;
                        }
                    }
                }
                $mapa_lote['qtde_presencial'] = $qtde_presencial;
                $mapa_lote['qtde_online'] = $qtde_online;
            }
        }

        if ($mapa_leilao === "true") {
            // LEILAO INTEIRO
            $qtde_online = 0;
            $qtde_presencial = 0;
            $query = "SELECT COUNT(*) AS total_lance, CASE WHEN iu.estado <> '' THEN CASE when e2.UF is null then e.UF else e2.UF end ELSE e.UF END AS Estado
					from sslonline_lance sl
					LEFT join apss_users au ON sl.user_id = au.id
					LEFT join estados e ON au.estado = e.id
					LEFT join imoveis_lanceonline il ON sl.user_id = il.user_id AND sl.nm_identificacao = il.plaqueta  and sl.leilao_id = il.leilao_id
					LEFT join imoveis_users iu ON il.imoveis_user_id = iu.id
					LEFT join estados e2 ON iu.estado=e2.estado
					WHERE sl.leilao_id = $leilao_id
					GROUP by estado";
            $rs = $conn->query($query);
            while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                $mapa_lote[] = $row;
            }

            $query = "select lote_id from sslonline_status where leilao_id = $leilao_id";
            $rs = $conn->query($query);
            while (@$id = $rs->fetch(PDO::FETCH_ASSOC)) {
                $lances = self::getAllLancesBradesco($id['lote_id']);
                foreach ($lances as $lance) {
                    if ($lance == 135361) {
                        $qtde_online = $qtde_online + 1;
                    } elseif ($lance == 135360) {
                        $qtde_online = $qtde_online + 1;
                    } else {
                        if (in_array($lance, $USUARIO_DE_AUDITORIO)) {
                            $qtde_presencial = $qtde_presencial + 1;
                        } else {
                            $qtde_online = $qtde_online + 1;
                        }
                    }
                }
            }

            $mapa_lote['qtde_presencial'] = $qtde_presencial;
            $mapa_lote['qtde_online'] = $qtde_online;
        }

        $retorno["leilao_id"] = $leilao_id;
        $retorno["lote_id"] = $lote_id;
        $retorno["fl_aovivo"] = $fl_aovivo;
        $retorno["prox_lote_id"] = $prox_lote_id;
        $retorno["ant_lote_id"] = $ant_lote_id;
        $retorno["fotos"] = $fotos;
        $retorno["mapa_lotes"] = $mapa_lote;
        $retorno["mapa_leilao"] = $mapa_leilao;
        $retorno["result"] = $resultado;
        // $retorno["agio"] = $agio;
        $retorno["vl_maximo"] = $vl_maximo;
        $retorno["listaSeparados"] = $listaSeparados;
        $retorno["descricao_lote"] = $descricao_lote;
        $retorno["vl_minimo"] = $vl_minimo;
        $retorno["qtde_lance"] = $qtde_lance;
        $retorno["totais"] = $totais_format;
        $retorno["linha_total"] = $linha_total;
        $retorno["descricao_topo"] = $descricao_topo;

        return $retorno;
    }
    public static function switchStLote($st_lote)
    {
        switch ($st_lote) {
            // case 1 : $retorno = ST_LOTE_CONDICIONAL;break;
            case 2:
                $retorno = ST_LOTE_RETIRADO;
                break;
            // case 3 : $retorno = ST_LOTE_VENDIDO;break;
            // case 4 : $retorno = ST_LOTE_NAO_VENDIDO;break;
            // case 5 : $retonro = Cancelado;
            default:
                $retorno = ST_LOTE_ANDAMENTO;
        }
        return $retorno;
    }
    public static function getPerguntasValidaEmail($id = '')
    {
        global $conn;

        if (empty($id)) {
            $query = "SELECT * FROM apss_users_valida_alteracao_email WHERE status = 1";
        } else {
            $query = "SELECT * FROM apss_users_valida_alteracao_email WHERE status = 1 AND id = '" . $id . "'";
        }
        $retorno = Transacao::getQueryArray($query);

        return $retorno;
    }
    public static function listaRamais($buscar = '')
    {
        global $conn;

        $buscar = trim($buscar);
        $buscar = explode(' ', $buscar);

        $retorno = array();

        $query = "SELECT * FROM adm_ramal";
        if (count($buscar) > 0) {
            $where = array();
            foreach ($buscar as $b) {
                $where[] = "departamento LIKE '%" . $b . "%'";
                $where[] = "responsavel LIKE '%" . $b . "%'";
                $where[] = "ramal LIKE '%" . $b . "%'";
            }

            $query .= " WHERE " . implode(' OR ', $where);
        }
        $query .= " ORDER BY departamento, responsavel, ramal;";
        $rs = $conn->query($query);

        $i = 0;
        $departamento_atual = '';
        while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
            // $departamento = $row['departamento'];
            // $responsavel = $row['responsavel'];
            // $ramal = $row['ramal'];

            // if ($departamento_atual != $departamento) {
            // $i++;
            // $departamento_atual = $departamento;
            // $retorno[$i] = array(
            // 'nome' => $departamento,
            // 'itens' => array()
            // );
            // }

            // $retorno[$i]['itens'][] = array(
            // 'departamento' => $departamento,
            // 'responsavel' => $responsavel,
            // 'ramal' => $ramal,
            // );

            $retorno[] = $row;
        }

        return $retorno;
    }
    public static function print_erro($msg, $erro = true, $bold = false)
    {
        if (!empty($_SERVER['DOCUMENT_ROOT'])) {
            if ($erro) {
                print "<span style='color:#FF0000'>$msg</span><br/>";
            } elseif ($bold) {
                print "<span><b>$msg</b></span><br/>";
            } else {
                print "<span style='color:blue'>$msg</span><br/>";
            }
        } else {
            print $msg . "\n";
        }
    }

    public function loadCacheQuantidadeSegmentos() {
        global $cache;
        $cache_tmp = Leilao::getSegmentosQuantidade ();
        $cache->setCache('segmento_quantidades', $cache_tmp);
    }

    public static function loadCacheVeiculos($modo = 0)
    {
        global $conn, $where_leilao_not_in, $cache;

        switch ($modo) {

            // load cache destaques
            case 0:
                {
                    $veiculo = new Veiculo();

                    $destaques = $cache->getCache('destaques');
                    $destaques['veiculo'] = $veiculo->getDestaque(20);

                    $cache->deleteCache('destaques');
                    $cache->setCache('destaques', $destaques);
                }
                ;

            // load cache filtro de veiculos
            case 1:
                {
                    $params = array();
                    $filtros = Veiculo::getVeiculosFiltros($params);
                    $cache->deleteCache('filtro_veiculos');
                    $cache->setCache('filtro_veiculos', $filtros);
                }
                ;
                break;

            // load menu quantidade
            case 2:
                {
                    /*
                    $query = "SELECT count((ss.lote_id)) as qtde  , 'VeÃ­culos' as segmento   , 'veiculos'  as link
					FROM sslonline_veiculos lt "     . Veiculo::getQueryJoinsDefault() . " WHERE ss.leilao_id not in ($where_leilao_not_in)";

                    $rs = $conn->query($query);
                    $segmentos = false;
                    if ($rs) {
                        while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                            if (!empty($row)) {
                                $veiculos = array(
                                    'link' => $row['link'],
                                    'titulo' => $row['segmento'],
                                    'qtde' => $row['qtde'],
                                    'ativo' => 0,
                                );
                            }
                        }
                    }

                    $segmento_quantidades = $cache->getCache('segmento_quantidades');
                    $segmento_quantidades[0] = $veiculos;
                    $cache->deleteCache('segmento_quantidades');
                    $cache->setCache('segmento_quantidades', $segmento_quantidades);
                    */
                }
                ;
                break;

            // load array de navegacao
            case 3:
                {
                    $query_veiculos = Veiculo::query();
                    $query_veiculos .= Veiculo::make_where_veiculos(array(), 0);
                    $query_veiculos .= Veiculo::makeOrder('data_leilao');

                    $rs = $conn->query($query_veiculos);
                    $i = 0;
                    $busca = array();
                    if ($rs) {
                        while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                            $busca[] = array(
                                'link' => "/lote/{$row['tipo_segmento']}/{$row['segmento_id']}/",
                                'label' => " {$row['nu_lote']}",
                                'leilao' => "{$row['leilao_id']}",
                            );
                        }
                    }

                    $cache->deleteCache('array_navegacao_veiculos');
                    $cache->setCache('array_navegacao_veiculos', $busca);
                }
        }
    }
    public static function loadCacheMateriais($modo = 0)
    {
        global $conn, $where_leilao_not_in, $cache;

        switch ($modo) {

            // load cache destaques
            case 0:
                {
                    $material = new Material();

                    $destaques = $cache->getCache('destaques');
                    $destaques['material'] = $material->getDestaque(20);

                    $cache->deleteCache('destaques');
                    $cache->setCache('destaques', $destaques);
                }
                ;

            // load cache filtro de materiais
            case 1:
                {

                    $params = array();
                    $filtros = Material::getMateriasFiltros($params);
                    $cache->deleteCache('filtro_materiais');
                    $cache->setCache('filtro_materiais', $filtros);
                }
                ;
                break;

            // load menu quantidade
            case 2:
                {
                    /*
                    $query = "SELECT count((ss.lote_id)) as qtde   , 'Materiais' as segmento , 'materiais' as link
					from sslonline_materiais lt "     . Material::getQueryJoinsDefault() . " WHERE ss.leilao_id not in ($where_leilao_not_in) and ss.lote_id > 0";

                    $rs = $conn->query($query);
                    $segmentos = false;
                    if ($rs) {
                        while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                            if (!empty($row)) {
                                $materiais = array(
                                    'link' => $row['link'],
                                    'titulo' => $row['segmento'],
                                    'qtde' => $row['qtde'],
                                    'ativo' => 0,
                                );
                            }
                        }
                    }

                    $segmento_quantidades = $cache->getCache('segmento_quantidades');
                    $segmento_quantidades[1] = $materiais;
                    $cache->deleteCache('segmento_quantidades');
                    $cache->setCache('segmento_quantidades', $segmento_quantidades);
                    */
                }
                ;
                break;

            // load array de navegacao
            case 3:
                {

                    $query_materiais = Material::query();
                    $query_materiais .= Material::make_where_materiais(array(), 0);
                    $query_materiais .= Material::makeOrder('data_leilao');

                    $rs = $conn->query($query_materiais);
                    $busca = array();
                    if ($rs) {
                        while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                            $busca[] = array(
                                'link' => "/lote/{$row['tipo_segmento']}/{$row['segmento_id']}/",
                                'label' => " {$row['nu_lote']}",
                                'leilao' => "{$row['leilao_id']}",
                            );
                        }
                    }

                    $cache->deleteCache('array_navegacao_materiais');
                    $cache->setCache('array_navegacao_materiais', $busca);
                }
        }
    }
    public static function loadCacheImoveis($modo = 0)
    {
        global $conn, $where_leilao_not_in, $cache;
        switch ($modo) {

            // load cache destaques
            case 0:
                {
                    $imovel = new Imovel();

                    $destaques = $cache->getCache('destaques');
                    $destaques['imovel'] = $imovel->getDestaque(20);

                    $cache->deleteCache('destaques');
                    $cache->setCache('destaques', $destaques);
                }
                ;

            // load cache filtro de imoveis
            case 1:
                {
                    $params = array();
                    $filtros = Imovel::getImoveisFiltros($params);
                    $cache->deleteCache('filtro_imoveis');
                    $cache->setCache('filtro_imoveis', $filtros);
                }
                ;
                break;

            // load menu quantidade
            case 2:
                {
                    /*
                    $query = "select count((ss.lote_id))  as qtde  ,  'ImÃ³veis' as segmento  , 'imoveis'   as link
					from sslonline_imoveis lt "     . Imovel::getQueryJoinsDefault() . "
					where ss.leilao_id not in ($where_leilao_not_in) and ss.lote_id > 0";

                    $rs = $conn->query($query);
                    $segmentos = false;
                    if ($rs) {
                        while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                            if (!empty($row)) {
                                $imoveis = array(
                                    'link' => $row['link'],
                                    'titulo' => $row['segmento'],
                                    'qtde' => $row['qtde'],
                                    'ativo' => 0,
                                );
                            }
                        }
                    }

                    $segmento_quantidades = $cache->getCache('segmento_quantidades');
                    $segmento_quantidades[2] = $imoveis;
                    $cache->deleteCache('segmento_quantidades');
                    $cache->setCache('segmento_quantidades', $segmento_quantidades);
                    */
                }
                ;
                break;

            // load array de navegacao
            case 3:
                {

                    $query_imoveis = Imovel::query();
                    $query_imoveis .= Imovel::make_where_imoveis(array());
                    $query_imoveis .= Imovel::makeOrder('data_leilao');

                    $rs = $conn->query($query_imoveis);
                    $busca = array();
                    if ($rs) {
                        while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                            $busca[] = array(
                                'link' => "/lote/{$row['tipo_segmento']}/{$row['segmento_id']}/",
                                'label' => " {$row['nu_lote']}",
                                'leilao' => "{$row['leilao_id']}",
                            );
                        }
                    }

                    $cache->deleteCache('array_navegacao_imoveis');
                    $cache->setCache('array_navegacao_imoveis', $busca);
                }
        }
    }
    public static function loadCacheMaioresLances()
    {
        global $cache;
        $top = Lote::_getTop(50);
        $cache->deleteCache('maiores_lances');
        $cache->setCache('maiores_lances', $top);
    }
    public static function loadCacheJudicial($modo = 0)
    {
        global $conn, $where_leilao_not_in, $cache;
        switch ($modo) {
            // load menu quantidade
            case 2:
                {
                    /*
                    $query = "select count((ss.lote_id))  as qtde  ,  'Judiciais' as segmento, 'judiciais' as link
					from sslonline_status ss "     . Judicial::getQueryJoinsDefault() . "
					where j.leilao_id not in ($where_leilao_not_in)";

                    $rs = $conn->query($query);
                    $segmentos = false;
                    if ($rs) {
                        while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                            if (!empty($row)) {
                                $judicial = array(
                                    'link' => $row['link'],
                                    'titulo' => $row['segmento'],
                                    'qtde' => $row['qtde'],
                                    'ativo' => 0,
                                );
                            }
                        }
                    }

                    $segmento_quantidades = $cache->getCache('segmento_quantidades');
                    $segmento_quantidades[3] = $judicial;
                    $cache->deleteCache('segmento_quantidades');
                    $cache->setCache('segmento_quantidades', $segmento_quantidades);
                    */
                }
                ;
                break;

            // load array de navegacao
            case 3:
                {

                    $query_judiciais = Judicial::query(array());
                    $query_judiciais .= self::make_where_judiciais(array());
                    $query_judiciais .= " group by j.lote_id ";
                    $query_judiciais .= Judicial::makeOrder('data_leilao');

                    $rs = $conn->query($query_judiciais);
                    $busca = array();
                    if ($rs) {
                        while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                            $busca[] = array(
                                'link' => "/lote/{$row['tipo_segmento']}/{$row['segmento_id']}/",
                                'label' => " {$row['nu_lote']}",
                                'leilao' => "{$row['leilao_id']}",
                            );
                        }
                    }

                    $cache->deleteCache('array_navegacao_judiciais');
                    $cache->setCache('array_navegacao_judiciais', $busca);
                }
        }
    }
    public static function loadCacheLeilao($modo = 0)
    {
        global $cache;
        switch ($modo) {
            // lista de leilao
            case 0:
                {
                    $leiloes = Leilao::get_leiloes();
                    $cache->deleteCache('leiloes');
                    $cache->setCache('leiloes', $leiloes);
                }
                ;
                break;

            // filtro home
            case 1:
                {
                    $filtro = Leilao::make_filtros_leilao();
                    $cache->deleteCache('leiloes_filtro');
                    $cache->setCache('leiloes_filtro', $filtro);
                }
                ;
                break;
        }
    }
    public static function loadCacheCategorias()
    {
        global $cache;

        $menu = $cache->getCache('categorias');

        $menu_new['VeÃ­culos'] = Categorias::getCategoriasVeiculos();
        $menu_new['Materiais'] = Categorias::getCategoriasMateriais();
        $menu_new['ImÃ³veis'] = Categorias::getCategoriasImoveis();
        $cache->deleteCache('categorias');
        $cache->setCache('categorias', $menu_new);
    }
    public static function loadCacheMenu($limit = '15')
    {
        global $conn, $where_leilao_not_in, $cache;

        $menu = Categorias::menu();
        $cache->deleteCache('menu_categoria');
        $cache->setCache('menu_categoria', $menu);

        /*$limit_unitario = round($limit / 3);
        $menu = $cache->getCache('menu_categoria');

        $menu['VeÃ­culos'] = array();

        $query_veiculos = " select count(1) as qtde , C.NM_Categoria , 'veiculos' as segmento , '2' as prioridade , url_amigavel_nm_categoria as link
    	from sslonline_veiculos V
    	inner join leilao L ON V.leilao_id = L.leilao_id
    	inner join categorias C on (C.ST_Categoria = 1 and V.ST_Categoria = C.ID)
    	WHERE V.leilao_id not in ($where_leilao_not_in)
    	group by C.ID order by prioridade,qtde desc limit $limit_unitario ";

        $rs = $conn->query($query_veiculos);
        if ($rs) {
            while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                $menu['VeÃ­culos'][] = array(
                    'nm_categoria' => ucfirst(strtolower($row['NM_Categoria'])),
                    'qtde' => $row['qtde'],
                    'link' => "/" . $row['segmento'] . "/v_categoria/" . $row['link'] . "/",
                );
            }
        }

        if (isset($menu['VeÃ­culos'])) {
            sort($menu['VeÃ­culos']);
        }

        $menu['Materiais'] = array();

        $query_materiais = " select count(1) as qtde , C.NM_Categoria , 'materiais' as segmento , '3' as prioridade , url_amigavel_nm_categoria as link
    	from sslonline_materiais M
    	inner join leilao L ON M.leilao_id = L.leilao_id
    	inner join categorias C on (C.ST_Categoria = 2 and M.categoria_id = C.ID)
    	WHERE M.leilao_id not in ($where_leilao_not_in)
    	group by C.ID order by prioridade,qtde desc limit $limit_unitario ";

        $rs = $conn->query($query_materiais);
        if ($rs) {
            while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                $menu['Materiais'][] = array(
                    'nm_categoria' => ucfirst(strtolower($row['NM_Categoria'])),
                    'qtde' => $row['qtde'],
                    'link' => "/" . $row['segmento'] . "/m_categoria/" . $row['link'] . "/",
                );
            }
        }

        if (isset($menu['Materiais'])) {
            sort($menu['Materiais']);
        }

        // unset($menu['ImÃ³veis']);
        $menu['ImÃ³veis'] = array();

        $query_imoveis = " select count(1) as qtde , C.NM_Categoria , 'imoveis' as segmento , '1' as prioridade , url_amigavel_nm_categoria as link
    	from sslonline_imoveis I
    	inner join leilao L ON I.leilao_id = L.leilao_id
    	inner join categorias C on (C.ST_Categoria = 3 and I.categoria = C.ID)
    	WHERE I.leilao_id not in ($where_leilao_not_in)
    	group by C.ID order by prioridade,qtde desc limit $limit_unitario ";

        $rs = $conn->query($query_imoveis);
        if ($rs) {
            while (@$row = $rs->fetch(PDO::FETCH_ASSOC)) {
                $menu['ImÃ³veis'][] = array(
                    'nm_categoria' => ucfirst(strtolower($row['NM_Categoria'])),
                    'qtde' => $row['qtde'],
                    'link' => "/" . $row['segmento'] . "/i_categoria/" . $row['link'] . "/",
                );
            }
        }

        if (isset($menu['ImÃ³veis'])) {
            sort($menu['ImÃ³veis']);
        }

        $cache->deleteCache('menu_categoria');
        $cache->setCache('menu_categoria', $menu); */
    }
    public function existsRow($tabela = '', $where = '')
    {
        global $conn;

        if (!empty($where)) {
            $query = "SELECT * FROM " . $tabela . " WHERE " . $where;

            $rs = $conn->query($query);
            if ($rs->rowCount() > 0) {
                return true;
            }
        }
        return false;
    }
    public function loadTbMaterial($args = array())
    {
        if (count($args) > 0) {
            foreach ($args as $item) {

                $lote = array();
                $lote['lote_id'] = $item['lote_id'];
                $lote['leilao_id'] = $item['leilao_id'];
                $lote['segmento_id'] = $item['id'];
                $lote['lance_id'] = 0;
                $lote['categoria_id'] = $item['categoria_id'];
                $lote['deposito_id'] = $item['deposito_id'];
                $lote['deposito_leilao_id'] = 0;
                $lote['cliente_id'] = $item['cliente_id'];
                $lote['lote_id_principal'] = $item['lote_id_principal'];
                $lote['tipo_segmento'] = 'M';
                $lote['descricao'] = $item['descricao'];
                $lote['status_lote'] = $item['st_lote'];
                $lote['status_lote_online'] = $item['st_loteonline'];
                $lote['status_nao_visivel'] = $item['st_nao_visivel'];
                $lote['status_liberacao_condicao_empresa'] = 0;
                $lote['termino'] = '0000-00-00';
                $lote['data_inicio_leilao_online'] = $item['dt_inicioleilaoonline'];
                $lote['numero_lote'] = $item['lote'];
                $lote['numero_quantidade'] = $item['nu_qtde'];
                $lote['numero_contador_visita'] = 0;
                $lote['numero_quantidade_lance'] = 0;
                $lote['incremento_minimo'] = 0;
                $lote['valor_incremento'] = $item['vl_incremento'];
                $lote['valor_multiplo'] = 0;
                $lote['valor_lance_inicial'] = $item['vl_lanceinicial'];
                $lote['valor_lance_minimo'] = $item['vl_lanceminimo'];
                $lote['valor_maior_lance'] = 0;
                $lote['valor_contra_oferta'] = 0;
                $lote['nome_caminho_imagem'] = $item['nm_caminhoimagem'];
                $lote['nome_imagem'] = $item['nm_img'];
                $lote['nome_vistoria'] = $item['nm_vistoria'];
                $lote['nome_unidade'] = $item['nm_unidade'];
                $lote['nome_endereco_leilao'] = $item['nm_localleilao'];
                $lote['nome_deposito_leilao'] = $item['deposito'];
                $lote['nome_deposito_lote'] = $item['nm_depositoleilao'];
                $lote['nome_endereco_exposicao'] = $item['nm_enderecoexpo'];
                $lote['nome_local_data_visitacao'] = $item['nm_localdatavisitacao'];
                $lote['nome_retirada'] = '';
                $lote['nome_cliente'] = $item['cliente'];
                $lote['nome_link'] = $item['nm_link'];
                $lote['nome_mensagem_telao'] = $item['nm_mensagemtelao'];
                $lote['mensagem'] = '';
                $lote['bn_destaque'] = $item['bn_destaque'];
                $lote['encerrado'] = '';

                $where = "lote_id = '" . $item['lote_id'] . "' AND leilao_id = '" . $item['leilao_id'] . "' AND tipo_segmento = 'M'";
                if ($this->existsRow('lote', $where)) {

                    $where = array();
                    $where['lote_id'] = $item['lote_id'];
                    $where['leilao_id'] = $item['leilao_id'];
                    $where['tipo_segmento'] = "'M'";

                    self::update('lote', $lote, $where, true);
                } else {
                    self::insert("lote", $lote, true);
                }

                $tabela = array();
                $tabela['lote_id'] = $item['lote_id'];

                $tabela['leilao_id'] = $item['leilao_id'];
                $tabela['sslonline_materiais_id'] = $item['id'];
                $tabela['subcategoria_id'] = $item['subcategoria_id'];
                $tabela['carro_id'] = $item['carro_id'];
                $tabela['marca'] = $item['marca'];
                $tabela['modelo'] = $item['modelo'];
                $tabela['numero_ano'] = $item['nu_ano'];
                $tabela['nome_tipo'] = $item['nm_tipo'];
                $tabela['observacao_antes'] = $item['obs_antes'];
                $tabela['observacao_depois'] = $item['obs_depois'];
                $tabela['descricao_resumida'] = $item['descricao_resumida'];
                $tabela['url_amigavel_material_nome_deposito_leilao'] = $item['url_amigavel_m_nm_depositoleilao'];
                $tabela['url_amigavel_material_marca'] = $item['url_amigavel_m_marca'];
                $tabela['url_amigavel_material_modelo'] = $item['url_amigavel_m_modelo'];
                $tabela['url_amigavel_material_cliente'] = $item['url_amigavel_m_cliente'];
                $tabela['url_amigavel_material_deposito_lote'] = $item['url_amigavel_m_depositolote'];

                $where = "lote_id = '" . $item['lote_id'] . "' AND leilao_id = '" . $item['leilao_id'] . "'";
                if ($this->existsRow('material', $where)) {

                    $where = array();
                    $where['lote_id'] = $item['lote_id'];
                    $where['leilao_id'] = $item['leilao_id'];

                    self::update('material', $tabela, $where, true);
                } else {
                    self::insert("material", $tabela, true);
                }
            }
        }
    }
    public function loadTbImovel($args = array())
    {
        if (count($args) > 0) {
            foreach ($args as $item) {

                $lote = array();
                $lote['lote_id'] = $item['lote_id'];
                $lote['leilao_id'] = $item['leilao_id'];
                $lote['segmento_id'] = $item['id'];
                $lote['lance_id'] = 0;
                $lote['categoria_id'] = $item['categoria'];
                $lote['deposito_id'] = 0;
                $lote['deposito_leilao_id'] = 0;
                $lote['cliente_id'] = $item['cliente_id'];
                $lote['lote_id_principal'] = $item['lote_id_principal'];
                $lote['tipo_segmento'] = 'I';
                $lote['descricao'] = $item['descricao'];
                $lote['status_lote'] = $item['st_lote'];
                $lote['status_lote_online'] = $item['st_loteonline'];
                $lote['status_nao_visivel'] = $item['st_nao_visivel'];
                $lote['status_liberacao_condicao_empresa'] = 0;
                $lote['termino'] = '0000-00-00';
                $lote['data_inicio_leilao_online'] = $item['dt_inicioleilaoonline'];
                $lote['numero_lote'] = $item['lote'];
                $lote['numero_quantidade'] = $item['nu_qtde'];
                $lote['numero_contador_visita'] = 0;
                $lote['numero_quantidade_lance'] = 0;
                $lote['incremento_minimo'] = 0;
                $lote['valor_incremento'] = $item['vl_incremento'];
                $lote['valor_multiplo'] = 0;
                $lote['valor_lance_inicial'] = $item['vl_lanceinicial'];
                $lote['valor_lance_minimo'] = $item['vl_lanceminimo'];
                $lote['valor_maior_lance'] = 0;
                $lote['valor_contra_oferta'] = 0;
                $lote['nome_caminho_imagem'] = $item['nm_caminhoimagem'];
                $lote['nome_imagem'] = $item['nm_img'];
                $lote['nome_vistoria'] = $item['vistoria'];
                $lote['nome_unidade'] = $item['nm_unidade'];
                $lote['nome_endereco_leilao'] = '';
                $lote['nome_deposito_leilao'] = '';
                $lote['nome_deposito_lote'] = '';
                $lote['nome_endereco_exposicao'] = '';
                $lote['nome_local_data_visitacao'] = '';
                $lote['nome_retirada'] = '';
                $lote['nome_cliente'] = $item['nm_cliente'];
                $lote['nome_link'] = $item['nm_link'];
                $lote['nome_mensagem_telao'] = $item['nm_mensagemtelao'];
                $lote['mensagem'] = '';
                $lote['bn_destaque'] = '';
                $lote['encerrado'] = 1;

                $where = "lote_id = '" . $item['lote_id'] . "' AND leilao_id = '" . $item['leilao_id'] . "' AND tipo_segmento = 'I'";
                if ($this->existsRow('lote', $where)) {

                    $where = array();
                    $where['lote_id'] = $item['lote_id'];
                    $where['leilao_id'] = $item['leilao_id'];
                    $where['tipo_segmento'] = "'I'";

                    self::update('lote', $lote, $where, true);
                } else {
                    self::insert("lote", $lote, true);
                }

                $tabela = array();
                $tabela['lote_id'] = $item['lote_id'];

                $tabela['leilao_id'] = $item['leilao_id'];
                $tabela['sslonline_imoveis_id'] = $item['id'];
                $tabela['dormitorios'] = $item['dorms'];
                $tabela['area_util'] = $item['areautil'];
                $tabela['area_total'] = $item['areatotal'];
                $tabela['bairro'] = $item['bairro'];
                $tabela['cidade'] = $item['cidade'];
                $tabela['estado'] = $item['estado'];
                $tabela['endereco'] = $item['endereco'];
                $tabela['suites'] = $item['suites'];
                $tabela['bn_foto'] = $item['bn_foto'];
                $tabela['bn_ocupado'] = $item['bn_ocupado'];
                $tabela['url_amigavel_imovel_cidade'] = $item['url_amigavel_i_cidade'];
                $tabela['url_amigavel_imovel_bairro'] = $item['url_amigavel_i_bairro'];
                $tabela['url_amigavel_imovel_nome_cliente'] = $item['url_amigavel_i_nm_cliente'];

                $where = "lote_id = '" . $item['lote_id'] . "' AND leilao_id = '" . $item['leilao_id'] . "'";
                if ($this->existsRow('imovel', $where)) {

                    $where = array();
                    $where['lote_id'] = $item['lote_id'];
                    $where['leilao_id'] = $item['leilao_id'];

                    self::update('imovel', $tabela, $where, true);
                } else {
                    self::insert("imovel", $tabela, true);
                }
            }
        }
    }
    public function loadTbVeiculo($args = array())
    {
        if (count($args) > 0) {
            foreach ($args as $item) {

                $lote = array();
                $lote['lote_id'] = $item['lote_id'];
                $lote['leilao_id'] = $item['leilao_id'];
                $lote['segmento_id'] = $item['carro_id'];
                $lote['lance_id'] = 0;
                $lote['categoria_id'] = $item['st_categoria'];
                $lote['deposito_id'] = $item['deposito_id'];
                $lote['deposito_leilao_id'] = $item['depositoleilao_id'];
                $lote['cliente_id'] = $item['cliente_id'];
                $lote['lote_id_principal'] = $item['lote_id_principal'];
                $lote['tipo_segmento'] = 'V';
                $lote['descricao'] = $item['descricao'];
                $lote['status_lote'] = $item['st_lote'];
                $lote['status_lote_online'] = $item['st_loteonline'];
                $lote['status_nao_visivel'] = $item['st_nao_visivel'];
                $lote['status_liberacao_condicao_empresa'] = 0;
                $lote['termino'] = '0000-00-00';
                $lote['data_inicio_leilao_online'] = $item['dt_inicioleilaoonline'];
                $lote['numero_lote'] = $item['nu_lote'];
                $lote['numero_quantidade'] = $item['nu_qtde'];
                $lote['numero_contador_visita'] = 0;
                $lote['numero_quantidade_lance'] = 0;
                $lote['incremento_minimo'] = 0;
                $lote['valor_incremento'] = $item['vl_incremento'];
                $lote['valor_multiplo'] = 0;
                $lote['valor_lance_inicial'] = $item['vl_lanceinicial'];
                $lote['valor_lance_minimo'] = $item['vl_lanceminimo'];
                $lote['valor_maior_lance'] = 0;




                $lote['valor_contra_oferta'] = 0;
                $lote['nome_caminho_imagem'] = $item['nm_caminhoimagem'];
                $lote['nome_imagem'] = $item['nm_img'];
                $lote['nome_vistoria'] = $item['nm_vistoria'];
                $lote['nome_unidade'] = $item['nm_unidade'];
                $lote['nome_endereco_leilao'] = $item['nm_enderecoleilao'];
                $lote['nome_deposito_leilao'] = $item['nm_depositoleilao'];
                $lote['nome_deposito_lote'] = $item['nm_depositolote'];
                $lote['nome_endereco_exposicao'] = $item['nm_enderecoexpo'];
                $lote['nome_local_data_visitacao'] = $item['nm_localdatavisitacao'];
                $lote['nome_cliente'] = $item['nm_cliente'];
                $lote['nome_link'] = $item['nm_link'];
                $lote['nome_mensagem_telao'] = $item['nm_mensagemtelao'];
                $lote['mensagem'] = '';
                $lote['bn_destaque'] = $item['bn_destaque'];
                $lote['bn_simultaneo_patios'] = !empty($item['bn_simultaneopatios']) ? $item['bn_simultaneopatios'] : 0;
                $lote['encerrado'] = 1;

                $where = "lote_id = '" . $item['lote_id'] . "' AND leilao_id = '" . $item['leilao_id'] . "' AND tipo_segmento = 'V'";
                if ($this->existsRow('lote', $where)) {

                    $where = array();
                    $where['lote_id'] = $item['lote_id'];
                    $where['leilao_id'] = $item['leilao_id'];
                    $where['tipo_segmento'] = "'V'";

                    self::update('lote', $lote, $where, true);
                } else {
                    self::insert("lote", $lote, true);
                }

                $tabela = array();
                $tabela['sslonline_veiculos_id'] = $item['carro_id'];
                $tabela['lote_id'] = $item['lote_id'];
                $tabela['leilao_id'] = $item['leilao_id'];
                $tabela['coligacao_id'] = $item['coligacao_id'];
                $tabela['data_leilao'] = $item['lote_id'];
                $tabela['numero_ano_fabricacao'] = $item['nu_anofab'];
                $tabela['numero_ano_modelo'] = $item['nu_anomodelo'];
                $tabela['status_estado_geral'] = $item['st_estadogeral'];
                $tabela['nome_marca'] = $item['nm_marca'];
                $tabela['nome_modelo'] = $item['nm_modelo'];
                $tabela['status_combustivel'] = $item['st_combustivel'];
                $tabela['nome_placa'] = $item['nm_placa'];
                $tabela['nome_chassi_origem'] = $item['nm_chassiorig'];
                $tabela['nome_chassi_adult'] = $item['nm_chassiadult'];
                $tabela['status_espelho_eletrico'] = $item['st_espelhoeletrico'];
                $tabela['status_trava_eletrica'] = $item['st_travaeletrica'];
                $tabela['status_vidro_eletrico'] = $item['st_vidroeletrico'];
                $tabela['status_direcao_hidraulica'] = $item['st_direcaohidraulica'];
                $tabela['bn_funcionando'] = $item['bn_funcionando'];
                $tabela['bn_chaves'] = $item['bn_chaves'];
                $tabela['bn_zero'] = $item['bn_chaves'];
                $tabela['bn_importado'] = $item['bn_importado'];
                $tabela['bn_ar_condicionado'] = $item['bn_arcondicionado'];
                $tabela['nome_km'] = $item['nu_km'];
                $tabela['nome_cor'] = $item['nm_cor'];
                $tabela['nome_obs'] = $item['nm_obs'];
                $tabela['nome_sinistro'] = $item['nm_sinistro'];
                $tabela['valor_jornal_do_carro'] = $item['vl_jornaldocarro'];
                $tabela['omni_financiavel'] = $item['omni_financiavel'];
                $tabela['status_financiavel'] = $item['st_financiavel'];
                $tabela['modelo_nome'] = $item['modelo_nome'];
                $tabela['modelo_versao'] = $item['modelo_versao'];
                $tabela['nome_renavam'] = $item['nm_renavam'];
                $tabela['valor_debitos'] = $item['vl_debitos'];
                $tabela['bn_sinistrado'] = $item['bn_sinistrado'];
                $tabela['nome_retirada'] = $item['nm_retirada'];
                $tabela['status_sucata_tipo'] = $item['st_sucatatipo'];
                $tabela['status_ar_condicionado'] = $item['st_arcondicionado'];
                $tabela['status_blindagem'] = $item['st_blindagem'];
                $tabela['status_kit_gas'] = $item['st_kitgas'];
                $tabela['status_origem'] = $item['st_origem'];
                $tabela['status_cambio'] = $item['st_cambio'];
                $tabela['url_amigavel_veiculo_nome_deposito_leilao'] = $item['url_amigavel_v_nm_depositoleilao'];
                $tabela['url_amigavel_veiculo_nome_marca'] = $item['url_amigavel_v_nm_marca'];
                $tabela['url_amigavel_veiculo_nome_modelo'] = $item['url_amigavel_v_nm_modelo'];
                $tabela['url_amigavel_veiculo_nome_cliente'] = $item['url_amigavel_v_nm_cliente'];
                $tabela['url_amigavel_veiculo_nome_deposito_lote'] = $item['url_amigavel_v_nm_depositolote'];
                $tabela['url_amigavel_status_ar_condicionado'] = $item['url_amigavel_st_ar_condicionado'];
                $tabela['url_amigavel_status_blindagem'] = $item['url_amigavel_st_blindagem'];
                $tabela['url_amigavel_status_direcao_hidraulica'] = $item['url_amigavel_st_direcaohidraulica'];
                $tabela['url_amigavel_status_kitgas'] = $item['url_amigavel_st_kitgas'];
                $tabela['url_amigavel_status_origem'] = $item['url_amigavel_st_origem'];
                $tabela['url_amigavel_status_cambio'] = $item['url_amigavel_st_cambio'];

                $where = "lote_id = '" . $item['lote_id'] . "' AND leilao_id = '" . $item['leilao_id'] . "'";
                if ($this->existsRow('veiculo', $where)) {

                    $where = array();
                    $where['lote_id'] = $item['lote_id'];
                    $where['leilao_id'] = $item['leilao_id'];

                    self::update('veiculo', $tabela, $where, true);
                } else {
                    self::insert("veiculo", $tabela, true);
                }
            }
        }
    }

    /**
    * Retorna os ultimos arquivos do mesmo tipo que foram feito upload pelo usuario
    *
    * @param mixed $user_id
    */
    public static function getDocsSalvos_arquivo_lote($user_id){

        global $conn;

        $pessoais = array();
        $lotes = array();

        $query = "SELECT t.nome AS nm_tipo,t.rotulo,a.*,t.data_validade as mostra_validade
                FROM apss_users_arquivos a
                INNER JOIN apss_users_arquivo_tipo t
                ON t.id = a.tipo_id
                WHERE a.id IN (
                SELECT MAX(b.id) FROM apss_users_arquivos b WHERE b.user_id = {$user_id} AND b.status = 1 GROUP BY b.conjunto_id, b.tipo_id, b.tipo
                )
                AND user_id = {$user_id}
                AND a.status = 1
                AND a.situacao <> 'expirado'
                ORDER BY a.tipo;";
        $rs = $conn->query($query);
        while($row = $rs->fetch(PDO::FETCH_ASSOC)){
            $row['param'] = 'doc_pessoal';

            $row['data_validade'] = $row['data_validade'] == '0000-00-00' || $row['data_validade'] == null ? '' : date('d/m/Y',strtotime($row['data_validade']));
            $pessoais[] = $row;
        }

        $query = "SELECT t.nome AS nm_tipo,t.rotulo,a.*
                FROM apss_users_documento_lote a
                INNER JOIN apss_users_documento_lote_tipo t
                ON t.id = a.tipo_id
                WHERE a.id IN (
                SELECT MAX(b.id) FROM apss_users_documento_lote b WHERE b.user_id = {$user_id} AND b.status = 1 GROUP BY b.leilao_id, b.lote_id, b.tipo
                )
                AND user_id = {$user_id}
                AND a.status = 1
                ORDER BY a.tipo;";
        $rs = $conn->query($query);
        while($row = $rs->fetch(PDO::FETCH_ASSOC)){
            $row['param'] = 'doc_lote';
            $lotes[] = $row;
        }

        return array('pessoais'=>$pessoais,'lotes'=>$lotes);
    }

    /**
    * Traz as perguntas para liberar alteracao do CPF tabela "apss_users_valida_alteracao_cpf"
    *
    * @param mixed $id
    */
    public static function getPerguntasValidaCPF($id = ''){
        global $conn;

        if (empty($id)) {
            $query = "SELECT * FROM apss_users_valida_alteracao_cpf WHERE status = 1";
        } else {
            $query = "SELECT * FROM apss_users_valida_alteracao_cpf WHERE status = 1 AND id = '" . $id . "'";
        }
        $retorno = Transacao::getQueryArray($query);

        return $retorno;
    }

    /**
    * Insere uma cï¿½pia do arquivo de REF trocando apenas o lote_id, nu_lote
    *
    * @param mixed $idDoc = ID do documento de REF
    * @param mixed $lote_id = ID do lote
    * @param mixed $nu_lote = numero do lote
    */
    public static function copiaDocumentoLote($idDoc,$lote_id,$nu_lote){
        global $conn;

        $query = "SELECT * FROM apss_users_documento_lote WHERE id = {$idDoc}";
        $documento = $conn->query($query)->fetch(PDO::FETCH_ASSOC);

        unset($documento['data_solicitacao']);

        $documetoAtual = $documento['caminho'];

        $nu_lote = (int)trim($nu_lote);

        array_shift($documento);

        $documento['lote_id'] = $lote_id;

        $documento['nu_lote'] = str_pad($nu_lote,4,0,STR_PAD_LEFT);

        $documento['situacao'] = 'aguardando_aprovacao';

        $aux2 = explode('_',$documento['nome_criado']);
        $documento['nome_criado'] = implode('_',array($aux2[0],$aux2[1],$lote_id,$aux2[3],$aux2[4],$aux2[5]));

        $aux3 = explode('_',$documento['caminho']);
        $documento['caminho'] = implode('_',array($aux3[0],$aux3[1],$lote_id,$aux3[3],$aux3[4],$aux3[5]));

        $nome_coluna  = implode(',',array_keys($documento));
        $valor_coluna = "'".implode("','",array_values($documento))."'";

        //faz copia da foto
        if(!copy($documetoAtual,$documento['caminho']))
            return false;

        //faz copia do arquivo com os novos valores
        $query = "INSERT INTO apss_users_documento_lote ({$nome_coluna},data_solicitacao) VALUES ({$valor_coluna},NOW())";

        if($conn->query($query)){
            return true;
        }else{
            return false;
        }
    }

    /**
    * Faz upadate da situa��o dos documentos com data_validade expirada;
    * R
    */
    public static function setDocumentoExpirado(){
        global $conn;

        $query = "SELECT id FROM apss_users_arquivos a WHERE a.situacao = 'aprovado' AND a.data_validade IS NOT NULL AND a.data_validade <> '0000-00-00' AND a.data_validade < NOW();";
        $rs = $conn->query($query);

        $ids = array();
        while($row = $rs->fetch(PDO::FETCH_ASSOC)){
            $ids[] = $row['id'];
        }
        $ids = implode(',',$ids);

        if(!empty($ids)){
            $query = "UPDATE apss_users_arquivos SET situacao = 'expirado' WHERE id IN ({$ids})";
            $conn->query($query);
            Transacao::commit();

            return true;
        }
        return false;
    }

    /**
     * Metodo para copiar os dados das tabelas (leilao, sslonlinestatus, sslonline_lance) para
     * (leilao_encerrado, sslonlinestatus_encerrado, sslonline_lance_encerrado)
     * executado apenas na acao de "cadastrar nao vendido" do TELAO
     * @param int $leilao_id
     * @return boolean
     */
    public function copiarLotesEncerrados($leilao_id)
    {
        global $conn;

        $leilao_id = (int)$leilao_id;

        //Verifica se o leiao ja foi inserido na tabela leilao_encerrado
        $query = "SELECT 1 FROM leilao_encerrado WHERE leilao_id = $leilao_id ;";
        $existe = $conn->query($query)->fetch(PDO::FETCH_ASSOC);
        if(!$existe) {
            // 1 - FAZ COPIA DA TABELA LEILAO PARA LEILAO_ENCERRADO
            $query = "SELECT leilao_id
                            ,dt_leilao
                            ,deposito
                            ,deposito_ID
                            ,st_categoria
                            ,mm_edital
                            ,st_leilaofisico
                            ,st_leilaoonline
                            ,st_leilaointerno
                            ,st_montagem
                            ,qt_lotes
                            ,horario_leilao
                            ,nm_endereco
                            ,nm_leilao
                            ,nm_junta
                            ,nm_leiloeiro
                            ,st_aeronave
                            ,nu_diaspropostas
                            ,qt_lotes_online
                            ,bn_enviocomprova
                            ,comissao
                            ,st_leilao
                            ,dt_time_leilao
                            ,bn_fechamentosequencial
                            ,nu_intervalolote
                            ,nm_tipoleilao
                            ,cliente_id
                            ,BN_BloqueioBoleto
                            ,MM_FormaPagto
                            ,dt_encerramento
                            ,url_amigavel_i_deposito
                            ,url_amigavel_j_nm_tipoleilao
                        FROM leilao WHERE st_leilao = 'E' AND leilao_id = $leilao_id ";

            $rs = $conn->query($query);
            $arrLeilao = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);

            if (date("Y-m-d", strtotime($arrLeilao[0]['dt_encerramento'])) == '1969-12-31') {
                $arrLeilao[0]['dt_encerramento'] = date('Y-m-d', strtotime($arrLeilao[0]['dt_leilao'] . '+7day')) . " 12:00:00";
            }

            $colunas = implode(",", array_keys($arrLeilao[0]));
            $valores = "'" . implode("','", array_values($arrLeilao[0])) . "'";

            $query2 = "INSERT INTO leilao_encerrado ($colunas,data_insercao) VALUES ($valores,NOW());";

            if ($query2) {
                $conn->query($query2);
                Transacao::commit();
            }

            // 2 - FAZ COPIA DA TABELA SSLONLINE_STATUS PARA SSLONLINE_STATUS_ENCERRADO
            $query = "INSERT INTO sslonline_status_encerrado
                     (
                        SELECT s.*,NOW()
                        FROM sslonline_status s
                        INNER JOIN leilao_encerrado l ON l.leilao_id = s.leilao_id
                        WHERE l.st_leilao = 'E' and s.leilao_id not in (select leilao_id from sslonline_status_encerrado group by leilao_id)
                     ) ";
            $conn->query($query);
            Transacao::commit();

            // 3 - FAZ COPIA DA TABELA SSLONLINE_LANCE PARA SSLONLINE_LANCE_ENCERRADO COM OS ULTIMOS 4 LANCES DE CADA LOTE
            $query = "SELECT lote_id FROM sslonline_status_encerrado WHERE leilao_id = $leilao_id";
            $rs = $conn->query($query);
            while ($row = $rs->fetch(PDO::FETCH_ASSOC)) {
                $query2 = "SELECT * FROM sslonline_lance l WHERE l.lote_id = {$row['lote_id']} ORDER BY l.sslonline_lance_id DESC LIMIT 4";
                $arrLance = $conn->query($query2)->fetchAll(PDO::FETCH_ASSOC);

                $query3 = '';
                foreach ($arrLance as $valor) {
                    $colunas = implode(",", array_keys($valor));
                    $valores = "'" . implode("','", array_values($valor)) . "'";

                    $query3 .= "INSERT INTO sslonline_lance_encerrado ($colunas,data_insercao) VALUES ($valores,NOW());";
                }

                if ($query3 != '') {
                    $conn->query($query3);
                    Transacao::commit();
                }
            }

            return true;
        }
        return false;
    }

    /**
     * Metodo para copiar os dados das tabelas (leilao, sslonlinestatus, sslonline_lance) para
     * (leilao_encerrado, sslonlinestatus_encerrado, sslonline_lance_encerrado)
     * executado apenas quando � rodado o "UPDATE_CATALOGO"
     * @return boolean
     */
    public function copiarLotesEncerradosGeral()
    {
        global $conn;

        // 1 - FAZ COPIA DA TABELA LEILAO PARA LEILAO_ENCERRADO
        $query = "SELECT l.leilao_id,
                        l.dt_leilao,
                        l.deposito,
                        l.deposito_ID,
                        l.st_categoria,
                        l.mm_edital,
                        l.st_leilaofisico,
                        l.st_leilaoonline,
                        l.st_leilaointerno,
                        l.st_montagem,
                        l.qt_lotes,
                        l.horario_leilao,
                        l.nm_endereco,
                        l.nm_leilao,
                        l.nm_junta,
                        l.nm_leiloeiro,
                        l.st_aeronave,
                        l.nu_diaspropostas,
                        l.qt_lotes_online,
                        l.bn_enviocomprova,
                        l.comissao,
                        l.st_leilao,
                        l.dt_time_leilao,
                        l.bn_fechamentosequencial,
                        l.nu_intervalolote,
                        l.nm_tipoleilao,
                        l.cliente_id,
                        l.BN_BloqueioBoleto,
                        l.MM_FormaPagto,
                        l.dt_encerramento,
                        l.url_amigavel_i_deposito,
                        l.url_amigavel_j_nm_tipoleilao
                    FROM leilao l
                    LEFT JOIN leilao_encerrado le
                        ON (le.leilao_id = l.leilao_id)
                    WHERE l.st_leilao = 'E'
                        AND le.data_insercao IS NULL ";

        $rs = $conn->query($query);
        $arrLeilao = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);

        foreach($arrLeilao as $valor){
            $query = '';

            if(date("Y-m-d",strtotime($valor['dt_encerramento'])) == '1969-12-31'){
               $valor['dt_encerramento'] = date('Y-m-d',strtotime($valor['dt_leilao'].'+7day'))." 12:00:00";
            }

            $colunas = implode(",",array_keys($valor));
            $valores = "'".implode("','",array_values($valor))."'";

            $query = "INSERT INTO leilao_encerrado ($colunas,data_insercao) VALUES ($valores,NOW());";

            if($query){
                $conn->query($query);
                Transacao::commit();
            }

            // 2 - FAZ COPIA DA TABELA SSLONLINE_STATUS PARA SSLONLINE_STATUS_ENCERRADO
            $query = "INSERT INTO sslonline_status_encerrado
                 (
                    SELECT s.*,NOW()
                    FROM sslonline_status s
                    INNER JOIN leilao_encerrado l ON l.leilao_id = s.leilao_id
                    WHERE l.st_leilao = 'E' and s.leilao_id not in (select leilao_id from sslonline_status_encerrado group by leilao_id)
                 ) ";
            $conn->query($query);
            Transacao::commit();

            // 3 - FAZ COPIA DA TABELA SSLONLINE_LANCE PARA SSLONLINE_LANCE_ENCERRADO COM OS ULTIMOS 4 LANCES DE CADA LOTE
            $query = "SELECT lote_id FROM sslonline_status_encerrado WHERE leilao_id = {$valor['leilao_id']}";
            $rs = $conn->query($query);
            while($row = $rs->fetch(PDO::FETCH_ASSOC)){
                $query2 = "SELECT * FROM sslonline_lance l WHERE l.lote_id = {$row['lote_id']} ORDER BY l.sslonline_lance_id DESC LIMIT 4";
                $arrLance = $conn->query($query2)->fetchAll(PDO::FETCH_ASSOC);

                $query3 = '';
                foreach($arrLance as $valor){
                    $colunas = implode(",",array_keys($valor));
                    $valores = "'".implode("','",array_values($valor))."'";

                    $query3 .= "INSERT INTO sslonline_lance_encerrado ($colunas,data_insercao) VALUES ($valores,NOW());";
                }

                if($query3 != ''){
                    $conn->query($query3);
                    Transacao::commit();
                }
            }
        }

        return true;
    }

     //Desativado
     /** public function atualizaCacheCPTM(){
        global $conn, $cache;

        $query = "SELECT user_id FROM leilaoCPTM_temp";

        $rs = $conn->query($query);
        while($row=$rs->fetch(PDO::FETCH_ASSOC) ){
            $usuariosCPTM[] = $row['user_id'];
        }

        $cache->setCache('usuariosCPTM',$usuariosCPTM);
    }*/

}
