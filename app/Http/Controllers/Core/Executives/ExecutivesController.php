<?php

namespace App\Http\Controllers\Core\Executives;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Company\CompanyModel;
use Illuminate\Http\Request;

class ExecutivesController extends Controller
{
	public $view = 'Core.Executives.backend.';
	private $storage_folder = 'executives';

	public function getListAllView()
	{
        $input = request()->all();
        $company_table = (new CompanyModel)->getTable();
        $data = CompanyModel::select($company_table.'.id as company_id', 'company_name');

        if (isset($input['search'])){
            $data = $data->where('company_name','like', '%'.$input['search'].'%')
                         ->orWhere('short_code', 'like', '%'.$input['search'].'%');
        }
        $data = $data->orderBy('company_name', 'ASC')->paginate(20);

        return view($this->view.'list-all')
                ->with('data', $data);
	}

    public function getListView($company_id)
    {
        $company = CompanyModel::where('id',$company_id)->first();
        $columns = ExecutivesColumnModel::orderBy('ordering','ASC')->get();
        $_executives = ExecutivesModel::where('company_id', $company_id)
                            ->with('tab')
                            ->get();
        $executives = [];
        foreach ($_executives as $exe){
            $executives[$exe->row_id][] = $exe;
        }

        return view($this->view.'list')
            ->with('company', $company)
            ->with('columns', $columns)
            ->with('executives', $executives);
    }

    public function getCreateView($company_id)
    {
        $tabs = ExecutivesTabModel::get();
        $company = CompanyModel::where('id', $company_id)->firstOrFail();
        $columns = ExecutivesColumnModel::orderBy('ordering', 'ASC')->get();

        return view($this->view.'create')
                ->with('tabs', $tabs)
                ->with('company', $company)
                ->with('columns', $columns);
    }

    public function postCreateView($company_id)
    {
        $data = request()->all();

        $validator = \Validator::make($data['data'], (new ExecutivesModel)->getRule());
        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        } else {
            \DB::beginTransaction();
                $row = ['company_id' => $company_id, 'tab_id' => $data['data']['tab_id'], 'row_id' => ExecutivesModel::max('row_id')+1];
                unset($data['data']['tab_id']);

                foreach ($data['data'] as $heading=>$value){
                    $column = ExecutivesColumnModel::where('column_name', $heading)->first();
                    $row['column_id'] = $column->id;
                
                    if ($column->type == 'varchar'){
                        $row['value_string'] = $value;
                        ExecutivesModel::create($row);
                        unset($row['value_string']);
                    }    
                    else if ($column->type == 'integer'){
                        $row['value_int'] = $value;
                        ExecutivesModel::create($row);
                        unset($row['value_int']);
                    }
                    else if ($column->type == 'float'){
                        $row['value_float'] = $value;
                        ExecutivesModel::create($row);
                        unset($row['value_float']);
                    }
                    else if ($column->type == 'text'){
                        $row['value_text'] = $value;
                        ExecutivesModel::create($row);
                        unset($row['value_text']);
                    }
            }
            \DB::commit();
        }

        \Session::flash('success-msg', 'Executive successfully added');

