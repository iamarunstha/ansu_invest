<?php

namespace App\Http\Controllers\Core\Stock\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Company\CompanyModel;
use App\Http\Controllers\Core\Stock\StockModel;
use Illuminate\Http\Request;
use Validator;


class ApiStockController extends Controller
{
    public function getStock($slug) {
    	
        $company = CompanyModel::where('slug', $slug)->firstOrFail();
        
        $stock = StockModel::where('company_id', $company->id)
                            ->select(['id', 'title', 'summary', 'posted_at', 'company_id'])
                            //->orderBy('posted_at','DESC')
                            ->first();

        if($stock) {
        	$stock->summary = '<p>' . implode('</p><p>', array_filter(explode("\r\n", $stock->summary))) . '</p>';
        }

        return $stock;
    }

    public function getStockFull($slug) {
    	$company = CompanyModel::where('slug', $slug)->firstOrFail();
        
        $stock = StockModel::where('company_id', $company->id)
                            //->select(['id', 'summary', 'posted_at', 'company_id'])
                            //->orderBy('posted_at','DESC')
                            ->first();

        if($stock) {
        	$stock->summary = '<p>' . implode('</p><p>', array_filter(explode("\r\n", $stock->summary))) . '</p>';
        	$stock->description = '<p>' . implode('</p><p>', array_filter(explode("\r\n", $stock->description))) . '</p>';
        }

        return $stock;	
    }
}