<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdNeighborsFavorites extends Model
{
	protected $table = 'ad_neighbors_favorites';
	protected $primaryKey = 'ad_id';
	public $timestamps = false;

	protected $fillable = [
		'user_id', 'ad_id'
	];
}
