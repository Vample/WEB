<?php

namespace loveletters\controler;

use \loveletters\model\Carte;
use \loveletters\model\Partie;
use \loveletters\model\Pioche;
use \loveletters\model\Manche;
use \loveletters\model\Joueur;
use \loveletters\model\Utilisateur;
use \loveletters\model\EstPlacee;
use \loveletters\model\Possede;
use \loveletters\model\Comporte;
use \loveletters\model\Participe;
use \loveletters\model\SalonParticipe;
use \loveletters\model\Posee;
use \loveletters\model\DBConnection;
use \loveletters\model\Defausse;
use \loveletters\view\VueJeu;
use \loveletters\view\VueCarte;

class ControlerPartie {

  public function partie($idPartie){
    DBConnection::getInstance();
    if(!isset($_SESSION)){
      session_start();
    }
    $_SESSION['idPartie']=$idPartie;
    $idJoueur = Joueur::where('idUtilisateur','=',$_SESSION['idUtilisateur'])->max('idJoueur');
    $_SESSION['idJoueur']=$idJoueur;
    $idManche = Participe::where('idJoueur','=',$idJoueur)->max('idManche');
    $participes = Participe::where('idManche','=',$idManche)->get();
    $joueurs=array();
    //$idJoueurs=array();
    foreach($participes as $participe){
      $joueur = Joueur::where('idJoueur','=',$participe->idJoueur)->first();
      $user = Utilisateur::where('idUtilisateur','=',$joueur->idUtilisateur)->first();
      $joueurs[$participe->idJoueur]=$user->login;
      //$idJoueurs[]=$participe->idJoueur;
    }
    //$this->nouvelleManche($idPartie, $idJoueurs);
    $vueJeu = new VueJeu();
    $vueJeu->render(VueJeu::PARTIE,$joueurs);
  }

  public function terrain($idJoueur){
    DBConnection::getInstance();
    if(!isset($_SESSION)){
      session_start();
    }
    $posees= Posee::where('idJoueur','=',$idJoueur)->get();
    $cartes= array();
    foreach($posees as $posee){
      $cartes[]= Carte::where('idCarte','=',$posee->idCarte)->first();
    }
    $vueCarte = new VueCarte();
    echo $vueCarte->cardsToHtml($cartes);
  }

  public function main(){
    DBConnection::getInstance();
    if(!isset($_SESSION)){
      session_start();
    }
    $possedes = Possede::where('idJoueur','=',$_SESSION['idJoueur'])->get();
    $cartes=array();
    foreach($possedes as $possede){
      for($i = 0; $i<$possede->nbCartes; $i++){
        $cartes[] = Carte::where('idCarte','=',$possede->idCarte)->first();
      }
    }
    $vueCarte = new VueCarte();
    echo $vueCarte->cardsToHtml($cartes);
  }

  public function nouvellePartie($idSalon){
    DBConnection::getInstance();
    if(!isset($_SESSION)){
      session_start();
    }
    // Instanciation des IdUtlisateurs
      $idUtilisateurs=array();
      $salon_participants = SalonParticipe::where('idSalon','=',$idSalon)->get();
      foreach($salon_participants as $participant){
        $idUtilisateurs[]=$participant->idUtilisateur;
      }
    // Instanciation de la partie
      $partie = new Partie;
      $partie->idSalon = $idSalon;
      $partie->save();
    // Création des joueurs pour chaque utilisateurs
      $idJoueurs=array();
      foreach($idUtilisateurs as $idUser){
        $joueur = new Joueur;
        $joueur->idUtilisateur = $idUser;
        $joueur->etat_tour = 'fin';
        $joueur->protect = false;
        $joueur->elimine = false;
        $joueur->score = 0;
        $joueur->save();
        $idJoueurs[]=$joueur->idJoueur;
      }
      $this->nouvelleManche($partie->idPartie, $idJoueurs);
  }

