<?php

namespace App\Http\Controllers\Core\AgmSgm;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\FiscalYear\FiscalYearModel;
use App\Http\Controllers\Core\AgmSgm\AgmSgmModel;
use App\Http\Controllers\Core\Sector\SectorModel;
use Illuminate\Http\Request;

class AgmSgmController extends Controller
{
	public $view = 'Core.AgmSgm.backend.';

	public function getListView(){
		$agms = AgmSgmModel::orderBy('id')->with('fiscalYear','sector')->get();
		$years = FiscalYearModel::orderBy('ordering')->get();
		$sectors = SectorModel::orderBy('id')->get();
		return view($this->view.'list')
                ->with('agms', $agms)
                ->with('years', $years)
                ->with('sectors', $sectors);
	}

	public function postCreateView(){
		$input = request()->all();

		$validator = \Validator::make($input['data'], (new AgmSgmModel)->getRule());
     
        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        }

		$record = AgmSgmModel::firstOrNew([
                                'symbol' => $input['data']['symbol'],
                                'fiscal_year_id' =>  $input['data']['fiscal_year_id']
                            ]);
		
		foreach ($input['data'] as $title=>$value){
			$record[$title] = $value;
		}
		$record->save();

		session()->flash('success-msg', 'AGM successfully created');

        return redirect()->back();
	}

	public function getEditView($agm_id){
		$agm = AgmSgmModel::where('id', $agm_id)->firstOrFail();

		$years = FiscalYearModel::orderBy('ordering')->get();
		$sectors = SectorModel::orderBy('id')->get();
		return view($this->view.'edit')
                ->with('agm', $agm)
                ->with('years', $years)
                ->with('sectors', $sectors);
	}

	public function postEditView($agm_id){
		$data = request()->all();
        $validator = \Validator::make($data['data'], (new AgmSgmModel)->getRule());

        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);

        } else {
            AgmSgmModel::where('id', $agm_id)->update($data['data']);
            \Session::flash('success-msg', 'AGM successfully updated');

            return redirect()->route('admin-agm-sgm-list-get');
        }
	}

	public function postDeleteView($agm_id){
		$agm = AgmSgmModel::where('id', $agm_id)->firstOrFail();
		$agm->delete();

		session()->flash('success-msg', 'AGM successfully deleted');
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
            $data = AgmSgmModel::where('id', $id)->firstOrFail();

            try{
                \Storage::delete($this->storage_folder.DS.$data->asset);
            } catch(\Exception $e) {
                //do nothing
            }

            $data->delete();       
        } catch(\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage(), 'friendly-message' => 'AGM could not be deleted'];
        }

        return ['status' => true, 'message' => 'AGM successfully deleted'];
        
    }
}