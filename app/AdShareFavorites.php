<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdShareFavorites extends Model
{
	protected $table = 'ad_share_favorites';
	protected $primaryKey = 'ad_id';
	public $timestamps = false;

	protected $fillable = [
		'user_id', 'ad_id'
	];
}
