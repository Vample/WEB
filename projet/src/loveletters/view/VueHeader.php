<?php

namespace loveletters\view;

use \loveletters\controler\ControlerJeu;

class VueHeader {

  function headerToHtml(){
    if(!isset($_SESSION)){
        session_start();
    }
    $app=\Slim\Slim::getInstance();
    $res='<header>
            <nav>
              <div class="nav-wrapper grey darken-1">
                <a href="'.$app->urlFor('racine').'" class="brand-logo"> LoveLetters </a>
                <a href="#" data-activates="mobile" class="button-collapse"><i class="material-icons">menu</i></a>
                <ul class="right hide-on-med-and-down">';
    $controleurJeu = new ControlerJeu();
    if(isset($_SESSION['connected']) && $_SESSION['connected'] && $controleurJeu->verify()){
        $res.='       <div id="connected">
                        <li class="menu-item">Bonjour, '.$_SESSION['login'].' </li>
                        <li class="menu-item"><img class="headerProfilePictures" src="'.$app->urlFor('img').'/profile_pictures/'.$_SESSION['img'].'.png"</li>
                      </div>
                      <div class="hide" id="action_box">
                        <li class="menu-item"><a href="'.$app->urlFor('deconnexion').'">Déconnexion</a></li>
                      </div>
                    </ul>
                    <ul class="side-nav" id="mobile">
                      <li class="menu-item"><a href="'.$app->urlFor('deconnexion').'">Déconnexion</a></li>
                    </ul>';
    }else{
    $_SESSION['connected']=false;
    $res.='       <li class="menu-item"><a href="'.$app->urlFor('inscription').'">Inscription</a></li>
                      <li class="menu-item connexion_menu"><a href="javascript:void(0)">Connexion</a></li>
                      <div class="hide connexion_box" id="action_box">
                        <div class="input-field">
                          <input id="login_connexion" type="text" name="login_connexion" class="active">
                          <label for="login_connexion">Nom d\'utilisateur</label>
                        </div>
                        <div class="input-field">
                          <input id="pwd_connexion" type="password" name="pwd_connexion" class="active">
                          <label for="pwd_connexion">Mot de passe</label>
                        </div>
                        <button id="connexion" class="btn waves-effect waves-light grey darken-1" type="button" name="action" action="javascript:void(0)">Connexion</button>
                      </div>
                    </ul>
                    <ul class="side-nav" id="mobile">
                      <li class="menu-item"><a href="'.$app->urlFor('inscription').'">Inscription</a></li>
                      <li class="menu-item"><a href="#">Connexion</a></li>
                    </ul>';
    }
    $res.='</div>
            </nav>
          </header>';
    return $res;
  }
}
