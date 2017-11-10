<?php

namespace loveletters\model;
	
class Partie extends \Illuminate\Database\Eloquent\Model{
	protected $table = 'Partie';
	protected $primaryKey = 'idPartie';
	public $timestamps = false;
	
	public function manches(){
		return $this->hasMany('\loveletters\model\manche','idPartie');
	}
}