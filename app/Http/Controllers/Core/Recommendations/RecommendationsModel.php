<?php

namespace App\Http\Controllers\Core\Recommendations;

use Illuminate\Database\Eloquent\Model;

class RecommendationsModel extends Model
{
    protected $table = 'recommendations';
    protected $guarded = ['id'];
    public $core = '\App\Http\Controllers\Core\\';

    public function getRule($id = 0) {

    	$rule = [
    		'title'	=>	['required'],
    		'asset'	=>	['nullable'],
    		'asset_type'	=>	['nullable','in:video,image'],
    		'summary'	=>	['required'],
    		'posted_at'	=>	['date_format:Y-m-d H:i:s'],
    		'is_active'	=>	['required', 'in:yes,no']
    	];

        if($id) {
            $rule['asset'] = [];
        }

    	return $rule;
    }

    public function getFrontendRecommendations($paginate=9) {
        return self::where('is_active', 'yes')->orderby('ordering', 'ASC')->paginate($paginate);
    }
}
