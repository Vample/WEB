<?php

require 'vendor/autoload.php';
use \loveletters\controler\ControlerTest;

$app = new \Slim\Slim();

$app->get('/css',function(){})->name('css');

$app->get('/', function(){
	(new ControlerTest())->index();
})->name('racine');

$app->run();
