<?php

namespace loveletters\view;

class VueHeader {

  function headerToHtml(){
    $app=\Slim\Slim::getInstance();
    $res='<header>
            <nav>
              <div class="nav-wrapper grey darken-1">
                <a href="'.$app->urlFor('racine').'" class="brand-logo"> LoveLetters </a>
                <a href="#" data-activates="mobile" class="button-collapse"><i class="material-icons">menu</i></a>
                <ul class="right hide-on-med-and-down">
                  <li class="menu-item"><a href="'.$app->urlFor('inscription').'">Inscription</a></li>
                  <li class="menu-item"><a href="#">Connexion</a></li>
                </ul>
                <ul class="side-nav" id="mobile">
                  <li class="menu-item"><a href="'.$app->urlFor('inscription').'">Inscription</a></li>
                  <li class="menu-item"><a href="#">Connexion</a></li>
                </ul>
              </div>
            </nav>
          </header>';
    return $res;
  }
}
