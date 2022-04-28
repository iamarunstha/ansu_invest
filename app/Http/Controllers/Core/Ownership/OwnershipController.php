<?php

namespace App\Http\Controllers\Core\Ownership;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Company\CompanyModel;
use App\Http\Controllers\Core\FiscalYear\FiscalYearModel;
use App\Http\Controllers\Core\Sector\SectorModel;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OwnershipController extends Controller
{
	public $view = 'Core.Ownership.backend.';

	public function getCompanyListView($tab_id){
        $input = request()->all();

		$tab = OwnershipTabModel::where('id',$tab_id)->first();
		$companies = CompanyModel::orderBy('company_name', 'ASC');

        if (isset($input['search'])){
            $companies = $companies->where('company_name','like', '%'.$input['search'].'%')
                         ->orWhere('short_code', 'like', '%'.$input['search'].'%');
        }
        $companies = $companies->get();

		return view($this->view.'list-company')
				->with('companies', $companies)
				->with('tab', $tab);
	}
	
	public function getListView($company_id, $tab_id){
		$company = CompanyModel::where('id', $company_id)->first();
		$headings = OwnershipColumnModel::orderBy('ordering')->get();

		$_ownerships = OwnershipModel::where('company_id', $company_id)
									->where('tab_id', $tab_id)
									->orderBy('name_id','ASC')
									->get();

		$ownerships = [];
		$index=1;
		foreach ($_ownerships as $o){
			$column_name = OwnershipColumnModel::where('id', $o->column_id)->first()->column_name;
			$ownership = OwnershipNameModel::where('id', $o->name_id)->first();
			$name =	$ownership->name;
			$name_id = $ownership->id;
			
			if(!isset($ownerships[$index])){
				$current_name_id=$name_id;
				$ownerships[$index] = [
					'name'=>$name,
					'name_id' =>$name_id,
					$column_name=>$o->value
				];
			}else{
				if ($name_id==$current_name_id){
					$ownerships[$index][$column_name] = $o->value;
				}else{
					$current_name_id = $name_id;
					$index+=1;
					$ownerships[$index] = [
						'name'=>$name,
						'name_id'=>$name_id,
						$column_name=>$o->value
					];
				}
			}
		}

		return view($this->view.'list')
				->with('company',$company)
				->with('tab_id', $tab_id)
				->with('headings', $headings)
				->with('ownerships',$ownerships);
	}

	public function getTabsListView(){
		$tabs = OwnershipTabModel::orderBy('ordering')->get();

		return view($this->view.'list-tabs')
				->with('tabs', $tabs);
	}

	public function postTabsCreateView(){
		$input = request()->all();
		
		if(!isset($input['data']['tab_name'])){
			\Session::flash('error_msg', 'Tab name is required');
			return redirect()->back();
		}

		$tab = OwnershipTabModel::firstOrCreate([
			'tab_name' => $input['data']['tab_name']
		]);
		$tab->ordering = $input['data']['ordering'];
		$tab->save();

		\Session::flash('success-msg', 'Tabs-list successfully updated');
		return redirect()->back();
	}

	public function postTabsUpdateView($tab_id){
		$input = request()->all();
		
		if(!isset($input['data']['tab_name'])){
			\Session::flash('error_msg', 'Tab name is required');
			return redirect()->back();
		}
		
		if (OwnershipTabModel::where('tab_name', $input['data']['tab_name'])->where('id','<>',$tab_id)->get()){
			\Session::flash('error-msg', 'Tab already exists');
			return redirect()->back();
		}

		$tab = OwnershipTabModel::where('id', $tab_id)->get();
		$tab->tab_name = $input['data']['tab_name'];
		$tab->ordering = $input['data']['ordering'];
		$tab->save();

		\Session::flash('success-msg', 'Tab successfully updated');
		return redirect()->back();
	}


	public function postTabsDeleteView($tab_id){
		$tab = OwnershipTabModel::where('id',$tab_id)->first();
		$tab->delete();

		\Session::flash('success-msg', 'Tab successfully deleted');
		return redirect()->back();
	}

	public function getCreateView($company_id, $tab_id){
        $columns = OwnershipColumnModel::orderBy('ordering')->get();
		return view($this->view.'create')
                ->with('tab_id', $tab_id)
                ->with('company_id', $company_id)
                ->with('columns', $columns);
	}

	public function postCreateView($company_id, $tab_id){
		$input = request()->all();
		$validator = \Validator::make($input['data'], (new OwnershipNameModel)->getRule());
		if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        }

        \DB::beginTransaction();
        $columns = OwnershipColumnModel::get();
        foreach ($input['data'] as $heading=>$value){
            if (isset($value)){
                if($heading == 'name'){
                    $data = OwnershipNameModel::Create(['name'=>$value]);
                    $name_id = $data->id;
                }else{
                    $column_id = $columns->where('column_name', $heading)->first()->id;
                    $data = OwnershipModel::firstOrNew([
                        'company_id' => $company_id,
                        'tab_id' => $tab_id,
                        'name_id' => $name_id,
                        'column_id' => $column_id
                    ]);
                    $data->value = $value;
                    $data->save();
                } 
            }
        }
        \DB::commit();
		\Session::flash('success-msg', 'ownership successfully created');
		return redirect()->route('admin-ownership-list-get', [$company_id,$tab_id]);
	}

	public function getEditView($company_id, $tab_id, $name_id){
		$_ownerships = OwnershipModel::where('company_id', $company_id)
								->where('tab_id', $tab_id)
								->where('name_id', $name_id)
								->get();
		$ownership = [];
		$ownership['name'] = OwnershipNameModel::where('id',$name_id)->first()->name;
		foreach($_ownerships as $o){
			$column_name = OwnershipColumnModel::where('id', $o->column_id)->first()->column_name;
			$ownership[$column_name] = $o->value;
		}
		$columns = OwnershipColumnModel::get();
		foreach($columns as $c){
			if (!isset($ownership[$column_name]))
				$ownership[$column_name] = Null;
		}

		return view($this->view.'edit')
				->with('company_id', $company_id)
				->with('tab_id', $tab_id)
				->with('name_id', $name_id)
                ->with('columns', $columns)
                ->with('ownership', $ownership); 
	}

	public function postEditView($company_id, $tab_id, $name_id){
		$input = request()->all();

		$validator = \Validator::make($input['data'], (new OwnershipNameModel)->getRule());
		if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        }

        \DB::beginTransaction();
        $columns = OwnershipColumnModel::get();
        foreach ($input['data'] as $heading=>$value){
            if (isset($value)){
                if($heading == 'name'){
                    OwnershipNameModel::where('id', $name_id)->update(['name'=>$value]);
                }else{
                    $column_id = $columns->where('column_name', $heading)->first()->id;
                    $data = OwnershipModel::firstOrNew([
                        'company_id' => $company_id,
                        'tab_id' => $tab_id,
                        'name_id' => $name_id,
                        'column_id' => $column_id
                    ]);
                    $data->value = $value;
                    $data->save();
                }    
            }
        }
        \DB::commit();

		session()->flash('success-msg', 'Ownership successfully updated');
        return redirect()->route('admin-ownership-list-get', [$company_id, $tab_id]);
	}

	public function postDeleteView($company_id, $tab_id, $name_id){
		$ownership = OwnershipModel::where('company_id', $company_id)
								->where('tab_id', $tab_id)
								->where('name_id', $name_id)
								->get();
		\DB::beginTransaction();
			foreach ($ownership as $o){
				$o->delete();
			}
		\DB::commit();

		session()->flash('success-msg', 'Ownership successfully deleted');
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
            $data = OwnershipModel::where('id', $id)->firstOrFail();
            $data->delete();
        } 
        catch(\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage(), 'friendly-message' => 'Ownership could not be deleted'];
        }
        return ['status' => true, 'message' => 'Ownerships successfully deleted'];
    }

    public function getColumnListView(){
        $columns = OwnershipColumnModel::orderBy('ordering')->get();
        return view($this->view.'list-columns')
                    ->with('columns', $columns);
    }

    public function postColumnCreateView(){
        $input = request()->all(); 

        if (OwnershipColumnModel::where('display_name', $input['data']['display_name'])->first()){
            \Session::flash('error-msg', 'Column with the same name already exists');
            return redirect()->back();
        }
        $input['data']['column_name'] = Str::snake($input['data']['display_name']);
        $column = OwnershipColumnModel::Create($input['data']);
        $column->column_name = $column->column_name.'_'.$column->id;
        $column->save();

        \Session::flash('success-msg', 'Columns-list successfully updated');
        return redirect()->back();
    }

    public function postColumnsEditView($column_id){
        $input = request()->all();
        
        if (OwnershipColumnModel::where('display_name', $input['data']['display_name'])->where('id','<>',$column_id)->first()){
            \Session::flash('error-msg', 'Column with the same name already exists');
            return redirect()->back();
        }
        $input['data']['column_name'] = Str::snake($input['data']['display_name']).'_'.$column_id;
        $tab = OwnershipColumnModel::where('id', $column_id)->firstOrFail();
        $tab->update($input['data']);
        $tab->save();

        \Session::flash('success-msg', 'Column successfully updated');
        return redirect()->back();        
    }

    public function postColumnDeleteView($column_id){
        $column = OwnershipColumnModel::where('id', $column_id)->firstOrFail();
        $column->delete();

        session()->flash('success-msg', 'Ownership Column successfully deleted');
        return redirect()->back();        
    }
}