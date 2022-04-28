<?php

namespace App\Http\Controllers\Core\News;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Core\Company\CompanyModel;

class NewsModel extends Model
{
    protected $table = 'news';
    protected $guarded = ['id'];
    public $core = '\App\Http\Controllers\Core\\';

    public function getRule($id = 0) {

    	$rule = [
    		'title'	=>	['required'],
    		'asset'	=>	['nullable'],
    		'asset_type'	=>	['nullable','in:video,image'],
    		'summary'	=>	['required'],
    		'posted_at'	=>	['date_format:Y-m-d H:i:s'],
    		'is_active'	=>	['required', 'in:yes,no'],
            'news_type' => ['required', 'in:news,report']
    	];

    	return $rule;
    }

    public function newsData($id) {
        $core = $this->core.'Tags\TagsModel';
        $tags = (new $core)->getTagsOfNews($id);
        $related_companies = (new CompanyNewsModel)->getRelatedCompany($id);

        $core = $this->core.'Company\CompanyModel';
        $companies = $core::orderBy('company_name', 'ASC')->get();

        return ['related_companies' => $related_companies, 'companies' => $companies, 'tags' => $tags];
    }

    public function getLatestNews() {
        return self::where('is_active', 'yes')->orderBy('posted_at', 'DESC')->paginate(8);
    }

    public function getTopNews() {
        return self::where('is_active', 'yes')->where('is_top_news', 'yes')->orderBy('posted_at', 'DESC')->paginate(8);   
    }

    public function getMostRead() {
        return self::where('is_active', 'yes')->orderBy('counter', 'DESC')->orderBy('posted_at', 'DESC')->paginate(8);
    }

    public function getRelatedCompanies(){
        return $this->belongsToMany(CompanyModel::class, CompanyNewsModel::class, 'news_id', 'company_id');
    }

    public function suggested(){
        return $this->morphOne(SuggestedReadModel::class, 'post');
    }
}
