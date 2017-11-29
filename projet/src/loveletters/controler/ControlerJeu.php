<?php

namespace loveletters\controler;

use \loveletters\view\VueJeu;

class ControlerJeu{
  public function index(){
    $vueJeu=new VueJeu();
    $vueJeu->render(VueJeu::INDEX);
  }

  public function inscription(){
    $vueJeu=new VueJeu();
    $vueJeu->render(VueJeu::INSCRIPTION);
  }
}
