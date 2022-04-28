<?php

namespace App\Http\Controllers\Core\Footer;

use Illuminate\Database\Eloquent\Model;

class FooterLinkModel extends Model
{
    protected $table = 'footer_links';
    protected $guarded = ['id'];
    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';
}
