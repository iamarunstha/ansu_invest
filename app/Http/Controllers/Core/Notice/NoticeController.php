<?php

namespace App\Http\Controllers\Core\Notice;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Core\Company\CompanyModel;

class NoticeController extends Controller
{
	public $view = 'Core.Notice.backend.';

	public function getListView(){
        $companies = CompanyModel::orderBy('company_name', 'asc')->get();
		$notices = NoticeModel::orderBy('notice_date', 'DESC')->get();
        return view($this->view.'list')
                ->with('companies', $companies)
                ->with('notices', $notices);
	}

	public function postCreateView(){
		$input = request()->all();

		$validator = \Validator::make($input['data'], (new NoticeModel)->getRule());
     
        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        }

        \DB::beginTransaction();
		$record = NoticeModel::firstOrNew([
                                'name' => $input['data']['name'],
                            ]);
		
		$record['description'] = $input['data']['description'];
		$record['notice_date'] = $input['data']['notice_date'];
        $record->save();

        if(isset($input['data']['company_ids'])) {
            foreach($input['data']['company_ids'] as $company_id)
            NoticeCompanyModel::create([
                'company_id'    =>  $company_id,
                'notice_id' =>  $record->id
            ]);
        }
        
        \DB::commit();

		session()->flash('success-msg', 'Notice successfully created');

        return redirect()->back();
	}

	public function getEditView($notice_id){
        $companies = CompanyModel::orderBy('company_name', 'ASC')->get();
        $selected_companies = NoticeCompanyModel::where('notice_id', $notice_id)->pluck('company_id')->toArray();
        $notice = NoticeModel::where('id', $notice_id)->firstOrFail();

		return view($this->view.'edit')
                ->with('notice', $notice)
                ->with('companies', $companies)
                ->with('selected_companies', $selected_companies);
	}

	public function postEditView($notice_id){
		$input = $data = request()->all();
        $validator = \Validator::make($data['data'], (new NoticeModel)->getRule());

        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);

        } else {
            if(isset($data['data']['company_ids'])) {
                unset($data['data']['company_ids']);
            }
            \DB::beginTransaction();
                NoticeModel::where('id', $notice_id)->update($data['data']);
                NoticeCompanyModel::where('notice_id', $notice_id)->delete();
                if(isset($input['data']['company_ids'])) {
                    foreach($input['data']['company_ids'] as $company_id)
                    NoticeCompanyModel::create([
                        'company_id'    =>  $company_id,
                        'notice_id' =>  $notice_id
                    ]);
                }
            \DB::commit();
            \Session::flash('success-msg', 'Notice successfully updated');

            return redirect()->route('admin-notice-list-get');
        }

	}

	public function postDeleteView($notice_id){
		$notice = NoticeModel::where('id', $notice_id)->firstOrFail();
		$notice->delete();

		session()->flash('success-msg', 'Notice successfully deleted');
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
            $data = NoticeModel::where('id', $id)->firstOrFail();

            try{
                \Storage::delete($this->storage_folder.DS.$data->asset);
            } catch(\Exception $e) {
                //do nothing
            }

            $data->delete();       
        } catch(\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage(), 'friendly-message' => 'Notice could not be deleted'];
        }

        return ['status' => true, 'message' => 'Notice successfully deleted'];
        
    }
}