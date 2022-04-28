<?php

namespace App\Http\Controllers\Core\Investment;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\FiscalYear\FiscalYearModel;
use App\Http\Controllers\Core\Investment\InvestmentModel;
use App\Http\Controllers\Core\Investment\InvestmentTabModel;
use App\Http\Controllers\Core\Sector\SectorModel;
use Illuminate\Http\Request;

class InvestmentController extends Controller
{
	public $view = 'Core.Investment.backend.';

	public function getTabsListView(){
		$tabs = InvestmentTabModel::orderBy('ordering')->get();
		return view($this->view.'list-tabs')
				->with('tabs', $tabs);
	}

	public function getListView($tab_id){
		$tab = InvestmentTabModel::where('id', $tab_id)->first();
		$investments = InvestmentModel::where('tab_id', $tab_id)->get();
		return view($this->view.'list')
                ->with('tab', $tab)
                ->with('investments', $investments);
	}

	public function getCreateView($tab_id){
		$tab = InvestmentTabModel::where('id', $tab_id)->first();
		return view($this->view.'create')
                ->with('tab', $tab);
	}

	public function postCreateView($tab_id){
		$input = request()->all();
		$tab_name = InvestmentTabModel::where('id', $tab_id)->first()->tab_name;
		$validator = \Validator::make($input['data'], (new InvestmentModel)->getRule($tab_name));
     
        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        }
		
		$record = (new InvestmentModel);
		foreach ($input['data'] as $title=>$value){
			$record[$title] = $value;
		}
		$record['tab_id'] =$tab_id;
		$record->save();

		session()->flash('success-msg', 'Investment/Existing Issues successfully created');

        return redirect()->route('admin-investment-list-get', $tab_id);
	}

	public function getEditView($id){
		$investment = InvestmentModel::where('id', $id)->first();
		$tab_id = $investment->tab_id;
		$tab = InvestmentTabModel::where('id', $tab_id)->first();
		return view($this->view.'edit')
                ->with('investment', $investment)
                ->with('tab', $tab);
	}

	public function postEditView($id){
		$investment = InvestmentModel::where('id', $id)->firstOrFail();

		$input = request()->all();
		$tab_name = InvestmentTabModel::where('id', $investment->tab_id)->first()->tab_name;
		$validator = \Validator::make($input['data'], (new InvestmentModel)->getRule($tab_name));

		if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        }

        foreach ($input['data'] as $title=>$value){
        	$investment[$title] = $value;
        }

        $investment->save();
		session()->flash('success-msg', 'Investment/Existing Issues successfully updated');
        return redirect()->back();
	}

	public function postDeleteView($dividend_id){
		$dividend = InvestmentModel::where('id', $dividend_id)->firstOrFail();
		$dividend->delete();

		session()->flash('success-msg', 'Investment/Existing Issues successfully deleted');
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
            $data = InvestmentModel::where('id', $id)->firstOrFail();

            try{
                \Storage::delete($this->storage_folder.DS.$data->asset);
            } catch(\Exception $e) {
                //do nothing
            }

            $data->delete();       
        } catch(\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage(), 'friendly-message' => 'Investment/Existing Issues could not be deleted'];
        }

        return ['status' => true, 'message' => 'Investment/Existing Issues successfully deleted'];
        
    }
}