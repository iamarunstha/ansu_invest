<?php

namespace App\Http\Controllers\Core\MarketVideos;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MarketVideosController extends Controller
{

	public $view = 'Core.MarketVideos.backend.';
    public $frontend_view = 'Core.MarketVideos.frontend.';
	private $storage_folder = 'market-videos';

    /////////// Frontend ////////////////////
    public function getViewMarketVideos($id) {
        $data = MarketVideosModel::where('id', $id)->where('is_active', 'yes')->firstOrFail();
        $data->counter += 1;
        $data->save();
        //$view = (new MarketVideosModel)->marketVideosData($id);

        return view($this->frontend_view.'market-video')
                ->with('data', $data);
    }

    public function getMarketVideos() {
        $view = [];
        $view['data'] = (new MarketVideosModel)->getFrontendMarketVideos(9);
        return view($this->frontend_view.'market-videos')
                ->with($view);
    }

    /////////// Frontend ////////////////////

    public function getListView() {
        $data = MarketVideosModel::orderBy('id', 'DESC')->paginate(20);
        return view($this->view.'list')
                ->with('data', $data);
    }

    public function getCreateView() {
        return view($this->view.'create');
    }

    public function postCreateView() {
        $data = request()->all();

        $validator = \Validator::make($data['data'], (new MarketVideosModel)->getRule());

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

            $news_id = MarketVideosModel::create($data['data'])->id;
        }

        \Session::flash('success-msg', 'MarketVideos successfully created');

        return redirect()->back();
    }

    public function getEditView($id) {
        $data = MarketVideosModel::where('id', $id)->firstOrFail();
        
        return view($this->view.'edit')
                ->with('data', $data);

    }

    public function postEditView($id) {
        MarketVideosModel::where('id', $id)->firstOrFail();
        $news_id = $id;
        $data = request()->all();
        $validator = \Validator::make($data['data'], (new MarketVideosModel)->getRule());

        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        } else {

            MarketVideosModel::where('id', $id)->update($data['data']);

            \Session::flash('success-msg', 'MarketVideos successfully updated');

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
            $data = MarketVideosModel::where('id', $id)->firstOrFail();

            try{
                \Storage::delete($this->storage_folder.DS.$data->asset);
            } catch(\Exception $e) {
                //do nothing
            }

            $data->delete();       
        } catch(\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage(), 'friendly-message' => 'MarketVideos could not be deleted'];
        }

        return ['status' => true, 'message' => 'MarketVideos successfully deleted'];
        
    }

    public function postSetAsTopMarketVideosView($id) {
        $msg = '';
        $data = MarketVideosModel::where('id', $id)->firstOrFail();
        if($data->feature == 'yes') {
            $msg = 'Unfeatured';
            $data->feature = 'no';
        } else {
            $msg = 'Featured';
            $data->feature = 'yes';
        }

        $data->save();
        session()->flash('success-msg', $msg);

        return redirect()->back();
    }
}