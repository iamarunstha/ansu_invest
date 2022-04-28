<?php

namespace App\Http\Controllers\Core\Recommendations\Api;

use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Recommendations\RecommendationsModel;


class ApiRecommendationsController extends Controller
{
    public function getRecommendations($id) {
        $news = RecommendationsModel::where('id', $id)->firstOrFail();
        if($news->show_asset_in_page == 'yes'){
            if($news->asset_type == 'image') {
                $news->asset = route('get-image-asset-type-filename', ['news', $news->asset]);
            }
        }else if($news->show_asset_in_page == 'no'){
            $news->asset_type = Null;
            $news->asset = Null;
        }
        return response()->json(['data' => $news]);
    }

    public function getRecommendationsList() {
        $news = RecommendationsModel::where('is_active', 'yes')
                        ->orderBy('ordering', 'ASC')
                        ->select([
                            'id', 
                            'title', 
                            'posted_by',
                            'summary',
                            'asset',
                            'asset_type',
                            'posted_at',
                            'counter'
                        ])->paginate(9);
        
        foreach($news as $index => $n) {
            if($n->asset_type == 'image') {
                $n->asset = route('get-image-asset-type-filename', ['recommendations', $n->asset]);
            }
        }

        return $news;

        //return response()->json(['data' => $news]);
    }


    public function getRecommendationsShow($no_of_items){
        $news = RecommendationsModel::where('is_active', 'yes')
                    ->select([
                        'id', 'summary', 'title', 'posted_at'
                    ])->inRandomOrder()
                      ->limit($no_of_items)
                      ->get();

        return response()->json(['data' => $news]);            
    }

    public function getTopRecommendations($no_of_items){
        $news = RecommendationsModel::where('is_active', 'yes')
                    ->select([
                        'id', 'summary', 'title', 'posted_at'
                    ])->inRandomOrder()
                        ->where('is_top_recommendations', 'yes')
                      ->limit($no_of_items)
                      ->get();

        return response()->json(['data' => $news]);            
    }

    public function getRelatedRecommendations($id=NULL) {
        $news = RecommendationsModel::where('is_active', 'yes')
                    ->select([
                        'id', 'summary', 'title', 'posted_at', 'asset', 'asset_type'
                    ])->inRandomOrder()
                        ->where('is_top_recommendations', 'yes')
                      ->limit(4)
                      ->get();

        foreach($news as $index => $n) {
            if($n->asset_type == 'image') {
                $n->asset = route('get-image-asset-type-filename', ['recommendations', $n->asset]);
            }
        }

        return response()->json(['data' => $news]);               
    }
}