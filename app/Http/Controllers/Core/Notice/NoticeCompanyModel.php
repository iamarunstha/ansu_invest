<?php

namespace App\Http\Controllers\Core\Notice;

use Illuminate\Database\Eloquent\Model;

class NoticeCompanyModel extends Model
{
	protected $table = 'notice_company';
    protected $guarded = ['id'];
    public $core = '\App\Http\Controllers\Core\\';
    public $timestamps = false;
}