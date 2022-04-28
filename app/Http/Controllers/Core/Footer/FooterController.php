<?php

namespace App\Http\Controllers\Core\Footer;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Company\CompanyModel;
use Illuminate\Http\Request;

class FooterController extends Controller {

    public $view = 'Core.Footer.';

    public function getFooter() {
        $links = FooterLinkModel::orderBy('link_order', 'ASC')->get();
        return view($this->view.'list')
            ->with('data', $links);
    }

    public function postUpdateFooterLinks(){
        $data = request()->get('data');

		\DB::beginTransaction();
		foreach ($data as $id => $row) {
			FooterLinkModel::where('id', $id)
								->update($row);
		}
		\DB::commit();

		\Session::flash('success-msg', 'Links successfully updated');
		return redirect()->back();
    }

    public function postDeleteFooterLinks($id){
        $footer_link = FooterLinkModel::where('id', $id)->firstOrFail();
        $footer_link->delete();

        \Session::flash('success-msg', 'Link successfully deleted');
		return redirect()->back();
    }

    public function postDeleteMultipleFooterLinks(){
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
            $data = FooterLinkModel::where('id', $id)->firstOrFail();
            $data->delete();       
        } catch(\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage(), 'friendly-message' => 'Tabs could not be deleted'];
        }
        return ['status' => true, 'message' => 'Tabs successfully deleted'];
    }

    public function postAddFooterLinks(){
        $data = request()->get('data');
        try {
            FooterLinkModel::create($data);
            \Session::flash('success-msg', 'Link successfully added');
        }catch(\Exception $e){
            \Session::flash('friendly-error-msg', 'Unsuccessful due to missing values!');
        }
        return redirect()->back();
    }

    public function getContacts(){
        $data = ContactModel::get();
        $data = $data->first() ? $data->first()->toArray() : ["address" => null, "phone"=> null, "email"=>null, "disclaimer" => null];

        return view($this->view.'footer-contacts')->with('data', $data);
    }

    public function postContacts(){
        $data = request()->get('data');
        $data['address'] = nl2br($data['address']);
        
        \DB::beginTransaction();
            ContactModel::truncate();
            ContactModel::create($data);
        \DB::commit();

        \Session::flash('friendly-error-msg', 'Successfully change contact address and disclaimer!');
        return redirect()->back();
    }
}
