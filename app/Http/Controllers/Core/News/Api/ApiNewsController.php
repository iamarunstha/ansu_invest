<?php

namespace App\Http\Controllers\Core\News\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Company\CompanyModel;
use App\Http\Controllers\Core\News\CompanyNewsModel;
use App\Http\Controllers\Core\News\NewsModel;
use Illuminate\Http\Request;
use Validator;


class ApiNewsController extends Controller
{
    public function getNews($id) {
        $news = NewsModel::where('id', $id)->with('getRelatedCompanies')->firstOrFail();

        $news->counter += 1;
        $news->save();
        $news['slug'] = count($news->getRelatedCompanies) == 1 ? $news->getRelatedCompanies[0]->slug : Null;
        unset($news->getRelatedCompanies);
        unset($news['asset']);
        unset($news['asset_type']);

        return response()->json(['data' => $news]);
    }

    public function getNewsList() {
        $no_of_items = request()->get('no_of_items', 6);
        $news = NewsModel::where('is_active', 'yes')
                        ->where('news_type', 'news')
                        ->orderBy('posted_at', 'DESC')
                        ->select([
                            'id', 
                            'title', 
                            'posted_by',
                            'summary',
                            'asset',
                            'asset_type',
                            'posted_at',
                            'counter'
                        ])->paginate($no_of_items);
        
        foreach($news as $index => $n) {
            if($n->asset_type == 'image') {
                $n->asset = route('get-image-asset-type-filename', ['news', $n->asset]);
            }
        }

        return response()->json(['data' => $news]);
    }

    public function getCompanyNewsList($slug){
        $no_of_items = request()->get('no_of_items', 6);
        $company = CompanyModel::where('slug', $slug)->firstOrFail();

        $_news_ids = CompanyNewsModel::select('news_id')->where('company_id', $company->id)->get();
        $news_ids = [];
        foreach ($_news_ids as $n){
            $news_ids[] = $n->news_id;
        }
        $news = NewsModel::where('is_active', 'yes')
                        ->whereIn('id', $news_ids)
                        ->where('news_type', 'news')
                        ->orderBy('posted_at', 'DESC')
                        ->select([
                            'id', 
                            'title', 
                            'posted_by',
                            'summary',
                            'asset',
                            'asset_type',
                            'posted_at',
                            'counter'
                        ])->paginate($no_of_items);

        foreach($news as $index => $n) {
            if($n->asset_type == 'image') {
                $n->asset = route('get-image-asset-type-filename', ['news', $n->asset]);
            }
        }
        return response()->json(['data' => $news]);
    }

    public function getTopNews() {
        $slug = request()->get('slug', NULL);
        $company_id = NULL;
        if($slug) {
            $company_id = CompanyModel::where('slug', $slug)->first();
            $company_id = $company_id ? $company_id->id : NULL;
        }

        $no_of_items = request()->get('no_of_items', 6);
        $news = NewsModel::where('is_active', 'yes');
        
        $news_table = (new NewsModel)->getTable();
        $company_news_table = (new CompanyNewsModel)->getTable();

        if($slug) {
            $news = $news->join($company_news_table, $company_news_table.'.news_id', '=', $news_table.'.id')
                         ->where('company_id', $company_id)->where('news_type', 'news');
        }
                        
        $news = $news->orderBy('posted_at', 'DESC')
                        ->select([
                            $news_table.'.id', 
                            'title', 
                            'posted_by',
                            'summary',
                            'asset',
                            'asset_type',
                            'posted_at',
                            'counter'
                        ])
                        ->where('is_top_news', 'yes')
                        ->paginate($no_of_items);

        foreach($news as $index => $n) {
            if($n->asset_type == 'image') {
                $n->asset = route('get-image-asset-type-filename', ['news', $n->asset]);
            }
        }

        return response()->json(['data' => $news]);
    }

    public function getMostRead() {
        $slug = request()->get('slug', NULL);
        $company_id = NULL;
        if($slug) {
            $company_id = CompanyModel::where('slug', $slug)->first();
            $company_id = $company_id ? $company_id->id : NULL;
        }

        $no_of_items = request()->get('no_of_items', 6);
        $news = NewsModel::where('is_active', 'yes');
        
        $news_table = (new NewsModel)->getTable();
        $company_news_table = (new CompanyNewsModel)->getTable();

        if($slug) {
            $news = $news->join($company_news_table, $company_news_table.'.news_id', '=', $news_table.'.id')
                         ->where('company_id', $company_id)->where('news_type', 'news');
        }
        $news = $news->orderBy('counter', 'DESC')
                        ->select([
                            $news_table.'.id', 
                            'title', 
                            'posted_by',
                            'summary',
                            'asset',
                            'asset_type',
                            'posted_at',
                            'counter'
                        ])
                        ->paginate($no_of_items);

        foreach($news as $index => $n) {
            if($n->asset_type == 'image') {
                $n->asset = route('get-image-asset-type-filename', ['news', $n->asset]);
            }
        }

        return response()->json(['data' => $news]);
    }

