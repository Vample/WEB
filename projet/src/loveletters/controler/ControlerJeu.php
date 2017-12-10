<?php

namespace loveletters\controler;

use \loveletters\view\VueJeu;
use \loveletters\view\VueHeader;
use \loveletters\model\Utilisateur;
use \loveletters\model\DBConnection;
use \loveletters\model\Salon;
use \loveletters\model\SalonParticipe;
use \loveletters\model\Partie;
use \loveletters\controler\ControlerPartie;
use \loveletters\model\Joueur;

class ControlerJeu{
  public function index(){
    $vueJeu = new VueJeu();
    $vueJeu->render(VueJeu::INDEX);
  }

  public function inscription(){
    $vueJeu = new VueJeu();
    $vueJeu->render(VueJeu::INSCRIPTION);
  }

  public function jouer(){
    if(!isset($_SESSION)){
      session_start();
    }
    if($this->verify()){
      //Si une partie est déjà en cours
      if(isset($_SESSION['idPartie'])){
        if(isset($_SESSION['idJoueur'])){
          $joueur = Joueur::where('idJoueur','=',$_SESSION['idJoueur']);
          if($joueur->idUtilisateur==$_SESSION['idUtilisateur']){
            $controlerPartie = new ControlerPartie();
            $controlerPartie->partie($_SESSION['idPartie']);
          }else{
            $vueJeu = new VueJeu();
            $vueJeu->render(VueJeu::JOUER);
          }
        }else{
          $vueJeu = new VueJeu();
          $vueJeu->render(VueJeu::JOUER);
        }
      }else{
        $vueJeu = new VueJeu();
        $vueJeu->render(VueJeu::JOUER);
      }

    }else{
      $this->index();
    }
  }

  public function launchGame(){
    DBConnection::getInstance();
    if(!isset($_SESSION)){
      session_start();
    }
    $idUtilisateurs=array();
    $salon_participe = SalonParticipe::where('idUtilisateur','=',$_SESSION['idUtilisateur'])->first();
    $controlerPartie = new ControlerPartie();
    $controlerPartie->nouvellePartie($salon_participe->idSalon);
  }

  public function creerSalon(){
    DBConnection::getInstance();
    if(!isset($_SESSION)){
      session_start();
    }
    $salon = new Salon;
    $salon->nom = $_POST['nom'];
    $salon->nbJoueurs = $_POST['nbJoueurs'];
    $salon->idProprio = $_SESSION['idUtilisateur'];
    $salon->save();
    $this->joinSalon($salon->idSalon);
  }

  public function leaveCurrentSalon(){
    DBConnection::getInstance();
    if(!isset($_SESSION)){
      session_start();
    }
    $participation = SalonParticipe::where('idUtilisateur','=',$_SESSION['idUtilisateur'])->first();
    $salon = Salon::where('idSalon','=',$participation->idSalon)->first();
    if($this->getCountParticipants($salon->idSalon)!=1){
      if($salon->idProprio==$_SESSION['idUtilisateur']){
        $first_participation=SalonParticipe::where('idSalon','=',$ancien_salon->idSalon)
                                            ->where('idUtilisateur','!=',$_SESSION['idUtilisateur'])
                                            ->first();
        $salon->idProprio=$first_participation->idUtilisateur;
        $salon->save();
        $participation->forceDelete();
      }else{
        $participation->forceDelete();
      }
    }else{
      $ancienne_participation->forceDelete();
      $ancien_salon->forceDelete();
    }
  }

  public function joinSalon($idSalon){
    DBConnection::getInstance();
    if(!isset($_SESSION)){
      session_start();
    }
    //Récupération du salon
    $salon = Salon::where('idSalon','=',$idSalon)->first();
    //Verification Salon non rempli
    $ancienne_participation = SalonParticipe::where('idUtilisateur','=',$_SESSION['idUtilisateur'])->first();
    if($this->getCountParticipants($idSalon)<$salon->nbJoueurs || ($ancienne_participation!=null && $ancienne_participation->idSalon == $idSalon)){
      $data;
      $data['nom']=$salon->nom;
      $data['nbJoueurs']=$salon->nbJoueurs;
      //Suppression d'une précédente participation de l'utilisateur
      if($ancienne_participation != null){
        $ancien_salon = Salon::where('idSalon','=',$ancienne_participation->idSalon)->first();
        if($ancien_salon!=null){
          if($ancien_salon->idSalon==$idSalon){
            $ancienne_participation->forceDelete();
          }else{
            if($this->getCountParticipants($ancien_salon->idSalon)!=1){
              //Changement de propriétaire si le salon précédent était encore plein
              if($ancien_salon->idProprio==$_SESSION['idUtilisateur']){
                $first_participation=SalonParticipe::where('idSalon','=',$ancien_salon->idSalon)
                                                    ->where('idUtilisateur','!=',$_SESSION['idUtilisateur'])
                                                    ->first();
                $ancien_salon->idProprio=$first_participation->idUtilisateur;
                $ancien_salon->save();
                $ancienne_participation->forceDelete();
              }else{
                $ancienne_participation->forceDelete();
              }
            }else{
              //Suppression du salon précédent si personne dedans
              $ancienne_participation->forceDelete();
              $ancien_salon->forceDelete();
            }
          }
        }
      }
      //Ajout de la participation de l'utilisateur
      $ma_participation = new SalonParticipe;
      $ma_participation->idSalon=$idSalon;
      $ma_participation->idUtilisateur=$_SESSION['idUtilisateur'];
      $ma_participation->save();
      $data['participants']=$this->getSalonParticipants($idSalon, $salon->idProprio);
    }else{
      $data = 'error';
    }
    $data['start']=false;
    echo json_encode($data);
  }

