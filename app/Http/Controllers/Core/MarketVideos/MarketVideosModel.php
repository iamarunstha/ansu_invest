<?php

namespace App\Http\Controllers\Core\MarketVideos;

use Illuminate\Database\Eloquent\Model;

class MarketVideosModel extends Model
{
    protected $table = 'market_videos';
    protected $guarded = ['id'];
    public $core = '\App\Http\Controllers\Core\\';

    public function getRule($id = 0) {

    	$rule = [
    		'title'	=>	['required'],
    		'asset'	=>	['nullable'],
    		'asset_type'	=>	['nullable','in:video'],
    		'summary'	=>	['required'],
    		'posted_at'	=>	['date_format:Y-m-d H:i:s'],
    		'is_active'	=>	['required', 'in:yes,no']
    	];

    	return $rule;
    }

    public function marketVideosData($id) {
        return self::where('id', $id)->firstOrFail();
    }

    public function getFrontendMarketVideos($paginate=0) {
        $data = self::where('is_active', 'yes')->orderBy('id', 'ASC')->select(array_diff($this->getColumns(), ['description']));
        if($paginate) {
            $data = $data->paginate($paginate);
        } else {
            $data = $data->get();
        }

        return $data;
    }

    public function getColumns() {
        return \Schema::getColumnListing($this->table);
    }
}
