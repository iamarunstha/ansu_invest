<?php

namespace App\Http\Controllers\Core\Tags;

use Illuminate\Database\Eloquent\Model;

class TagsModel extends Model
{
    public $timestamps = false;
    protected $table = 'tags';
    protected $guarded = ['id'];

    public function getRule($id = 0) {

    	$rule = [
    		
    	];

    	return $rule;
    }

    public function storeTags($tags, $delete) {
        $tags = explode(',', $tags);
        $tag_ids = [];
        foreach($tags as $index => $t) {
            $tags[$index] = trim($t);
            $tag = $this->firstOrCreate([
                'tag' => $tags[$index]
            ]);
            $tag_ids[] = $tag->id;
        }

        return $tag_ids;
    }

    public function storeTagsAndCompany($tags, $news_id, $delete=false) {
        $tag_ids = $this->storeTags($tags, $delete);
        if($delete) {
            TagsNewsModel::where('news_id', $news_id)->delete();
        }

        foreach($tag_ids as $id) {
            TagsNewsModel::firstOrCreate([
                'news_id'    =>  $news_id,
                'tag_id'    =>  $id
            ]);
        }
    }

    public function getTagsOfNews($news_id) {
        $tags_table = $this->table;
        $tag_news_table = (new TagsNewsModel)->getTable();

        $tags = \DB::table($tags_table)
                    ->join($tag_news_table, $tag_news_table.'.tag_id', '=', $tags_table.'.id')
                    ->where('news_id', $news_id)
                    //->select('tag', $tags_table.'.id')
                    ->pluck('tag')
                    ->toArray();

        $tags = !empty($tags) ? implode(',', $tags) : '';

        return $tags;
    }
}
