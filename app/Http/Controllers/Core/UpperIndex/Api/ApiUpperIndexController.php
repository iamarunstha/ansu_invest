<?php

namespace App\Http\Controllers\Core\UpperIndex\Api;   

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\UpperIndex\UpperIndexModel;
use App\Http\Controllers\Core\UpperIndex\NepseIndexModel;

class ApiUpperIndexController extends Controller
{
    public function getUpperIndexList($date=NULL) {
        $date = is_null($date) ? \Carbon\Carbon::now()->format('Y-m-d') : $date;
        $data = UpperIndexModel::where('as_on', UpperIndexModel::max('as_on'))
        						->paginate(1000);

        return $data;
    }

    public function getNepseIndex($date=NULL) {
        if ($date){
            $data = NepseIndexModel::where('as_on', $date);
            if($data->first()){
                $data = $data->first();
            }else{
                $data = NepseIndexModel::where('as_on', NepseIndexModel::max('as_on'))->first();
            }
        }else{
            $data = NepseIndexModel::where('as_on', NepseIndexModel::max('as_on'))->first();
        }
       	return $data;
    }
}
