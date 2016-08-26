<?php

    use Rocharor\Sistema\Conexao;
    use Rocharor\Sistema\Sessao;
 
    if($_SERVER['HTTP_HOST'] == 'localhost'){
        $caminho = '/BrechoAdventure/Public';        
        error_reporting(E_ALL);
        ini_set('display_errors', 1);        
    }else{
        $caminho = '';
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }

    Sessao::abrirSessao();

    define('INC_ROOT',str_replace('\\','/',dirname(__DIR__)));
    define('_PUBLIC_',$caminho);
    define('_CONFIG_',INC_ROOT.'/App/Sistema/config/');
    define('_IMAGENS_',INC_ROOT.'/Public/imagens/');

    define('CONTROLLERS',  INC_ROOT.'/App/Modulos/Site/Controllers/');
    define('VIEWS', INC_ROOT.'/App/Modulos/Site/Views/');
    define('MODELS',  INC_ROOT.'/App/Modulos/Site/Models/');

    define('CONTROLLERS_MC',  INC_ROOT.'/App/Modulos/MinhaConta/Controllers/');
    define('VIEWS_MC', INC_ROOT.'/App/Modulos/MinhaConta/Views/');
    define('MODELS_MC',  INC_ROOT.'/App/Modulos/MinhaConta/Models/');

    define('VIEWS_ADMIN', INC_ROOT.'/App/Modulos/Admin/Views/');

    $conn = new Conexao(_CONFIG_.'mysql.ini');
    $conn = $conn ->open();

    $smarty = new Smarty;
    $smarty->setTemplateDir(VIEWS);
    $smarty->setCompileDir(INC_ROOT . '/vendor/smarty/smarty/libs/templates_c/');
    $smarty->setConfigDir(INC_ROOT . '/vendor/smarty/smarty/libs/config/');
    $smarty->setCacheDir(INC_ROOT  . '/vendor/smarty/smarty/libs/cache/');

