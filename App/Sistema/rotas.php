<?php
use Rocharor\MinhaConta\Controllers\MeusFavoritos;
use Rocharor\MinhaConta\Controllers\MeusProdutos;
use Rocharor\MinhaConta\Controllers\Perfil;
use Rocharor\Site\Controllers\Cadastro;
use Rocharor\Site\Controllers\CadastroProduto;
use Rocharor\Site\Controllers\Contato;
use Rocharor\Site\Controllers\Home;
use Rocharor\Site\Controllers\Login;
use Rocharor\Site\Controllers\Produto;
use Rocharor\Admin\Controllers\HomeAdmin;

$app = new Silex\Application();
$app['debug'] = true;

// ======================
// Links Menu
// ======================

// ======================
// HOME
// ======================
$app->get('/', function () {
    $objHome = new Home();
    $objHome->indexAction();
    return false;
});
// ======================
// PRODUTOS
// ======================
$app->get('/produto/', function () {
    $objContato = new Produto();
    $objContato->indexAction();
    return false;
});

$app->get('/produto/todosProdutos/pg/{pg_num}/', function ($pg_num) {
    $objLogin = new Produto($pg_num);
    $objLogin->todosProdutosAction();
    return false;
});

$app->post('/Produto/getDescricaoProduto/', function () {
    $objContato = new Produto();
    $objContato->getDescricaoProdutoAction();
    return false;
});
// ======================
// CONTATO
// ======================
$app->get('/contato/', function () {
    $objContato = new Contato();
    $objContato->indexAction();
    return false;
});

$app->post('/contato/', function () {
    $objContato = new Contato();
    $objContato->indexAction();
    return false;
});
// ======================
// PERFIL
// ======================
$app->get('/Perfil/', function () {
    $objLogin = new Perfil();
    $objLogin->indexAction();
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
// ======================
// MEUS PRODUTOS
// ======================
$app->get('/MeusProdutos/', function () {
    $objLogin = new MeusProdutos();
    $objLogin->indexAction();
    return false;
});

$app->post('/MeusFavoritos/setFavorito/', function () {
    $objMeusFavorito = new MeusFavoritos();
    $objMeusFavorito->setFavoritoAction();
    return false;
});

$app->post('/MeusProdutos/deletarProduto/', function () {
    $objMeusProdutos = new MeusProdutos();
    $objMeusProdutos->deletarProdutoAction();
    return false;
});

$app->post('/MeusProdutos/deletarFoto/', function () {
    $objMeusProdutos = new MeusProdutos();
    $objMeusProdutos->deletarFotoAction();
    return false;
});

$app->get('/MeusProdutos/meusProdutosEditar/produto/{produto_id}', function ($produto_id) {
    $objMeusProdutos = new MeusProdutos();
    $objMeusProdutos->meusProdutosEditarAction($produto_id);
    return false;
});

$app->post('/MeusProdutos/alterarProduto/produto/{produto_id}/', function ($produto_id) {
    $objMeusProdutos = new MeusProdutos();
    $objMeusProdutos->alterarProdutoAction($produto_id);
    return false;
});

// ======================
// MEUS FAVORITOS
// ======================
$app->get('/MeusFavoritos/', function () {
    $objLogin = new MeusFavoritos();
    $objLogin->indexAction();
    return false;
});

// ======================
// CADASTRO DE PRODUTOS
// ======================
$app->get('/CadastroProduto/', function () {
    $objLogin = new CadastroProduto();
    $objLogin->indexAction();
    return false;
});

$app->post('/CadastroProduto/cadastrar/', function () {
    $objLogin = new CadastroProduto();
    $objLogin->cadastrarAction();
    return false;
});

// ======================
// LOGIN
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

// ======================
// ADMIN
// ======================

$app->get('/admin/', function () {
    $objHomeAdmin = new HomeAdmin();
    $objHomeAdmin->indexAction();
    return false;
});

// ======================
// ERRO
// ======================
$app->error(function () {
    $objHome = new Home();
    $objHome->erroAction();
    return false;
});

$app->run();