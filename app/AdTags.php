<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdTags extends Model
{
	protected $table = 'ad_tags';
	protected $primaryKey = 'tag_id';

	protected $fillable = [
		'ad_id', 'tag_id'
	];
}
