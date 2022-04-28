<?php

namespace App\Http\Controllers\Core\Notice\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Notice\NoticeModel;
use App\Http\Controllers\Core\Notice\NoticeCompanyModel;
use App\Http\Controllers\Core\Company\CompanyModel;

use Illuminate\Http\Request;

class ApiNoticeController extends Controller
{
	public function getNoticeList($no_of_notice=0){		
		$notices = NoticeModel::orderBy('notice_date', 'DESC');

		$notices = $notices->paginate($no_of_notice);
		return $notices;
	}

	public function getNotice($id){
		$notice = NoticeModel::where('id', $id)->firstOrFail();

		return response()->json(["data" => $notice]);
	}

	public function getCompanyNotices($slug){
		$no_of_items = request()->get('no_of_items', 6);
		$company = CompanyModel::where('slug', $slug)->firstOrFail();

		$notice_ids = NoticeCompanyModel::select('notice_id')->where('company_id', $company->id)->pluck('notice_id')->toArray();
		$notices = NoticeModel::whereIn('id', $notice_ids)
										->orderBy('notice_date', 'DESC')
										->paginate($no_of_items);

		return response()->json(['data' => $notices]);
	}
}