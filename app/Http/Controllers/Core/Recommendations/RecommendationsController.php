<?php

namespace App\Http\Controllers\Core\Recommendations;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RecommendationsController extends Controller
{

	public $view = 'Core.Recommendations.backend.';
    public $frontend_view = 'Core.Recommendations.frontend.';
	private $storage_folder = 'recommendations';

    /////////// Frontend ////////////////////
    public function getViewRecommendations($id) {
        $data = RecommendationsModel::where('id', $id)->firstOrFail();
        $data->counter += 1;
        $data->save();
        
        return view($this->frontend_view.'view')
                ->with('data', $data);
    }

    public function getRecommendations() {
        $view['data'] = (new RecommendationsModel)->getFrontendRecommendations(9);
        return view($this->frontend_view.'views')
                ->with($view);
    }

    /////////// Frontend ////////////////////

    public function getListView() {
        $data = RecommendationsModel::orderBy('id', 'DESC')->paginate(20);
        return view($this->view.'list')
                ->with('data', $data);
    }

    public function getCreateView() {
        return view($this->view.'create');
    }

    public function postCreateView() {
        $data = request()->all();

        $validator = \Validator::make($data['data'], (new RecommendationsModel)->getRule());

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

            $recommendations_id = RecommendationsModel::create($data['data'])->id;
        }

        \Session::flash('success-msg', 'Recommendations successfully created');

        return redirect()->back();
    }

    public function getEditView($id) {
        $data = RecommendationsModel::where('id', $id)->firstOrFail();
        
        return view($this->view.'edit')
                ->with('data', $data);

    }

    public function postEditView($id) {
        $original_data = RecommendationsModel::where('id', $id)->firstOrFail();
        $recommendations_id = $id;
        $data = request()->all();
        $validator = \Validator::make($data['data'], (new RecommendationsModel)->getRule($id));

        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        } else {
            if($data['data']['asset_type'] == 'image') {
                if(request()->hasFile('data.asset')) {
                    request()->file('data.asset')->store($this->storage_folder);
                    $data['data']['asset'] = request()->file('data.asset')->hashName();    
                } else {
                    $data['data']['asset'] = $original_data->asset;
                }
            }

            RecommendationsModel::where('id', $id)->update($data['data']);

            \Session::flash('success-msg', 'Recommendations successfully updated');

            return redirect()->back();
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
            $data = RecommendationsModel::where('id', $id)->firstOrFail();

            try{
                \Storage::delete($this->storage_folder.DS.$data->asset);
            } catch(\Exception $e) {
                //do nothing
            }

            $data->delete();       
        } catch(\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage(), 'friendly-message' => 'Recommendations could not be deleted'];
        }

        return ['status' => true, 'message' => 'Recommendations successfully deleted'];
        
    }

    public function postSetAsTopRecommendationsView($id) {
        $msg = '';
        $data = RecommendationsModel::where('id', $id)->firstOrFail();
        if($data->is_top_recommendations == 'yes') {
            $msg = 'Unset As Top Recommendations';
            $data->is_top_recommendations = 'no';
        } else {
            $msg = 'Set As Top Recommendations';
            $data->is_top_recommendations = 'yes';
        }

        $data->save();
        session()->flash('success-msg', $msg);

        return redirect()->back();
    }
}