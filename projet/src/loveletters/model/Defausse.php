<?php

namespace loveletters\model;
	
class Defausse extends \Illuminate\Database\Eloquent\Model{
	protected $table = 'Defausse';
	protected $primaryKey = 'idDefausse';
	public $timestamps = false;
	
	public function estPlacee(){
		return $this->hasMany('\loveletters\model\estplacee','idDefausse');
	}
}