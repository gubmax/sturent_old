<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactData extends Model
{
	protected $table = 'contact_data';
	protected $primaryKey = 'id';

	protected $fillable = [
		'id', 'name'
	];
}
