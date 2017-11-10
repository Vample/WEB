<?php

namespace loveletters\model;
	
class Pioche extends \Illuminate\Database\Eloquent\Model{
	protected $table = 'Pioche';
	protected $primaryKey = 'idPioche';
	public $timestamps = false;
	
	public function comporte(){
		return $this->hasMany('\loveletters\model\comporte','idPioche');
	}
	
	public function manche(){
		return $this->belongsTo('\loveletters\model\manche','idPioche');
	}
}