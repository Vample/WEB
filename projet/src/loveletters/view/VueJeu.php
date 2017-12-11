<?php

namespace loveletters\view;

use \loveletters\view\VueHeader;
use \loveletters\controler\ControlerJeu;

class VueJeu {
  const INDEX=0;
  const INSCRIPTION=1;
  const JOUER=2;
  const PARTIE=3;

  private function index(){
    $app=\Slim\Slim::getInstance();
    $controlerJeu = new ControlerJeu();
    $res='<div class="center">
            <h2 class="white-text">Love Letters : Le Jeu</h2>
            <p class="white-text">Qui saura délivrer sa lettre d\'amour à la princesse ?</p>';
    if($controlerJeu->verify()){
      $res.='<div class="center">
                <a href="'.$app->urlFor('jouer').'" class="btn waves-effect white grey-text darken-text-2">JOUER</a>
              </div>
            </div>';
    }else{
      $res.='<div class="center">
                <a href="'.$app->urlFor('inscription').'" class="btn waves-effect white grey-text darken-text-2">INSCRIPTION</a>
                <a href="javascript:void(0)" class="btn waves-effect white grey-text darken-text-2 connexion_menu">CONNEXION</a>
              </div>
            </div>';
    }
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
              <div class="input-field">
                <input id="login" type="text" name="login" class="active">
                <label for="login">Nom d\'utilisateur</label>
              </div>
              <div class="input-field">
                <input id="pwd" type="password" name="pwd" class="active">
                <label for="pwd">Mot de passe</label>
              </div>
              <div class="input-field">
                <input id="pwd2" type="password" name="pwd2" class="active">
                <label for="pwd">Retapez votre mot de passe</label>
              </div>
                <input id="img" type="hidden" name="img" value="1">
                <button id="inscription" class="btn waves-effect waves-light grey darken-1" type="button" name="action" action="javascript:void(0)">S\'inscrire
                  <i class="material-icons right">send</i>
                </button>
             </form>
             </div>
          </div>';
    return $res;
  }

  public function jouer(){
    $res='<div class="game_list">
            <div class="title center">
              <h4>Liste des parties</h4>
              <button id="refresh" class="btn waves-effect waves-light grey darken-1" type="button" name="action" action="javascript:void(0)">
                <i class="material-icons">refresh</i>
              </button>
            </div>
            <div class="games">
              <div class="center" style="margin-top: 30px">
                <div class="preloader-wrapper big active">
                  <div class="spinner-layer spinner-white-only">
                    <div class="circle-clipper left">
                      <div class="circle"></div>
                    </div><div class="gap-patch">
                      <div class="circle"></div>
                    </div><div class="circle-clipper right">
                      <div class="circle"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="center">
              <button id="rejoindre" class="btn waves-effect waves-light grey darken-1" type="button" name="action" action="javascript:void(0)">Rejoindre</button>
            </div>
          </div>
          <div class="create_game">
            <div class="title center">
              <h4>Création de partie</h4>
            </div>
            <form action="/" method="post">
             <div class="input-field">
               <input id="nom" type="text" name="nom" class="active">
               <label for="nom">Nom de la partie</label>
             </div>
             <div class="input-field">
               <input id="nbJoueurs" type="number" name="nbJoueurs" class="active">
               <label for="nbJoueurs">Nombre de joueurs (max 4.)</label>
             </div>
             <div class="center">
               <button id="creerGame" class="btn waves-effect waves-light grey darken-1" type="button" name="action" action="javascript:void(0)">Créer la partie</button>
             </div>
            </form>
          </div>';
    return $res;
  }

  public function partie($joueurs){
    $res='<div class="overlay_defausse"></div>
          <button id="jouer" class="btn waves-effect waves-light grey darken-1" type="button" name="action" action="javascript:void(0)">Jouer</button>
          <button class="btn waves-effect waves-light grey darken-1 defausse_manche"  type="button" name="action" action="javascript:void(0)">Defausse</button>';
    $i = 1;
    foreach($joueurs as $id => $login){
      if($id!=$_SESSION['idJoueur']){
        switch($i){
          case 1: $res.='<div id="'.$id.'" class="main main_top">
                         </div>
                         <div id="'.$id.'" class="terrain_top terrain">
                         </div>
                         <div id="'.$id.'" class="pseudo pseudo_top">'.$login.'</div>
                         <div id="'.$id.'" class="score score_top"></div>';
                  break;
          case 2:$res.= '<div id="'.$id.'" class="main main_left">
                         </div>
                         <div id="'.$id.'" class="terrain_left terrain">
                         </div>
                         <div id="'.$id.'" class="pseudo pseudo_left">'.$login.'</div>
                         <div id="'.$id.'" class="score score_left"></div>';
                  break;
          case 3:$res.= '<div id="'.$id.'" class="main main_right">
                         </div>
                         <div id="'.$id.'" class="terrain_right terrain">
                         </div>
                         <div id="'.$id.'" class="pseudo pseudo_right">'.$login.'</div>
                         <div id="'.$id.'" class="score score_right"></div>';
                  break;
        }
        $i++;
      }
    }
    $res.='<div id="'.$_SESSION['idJoueur'].'" class="terrain_user terrain">
          </div>
          <div class="main main_user">
          </div>
          <div id="'.$_SESSION['idJoueur'].'" class="pseudo pseudo_user">'.$_SESSION['login'].'</div>
          <div id="'.$_SESSION['idJoueur'].'" class="score score_user"></div>
          <button id="pioche" class="btn waves-effect waves-light grey darken-1" type="button" name="action" action="javascript:void(0)">Piocher</button>';
    return $res;
  }

  public function render($selecteur, $joueurs = null){
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
      case self::JOUER:
      $res=$this->jouer();
      break;
      case self::PARTIE:
      $res=$this->partie($joueurs);
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
            <body>'.$header.'<div class="content">';
    $html.=$res;
    $html.='</div>
            <!--Import jQuery before materialize.js-->
              <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
              <script type="text/javascript" src="'.$route_materialize.'/js/materialize.js"></script>
              <script type="text/javascript" src="'.$route_js.'/main.js"></script>';
    if($selecteur == self::JOUER){
      $html.='<script type="text/javascript" src="'.$route_js.'/jeu.js"></script>';
    }
    if($selecteur == self::PARTIE){
      $html.='<script type="text/javascript" src="'.$route_js.'/partie.js"></script>';
    }
    $html.='</body>
          </html>';
    echo $html;
  }

}
