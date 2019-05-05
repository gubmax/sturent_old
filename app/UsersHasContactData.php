<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersHasContactData extends Model
{
	protected $table = 'users_has_contact_data';
	protected $primaryKey = 'contact_data_id';
	public $timestamps = false;

	protected $fillable = [
		'contact_data_id', 'user_id', 'value'
	];

	/**
	 * Получить контактные данные пользователя.
	 */
	public function contactData()
	{
		return $this->belongsTo('App\ContactData', 'contact_data_id', 'id');
	}
}
