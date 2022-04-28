<?php

namespace App\Http\Controllers\Core\Experts;

use Illuminate\Database\Eloquent\Model;

class CompanyExpertModel extends Model
{
    public $timestamps = false;
    protected $table = 'company_expert';
    protected $guarded = ['id'];

    public function getRelatedCompany($expert_id) {
    	return $this->where('expert_id', $expert_id)->pluck('company_id')->toArray();
    }
}
