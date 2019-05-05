<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdNeighborsRent extends Model
{
	protected $table = 'ad_neighbors_rent';
	protected $primaryKey = 'rent_id';

	protected $fillable = [
		'ad_id', 'rent_id'
	];
}
