<?php

require 'vendor/autoload.php';
use \loveletters\controler\ControlerJeu;

$app = new \Slim\Slim();

$app->get('/css',function(){})->name('css');
$app->get('/js',function(){})->name('js');
$app->get('/img',function(){})->name('img');
$app->get('/materialize',function(){})->name('materialize');

$app->get('/', function(){
	(new ControlerJeu())->index();
})->name('racine');

$app->get('/inscription/', function(){
	(new ControlerJeu())->inscription();
})->name('inscription');

$app->get('/deconnexion/', function(){
	(new ControlerJeu())->deconnexion();
})->name('deconnexion');

$app->post('/connexion/', function(){
	(new ControlerJeu())->connexion();
})->name('connexion');

$app->post('/inscription/', function(){
	(new ControlerJeu())->verifInscription();
})->name('post_inscription');

$app->run();
