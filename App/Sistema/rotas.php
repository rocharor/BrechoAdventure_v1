<?php
use Rocharor\Site\Controllers\Cadastro;
use Rocharor\Site\Controllers\CadastroProduto;
use Rocharor\Site\Controllers\Contato;
use Rocharor\Site\Controllers\Home;
use Rocharor\Site\Controllers\Login;
use Rocharor\Site\Controllers\Produto;
use Rocharor\MinhaConta\Controllers\MeusFavoritos;
use Rocharor\MinhaConta\Controllers\MeusProdutos;
use Rocharor\MinhaConta\Controllers\Perfil;
use Rocharor\Admin\Controllers\Admin;

$app = new Silex\Application();
$app['debug'] = true;

// ======================
// HOME
// ======================
$app->get('/', function () {
    $objHome = new Home();
    $objHome->indexAction();
    return false;
});
$app->get('/busca/{busca}/', function ($busca){
    $objLogin = new Produto(1);
    $objLogin->getBusca($busca);
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

$app->get('/produto/todosProdutos/categoria/{categorias}/', function ($categorias) use ($app){
    $objLogin = new Produto(1);
    $objLogin->todosProdutosAction($categorias);
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
$app->get('/minha-conta/perfil/', function () {
    $objLogin = new Perfil();
    $objLogin->indexAction();
    return false;
});

$app->post('/minha-conta/perfil/updateFoto/', function () {
	
    $objContato = new Perfil();
    $objContato->updateFotoAction();
    return false;
});

$app->post('/minha-conta/perfil/updatePerfil/', function () {	
    $objContato = new Perfil();
    $objContato->updatePerfilAction();
    return false;
});

// ======================
// MEUS PRODUTOS
// ======================
$app->get('/minha-conta/meus-produtos/', function () {
	
    $objLogin = new MeusProdutos();
    $objLogin->indexAction();
    return false;
});

$app->post('/minha-conta/meus-produtos/deletarProduto/', function () {
    $objMeusProdutos = new MeusProdutos();
    $objMeusProdutos->deletarProdutoAction();
    return false;
});

$app->post('/minha-conta/meus-produtos/deletarFoto/', function () {
    $objMeusProdutos = new MeusProdutos();
    $objMeusProdutos->deletarFotoAction();
    return false;
});

$app->get('/minha-conta/meus-produtos/meusProdutosEditar/produto/{produto_id}', function ($produto_id) {
    $objMeusProdutos = new MeusProdutos();
    $objMeusProdutos->meusProdutosEditarAction($produto_id);
    return false;
});

$app->post('/minha-conta/meus-produtos/alterarProduto/produto/{produto_id}/', function ($produto_id) {
    $objMeusProdutos = new MeusProdutos();
    $objMeusProdutos->alterarProdutoAction($produto_id);
    return false;
});

$app->post('/minha-conta/meus-favoritos/setFavorito/', function () {
	$objMeusFavorito = new MeusFavoritos();
	$objMeusFavorito->setFavoritoAction();
	return false;
});

// ======================
// MEUS FAVORITOS
// ======================
$app->get('/minha-conta/meus-favoritos/', function () {
    $objLogin = new MeusFavoritos();
    $objLogin->indexAction();
    return false;
});

// ======================
// CADASTRO DE PRODUTOS
// ======================
$app->get('/minha-conta/cadastro-produto/', function () {
    $objLogin = new CadastroProduto();
    $objLogin->indexAction();
    return false;
});

$app->post('/minha-conta/cadastro-produto/cadastrar/', function () {
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

$app->get('/admin', function () {
    $objAdmin = new Admin();
    $objAdmin->indexAction();
    return false;
});

$app->post('/admin', function () {
    $objAdmin = new Admin();
    $objAdmin->indexAction();
    return false;
});

$app->get('/admin/{tipo}/{valor}/', function ($tipo,$valor) {
    $objAdmin = new Admin();
    $objAdmin->buscaDadosAction($tipo,$valor);
    return false;
});

$app->post('/admin/aprovar/', function () {
    $objAdmin = new Admin();
    $objAdmin->aprovarProdutoAction();
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