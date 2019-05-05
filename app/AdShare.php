<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdShare extends Model
{
	protected $table = 'ad_share';
	protected $primaryKey = 'ad_id';

	protected $fillable = [
		'ad_id', 'user_id', 'text', 'region', 'city', 'settlement', 'district', 'street', 'house', 'looking', 'tenants', 'rent', 'pay', 'pledge', 'latitude', 'longitude'
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
		return $this->hasMany('App\AdShareImgs', 'ad_id', 'ad_id');
	}

	/**
	 * Получить все теги для обьявления.
	 */
	public function tags()
	{
		return $this->hasManyThrough('App\AdTags', 'App\AdShareHasAdTags', 'ad_id', 'tag_id');
	}
}
