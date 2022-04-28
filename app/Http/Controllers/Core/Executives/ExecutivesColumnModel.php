<?php

namespace App\Http\Controllers\Core\Executives;

use Illuminate\Database\Eloquent\Model;

class ExecutivesColumnModel extends Model
{
    protected $table = 'executive_columns';
    protected $guarded = ['id'];

    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';

    public function getRule(){
    	return [
    		'column_name' => ['required', 'string', 'unique:'.$this->getTable()],
            'ordering' => ['nullable', 'integer'],
            'type' => ['required', 'in:varchar,integer,text,float'],
    	];
    }
}