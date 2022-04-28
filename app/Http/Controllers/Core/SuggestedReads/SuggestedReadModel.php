<?php

namespace App\Http\Controllers\Core\SuggestedReads;

use Illuminate\Database\Eloquent\Model;

class SuggestedReadModel extends Model
{
    public $timestamps = false;
    protected $table = 'suggested_reads';
    protected $guarded = ['id'];

    /**
     * Get the model that the suggested-read belongs to.
     */
    public function post()
    {
        return $this->morphTo(__FUNCTION__, 'post_type', 'post_id');
    }
}
