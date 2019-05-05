<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdNeighbors extends Model
{
	protected $table = 'ad_neighbors';
	protected $primaryKey = 'ad_id';

	protected $fillable = [
		'ad_id', 'user_id', 'text', 'region', 'city', 'settlement', 'pay', 'looking', 'tenants'
	];

	/**
	 * Получить пользователя подавшего объявления.
	 */
	public function user()
	{
		return $this->belongsTo('App\User', 'user_id', 'id');
	}

	/**
	 * Получить картинки для обьявления.
	 */
	public function imgs()
	{
		return $this->hasMany('App\AdNeighborsImgs', 'ad_id', 'ad_id');
	}

	/**
	 * Получить типы аренды для обьявления.
	 */
	public function rent()
	{
		return $this->hasMany('App\AdNeighborsRent', 'ad_id', 'ad_id');
	}

	/**
	 * Получить все теги для обьявления.
	 */
	public function tags()
	{
		return $this->hasManyThrough('App\AdTags', 'App\AdNeighborsHasAdTags', 'ad_id', 'tag_id');
	}
}
