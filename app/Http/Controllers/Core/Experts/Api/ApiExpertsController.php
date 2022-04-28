<?php

namespace App\Http\Controllers\Core\Experts\Api;

use Illuminate\Http\Request;
use Validator;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Experts\ExpertsModel;


class ApiExpertsController extends Controller
{
    public function getExperts($id) {
        $news = ExpertsModel::where('id', $id)->firstOrFail();
        if($news->show_asset_in_page == 'yes'){
            if($news->asset_type == 'image') {
                $news->asset = route('get-image-asset-type-filename', ['experts', $news->asset]);
            }
        }else if($news->show_asset_in_page == 'no'){
            $news->asset_type = Null;
            $news->asset = Null;
        }
        return response()->json(['data' => $news]);
    }

    public function getExpertsList() {
        $news = ExpertsModel::where('is_active', 'yes')
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
                        ])->paginate(16);
        
        foreach($news as $index => $n) {
            if($n->asset_type == 'image') {
                $news[$index]->asset = route('get-image-asset-type-filename', ['experts', $n->asset]);
            }
        }

        return $news;

        //return response()->json(['data' => $news]);
    }

    public function getExpertsShow($no_of_items) {
        $select_column = ['id','title','summary','asset','asset_type'];

        $news = ExpertsModel::where('is_active', 'yes')
                        ->orderBy('ordering', 'ASC')
                        ->select($select_column)
                        ->inRandomOrder()
                        ->limit($no_of_items)
                        ->get();
        
        foreach($news as $index => $n) {
            if($n->asset_type == 'image') {
                $news[$index]->asset = route('get-image-asset-type-filename', ['experts', $n->asset]);
            }
        }

        return response()->json(['data' => $news]);
    }

    public function getFeaturedExpertsShow($no_of_items) {
        $select_column = ['id','title','summary','asset','asset_type'];

        $news = ExpertsModel::where('is_active', 'yes')
                        ->orderBy('ordering', 'ASC')
                        ->select($select_column)
                        ->inRandomOrder()
                        ->where('feature', 'yes')
                        ->limit($no_of_items)
                        ->get();
        
        foreach($news as $index => $n) {
            if($n->asset_type == 'image') {
                $news[$index]->asset = route('get-image-asset-type-filename', ['experts', $n->asset]);
            }
        }

        return response()->json(['data' => $news]);
    }

    public function getRelatedExperts($id=NULL) {
        $select_column = ['id','title','summary','asset','asset_type', 'posted_at', 'posted_by'];

        $news = ExpertsModel::where('is_active', 'yes')
                        ->orderBy('ordering', 'ASC')
                        ->select($select_column)
                        ->inRandomOrder()
                        ->limit(4)
                        ->get();
        
        foreach($news as $index => $n) {
            if($n->asset_type == 'image') {
                $news[$index]->asset = route('get-image-asset-type-filename', ['experts', $n->asset]);
            }
        }

        return response()->json(['data' => $news]);   
    }
}