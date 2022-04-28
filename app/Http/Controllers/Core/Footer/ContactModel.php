<?php

namespace App\Http\Controllers\Core\Footer;

use Illuminate\Database\Eloquent\Model;

class ContactModel extends Model
{
    protected $table = 'footer_contacts';
    protected $guarded = ['id'];
    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';
}
