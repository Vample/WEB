<?php

namespace loveletters\controler;

use \loveletters\view\VueJeu;
use \loveletters\view\VueHeader;
use \loveletters\model\Utilisateur;
use \loveletters\model\DBConnection;

class ControlerJeu{
  public function index(){
    $vueJeu = new VueJeu();
    $vueJeu->render(VueJeu::INDEX);
  }

  public function inscription(){
    $vueJeu = new VueJeu();
    $vueJeu->render(VueJeu::INSCRIPTION);
  }

  public function connexion(){
    DBConnection::getInstance();
    session_start();
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