        return redirect()->route('admin-executives-list-get', $company_id);
    }

    public function getEditView($executive_id)
    {
        $tabs = ExecutivesTabModel::get();
        $columns = ExecutivesColumnModel::orderBy('ordering', 'ASC')->get();
        $_executive = ExecutivesModel::where('row_id', $executive_id)->get();
        $executive = [];
        foreach ($_executive as $index => $value) {
            $column = $value->column;
            if ($column->type == 'varchar')
                $executive[$column->column_name] = $value->value_string;
            elseif ($column->type == 'integer')
                $executive[$column->column_name] = $value->value_int;
            elseif ($column->type == 'float')
                $executive[$column->column_name] = $value->value_float;
            elseif ($column->type == 'text')
                $executive[$column->column_name] = $value->value_text;
        }
        $executive['tab'] = $value->tab->tab_name;
        $company_id = $value->company_id;

        return view($this->view.'edit')
                ->with('tabs', $tabs)
                ->with('columns', $columns)
                ->with('company_id', $company_id)
                ->with('executive', $executive); 
    }

    public function postEditView($executive_id)
    {
        $data = request()->all();
 //       dd($data);
        $validator = \Validator::make($data['data'], (new ExecutivesModel)->getRule());

        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        } else {
            $executive = ExecutivesModel::where('row_id', $executive_id)->get();
            \DB::beginTransaction();
            foreach($executive as $e){
                foreach ($data['data'] as $key => $value){
                    if ($key == 'tab_id'){
                        $e->tab_id = $value;
                        $e->save();
                    }
                    if($key == $e->column->column_name){
                        if($e->column->type == 'varchar'){
                            $e->value_string = $value;
                            $e->save();
                            continue;
                        }elseif($e->column->type == 'text'){
                            $e->value_text = $value;
                            $e->save();
                            continue;
                        }elseif($e->column->type == 'integer'){
                            $e->value_int = $value;
                            $e->save();
                            continue;
                        }elseif($e->column->type == 'float'){
                            $e->value_float = $value;
                            $e->save();
                        }
                    }
                }
            }
            \DB::commit();
        }
        \Session::flash('success-msg', 'Executive successfully updated');

        return redirect()->route('admin-executives-list-get', $e->company_id);
    }

    public function postDeleteView($executive_id)
    {
        $executive = ExecutivesModel::where('row_id', $executive_id);

        $executive->delete();

        \Session::flash('success-msg', 'Executive successfully deleted');

        return redirect()->back();        
    }

    public function postDeleteMultipleView(){
        $rids = request()->get('rid');
        $ids = ExecutivesModel::whereIn('row_id', $rids)->pluck('id')->toArray();
        $success = 0;
        $error = 0;
        if($ids) {
            foreach($ids as $i) {
                $response = $this->apiDelete(ExecutivesModel::class, $i);
                if($response['status']) {
                    $success++;
                } else {
                    $error++;
                }
            }
            if($success) {
                \Session::flash('success-msg', 'Executives successfully deleted');
            }

            if($error) {
                \Session::flash('friendly-error-msg', 'Executives could not be deleted');   
            }
        } else {
            \Session::flash('friendly-error-msg', 'No items selected');   
        }

        return redirect()->back();
    }

    public function apiDelete($model, $id) {
        try {
            $data = $model::where('id', $id)->firstOrFail();
            $data->delete();

        } catch(\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage(), 'friendly-message' => 'Executives could not be deleted'];
        }

        return ['status' => true, 'message' => 'Executives successfully deleted'];
        
    }

    public function getListColumnsView(){
        $columns = ExecutivesColumnModel::orderBy('ordering')->get();

        return view($this->view.'list-columns')
                    ->with('columns', $columns);
    }

    public function getCreateColumnsView(){
        return view($this->view.'create-columns');
    }

    public function postCreateColumnsView(){
        $data = request()->all();
        $validator = \Validator::make($data['data'], (new ExecutivesColumnModel)->getRule());

        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        } else {
            $record = ExecutivesColumnModel::create($data['data']);
        }
        \Session::flash('success-msg', 'Executive Column successfully added');
        return redirect()->route('admin-executives-column-list-get');
    }

    public function postDeleteColumnView($column_id){
        $column = ExecutivesColumnModel::where('id', $column_id)->firstOrFail();

        $column->delete();

        \Session::flash('success-msg', 'Executive Column successfully deleted');

        return redirect()->back();
    }

    public function getEditColumnsView($column_id){
        $column = ExecutivesColumnModel::where('id', $column_id)->firstOrFail();
        return view($this->view.'edit-column')
                ->with('column', $column);
    }

    public function postEditColumnsView($column_id){
        $data = request()->all();
            
        $column = ExecutivesColumnModel::where('id', $column_id)->firstOrFail();
        $column->update($data['data']);

        \Session::flash('success-msg', 'Executive Column successfully updated');
        return redirect()->route('admin-executives-column-list-get');        
    }

    public function postDeleteMultipleColumnView(){
        $rids = request()->get('rid');
        $success = 0;
        $error = 0;
        if($rids) {
            foreach($rids as $r) {
                $response = $this->apiDelete(ExecutivesColumnModel::class, $r);
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

    public function getTabListView(){
        $tabs = ExecutivesTabModel::get();

        return view($this->view.'list-tabs')
                    ->with('tabs', $tabs);
    }
    public function postTabCreateView(){
        $data = request()->all();

        $validator = \Validator::make($data['data'], (new ExecutivesTabModel)->getRule());

        if($validator->fails()) {
            \Session::flash('friendly-error-msg', 'There are some validation errors');
            return redirect()->back()
                            ->withInput()
                            ->withErrors($validator);
        } else {
            $record = ExecutivesTabModel::create($data['data']);
        }
        \Session::flash('success-msg', 'Executive tab successfully added');
        return redirect()->route('admin-executives-tab-list-get');
    }

    public function postTabEditView($tab_id){
        $data = request()->all();
        ExecutivesTabModel::where('id', $tab_id)->update($data['data']);

        \Session::flash('success-msg', 'Executive tab successfully edited');
        return redirect()->route('admin-executives-tab-list-get');         
    }

    public function postTabDeleteView($tab_id){
        $tab = ExecutivesTabModel::where('id', $tab_id)->firstOrFail();
        $tab->delete();
        
        \Session::flash('success-msg', 'Executive tab successfully deleted');
        return redirect()->route('admin-executives-tab-list-get');
    }
}