<?php

namespace App\Http\Controllers\Core\BalanceSheet;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\BalanceSheet\BalanceSheetSectorTabsModel;
use App\Http\Controllers\Core\BalanceSheet\BalanceSheetTabsModel;
use App\Http\Controllers\Core\Sector\SectorModel;
use Illuminate\Http\Request;

class BalanceSheetController extends Controller
{
	public $view = 'Core.BalanceSheet.backend.';
    public $frontend_view = 'Core.BalanceSheet.frontend.';
	private $storage_folder = 'balanceSheet';

	public function getListView()
	{
		$sectors = SectorModel::orderBy('name', 'ASC')->with('tabs')->get();
		$tabs = BalanceSheetTabsModel::where('historical', 0)->orWhere('historical', Null)->whereNull('permanent')->orderBy('ordering')->get();
		$permanent_tabs = BalanceSheetTabsModel::where('permanent', 1)->get();
		$historical_tabs = BalanceSheetTabsModel::where('historical',1)->orderBy('ordering')->get();
		$parent_tabs = BalanceSheetTabsModel::where('is_parent','yes')->orderBy('ordering')->get();
		$sector_tabs = BalanceSheetSectorTabsModel::orderBy('tab_id', 'ASC')
					->with('sector')->with('tab')->get();

		return view($this->view.'list')
            ->with('sectors', $sectors)
            ->with('tabs', $tabs)
            ->with('historical_tabs', $historical_tabs)
            ->with('parent_tabs', $parent_tabs)
            ->with('permanent_tabs', $permanent_tabs)
            ->with('sector_tabs', $sector_tabs);
	}

	public function postBalanceSheetSectorCreateView()
	{
		$data = request()->all();
		SectorModel::create($data['data']);

		\Session::flash('success-msg', 'Sector successfully created');
		return redirect()->back();
	}

	public function postBalanceSheetSectorDeleteView($sector_id)
	{
		$sector_id = (int) $sector_id;
		$sector = SectorModel::where('id', $sector_id)->first();
		$sector->delete();

		\Session::flash('success-msg', 'Sector successfully deleted');
		return redirect()->back();		
	}

	public function postBalanceSheetSectorUpdateView($sector_id)
	{
		$data = request()->all();

		$sector_id = (int) $sector_id;
		$sector = SectorModel::where('id', $sector_id)->first();
		$sector->name = $data['data']['name'];
		$sector->save();

		\Session::flash('success-msg', 'Sector successfully Edited');
		return redirect()->back();
	}

	public function getBalanceSheetHeadingsView($sector_id, $tab_id)
	{
		$sector = SectorModel::where('id', $sector_id)->first();
		$tab = BalanceSheetTabsModel::where('id', $tab_id)->first();
		$data = BalanceSheetModel::where('sector_id', $sector_id)
									->where('tab_id', $tab_id)
									->orderBy('ordering', 'ASC')
									->get();

		return view($this->view.'edit') 
			->with('sector', $sector)
			->with('tab', $tab)
			->with('data', $data);
	}

	public function postBalanceSheetHeadingsView($sector_id, $tab_id)
	{
		$data = request()->all();

		\DB::beginTransaction();

		foreach ($data['data'] as $id => $row) {
			BalanceSheetModel::where('id', $id)
								->update($row);
		}
		\DB::commit();

		\Session::flash('success-msg', 'Headings successfully updated');

		return redirect()->back();
	}

	public function postBalanceSheetHeadingsCreateView($sector_id, $tab_id)
	{
		$data = request()->all()['data'];
		
		$data['has_value'] = 'yes';
		$data['sector_id'] = $sector_id;
		$data['tab_id'] = $tab_id;

		BalanceSheetModel::create($data);

		\Session::flash('success-msg', 'Headings successfully created');

		return redirect()->back();
	}

	public function postBalanceSheetHeadingsDeleteView($heading_id)
	{
		$heading = BalanceSheetModel::where('id', $heading_id);

		$heading->delete();

		\Session::flash('success-msg', 'Headings successfully deleted');
		return redirect()->back();
	}

	public function getTabsListView(){
		$data = BalanceSheetTabsModel::whereNull('permanent')->orderBy('ordering', 'ASC')->get();

		$parent_tabs = BalanceSheetTabsModel::where('is_parent', 'yes')->get();

		return view($this->view.'tab-list')
					->with('data', $data)
					->with('parent_tabs', $parent_tabs);
	}

	public function postBalanceSheetTabsCreateView()
	{
		$data = request()->all();
		if($data['data']['is_parent']=='yes'){
			$data['data']['parent_id'] = Null;
		}

		BalanceSheetTabsModel::create($data['data'])->id;
		\Session::flash('success-msg', 'Tab successfully created');
		
		return redirect()->back();		
	}

	public function postBalanceSheetTabsAddView($sector_id)
	{
		$data = request()->all();
		if (!isset($data['data'])){
			\Session::flash('error-msg', 'No tab selected');
			return redirect()->back();
		}
		\DB::beginTransaction();
			foreach($data['data'] as $index=>$value){
				$tab_to_add = BalanceSheetSectorTabsModel::firstOrcreate([
					'tab_id' => $value,
					'sector_id' => $sector_id
				]);
				$tab_to_add->save();
			}
			\Session::flash('success-msg', 'Tabs successfully Added');
		\DB::commit();
		
		return redirect()->back();		
	}

	public function postBalanceSheetTabsUpdateView($tab_id)
	{
		$data = request()->all();

		$tab = BalanceSheetTabsModel::where([
			'id' => $tab_id
		])->update($data['data']);

		\Session::flash('success-msg', 'Tab successfully updated');
		
		return redirect()->back();		
	}

	public function postBalanceSheetSectorTabsDeleteView($sector_id, $tab_id){
		$tab_to_remove = BalanceSheetSectorTabsModel::where('tab_id', $tab_id)
									->where('sector_id', $sector_id)
									->first();
		$tab_to_remove->delete();

		\Session::flash('success-msg', 'Tab successfully removed');
		return redirect()->back();
	}

	public function postBalanceSheetTabsDeleteView($tab_id){
		$tab = BalanceSheetTabsModel::where('id', $tab_id)->firstOrFail();
		$tab->delete();

		\Session::flash('success-msg', 'Tab successfully deleted');
		return redirect()->back();
	}

	public function postBalanceSheetTabsDeleteMultipleView(){
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
            $data = BalanceSheetTabsModel::where('id', $id)->firstOrFail();

            try{
                \Storage::delete($this->storage_folder.DS.$data->asset);
            } catch(\Exception $e) {
                //do nothing
            }

            $data->delete();       
        } catch(\Exception $e) {
            return ['status' => false, 'message' => $e->getMessage(), 'friendly-message' => 'Tabs could not be deleted'];
        }

        return ['status' => true, 'message' => 'Tabs successfully deleted'];
        
    }
}
