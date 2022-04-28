<?php

namespace App\Http\Controllers\Core\ProposedDividend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\FiscalYear\FiscalYearModel;
use App\Http\Controllers\Core\ProposedDividend\ProposedDividendModel;
use App\Http\Controllers\Core\Sector\SectorModel;
use Illuminate\Http\Request;

class ProposedDividendController extends Controller
{
	public $view = 'Core.ProposedDividend.backend.';

	public function getListView(){
		$dividends = ProposedDividendModel::orderBy('id')->with('fiscalYear','sector')->get();

		$years = FiscalYearModel::orderBy('ordering')->get();
		$sectors = SectorModel::orderBy('id')->get();
		return view($this->view.'list')
                ->with('dividends', $dividends)
                ->with('years', $years)
                ->with('sectors', $sectors);
	}

	public function postCreateView(){
		$input = request()->all();

		$validator = \Validator::make($input['data'], (new ProposedDividendModel)->getRule());
     
        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        }

		$record = ProposedDividendModel::firstOrNew([
                                'symbol' => $input['data']['symbol'],
                                'fiscal_year_id' =>  $input['data']['fiscal_year_id']
                            ]);
		
		foreach ($input['data'] as $title=>$value){
			$record[$title] = $value;
		}
		$record->save();

		session()->flash('success-msg', 'Proposed dividend successfully created');

        return redirect()->back();
	}

	public function getEditView($dividend_id){
		$dividend = ProposedDividendModel::where('id', $dividend_id)->firstOrFail();

		$years = FiscalYearModel::orderBy('ordering')->get();
		$sectors = SectorModel::orderBy('id')->get();
		return view($this->view.'edit')
                ->with('dividend', $dividend)
                ->with('years', $years)
                ->with('sectors', $sectors);
	}

	public function postEditView($dividend_id){
		$data = request()->all();
        $validator = \Validator::make($data['data'], (new ProposedDividendModel)->getRule());

        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);

        } else {
            ProposedDividendModel::where('id', $dividend_id)->update($data['data']);
            \Session::flash('success-msg', 'Proposed dividend successfully updated');

            return redirect()->route('admin-proposed-dividend-list-get');
        }
	}

	public function postDeleteView($dividend_id){
		$dividend = ProposedDividendModel::where('id', $dividend_id)->firstOrFail();
		$dividend->delete();

		session()->flash('success-msg', 'Proposed dividend successfully deleted');
		return redirect()->back();
	}
	
	public function postDeleteMultipleView(){
    	$rids = request()->get('rid');
        $success = 0;
        $error = 0;
        if($rids) {
            foreach($rids as $r) {
                $response = $this->apiDelete($r);
                if($response['status']) {
                    $success++;
                } else {
                    $error++;
                }
            }
            if($success) {
                \Session::flash('success-msg', $success.' successfully deleted');
            }

            if($error) {
                \Session::flash('friendly-error-msg', $error.' could not be deleted');   
            }
        } else {
            \Session::flash('friendly-error-msg', 'No items selected');   
        }

        return redirect()->back();
    }

    public function apiDelete($id) {
        try {
            $data = ProposedDividendModel::where('id', $id)->firstOrFail();

            try{
                \Storage::delete($this->storage_folder.DS.$data->asset);
            } catch(\Exception $e) {
                //do nothing
            }

            $data->delete();       
        } catch(\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage(), 'friendly-message' => 'Proposed dividend could not be deleted'];
        }

        return ['status' => true, 'message' => 'Proposed dividend successfully deleted'];
        
    }
}