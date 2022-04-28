<?php

namespace App\Http\Controllers\Core\StaticPage;

use Illuminate\Database\Eloquent\Model;

class StaticPageModel extends Model
{
    protected $table = 'static_pages';
    protected $guarded = ['id'];
    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';

    public function definitions(){
        return $this->hasMany(DefinitionModel::class, 'page_id', 'id')->orderBy('term');
    }
}
