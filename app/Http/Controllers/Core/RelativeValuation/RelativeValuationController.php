<?php

namespace App\Http\Controllers\Core\RelativeValuation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Core\RelativeValuation\RelativeValuationModel;
use App\Http\Controllers\Core\Company\CompanyModel;

class RelativeValuationController extends Controller
{
	public $view = 'Core.RelativeValuation.backend.';
    public $frontend_view = 'Core.RelativeValuation.frontend.';
	private $storage_folder = 'relative-valuation';

	public function getListView()
	{
        $company_name = request()->get('company_name', NULL);
        $relative_valuation_table = (new RelativeValuationModel)->getTable();
        $company_table = (new CompanyModel)->getTable();
        $data = CompanyModel::leftJoin(
                                $relative_valuation_table, $relative_valuation_table.'.company_id', '=', $company_table.'.id'
                            );

        if($company_name) {
            $data = $data->where('company_name', 'LIKE', '%'.$company_name.'%');
        }
        
        $data = $data->select($company_table.'.id as company_id', 'company_name', $relative_valuation_table.'.id as relative_valuation_id')
                            ->orderBy('company_name', 'ASC')
                            ->paginate(20);

        return view($this->view.'list')
                ->with('data', $data);
	}

	private function stripTag($description){
		return trim(trim($description, '<p>'), '</p>');
	}
	private function addTag($description){
		return '<p>'.$description.'</p>';
	}

	public function getCreateView($company_id) {
		$relative_valuation_table = (new RelativeValuationModel)->getTable();
        $company_table = (new CompanyModel)->getTable();
        $data = CompanyModel::leftJoin(
                                $relative_valuation_table, $relative_valuation_table.'.company_id', '=', $company_table.'.id'
                            )
                            ->select($company_table.'.id as company_id', 'company_name', $relative_valuation_table.'.id as relative_valuation_id', $relative_valuation_table.'.*')
                            ->where($company_table.'.id', $company_id)
                            ->first();
        unset($data->id);
        return view($this->view.'create')
        		->with('data', $data);
    }

    public function postCreateView($company_id) {
        $data = request()->all();

        $validator = \Validator::make($data['data'], (new RelativeValuationModel)->getRule());

        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        } else {
        	$data['data']['company_id'] = $company_id;
            
            $record = RelativeValuationModel::firstOrNew([
                'company_id' => $company_id
            ]);

            foreach($data['data'] as $index => $value){
                if($index != 'company_id'){
                    $record->$index = $value;
                }
            }
            $record->save();
        }

        \Session::flash('success-msg', 'Relative Valuation successfully created');

        return redirect()->route('admin-relative-valuation-list-get');
    }

    public function postDeleteView($id) {
        $relative_valuation = RelativeValuationModel::where('company_id', $id);
        $relative_valuation->delete();

        \Session::flash('success-msg', 'Relative Valuation successfully deleted');
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
            $data = RelativeValuationModel::where('company_id', $id)->firstOrFail();

            try{
                \Storage::delete($this->storage_folder.DS.$data->asset);
            } catch(\Exception $e) {
                //do nothing
            }

            $data->delete();       
        } catch(\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage(), 'friendly-message' => 'Relative valuations could not be deleted'];
        }

        return ['status' => true, 'message' => 'Relative valuations successfully deleted'];
        
    }
}