    public function getLatestNews() {
        $slug = request()->get('slug', NULL);
        $company_id = NULL;
        if($slug) {
            $company_id = CompanyModel::where('slug', $slug)->first();
            $company_id = $company_id ? $company_id->id : NULL;
        }

        $no_of_items = request()->get('no_of_items', 6);
        $news = NewsModel::where('is_active', 'yes');
        
        $news_table = (new NewsModel)->getTable();
        $company_news_table = (new CompanyNewsModel)->getTable();

        if($slug) {
            $news = $news->join($company_news_table, $company_news_table.'.news_id', '=', $news_table.'.id')
                         ->where('company_id', $company_id)->where('news_type', 'news');
        }

        $news = $news->orderBy('posted_at', 'DESC')
                        ->select([
                            $news_table.'.id', 
                            'title', 
                            'posted_by',
                            'summary',
                            'asset',
                            'asset_type',
                            'posted_at',
                            'counter'
                        ])
                        ->paginate($no_of_items);

        foreach($news as $index => $n) {
            if($n->asset_type == 'image') {
                $n->asset = route('get-image-asset-type-filename', ['news', $n->asset]);
            }
        }

        return response()->json(['data' => $news]);
    }

    public function getNewsBoard() {
        $news = NewsModel::where('is_active', 'yes')
                        ->orderBy('posted_at', 'DESC')
                        ->select([
                            'id', 
                            'title', 
                            'posted_by',
                            'summary',
                            'asset',
                            'asset_type',
                            'posted_at',
                            'counter'
                        ])
                        ->where('is_newsboard', 'yes')
                        ->first();


        if(!is_null($news) && $news->asset_type == 'image') {
            $news->asset = route('get-image-asset-type-filename', ['news', $news->asset]);
        }

        //$status = !is_null($news) ? 'present' : 'not present';
        
        return response()->json(['news' => $news]);
    }

    public function getRelatedNews($id=NULL) {
        $data = NewsModel::inRandomOrder()
                         ->select([
                            'id', 
                            'title', 
                            'posted_by',
                            'summary',
                            'asset',
                            'asset_type',
                            'posted_at',
                            'counter'
                        ])
                        ->where('is_active', 'yes')
                        ->limit(4)
                        ->get();

        foreach($data as $index => $d) {
            $data[$index]->asset = $d->asset_type == 'image' ? route('get-image-asset-type-filename', ['news', $d->asset]) : $d->asset;
        }

        return [
            'data'  =>  $data
        ];
    }

    public function getNewsReports(){
        $no_of_items = request()->get('no_of_items', 6);
        $news = NewsModel::where('is_active', 'yes')
                        ->where('news_type', 'report')
                        ->orderBy('posted_at', 'DESC')
                        ->select([
                            'id', 
                            'title', 
                            'posted_by',
                            'summary',
                            'asset',
                            'asset_type',
                            'posted_at',
                            'counter'
                        ])->paginate($no_of_items);
        
        foreach($news as $index => $n) {
            if($n->asset_type == 'image') {
                $n->asset = route('get-image-asset-type-filename', ['news', $n->asset]);
            }
        }

        return response()->json(['data' => $news]);
    }

    public function getCompanyReportList($slug){
        $no_of_items = request()->get('no_of_items', 6);
        $company = CompanyModel::where('slug', $slug)->firstOrFail();

        $_news_ids = CompanyNewsModel::select('news_id')->where('company_id', $company->id)->get();
        $news_ids = [];
        foreach ($_news_ids as $n){
            $news_ids[] = $n->news_id;
        }
        $news = NewsModel::where('is_active', 'yes')
                        ->whereIn('id', $news_ids)
                        ->where('news_type', 'report')
                        ->orderBy('posted_at', 'DESC')
                        ->select([
                            'id',
                            'title',
                            'posted_by',
                            'summary',
                            'asset',
                            'asset_type',
                            'posted_at',
                            'counter'
                        ])->paginate($no_of_items);

        foreach($news as $index => $n) {
            if($n->asset_type == 'image') {
                $n->asset = route('get-image-asset-type-filename', ['news', $n->asset]);
            }
        }
        return response()->json(['data' => $news]);
    }
}