  public function getCountParticipants($idSalon){
    DBConnection::getInstance();
    $salon_participants = SalonParticipe::where('idSalon','=',$idSalon)->get();
    return $salon_participants->count();
  }

  public function loadParticipants(){
    $app=\Slim\Slim::getInstance();
    DBConnection::getInstance();
    if(!isset($_SESSION)){
      session_start();
    }
    $salon_participe = SalonParticipe::where('idUtilisateur','=',$_SESSION['idUtilisateur'])->first();

    $salon = Salon::where('idSalon','=',$salon_participe->idSalon)->first();
    $data['nom'] = $salon->nom;
    $data['nbJoueurs'] = $salon->nbJoueurs;
    $data['participants']=$this->getSalonParticipants($salon->idSalon,$salon->idProprio);
    if($_SESSION['idUtilisateur']==$salon->idProprio && count($data['participants'])==$salon->nbJoueurs){
      $data['start']=true;
    }else{
      $data['start']=false;
    }
    $partie = Partie::where('idSalon','=',$salon_participe->idSalon)->first();
    if($partie!=null){
      $data['game_link']=$app->urlFor('partie',array('id'=>$partie->idPartie));
    }else{
      $data['game_link']=null;
    }
    echo json_encode($data);
  }

  public function getSalonParticipants($idSalon, $idProprio){
    DBConnection::getInstance();
    $data=array();
    $salon_participants = SalonParticipe::where('idSalon','=',$idSalon)->get();
    foreach($salon_participants as $participant){
      $data[$participant->idUtilisateur]=array();
      $utilisateur = Utilisateur::where('idUtilisateur','=',$participant->idUtilisateur)->first();
      $data[$participant->idUtilisateur]['login']=$utilisateur->login;
      $data[$participant->idUtilisateur]['idImg']=$utilisateur->idImg;
      if($participant->idUtilisateur == $idProprio){
        $data[$participant->idUtilisateur]['proprio']=true;
      }else{
        $data[$participant->idUtilisateur]['proprio']=false;
      }
    }
    return $data;
  }

  public function loadSalons(){
    DBConnection::getInstance();
    if(!isset($_SESSION)){
      session_start();
    }
    $salons = Salon::get();
    $data;
    $data['salons']=array();
    foreach($salons as $salon){
      $data['salons'][$salon['idSalon']]=array();
      $data['salons'][$salon['idSalon']]['nom']=$salon['nom'];
      $data['salons'][$salon['idSalon']]['nbUsers'] = SalonParticipe::where('idSalon','=',$salon['idSalon'])->get()->count();
      $data['salons'][$salon['idSalon']]['nbJoueurs'] = $salon['nbJoueurs'];
    }
    echo json_encode($data);
  }

  public function verifPseudo($pseudo){
    DBConnection::getInstance();
    $user = Utilisateur::where('login',$pseudo)->first();
    $data;
    if($user!=null){
      $data['taken']=true;
    }else{
      $data['taken']=false;
    }
    echo json_encode($data);
  }

  public function connexion(){
    DBConnection::getInstance();
    if(!isset($_SESSION)){
      session_start();
    }
    $user = Utilisateur::where('login',$_POST['username'])->first();
    if(password_verify($_POST['password'],$user['pwd'])){
      $_SESSION['connected']=true;
      $_SESSION['login']=$_POST['username'];
      $_SESSION['password']=$_POST['password'];
      $_SESSION['img']=$user['idImg'];
      $_SESSION['idUtilisateur']=$user['idUtilisateur'];
    }
    $this->index();
  }

  public function deconnexion(){
    if(!isset($_SESSION)){
      session_start();
    }
    $_SESSION['connected']=false;
    unset($_SESSION['login']);
    unset($_SESSION['password']);
    unset($_SESSION['img']);
    unset($_SESSION['idUtilisateur']);
    $this->index();
  }

  public function verify(){
    DBConnection::getInstance();
    if(!isset($_SESSION)){
        session_start();
    }
    if(isset($_SESSION['login'])){
      $user = Utilisateur::where('login',$_SESSION['login'])->first();
      if(password_verify($_SESSION['password'],$user['pwd'])){
        return true;
      }
    }
    return false;
  }

  public function verifInscription(){
    DBConnection::getInstance();
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $user = new Utilisateur;
    $user->login = $_POST['username'];
    $user->pwd = $pass;
    $user->idImg = $_POST['image'];
    $user->save();
    $this->connexion();
  }
}
