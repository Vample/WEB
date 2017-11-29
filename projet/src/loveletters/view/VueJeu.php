<?php

namespace loveletters\view;

use \loveletters\view\VueHeader;

class VueJeu {
  const INDEX=0;
  const INSCRIPTION=1;

  private function index(){
    $app=\Slim\Slim::getInstance();
    $res='<div class="center">
            <h2 class="white-text">Love Letters : Le Jeu</h2>
            <p class="white-text">Qui sauras délivrer sa lettre d\'amour à la princesse ?</p>
            <div class="center">
              <a href="'.$app->urlFor('inscription').'" class="btn waves-effect white grey-text darken-text-2">INSCRIPTION</a>
              <a href="#" class="btn waves-effect white grey-text darken-text-2">CONNEXION</a>
            </div>
          </div>';
    return $res;
  }

  private function inscription(){
    $app=\Slim\Slim::getInstance();
    $route_img=$app->urlFor('img');
    $res='<div class="inscription">
            <h2 class="center white-text">Inscription</h2>
              <div class="galerie">';
    for($i = 1; $i<= 8; $i++){
      if($i==1){
        $res.='<img id="'.$i.'" class="profile_pictures selected" src="'.$route_img.'/profile_pictures/'.$i.'.png"/>';
      }else{
        $res.='<img id="'.$i.'" class="profile_pictures" src="'.$route_img.'/profile_pictures/'.$i.'.png"/>';
      }
    }
      $res.='</div>
             <div class="formulaire_inscription">
             <form action="/" method="post">
                <input id="login" type="text" name="login" class="active">
                <label for="login">Nom d\'utilisateur</label>
                <input id="pwd" type="password" name="pwd" class="active">
                <label for="pwd">Mot de passe</label>
                <input type="hidden" name="img" value="1">
                <input type="submit" value="S\'inscrire">
             </form>
             </div>
          </div>';
    return $res;
  }

  public function render($selecteur){
    $app=\Slim\Slim::getInstance();
    $res;
    switch($selecteur)
    {
      case self::INDEX:
      $res=$this->index();
      break;
      case self::INSCRIPTION:
      $res=$this->inscription();
      break;
    }

    $vueHeader=new VueHeader();
    $header=$vueHeader->headerToHtml();
    $route_css=$app->urlFor('css');
    $route_js=$app->urlFor('js');
    $route_materialize=$app->urlFor('materialize');

    $html='<!DOCTYPE HTML>
          <html>
            <head>
              <meta charset="UTF-8" />
              <title>LoveLetters</title>
              <link rel="stylesheet" href="'.$route_css.'/style.css">
              <!--Import Google Icon Font-->
              <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
              <!--Import materialize.css-->
              <link rel="stylesheet" href="'.$route_materialize.'/css/materialize.css">
            </head>
            <body>'.$header;
    $html.=$res;
    $html.='<!--Import jQuery before materialize.js-->
              <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
              <script type="text/javascript" src="'.$route_materialize.'/js/materialize.js"></script>
              <script type="text/javascript" src="'.$route_js.'/main.js"></script>
            </body>
          </html>';
    echo $html;
  }

}
