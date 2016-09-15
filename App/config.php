<?php

    use Rocharor\Sistema\Conexao;
    use Rocharor\Sistema\Sessao;
    use Rocharor\Sistema\Padrao;

    define('INC_ROOT',str_replace('\\','/',dirname(__DIR__)));
    
    define('_CONFIG_',INC_ROOT.'/App/Sistema/config/');
    define('_IMAGENS_',INC_ROOT.'/Public/imagens/');
    define('_LIBS_',INC_ROOT.'/Public/libs/');
    
    define('VIEWS', INC_ROOT.'/App/Modulos/Site/Views/');   
    define('VIEWS_MC', INC_ROOT.'/App/Modulos/MinhaConta/Views/');
    define('VIEWS_ADMIN', INC_ROOT.'/App/Modulos/Admin/Views/');
    
    Padrao::validaServidor();
    
    Sessao::abrirSessao();

    $conn = new Conexao(_CONFIG_.'mysql.ini');
    $conn = $conn ->open();

    $smarty = new Smarty;
    $smarty->setTemplateDir(VIEWS);
    $smarty->setCompileDir(INC_ROOT . '/vendor/smarty/smarty/libs/templates_c/');
    $smarty->setConfigDir(INC_ROOT . '/vendor/smarty/smarty/libs/config/');
    $smarty->setCacheDir(INC_ROOT  . '/vendor/smarty/smarty/libs/cache/');

