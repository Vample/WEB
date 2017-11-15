<?php

namespace loveletters\controler;

use \loveletters\model\Utilisateur;
use \loveletters\view\VueCarte;
use \loveletters\controler\ControlerPartie;
use \loveletters\model\DBConnection;

class ControlerTest {
    public function index(){
          DBConnection::getInstance();
          $idUtilisateurs=array();
          $utilisateurs=Utilisateur::get();
          foreach($utilisateurs as $user){
            $idUtilisateurs[]=$user->idUtilisateur;
          }
          $con = new ControlerPartie();
          $con->pioche(20,12);
    }
}
