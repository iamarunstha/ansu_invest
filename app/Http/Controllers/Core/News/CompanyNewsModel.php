<?php

namespace App\Http\Controllers\Core\News;

use Illuminate\Database\Eloquent\Model;

class CompanyNewsModel extends Model
{
    public $timestamps = false;
    protected $table = 'company_news';
    protected $guarded = ['id'];

    public function getRule($id = 0) {

    	$rule = [
    		
    	];

    	return $rule;
    }

    public function getRelatedCompany($news_id) {
    	return $this->where('news_id', $news_id)->pluck('company_id')->toArray();
    }
}
