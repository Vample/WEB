<?php

namespace loveletters\model;
	
class Carte extends \Illuminate\Database\Eloquent\Model{
	protected $table = 'Carte';
	protected $primaryKey = 'idCarte';
	public $timestamps = false;
	
	public function possede(){
		return $this->hasMany('\loveletters\model\possede','idCarte');
	}
	
	public function estPlacee(){
		return $this->hasMany('\loveletters\model\estplacee','idCarte');
	}
	
	public function comporte(){
		return $this->hasMany('\loveletters\model\comporte','idCarte');
	}
	
}