<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdShareHasAdTags extends Model
{
	protected $table = 'ad_share_has_ad_tags';
	protected $primaryKey = 'tag_id';
}
