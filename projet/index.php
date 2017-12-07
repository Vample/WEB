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

$app->get('/jouer/', function(){
	(new ControlerJeu())->jouer();
})->name('jouer');

$app->post('/jouer/creerSalon/', function(){
	(new ControlerJeu())->creerSalon();
})->name('creerSalon');

$app->post('/jouer/joinSalon/', function(){
	(new ControlerJeu())->joinSalon($_POST['idSalon']);
})->name('joinSalon');

$app->post('/jouer/loadSalons/', function(){
	(new ControlerJeu())->loadSalons();
})->name('loadSalons');

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

$app->post('/inscription/verifPseudo/', function(){
	(new ControlerJeu())->verifPseudo($_POST['username']);
})->name('verifPseudo');

$app->run();
