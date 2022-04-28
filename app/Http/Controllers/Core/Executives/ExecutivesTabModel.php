<?php

namespace App\Http\Controllers\Core\Executives;

use Illuminate\Database\Eloquent\Model;

class ExecutivesTabModel extends Model
{
    protected $table = 'executive_tabs';
    protected $guarded = ['id'];

    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';

    public function getRule(){
    	return [
    		'tab_name' => ['required', 'string', 'unique:'.$this->getTable()],
    		'ordering' => ['nullable', 'numeric']
    	];
    }
}