<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'id', 'vk_id', 'name', 'email', 'password', 'avatar'
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

	/**
	 * Получить контактные данные пользователя.
	 */
	public function contactData()
	{
		return $this->hasMany('App\UsersHasContactData', 'user_id', 'id');
	}
}
