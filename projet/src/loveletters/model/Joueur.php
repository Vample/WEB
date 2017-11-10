<?php

namespace loveletters\model;
	
class Joueur extends \Illuminate\Database\Eloquent\Model{
	protected $table = 'Joueur';
	protected $primaryKey = 'idJoueur';
	public $timestamps = false;
	
	public function utilisateur(){
		return $this->belongsTo('\loveletters\model\utilisateur','idUtilisateur');
	}
	
	public function participe(){
		return $this->hasMany('\loveletters\model\participe','idJoueur');
	}
	
	public function possede(){
		return $this->hasMany('\loveletters\model\possede','idJoueur');
	}
	
	public function defausses(){
		return $this->hasMany('\loveletters\model\defausse','idJoueur');
	}
}