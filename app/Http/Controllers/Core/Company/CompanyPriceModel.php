<?php

namespace App\Http\Controllers\Core\Company;

use Illuminate\Database\Eloquent\Model;

class CompanyPriceModel extends Model
{
    public $timestamps = false;
    protected $table = 'stock_price';
    protected $guarded = ['id'];

    public $core = '\App\Http\Controllers\Core\\';

    public function getRule($id = 0) {

    	$rule = [
    		
    	];

    	return $rule;
    }

    public function getPrice($date) {
        $prev_date = \Carbon\Carbon::createFromFormat('Y-m-d', $date)->subDay()->format('Y-m-d');

        $current_price = [];
        $company_table = (new CompanyModel)->getTable();
        $companies = \DB::table($company_table)
                        ->get();

        $_current_price = \DB::table($this->table)
                                ->select('company_id', 'price', 'price_at', 'company_id as company_name')
                                ->where('price_at', $date)
                                //->orderBy('company_name', 'ASC')
                                ->get();


        foreach($_current_price as $c) {
            $current_price[$c->company_id] = $c;
        }

        foreach($companies as $c) {
            if(!isset($current_price[$c->id])) {
                $current_price[$c->id] = json_decode(json_encode(['company_id' => $c->id, 'price' => NULL, 'price_at' => $date, 'company_name' => $c->company_name]));
            } else {
                $current_price[$c->id]->company_name = $c->company_name;
            }
            
        }

        
        $previous_price = [];
        $_previous_price = $this->where('price_at', $prev_date)->get();
        foreach($_previous_price as $p) {
            $previous_price[$p->company_id] = $p;
        }

        $diff = [];
        foreach($current_price as $company_id => $c) {
            $diff[$company_id] = ['name' => $c->company_name, 'company_id' => $company_id, 'price' => $c->price, 'difference' => 0, 'percentage' => 0];
            $diff[$company_id]['difference'] = isset($previous_price[$company_id]) ? $c->price - $previous_price[$company_id]->price : NULL;
            $diff[$company_id]['percentage'] = is_null($diff[$company_id]['difference']) ? NULL : \App\Http\Controllers\HelperController::noOfDecimals($diff[$company_id]['difference'] * 100 / $c->price);
        }

        return $diff;
    }

    public function getAllAndGainersAndLosers() {
        $date = \Carbon\Carbon::now()->format('Y-m-d');
        $data = $this->getPrice($date);   

        $gainers = [];
        $losers = [];

        $all = [];

        foreach($data as $company_id => $d) {
            $all[$company_id] = $d;
            if($d['percentage'] > 0) {
                $gainers[$company_id] =  $d;  
            } else {
                $losers[$company_id] = $d;
            }
            
        }


        return ['gainers' => $gainers, 'losers' => $losers, 'all' => $all];
    }
}
