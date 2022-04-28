<?php

namespace App\Http\Controllers\Core\SuggestedReads\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\SuggestedReads\SuggestedReadModel;

class ApiSuggestedReadsController extends Controller
{
	public function getSuggestedReads(){
		$_posts = SuggestedReadModel::orderBy('ordering')->with('post')->take(3)->get();
        $posts = [];

        foreach($_posts as $p){
            $post = $p->post->toArray();
            $filtered_post = array_filter($post, function($k) {
                $allowlist = array( 'title', 'slug', 'summary', 'id');
                if(in_array($k, $allowlist)){
                    return True;
                }
            }, ARRAY_FILTER_USE_KEY);
            $filtered_post['category'] = $p->category;
            $filtered_post['slug'] = $p->post->company ? $p->post->company->slug : Null;
            $filtered_post['category_slug'] = $this->matchCategoryToSlug($p->category);
            $posts[] = $filtered_post;
        }
        return response()->json($posts);
	}

    public function getSuggestedReadsSidebar(){
        $no_of_post = request()->get('no_of_items', 1);
		$_posts = SuggestedReadModel::orderBy('ordering')->with('post')->take($no_of_post)->get();
        $posts = [];
        foreach($_posts as $p){
            $post = $p->post;
            switch ($p->category){
                case 'News':
                    $post->path = '/news/'.$post->id;
                    break;
                case 'Research & Opinions':
                    $post->path = '/experts/'.$post->id;
                    break;
                case 'Stock Analysis':
                    $post->path = '/company/'.$post->company->slug.'/analysis';
                    break;
            }

            $posts[] = $post->only('id','title','summary', 'path');
        }
        return response()->json($posts);
    }

    private function matchCategoryToSlug($category){
        $category_slug = [
            'Research & Opinions' => 'research-opinion',
            'News' => 'news',
            'Stock Analysis' => 'stock'
        ];
        return $category_slug[$category];
    }
}
