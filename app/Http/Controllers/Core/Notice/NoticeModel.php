<?php

namespace App\Http\Controllers\Core\Notice;

use Illuminate\Database\Eloquent\Model;

class NoticeModel extends Model
{
	protected $table = 'notice';
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