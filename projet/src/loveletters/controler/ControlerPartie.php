<?php

namespace loveletters\controler;

use \loveletters\model\Carte;
use \loveletters\model\Partie;
use \loveletters\model\Pioche;
use \loveletters\model\Manche;
use \loveletters\model\Joueur;
use \loveletters\model\Utilisateur;
use \loveletters\model\Possede;
use \loveletters\model\Comporte;
use \loveletters\model\Participe;
use \loveletters\model\DBConnection;

class ControlerPartie {

  public function nouvellePartie($idUtilisateurs){
    DBConnection::getInstance();
    // Instanciation de la partie
      $partie = new Partie;
      $partie->save();
      // Création des joueurs pour chaque utilisateurs
      $idJoueurs=array();
      foreach($idUtilisateurs as $idUser){
        $joueur = new Joueur;
        $joueur->idUtilisateur = $idUser;
        $joueur->score = 0;
        $joueur->save();
        $idJoueurs[]=$joueur->idJoueur;
      }
      $this->nouvelleManche($partie->idPartie, $idJoueurs);
  }

  public function nouvelleManche($idPartie, $idJoueurs){
    DBConnection::getInstance();
    // Instanciation d'une nouvelle pioche
      $pioche = new Pioche;
      $pioche->save();
    // Instanciation d'une nouvelle manche
      $manche = new Manche;
      $manche->idPartie = $idPartie;
      $manche->idPioche = $pioche->idPioche;
      $manche->save();
    // Affectation des joueurs à la manche
    foreach($idJoueurs as $idJoueur){
      $participe = new Participe;
      $participe->idJoueur=$idJoueur;
      $participe->idManche=$manche->idManche;
      $participe->save();
    }
    // Remplissage de la pioche
      for($i = 1; $i<=8; $i++){
        $comporte = new Comporte;
        $comporte->idPioche = $pioche->idPioche;
        $comporte->idCarte = $i;
        if($i==1){
          $comporte->nbCartes = 5;
        }else{
          if($i<6){
            $comporte->nbCartes = 2;
          }else{
            $comporte->nbCartes = 1;
          }
        }
        $comporte->save();
      }
  }

  public function pioche($idManche, $idJoueur){
    DBConnection::getInstance();
    $pioche=array();
    $manche=Manche::where('idManche',$idManche)->first();
    $idPioche=$manche->idPioche;
    for($i=1; $i<=8; $i++){
      $comporte=Comporte::where('idPioche' ,$idPioche)
                        ->where('idCarte', $i)
                        ->first();
      for($n=0; $n<$comporte->nbCartes; $n++){
        $pioche[]=$i;
      }
    }
    shuffle($pioche);
    $randomId=$pioche[array_rand($pioche, 1)];
    $possede=Possede::where('idCarte', $randomId)
                    ->where('idJoueur', $idJoueur)
                    ->first();
    if(is_null($possede)){
      $possede=new Possede;
      $possede->idCarte=$randomId;
      $possede->idJoueur=$idJoueur;
      $possede->nbCartes=0;
    }
    $possede->nbCartes++;
    $possede->save();
    $comporte=Comporte::where('idPioche',$idPioche)
                      ->where('idCarte', $randomId)
                      ->first();
    if($comporte->nbCartes!=1){
      $comporte->nbCartes--;
      $comporte->save();
    }else{
      $comporte->forceDelete();
    }
  }
}
