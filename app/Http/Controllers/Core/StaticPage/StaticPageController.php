<?php

namespace App\Http\Controllers\Core\StaticPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{
	public $view = 'Core.StaticPage.backend.';

	public function getListView(){
        $data = StaticPageModel::orderBy('page', 'ASC')->get();

        return view($this->view.'list')
                ->with('data', $data);
	}

	public function getUpdate($id, $initial = 'A') {
		$data = StaticPageModel::where('id', $id)->first();
		$definitions = $data->definitions->where('initial', $initial);
		return view($this->view.'update')
				->with('data', $data)->with('definitions', $definitions)->with('initial', $initial);
	}

	public function postUpdate($page_id, $initial) {
		$input = request()->get('data');

		$page = StaticPageModel::where('id', $page_id)->firstOrFail();
		\DB::beginTransaction();
		foreach($input as $defn_id => $d){
			$d['page_id'] = $page_id;
			$d['initial'] = ucfirst($d['term'][0]);
			DefinitionModel::where('id', $defn_id)->update($d);
		}
		\DB::commit();

		\Session::flash('success-msg', 'Successfully upated');
		return redirect()->back();
	}

	public function postCreate($page_id){
		$input = request()->get('data');

		$defn = DefinitionModel::where('term', $input['term'])->where('page_id', $page_id)->first();
		if(isset($defn)){
			\Session::flash('friendly-error-msg', 'Term already exist for this page');
			return redirect()->back();
		}else{
			$defn = DefinitionModel::create([
				'term' => $input['term'],
				'definition' => $input['definition'],
				'page_id' => $page_id,
				'initial' => ucfirst($input['term'][0])
			]);
			\Session::flash('success-msg', 'Term successfully created!');
			return redirect()->back();
		}
	}

	public function postDelete($term_id){
		$defn = DefinitionModel::where('id', $term_id)->firstOrFail();
		$defn->delete();
		\Session::flash('success-msg', 'Term successfully deleted!');
		return redirect()->back();
	}

	public function postDeleteMultiple(){
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
            $data = DefinitionModel::where('id', $id)->firstOrFail();
            $data->delete();

		} catch(\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage(), 'friendly-message' => 'Definition could not be deleted'];
        }

        return ['status' => true, 'message' => 'Definition successfully deleted'];
    }
}
