<?php

namespace App\Http\Controllers\Core\Tags;

use Illuminate\Database\Eloquent\Model;

class TagsNewsModel extends Model
{
    public $timestamps = false;
    protected $table = 'tags_news';
    protected $guarded = ['id'];

    public function getRule($id = 0) {

    	$rule = [
    		
    	];

    	return $rule;
    }
}
