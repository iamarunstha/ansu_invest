<?php

namespace App\Http\Controllers\Core\MarketVideos\Api;

use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\MarketVideos\MarketVideosModel;


class ApiMarketVideosController extends Controller
{
    public function getMarketVideos($id) {
        $news = MarketVideosModel::where('id', $id)->firstOrFail();
        $news->counter = is_null($news->counter) ? 1 : $news->counter+1;
        $news->save();
        if($news->asset_type == 'image') {
            $news->asset = route('get-image-asset-type-filename', ['news', $news->asset]);
        }

        return response()->json(['data' => $news]);
    }

    public function getMarketVideosList() {
        $input = request()->all();
        $select_column = ['id', 'title', 'posted_by','summary','asset','asset_type','posted_at'];
        
        $news = MarketVideosModel::where('is_active', 'yes')->select($select_column);
        
        if($input['filter'] == 'latest'){
            $news = $news->orderBy('posted_at', 'desc');
        }else if($input['filter'] == 'most-watched'){
            $news = $news->orderBy('counter', 'desc');
        }else if($input['filter'] == 'recommended'){
            $news = $news->where('feature', 'yes');
        }
        
        if($input['sort'] == 'desc'){
            $news = $news->orderBy('title', 'desc');
        }else if($input['sort'] == 'asc'){
            $news = $news->orderBy('title', 'asc');
        }

        if(!isset($input['filter']) && !isset($input['sort'])){
            $news = $news->orderBy('ordering');
        }
        
        foreach($news as $index => $n) {
            if($n->asset_type == 'image') {
                $n->asset = route('get-image-asset-type-filename', ['news', $n->asset]);
            }
        }

        $news = $news->paginate(8);
        return $news;
    }

    public function getMarketVideosShow($no_of_items) {

        $select_column = ['id', 'asset_type', 'asset', 'summary', 'title'];

        $news = MarketVideosModel::where('is_active', 'yes')
                        ->orderBy('ordering', 'ASC')
                        ->select($select_column)
                        ->inRandomOrder()
                        ->limit($no_of_items-1)
                        ->get();
        
        foreach($news as $index => $n) {
            if($n->asset_type == 'image') {
                $n->asset = route('get-image-asset-type-filename', ['news', $n->asset]);
            }
        }

        return response()->json(['data' => $news]);
    }

    public function getFeaturedMarketVideos($no_of_items) {

        $select_column = ['id', 'asset_type', 'asset', 'summary', 'title'];

        $news = MarketVideosModel::where('is_active', 'yes')
                        ->orderBy('ordering', 'ASC')
                        ->select($select_column)
                        ->inRandomOrder()
                        ->where('feature', 'yes')
                        ->limit($no_of_items)
                        ->get();
        
        foreach($news as $index => $n) {
            if($n->asset_type == 'image') {
                $n->asset = route('get-image-asset-type-filename', ['news', $n->asset]);
            }
        }

        return response()->json(['data' => $news]);
    }

    public function getRelatedMarketVideos($id=NULL) {

        $data = MarketVideosModel::inRandomOrder()
                        ->where('is_active', 'yes')
                        ->limit(4)
                        ->inRandomOrder()
                        ->get();

        foreach($data as $index => $d) {
            $data[$index]->asset = $d->asset_type == 'image' ? route('get-image-asset-type-filename', ['market-videos', $d->asset]) : $d->asset;
        }

        return [
            'data'  =>  $data
        ];
    }
}
