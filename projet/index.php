<?php

require 'vendor/autoload.php';
use \loveletters\controler\ControlerJeu;
use \loveletters\controler\ControlerPartie;

if(!isset($_SESSION)){
	session_start();
}

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

$app->post('/jouer/leaveCurrentSalon/', function(){
	(new ControlerJeu())->leaveCurrentSalon();
})->name('leaveSalon');

$app->post('/jouer/loadSalons/', function(){
	(new ControlerJeu())->loadSalons();
})->name('loadSalons');

$app->post('/jouer/loadParticipants/', function(){
	(new ControlerJeu())->loadParticipants();
})->name('loadParticipants');

$app->post('/jouer/launchGame/', function(){
	(new ControlerJeu())->LaunchGame();
})->name('launchGame');

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

$app->get('/partie/:id', function($id){
	(new ControlerPartie())->partie($id);
})->name('partie');

$app->post('/partie/main', function(){
	(new ControlerPartie())->main();
})->name('main');

$app->post('/partie/pioche', function(){
	(new ControlerPartie())->pioche($_SESSION['idJoueur']);
})->name('pioche');

$app->post('/partie/terrain/:id', function($id){
	(new ControlerPartie())->terrain($id);
})->name('terrain');

$app->post('/partie/jouer/:id', function($id){
	(new ControlerPartie())->jouer($id);
})->name('jouerCarte');

$app->post('/partie/affichageDefausse', function(){
	(new ControlerPartie())->affichageDefausse();
})->name('affichageDefausseManche');

$app->post('/partie/affichageDefausse/:id', function($id){
	(new ControlerPartie())->affichageDefausse($id);
})->name('affichageDefausseJoueur');

$app->post('/partie/getEtat/', function(){
	(new ControlerPartie())->getEtat($_SESSION['idJoueur']);
})->name('getEtat');

$app->post('/partie/effetGarde/', function(){
	(new ControlerPartie())->effetGarde();
})->name('effetGarde');

$app->post('/partie/effetPretre/:id', function($id){
	(new ControlerPartie())->effetPretre($id);
})->name('effetPretre');

$app->post('/partie/effetBaron/:id', function($id){
	(new ControlerPartie())->effetBaron($id);
})->name('effetBaron');

$app->post('/partie/effetPrince/:id', function($id){
	(new ControlerPartie())->effetPrince($id);
})->name('effetPrince');

$app->post('/partie/effetRoi/:id', function($id){
	(new ControlerPartie())->effetRoi($id);
})->name('effetRoi');

$app->post('/partie/completeEffetBaron/:idJoueur/:idCarte', function($idJoueur, $idCarte){
	(new ControlerPartie())->completeEffetBaron($idJoueur, $idCarte);
})->name('completeEffetBaron');

$app->post('/partie/choixJoueur/', function(){
	(new ControlerPartie())->choixJoueur();
})->name('choixJoueur');

$app->post('/partie/verifCarteJoueur/:idJoueur/:idCarte', function($idJoueur, $idCarte){
	(new ControlerPartie())->verifCarteJoueur($idJoueur, $idCarte);
})->name('verifCarteJoueur');

$app->post('/partie/getScores/', function(){
	(new ControlerPartie())->getScores();
})->name('getScores');

$app->post('/partie/getMainsJoueurs/', function(){
	(new ControlerPartie())->getMainsJoueurs();
})->name('getMainJoueurs');

$app->run();
