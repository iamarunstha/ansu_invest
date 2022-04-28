<?php

namespace App\Http\Controllers\Core\FiscalYear;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Company\CompanyModel;
use Illuminate\Http\Request;

class FiscalYearController extends Controller
{

	public $view = 'Core.FiscalYear.backend.';
    public $frontend_view = 'Core.FiscalYear.frontend.';
	private $storage_folder = 'fiscalYear';  

    public function getListView() {
        $data = FiscalYearModel::orderBy('ordering', 'ASC')->paginate(20);
        return view($this->view.'list')
                ->with('data', $data);
    }

    public function getCreateView() {
        return view($this->view.'create');
    }

    public function postCreateView() {
        $data = request()->all();

        $validator = \Validator::make($data['data'], (new FiscalYearModel)->getRule());

        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        } else {
            
            $news_id = FiscalYearModel::create($data['data'])->id;
        }

        \Session::flash('success-msg', 'Fiscal year successfully created');

        return redirect()->back();
    }

    public function getEditView($id) {
        $data = FiscalYearModel::where('id', $id)->firstOrFail();
        
        return view($this->view.'edit')
                ->with('data', $data);

    }

    public function postEditView($id) {
        $original_data = FiscalYearModel::where('id', $id)->firstOrFail();
        $news_id = $id;
        $data = request()->all();
        $validator = \Validator::make($data['data'], (new FiscalYearModel)->getRule($id));

        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        } else {
            

            FiscalYearModel::where('id', $id)->update($data['data']);

            \Session::flash('success-msg', 'Fiscal year successfully updated');

            return redirect()->route('admin-fiscal-year-list-get');
        }
    }

    public function getOrderingEditView() {

        $data = FiscalYearModel::orderby('ordering', 'ASC')->get();
        
        return view($this->view.'edit-ordering')
                ->with('data', $data);
    }

    public function postOrderingEditView() {

        $data = request()->all();
        
        \DB::beginTransaction();
        foreach($data['data'] as $id => $ordering){
            $fiscal_year = FiscalYearModel::where('id', $id)->update(['ordering' => $ordering]);
        }
        \DB::commit();

        session()->flash('success-msg', 'Ordering done');

        return redirect()->back();

    }

    public function postDeleteView($id) {
        $response = $this->apiDelete($id);

        if($response['status']) {
            \Session::flash('success-msg', $response['message']);
        } else {
//            \Session::flash('error-msg', $response['message']);
            \Session::flash('friendly-error-msg', $response['friendly-message']);
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
            $data = FiscalYearModel::where('id', $id)->firstOrFail();
            $data->delete();       
        } catch(\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage(), 'friendly-message' => 'FiscalYear could not be deleted'];
        }

        return ['status' => true, 'message' => 'Fiscal year successfully deleted'];
        
    }

    public function getCompanyAssignView($id){
        $year = FiscalYearModel::where('id', $id)->first();
        $companies = CompanyModel::orderBy('company_name')->get();
        $assigned_companies = CompanyFiscalYearModel::where('fiscal_year_id', $id)->get()->pluck('company_id')->toArray();

        return view($this->view.'assign-company')
                ->with('year', $year)
                ->with('companies', $companies)
                ->with('assigned_companies', $assigned_companies);
    }

    public function postCompanyAssignView($id){
        $input = request()->all();

        \DB::beginTransaction();
        CompanyFiscalYearModel::where('fiscal_year_id', $id)->delete();
        foreach ($input['data']['company_ids'] as $c_id){
            $company_fiscal_year = CompanyFiscalYearModel::firstOrNew([
                'company_id' => $c_id,
                'fiscal_year_id' => $id
            ]);

            $company_fiscal_year->save();
        }
        \DB::commit();
        
        \Session::flash('success-msg', 'Companies successfully assigned to fiscal year');
        return redirect()->back();
    }
}