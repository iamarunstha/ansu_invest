<?php

namespace App\Http\Controllers\Core\Experts;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Core\Company\CompanyModel;
use App\Http\Controllers\Core\SuggestedReads\SuggestedReadModel;

class ExpertsController extends Controller
{

	public $view = 'Core.Experts.backend.';
    public $frontend_view = 'Core.Experts.frontend.';
	private $storage_folder = 'experts';

    /////////// Frontend ////////////////////
    public function getViewExperts($id) {
        $data = ExpertsModel::where('id', $id)->where('is_active', 'yes')->firstOrFail();
        $data->counter += 1;
        $data->save();

        return view($this->frontend_view.'view')
                ->with('data', $data);
    }

    public function getExperts() {
        $view = [];
        $view['data'] = (new ExpertsModel)->getFrontendExperts(9);
        
        return view($this->frontend_view.'views')
                ->with($view);
    }

    /////////// Frontend ////////////////////

    public function getListView() {
        $data = ExpertsModel::orderBy('id', 'DESC')->paginate(20);
        return view($this->view.'list')
                ->with('data', $data);
    }

    public function getCreateView() {
        $companies = CompanyModel::orderBy('company_name')->get();
        return view($this->view.'create')->with('companies', $companies);
    }

    public function postCreateView() {
        $data = request()->all();

        $validator = \Validator::make($data['data'], (new ExpertsModel)->getRule());

        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        } else {
            if($data['data']['asset_type'] == 'image') {
                request()->file('data.asset')->store($this->storage_folder);
                $data['data']['asset'] = request()->file('data.asset')->hashName();
            }

            \DB::beginTransaction();
                $experts_id = ExpertsModel::create($data['data'])->id;

                if(isset($data['data']['is_suggested'])){
                    SuggestedReadModel::create([
                        'post_type' => 'App\Http\Controllers\Core\Experts\ExpertsModel',
                        'category' => 'Research & Opinions',
                        'post_id' => $experts_id
                    ]);
                }

                if(isset($data['company']['company_id'])) {
                    foreach($data['company']['company_id'] as $company_id) {
                        CompanyExpertModel::create([
                            'company_id'   =>  $company_id,
                            'expert_id'   =>  $experts_id
                        ]);
                    }
                }
            \DB::commit();
            \Session::flash('success-msg', 'Experts successfully created');
            return redirect()->route('admin-experts-list-get');
        }
    }

    public function getEditView($id) {
        $data = ExpertsModel::where('id', $id)->firstOrFail();
        $companies = CompanyModel::orderBy('company_name')->get();
        $related_companies = (new CompanyExpertModel)->getRelatedCompany($id);
        return view($this->view.'edit')
                ->with('data', $data)
                ->with('companies', $companies)
                ->with('related_companies', $related_companies);

    }

    public function postEditView($id) {
        $original_data = ExpertsModel::where('id', $id)->firstOrFail();
        $data = request()->all();
        $validator = \Validator::make($data['data'], (new ExpertsModel)->getRule($id));

        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        } else {
            if($data['data']['asset_type'] == 'image' && request()->hasFile('data.asset')) {
                request()->file('data.asset')->store($this->storage_folder);
                $data['data']['asset'] = request()->file('data.asset')->hashName();
            } else {
                $data['data']['asset'] = $original_data->asset;
            }
            \DB::beginTransaction();

            if(isset($data['company']['company_id'])) {
                CompanyExpertModel::where('expert_id', $id)->delete();
                foreach($data['company']['company_id'] as $company_id) {
                    CompanyExpertModel::create([
                        'company_id'    =>  $company_id,
                        'expert_id'   =>  $id
                    ]);
                }
            }

            if(isset($data['data']['is_suggested'])){
                SuggestedReadModel::firstOrCreate(['category' => 'Research & Opinions', 
                    'post_type' => 'App\Http\Controllers\Core\Experts\ExpertsModel',
                    'post_id' => $id 
                ]);
            }else{
                SuggestedReadModel::where('category', 'Research & Opinion')->where('post_id', $id)->delete();
                $data['data']['is_suggested'] = Null;
            }
            ExpertsModel::where('id', $id)->update($data['data']);
            \DB::commit();

            \Session::flash('success-msg', 'Experts successfully updated');
            return redirect()->route('admin-experts-list-get');
        }
    }

    public function postDeleteView($id) {
        
        $response = $this->apiDelete($id);

        if($response['status']) {
            \Session::flash('success-msg', $response['message']);
        } else {
            \Session::flash('error-msg', $response['message']);
            \Session::flash('friendly-error-msg', $response['friendly-error-msg']);
        }

        return redirect()->back();
    }

    public function postDeleteMultipleView() {
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
            $data = ExpertsModel::where('id', $id)->firstOrFail();
            $suggested_read = SuggestedReadModel::where('category', 'Research & Opinions')->where('post_id', $id);

            try{
                \Storage::delete($this->storage_folder.DS.$data->asset);
            } catch(\Exception $e) {
                //do nothing
            }

            $data->delete();
            if (null !== $suggested_read->first()){
                $suggested_read->first()->delete();
            }
        } catch(\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage(), 'friendly-message' => 'Experts could not be deleted'];
        }

        return ['status' => true, 'message' => 'Experts successfully deleted'];
        
    }

    public function postSetAsTopExpertsView($id) {
        $msg = '';
        $data = ExpertsModel::where('id', $id)->firstOrFail();
        if($data->feature == 'yes') {
            $msg = 'Unset As Top Experts';
            $data->feature = 'no';
        } else {
            $msg = 'Set As Top Experts';
            $data->feature = 'yes';
        }

        $data->save();
        session()->flash('success-msg', $msg);

        return redirect()->back();
    }
}