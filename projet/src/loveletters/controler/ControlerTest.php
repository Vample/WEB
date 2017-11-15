<?php

namespace loveletters\controler;

use \loveletters\model\Utilisateur;
use \loveletters\model\Carte;
use \loveletters\view\VueCarte;
use \loveletters\controler\ControlerPartie;
use \loveletters\model\DBConnection;

class ControlerTest {
    public function index(){
          DBConnection::getInstance();
          $cartes=Carte::get();
          $app=\Slim\Slim::getInstance();
          $routecss=$app->urlFor('css');
          $vue = new VueCarte();
          $res=$vue->cardsToHtml($cartes);
          $html=<<<HTML
<!DOCTYPE HTML>
<html>
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="$routecss/style.css">
<title>Test Page</title>
</head>
<body>
$res
</body>
</html>
HTML;
    echo $html;
          //$con = new ControlerPartie();
          //$con->pioche(20,12);
    }
}
