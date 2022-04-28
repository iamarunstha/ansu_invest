<?php

namespace App\Http\Controllers\Core\Stock;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Company\CompanyModel;
use App\Http\Controllers\Core\SuggestedReads\SuggestedReadModel;
use Illuminate\Http\Request;

class StockController extends Controller
{
	public $view = 'Core.Stock.backend.';
	private $storage_folder = 'stocks';

	public function getListView(){
        $data = StockModel::orderBy('id', 'DESC')->with('company')->orderBy('posted_at','DESC')->paginate(20);
        return view($this->view.'list')
                ->with('data', $data);
	}

    public function getCreateView() {
   		$companies = CompanyModel::orderBy('company_name', 'ASC')->get();
        return view($this->view.'create')
                ->with('companies', $companies);
    }

    public function postCreateView() {
        $data = request()->all();

        $validator = \Validator::make($data['data'], (new StockModel)->getRule());
        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        } else {
            \DB::beginTransaction();
                $data['created_at'] = \Carbon\Carbon::now();
                $stock_id = StockModel::create($data['data'])->id;

                if(isset($data['data']['is_suggested'])){
                    SuggestedReadModel::create([
                        'post_type' => 'App\Http\Controllers\Core\Stock\StockModel',
                        'category' => 'Stock Analysis',
                        'post_id' => $stock_id
                    ]);
                }
            \DB::commit();
        }

        \Session::flash('success-msg', 'Stock successfully created');
        return redirect()->route('admin-stock-list-get');
    }

    public function getEditView($id) {
    	$companies = CompanyModel::orderBy('company_name', 'ASC')->get();
        $stock = StockModel::where('id', $id)->firstOrFail();
        $is_suggested = SuggestedReadModel::where('category', 'Stock Analysis')->where('post_id', $id);
        $stock['is_suggested'] = $is_suggested->first() ? 1 : Null;
        return view($this->view.'edit')
        		->with('companies', $companies)
                ->with('stock', $stock);

    }

    public function postEditView($id) {
        $original_data = StockModel::where('id', $id)->firstOrFail();
        $stock_id = $id;
        $data = request()->all();
        $validator = \Validator::make($data['data'], (new StockModel)->getRule());

        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        } else {
            \DB::beginTransaction();
            if(isset($data['data']['is_suggested'])){
                SuggestedReadModel::firstOrCreate([
                    'post_type' => 'App\Http\Controllers\Core\Stock\StockModel',
                    'category' => 'Stock Analysis',
                    'post_id' => $id
                ]);
                unset($data['data']['is_suggested']);
            }else{
                SuggestedReadModel::where('category', 'Stock Analysis')->where('post_id', $id)->delete();
            }
            StockModel::where('id', $id)->update($data['data']);
            \DB::commit();

            \Session::flash('success-msg', 'Stock analysis successfully updated');
            return redirect()->route('admin-stock-list-get');
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
            $data = StockModel::where('id', $id)->firstOrFail();
            $suggested_read = SuggestedReadModel::where('category', 'Stock Analysis')->where('post_id', $id);
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
            return ['status' => false, 'message' => $e->getMessage(), 'friendly-message' => 'News could not be deleted'];
        }

        return ['status' => true, 'message' => 'Stock analysis successfully deleted'];        
    }
}
