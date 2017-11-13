<?php

namespace loveletters\controler;

use \loveletters\model\Carte;
use \loveletters\model\DBConnection;

class ControlerTest {
    public function index(){
        /* Connexion à la BD et récupération des cartes grâce au modèle */
        DBConnection::getInstance();
        $cartes=Carte::get();

        /* Récupération de la route css dans l'index */
        $app=\Slim\Slim::getInstance();
        $routecss=$app->urlFor('css');

        /* Test affichage cartes */
        $res='';
        foreach($cartes as &$carte){
          $res.=<<<CARTE
<div class="carte" style="background-image:url($carte->url_illus)">
    <div class="rang">
      <h2>$carte->rang</h1>
    </div>
    <h1 class="nom">$carte->nom</h1>
    <div class="effet">
      <p>$carte->effet</p>
    </div>
</div>
CARTE;
        }
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
    }
}
