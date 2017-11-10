<?php

namespace loveletters\model;
	
class Manche extends \Illuminate\Database\Eloquent\Model{
	protected $table = 'Manche';
	protected $primaryKey = 'idManche';
	public $timestamps = false;
	
	public function participe(){
		return $this->hasMany('\loveletters\model\participe','idManche');
	}
	
	public function defausses(){
		return $this->hasMany('\loveletters\model\defausse','idManche');
	}
}