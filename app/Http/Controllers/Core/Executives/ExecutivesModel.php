<?php

namespace App\Http\Controllers\Core\Executives;

use App\Http\Controllers\Core\Executives\ExecutivesTabModel;
use Illuminate\Database\Eloquent\Model;

class ExecutivesModel extends Model
{
    protected $table = 'company_executives';
    protected $guarded = ['id'];

    public $timestamps = false;
    public $core = '\App\Http\Controllers\Core\\';

    public function getRule(){
    	return [
            'tab_id' => ['required', 'exists:'.(new ExecutivesTabModel)->getTable().',id']
    	];
    }

    public function tab(){
        return $this->hasOne(ExecutivesTabModel::class, 'id', 'tab_id');
    }
    public function column(){
        return $this->hasOne(ExecutivesColumnModel::class, 'id', 'column_id');
    }
}