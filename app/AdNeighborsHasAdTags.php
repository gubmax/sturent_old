<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdNeighborsHasAdTags extends Model
{
	protected $table = 'ad_neighbors_has_ad_tags';
	protected $primaryKey = 'tag_id';
}
