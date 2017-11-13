<?php

require 'vendor/autoload.php';
use \loveletters\controler\ControlerTest;

echo '<meta charset="UTF-8">';

$app = new \Slim\Slim();

$app->get('/', function(){
	(new ControlerTest())->index();
})->name('racine');

$app->run();