  public function nouvelleManche($idPartie, $idJoueurs){
    DBConnection::getInstance();
    if(!isset($_SESSION)){
      session_start();
    }
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
      //Suppression des tables et main
      $possedes = Possede::where('idJoueur','=',$idJoueur)->get();
      foreach($possedes as $possede){
        $possede->forceDelete();
      }
      $posees = Posee::where('idJoueur','=',$idJoueur)->get();
      foreach($posees as $posee){
        $posee->forceDelete();
      }
    }
    // Remplissage de la pioche
      $cartes=array();
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
        for($n=0; $n<$comporte->nbCartes; $n++){
          $cartes[]=$i;
        }
      }
      shuffle($cartes);
      // Suppression de la première carte sans la mettre dans la defausse
      $randomId=$cartes[array_rand($cartes, 1)];
      unset($cartes[array_search($randomId, $cartes)]);
      $comporte = Comporte::where('idCarte','=',$randomId)
                          ->where('idPioche','=',$manche->idPioche)
                          ->first();
      if($comporte->nbCartes==1){
        $comporte->forceDelete();
      }else{
        $comporte->nbCartes=$comporte->nbCartes-1;
        $comporte->save();
      }
      // Defausse des 3 premières cartes
      $defausse = new Defausse;
      $defausse->idManche=$manche->idManche;
      $defausse->idJoueur=null;
      $defausse->save();
      for($i = 0; $i<3; $i++){
        $randomId=$cartes[array_rand($cartes, 1)];
        unset($cartes[array_search($randomId, $cartes)]);
        $this->defausse($randomId);
      }
      // Don de la première carte à tous les joueurs
      foreach($idJoueurs as $idJoueur){
        $joueur = Joueur::where('idJoueur','=',$idJoueur)->first();
        $joueur->etat_tour='pioche';
        $joueur->elimine = false;
        $joueur->save();
        $this->pioche($idJoueur);
      }
      // Replacement des joueurs dans leurs états de départ
      $n = 0;
      foreach($idJoueurs as $idJoueur){
        $joueur = Joueur::where('idJoueur','=',$idJoueur)->first();
        if($n == 0){
          $joueur->etat_tour='pioche';
          $joueur->save();
        }else{
          $joueur->etat_tour='fin';
          $joueur->save();
        }
        $n++;
      }
  }

  public function jouer($idCarte){
    DBConnection::getInstance();
    if(!isset($_SESSION)){
      session_start();
    }
    $joueur = Joueur::where('idJoueur','=',$_SESSION['idJoueur'])->first();
    if($joueur->etat_tour=='jouer'){
      // Enleve la carte de la main du joueur
      $possede = Possede::where('idJoueur','=',$_SESSION['idJoueur'])
                       ->where('idCarte','=',$idCarte)
                       ->first();
      if($possede->nbCartes>1){
        $possede->nbCartes--;
        $possede->save();
      }else{
        $possede->forceDelete();
      }
      // Pose la carte sur le terrain (du coté du joueur)
      $posee = new Posee;
      $posee->idCarte=$idCarte;
      $posee->idJoueur=$_SESSION['idJoueur'];
      $posee->save();
      // Applique l'effet de la carte
      switch($idCarte){
        case 4: $this->protect();
                break;
        case 5:
                break;
        case 6:
                break;
      }
      $joueur->etat_tour='fin';
      $joueur->save();
      $this->next_turn($joueur->idJoueur);
    }
  }

  public function protect(){
    $joueur = Joueur::where('idJoueur','=',$_SESSION['idJoueur'])->first();
    $joueur->protect = true;
    $joueur->save();
  }

  public function effetGarde(){
    DBConnection::getInstance();
    $cartes=array();
    for($i=2;$i<=8;$i++){
      $cartes[]= Carte::where('idCarte','=',$i)->first();
    }
    $vueCarte = new VueCarte();
    echo $vueCarte->cardsToHtml($cartes);
  }

  public function effetPretre($idJoueur){
    DBConnection::getInstance();
    $joueur = Joueur::where('idJoueur','=',$idJoueur)->first();
    $possedes= Possede::where('idJoueur','=',$idJoueur)->get();
    $cartes=array();
    foreach($possedes as $possede){
      if(!$joueur->protect){
        $cartes[]= Carte::where('idCarte','=',$possede->idCarte)->first();
      }
    }
    $vueCarte = new VueCarte();
    echo $vueCarte->cardsToHtml($cartes);
  }

  public function effetBaron($idJoueur){
    DBConnection::getInstance();
    $posees= Posee::where('idJoueur','=',$idJoueur)->get();
    $cartes=array();
    foreach($posees as $posee){
      $cartes[]= Carte::where('idCarte','=',$posee->idCarte)->first();
    }
    $vueCarte = new VueCarte();
    echo $vueCarte->cardsToHtml($cartes);
  }

  public function completeEffetBaron($idJoueur, $idCarte){
    DBConnection::getInstance();
    $joueur = Joueur::where('idJoueur','=',$idJoueur)->first();
    if($idCarte<3){
      if(!$joueur->protect){
        $this->eliminer($idJoueur);
      }
    }
    if($idCarte>3){
      $this->eliminer($_SESSION['idJoueur']);
    }
  }

  public function effetPrince($idJoueur){
    DBConnection::getInstance();
    $joueur = Joueur::where('idJoueur','=',$idJoueur)->first();
    if(!$joueur->protect){
      $possede = Possede::where('idJoueur','=',$idJoueur)->first();
      $idManche = Participe::where('idJoueur','=',$_SESSION['idJoueur'])->max('idManche');
      $defausse = Defausse::where('idJoueur','=',$_SESSION['idJoueur'])
                          ->where('idManche','=',$idManche)
                          ->first();
      if($defausse==null){
        $defausse = new Defausse;
        $defausse->idJoueur=$_SESSION['idJoueur'];
        $defausse->idManche=$idManche;
        $defausse->save();
      }
      if($possede!=null){
        $estPlacee = EstPlacee::where('idCarte','=',$possede->idCarte)
                              ->where('idDefausse','=',$defausse->idDefausse)
                              ->first();
        if($estPlacee==null){
          $estPlacee = new EstPlacee;
          $estPlacee->idCarte=$possede->idCarte;
          $estPlacee->idDefausse=$defausse->idDefausse;
          $estPlacee->nbCartes=1;
        }else{
          $estPlacee->nbCartes=$estPlacee->nbCartes+1;
        }
        $estPlacee->save();
        $possede->forceDelete();
        if($estPlacee->idCarte == 8){
          $this->eliminer($idJoueur);
        }
      }
      $this->pioche($idJoueur,true);
    }
  }

  public function effetRoi($idJoueur){
    DBConnection::getInstance();
    if(!isset($_SESSION)){
      session_start();
    }
    $joueur = Joueur::where('idJoueur','=',$idJoueur)->first();
    if(!$joueur->protect){
      $possede = Possede::where('idJoueur','=',$idJoueur)->first();
      $possede2 = Possede::where('idJoueur','=',$_SESSION['idJoueur'])->first();
      $idCarte = $possede->idCarte;
      $possede->forceDelete();
      $possede = new Possede;
      $possede->idJoueur=$idJoueur;
      $possede->nbCartes=1;
      $possede->idCarte=$possede2->idCarte;
      $possede->save();
      $possede2->forceDelete();
      $possede2 = new Possede;
      $possede2->idJoueur=$_SESSION['idJoueur'];
      $possede2->nbCartes=1;
      $possede2->idCarte=$idCarte;
      $possede2->save();
    }
  }

  public function getScores(){
    DBConnection::getInstance();
    if(!isset($_SESSION)){
      session_start();
    }
    $idManche = Participe::where('idJoueur','=',$_SESSION['idJoueur'])->max('idManche');
    $participes = Participe::where('idManche','=',$idManche)->get();
    $idJoueurs=array();
    foreach($participes as $participe){
      $joueur= Joueur::where('idJoueur','=',$participe->idJoueur)->first();
      $idJoueurs[$participe->idJoueur] = $joueur->score;
    }
    echo json_encode($idJoueurs);
  }

  public function next_turn($idJoueur){
    DBConnection::getInstance();
    $idManche = Participe::where('idJoueur','=',$idJoueur)->max('idManche');
    $participes = Participe::where('idManche','=',$idManche)->get();
    $i = 0;
    $idJoueurTour;
    foreach($participes as $participe){
      $joueur= Joueur::where('idJoueur','=',$participe->idJoueur)->first();
      if($i == 0 && !$joueur->elimine){
        $idJoueurTour=$participe->idJoueur;
        $i++;
      }
      if($idJoueur==$participe->idJoueur){
        $i=0;
      }
    }
    $joueur= Joueur::where('idJoueur','=',$idJoueurTour)->first();
    $joueur->etat_tour='pioche';
    if($joueur->protect){
      $joueur->protect=false;
    }
    $joueur->save();
  }

  public function getEtat($idJoueur){
    DBConnection::getInstance();
    $joueur= Joueur::where('idJoueur','=',$idJoueur)->first();
    echo $joueur->etat_tour;
  }

  public function getMainsJoueurs(){
    DBConnection::getInstance();
    $idManche = Participe::where('idJoueur','=',$_SESSION['idJoueur'])->max('idManche');
    $participes = Participe::where('idManche','=',$idManche)->get();
    $joueurs=array();
    foreach($participes as $participe){
      $possedes= Possede::where('idJoueur','=',$participe->idJoueur)->get();
      $joueurs[$participe->idJoueur]=0;
      foreach($possedes as $possede){
        $joueurs[$participe->idJoueur]++;
      }
    }
    echo json_encode($joueurs);
  }

  public function affichageDefausse($idJoueur=null){
    DBConnection::getInstance();
    if(!isset($_SESSION)){
      session_start();
    }
    $idManche=Manche::where('idPartie','=',$_SESSION['idPartie'])->max('idManche');
    if($idJoueur!=null){
      $defausse = Defausse::where('idManche','=',$idManche)
                          ->where('idJoueur','=',$idJoueur)
                          ->first();
    }else{
      $defausse = Defausse::where('idManche','=',$idManche)
                          ->where('idJoueur','=',null)
                          ->first();
    }
    if($defausse==null){
      $defausse = new Defausse;
      $defausse->idJoueur=$idJoueur;
      $defausse->idManche=$idManche;
      $defausse->save();
    }
    $estPlacees = EstPlacee::where('idDefausse','=',$defausse->idDefausse)->get();
    $cartes= array();
    foreach($estPlacees as $estPlacee){
      for($i=0;$i<$estPlacee->nbCartes;$i++){
        $cartes[]= Carte::where('idCarte','=',$estPlacee->idCarte)->first();
      }
    }
    $vueCarte = new VueCarte();
    $res = $vueCarte->cardsToHtml($cartes);
    $res.='<div class="carte" style="background-image: url(&quot;../img/dos.jpg&quot;);"></div>';
    echo $res;
  }

  public function defausse($idCarte, $idJoueur=null, $selecteur=null){
    DBConnection::getInstance();
    if(!isset($_SESSION)){
      session_start();
    }
    $idManche=Manche::where('idPartie','=',$_SESSION['idPartie'])->max('idManche');
    if($idJoueur!=null){
      $defausse = Defausse::where('idManche','=',$idManche)
                          ->where('idJoueur','=',$idJoueur)
                          ->first();
      if($selecteur=='main'){
        $possede = Possede::where('idCarte','=',$idCarte)
                          ->where('idJoueur','=',$idJoueur)
                          ->first();
        if($possede->nbCartes==1){
          $possede->forceDelete();
        }else{
          $possede->nbCartes=$possede->nbCartes-1;
          $possede->save();
        }
      }else{
        $posee = Posee::where('idCarte','=',$idCarte)
                      ->where('idJoueur','=',$idJoueur)
                      ->first();
        $posee->forceDelete();
      }
    }else{
      $manche =  Manche::where('idManche','=',$idManche)->first();
      $comporte = Comporte::where('idCarte','=',$idCarte)
                          ->where('idPioche','=',$manche->idPioche)
                          ->first();
      if($comporte->nbCartes==1){
        $comporte->forceDelete();
      }else{
        $comporte->nbCartes=$comporte->nbCartes-1;
        $comporte->save();
      }
      $defausse = Defausse::where('idManche','=',$idManche)
                          ->where('idJoueur','=',null)
                          ->first();
      if($defausse==null){
        $defausse->idManche=$idManche;
        $defausse->idJoueur=null;
        $defausse->save();
      }
    }
    $estPlacee = EstPlacee::where('idDefausse','=',$defausse->idDefausse)
                          ->where('idCarte','=',$idCarte)
                          ->first();
    if($estPlacee==null){
      $estPlacee = new EstPlacee;
      $estPlacee->idDefausse=$defausse->idDefausse;
      $estPlacee->idCarte=$idCarte;
      $estPlacee->nbCartes=1;
      $estPlacee->save();
    }else{
      $estPlacee->nbCartes=$estPlacee->nbCartes+1;
      $estPlacee->save();
    }
  }

  public function pioche($idJoueur,$bypass=false){
    DBConnection::getInstance();
    if(!isset($_SESSION)){
      session_start();
    }
    $joueur= Joueur::where('idJoueur','=',$idJoueur)->first();
    if($joueur->etat_tour=='pioche' || $bypass){
      $pioche=array();
      $idManche=Manche::where('idPartie','=',$_SESSION['idPartie'])->max('idManche');
      $manche=Manche::where('idManche',$idManche)->first();
      $idPioche=$manche->idPioche;
      for($i=1; $i<=8; $i++){
        $comporte=Comporte::where('idPioche','=',$idPioche)
                          ->where('idCarte','=',$i)
                          ->first();
        if($comporte!=null){
          for($n=0; $n<$comporte->nbCartes; $n++){
            $pioche[]=$i;
          }
        }
      }
      shuffle($pioche);
      $randomId=$pioche[array_rand($pioche, 1)];
      $possede=Possede::where('idCarte', $randomId)
                      ->where('idJoueur', $idJoueur)
                      ->first();
      if($possede==null){
        $possede=new Possede;
        $possede->idCarte=$randomId;
        $possede->idJoueur=$idJoueur;
        $possede->nbCartes=0;
      }
      $possede->nbCartes++;
      $possede->save();
      $possede2=Possede::where('idJoueur','=',$idJoueur)->first();
      if(($possede2->idCarte==7 || $possede->idCarte==7) && ($possede2->idCarte==5 || $possede2->idCarte==6 || $possede->idCarte==5 || $possede->idCarte==6)){
        $possede = Possede::where('idCarte','=',7)->first();
        $possede->forceDelete();
        $defausse = Defausse::where('idJoueur','=',$_SESSION['idJoueur']);
        if($defausse==null){
          $defausse = new Defausse;
          $defausse->idJoueur=$_SESSION['idJoueur'];
          $defausse->idManche=$idManche;
          $defausse->save();
        }
        $estPlacee = new EstPlacee;
        $estPlacee->idCarte=7;
        $estPlacee->idDefausse=$defausse->idDefausse;
        $estPlacee->nbCartes=1;
        $estPlacee->save();
      }
      $comporte=Comporte::where('idPioche',$idPioche)
                        ->where('idCarte', $randomId)
                        ->first();
      if($comporte->nbCartes!=1){
        $comporte->nbCartes--;
        $comporte->save();
      }else{
        $comporte->forceDelete();
      }
      if(!$bypass){
        $joueur->etat_tour='jouer';
        $joueur->save();
      }
    }
  }

  public function choixJoueur(){
    DBConnection::getInstance();
    if(!isset($_SESSION)){
      session_start();
    }
    $res='';
    $idManche = Participe::where('idJoueur','=',$_SESSION['idJoueur'])->max('idManche');
    $participes = Participe::where('idManche','=',$idManche)->get();
    foreach($participes as $participe){
      if($participe->idJoueur!=$_SESSION['idJoueur']){
        $joueur= Joueur::where('idJoueur','=',$participe->idJoueur)->first();
        $utilisateur= Utilisateur::where('idUtilisateur','=',$joueur->idUtilisateur)->first();
        $res.='<button id="'.$participe->idJoueur.'" class="btn waves-effect waves-light grey darken-1 choix_joueur" type="button" name="action" action="javascript:void(0)">'.$utilisateur->login.'</button>';
      }
    }
    echo $res;
  }

  public function verifCarteJoueur($idJoueur, $idCarte){
    DBConnection::getInstance();
    $joueur= Joueur::where('idJoueur','=',$idJoueur)->first();
    $possede = Possede::where('idJoueur','=',$idJoueur)
                      ->where('idCarte','=',$idCarte)
                      ->first();
    if($possede!=null && !$joueur->protect){
      $this->eliminer($idJoueur);
    }
  }

  public function eliminer($idJoueur){
    DBConnection::getInstance();
    $joueur= Joueur::where('idJoueur','=',$idJoueur)->first();
    $joueur->elimine=true;
    $joueur->save();
    $this->verifElimine();
  }

  public function verifElimine(){
    DBConnection::getInstance();
    if(!isset($_SESSION)){
      session_start();
    }
    $idManche = Participe::where('idJoueur','=',$_SESSION['idJoueur'])->max('idManche');
    $participes = Participe::where('idManche','=',$idManche)->get();
    $count = 0;
    foreach($participes as $participe){
      $joueur= Joueur::where('idJoueur','=',$participe->idJoueur)->first();
      if($joueur->elimine){
        $count++;
      }
    }
    $count_participe = Participe::where('idManche','=',$idManche)->count();
    if($count==$count_participe-1){
      $idJoueurs=array();
      foreach($participes as $participe){
        $joueur= Joueur::where('idJoueur','=',$participe->idJoueur)->first();
        $idJoueurs[]=$participe->idJoueur;
        if(!$joueur->elimine){
          $joueur->score=$joueur->score+1;
        }
        $joueur->save();
      }
      $this->nouvelleManche($_SESSION['idPartie'],$idJoueurs);
    }
  }
}
