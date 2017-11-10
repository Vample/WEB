<?php

namespace loveletters\model;
	
class Utilisateur extends \Illuminate\Database\Eloquent\Model{
	protected $table = 'Utilisateur';
	protected $primaryKey = 'idUtilisateur';
	public $timestamps = false;
	
	public function joueurs(){
		return $this->hasMany('\loveletters\model\joueur','idJoueur');
	}
}