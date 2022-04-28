<?php

namespace App\Http\Controllers\Core\MarketSummary\Api;   

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\MarketSummary\MarketSummaryModel;

class ApiMarketSummaryController extends Controller
{
    public function getMarketSummaryList($date=NULL) {
        $date = is_null($date) ? \Carbon\Carbon::now()->format('Y-m-d') : $date;
        $data = MarketSummaryModel::where('as_on', MarketSummaryModel::max('as_on'))
        						->paginate(1000);

       	return $data;
    }
}