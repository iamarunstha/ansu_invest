<?php

namespace App\Http\Controllers\Core\Poll\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Poll\PollModel;
use App\Http\Controllers\Core\Company\CompanyModel;
use Illuminate\Http\Request;

class ApiPollController extends Controller
{
	public function getPollOfCompany($slug=NULL) {
		$company = CompanyModel::where('slug', $slug)
									->select(['asset', 'id', 'company_name', 'short_code'])
									->first();
		$company->asset = $company->asset ? route('get-image-asset-type-filename', ['company-logo', $company->asset]) : NULL;

		$data = PollModel::where('company_id', $company->id)
						->first();

		if($data) {
			
			$total = (int) ($data->buy + $data->sell + $data->hold);
			$result = [
				'buy' => $total > 0 ? round($data->buy / $total * 100) : NULL,
				'sell' => $total > 0 ? round($data->sell / $total * 100) : NULL,
				'hold' => $total > 0 ? round($data->hold / $total * 100) : NULL,
				'company'	=>	$company
			];

			$return = [
				'question'	=>	$company,
				'result'	=>	$result
			];

		} else {
			$return = [
				'question' => $company,
				'result' => NULL
			];
		}

		return $return;

	}

	public function getActivePoll() {
		
		$data = PollModel::where('is_active', 'yes')
						->first();

		if($data) {
			$company = CompanyModel::where('id', $data->company_id)
									->select(['asset', 'id', 'company_name', 'short_code'])
									->first();

			$company->asset = $company->asset ? route('get-image-asset-type-filename', ['company-logo', $company->asset]) : NULL;



			$total = (int) ($data->buy + $data->sell + $data->hold);
			$result = [
				'buy' => $total > 0 ? round($data->buy / $total * 100) : NULL,
				'sell' => $total > 0 ? round($data->sell / $total * 100) : NULL,
				'hold' => $total > 0 ? round($data->hold / $total * 100) : NULL,
				'company'	=>	$company
			];

			$return = [
				'question'	=>	$company,
				'result'	=>	$result
			];

		} else {
			$return = [
				'question' => NULL,
				'result' => NULL
			];
		}

		return $return;
	}

	public function updatePollCompany($company_id) {
		$input = request()->all();
		$column = $input['column'];
		$data = PollModel::firstOrNew(['company_id' => $company_id]);

		$data->$column += 1;
		$data->save();

		$slug = CompanyModel::where('id', $company_id)->first()->slug;

		return $this->getPollOfCompany($slug);
	}
}