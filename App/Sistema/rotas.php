<?php
    use Rocharor\Site\Controllers\Home;
    use Rocharor\Site\Controllers\Contato;
    use Rocharor\Site\Controllers\Login;
    use Rocharor\Site\Controllers\Cadastro;
    use Rocharor\Site\Controllers\CadastroProduto;
    use Rocharor\Site\Controllers\Produto;
    use Rocharor\MinhaConta\Controllers\Perfil;
    use Rocharor\MinhaConta\Controllers\MeusProdutos;
    use Rocharor\MinhaConta\Controllers\MeusFavoritos;
    use Rocharor\Site\Controllers\Favorito;
    
    $app = new Silex\Application();
    
    // ======================
    // Links Menu
    // ======================
    $app->get('/', function () {
        $objHome = new Home();
        $objHome->indexAction();
        return false;
    });
    
    $app->get('/produto/', function () {
        $objContato = new Produto();
        $objContato->indexAction();
        return false;
    });
    
    $app->get('/contato/', function () {
        $objContato = new Contato();
        $objContato->indexAction();
        return false;
    });
    
    // ======================
    // Topo
    // ======================
    $app->post('/login/', function () {
        $objLogin = new Login();
        $objLogin->indexAction();
        return false;
    });
    
    $app->post('/Login/deslogar/', function () {
        $objLogin = new Login();
        $objLogin->deslogarAction();
        return false;
    });
    
    $app->post('/cadastro/', function () {
        $objLogin = new Cadastro();
        $objLogin->indexAction();
        return false;
    });
    
    $app->get('/CadastroProduto/', function () {
        $objLogin = new CadastroProduto();
        $objLogin->indexAction();
        return false;
    });
    
    $app->get('/Perfil/', function () {
        $objLogin = new Perfil();
        $objLogin->indexAction();
        return false;
    });
    
    $app->get('/MeusProdutos/', function () {
        $objLogin = new MeusProdutos();
        $objLogin->indexAction();
        return false;
    });
    
    $app->get('/MeusFavoritos/', function () {
        $objLogin = new MeusFavoritos();
        $objLogin->indexAction();
        return false;
    });
    
    // ======================
    // Ações paginas
    // ======================
    
    $app->post('/CadastroProduto/cadastrar/', function () {
        $objLogin = new CadastroProduto();
        $objLogin->cadastrarAction();
        return false;
    });
    
    $app->get('/produto/todosProdutos/pg/{pg_num}/', function ($pg_num) {
        $objLogin = new Produto($pg_num);
        $objLogin->todosProdutosAction();
        return false;
    });
    
    $app->post('/contato/', function () {
        $objContato = new Contato();
        $objContato->indexAction();
        return false;
    });
    
    
    $app->post('/Perfil/updateFoto/', function () {
        $objContato = new Perfil();
        $objContato->updateFotoAction();
        return false;
    });
    
    $app->post('/Perfil/updatePerfil/', function () {
        $objContato = new Perfil();
        $objContato->updatePerfilAction();
        return false;
    });
    
    
    $app->post('/Produto/getDescricaoProduto/', function () {
        $objContato = new Produto();
        $objContato->getDescricaoProdutoAction();
        return false;
    });
    
    $app->post('/favorito/setFavorito/', function () {
        $objFavorito = new Favorito();
        $objFavorito->setFavoritoAction();
        return false;
    });
        
    
    $app->run();