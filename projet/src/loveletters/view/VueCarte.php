<?php

namespace loveletters\view;

class VueCarte {

  public function cardToHtml($carte){
    $res='<div class="carte" style="background-image:url('.$carte['url_illus'].')">
              <div class="rang">
                <h2>'.$carte['rang'].'</h2>
              </div>
              <h1 class="nom">'.$carte['nom'].'</h1>
              <div class="effet">
                <p>'.$carte['effet'].'</p>
              </div>
           </div>';
    return $res;
  }

  public function cardsToHtml($cartes){
    $res='';
    foreach($cartes as $carte){
      $res.=$this->cardToHtml($carte);
    }
    return $res;
  }
}
