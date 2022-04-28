<?php

namespace App\Http\Controllers\Core\Poll;

use Illuminate\Database\Eloquent\Model;

class PollModel extends Model
{
	protected $table = 'poll';
    protected $guarded = ['id'];
    public $core = '\App\Http\Controllers\Core\\';
    public $timestamps = false;

    public function getRule(){
    	return [
    		'name'=> ['string', 'required'],
    		'notice_date'=>['string','required'],
    		'description'=>['string','required']
    	];
    }
}