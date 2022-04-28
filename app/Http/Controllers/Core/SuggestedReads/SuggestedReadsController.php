<?php

namespace App\Http\Controllers\Core\SuggestedReads;

use App\Http\Controllers\Controller;

class SuggestedReadsController extends Controller {

    public $view = 'Core.SuggestedReads.';

    public function getSuggestedReads(){
        $data = SuggestedReadModel::orderBy('ordering')->paginate();
        return view($this->view.'list')->with('data', $data);
    }

    public function postSuggestedReads(){
        $input = request()->get('data');

        foreach($input as $id=>$ordering){
            SuggestedReadModel::where('id', $id)->update($ordering);
        }
        session()->flash('success-msg', 'Orders updated successfully!');
        return redirect()->back();
    }

    public function postDeleteSuggestedReads($id){
        $post = SuggestedReadModel::findOrFail($id);
        $post->delete();

        session()->flash('success-msg', 'Article removed from suggested!');
        return redirect()->back();
    }
}