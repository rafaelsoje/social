<?php
use core\Router;

$router = new Router();

$router->get('/', 'HomeController@index');

$router->get('/login', 'LoginController@sigin');
$router->post('/login', 'LoginController@siginAction');

$router->get('/cadastro', 'LoginController@sigup');
$router->post('/cadastro', 'LoginController@sigupAction');

$router->get('/senha', 'LoginController@recovery');
$router->post('/senha', 'LoginController@recoveryAction');

$router->post('/post/new', 'PostController@new');
$router->get('/post/{id}/delete', 'PostController@delete');

$router->get('/perfil/{id}/fotos', 'ProfileController@photos');
$router->get('/perfil/{id}/amigos', 'ProfileController@friends');
$router->get('/perfil/{id}/follow', 'ProfileController@follow');
$router->get('/perfil/{id}', 'ProfileController@index');
$router->get('/perfil', 'ProfileController@index');

$router->get('/amigos', 'ProfileController@friends');
$router->get('/fotos', 'ProfileController@photos');

$router->get('/pesquisa', 'SearchController@index');

$router->get('/config', 'ConfigController@index');
$router->post('/config', 'ConfigController@submit');

$router->get('/sair', 'LoginController@Logout');

$router->post('/ajax/upload', 'AjaxController@upload');
$router->get('/ajax/like/{id}', 'AjaxController@like');
$router->post('/ajax/comment', 'AjaxController@comment');

$router->get('/teste', 'TesteController@teste');
$router->post('/teste/upload', 'TesteController@upload');




//$route->get('pesquisa')
//$route->get('perfil')
//$route->get('sair')
