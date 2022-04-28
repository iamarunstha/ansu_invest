<?php

namespace App\Http\Controllers;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Routing\Controller as BaseController;
use \Modules\Category\Entities\CategoryModel;
use \Modules\Category\Entities\MapCategoryCategoryModel;
use \Modules\Feature\Entities\FeatureModel;
use \Modules\Feature\Entities\FeatureOptionModel;
use \Modules\Category\Entities\MapCategoryFeatureModel;
use \Modules\Location\Entities\LocationModel;
use \PhpOffice\PhpSpreadsheet\IOFactory;
use \Modules\Category\Entities\MapCategoryPriceAliasModel;

class ExcelController extends \App\Http\Controllers\Controller
{
	/*$styles = ['sheet' => '', 'data' => ['row_number' => $styles]]

	*/

	public function setStyling($style) {
		$styles = [];
		switch($style) {
			case 'bold' :
				$styles = [
					'font' => [
						'bold' => true,
					]
				];
			break;
		}
		

		return $styles;
		
	}
	public function apiDownloadExcel($data =[['title' => '', 'data' => [], 'styles' => [] ]], $filename='excel', $styles = []) {
		$spreadsheet = new Spreadsheet();
	
		$spreadsheet->getProperties()->setCreator('PhpOffice')
	        ->setLastModifiedBy('PhpOffice')
	        ->setTitle('Ansu Excel File')
	        ->setSubject('Office 2007 XLSX Test Document')
	        ->setDescription('PhpOffice')
	        ->setKeywords('PhpOffice')
	        ->setCategory('PhpOffice');
		// Add some data
	    foreach($data as $index => $d) {
	    	if($index) {
				$spreadsheet->createSheet();
			}

			$spreadsheet->setActiveSheetIndex($index)->fromArray($d['data']);
			
			$spreadsheet->getActiveSheet()->setTitle($d['title']);

			$sheet = $spreadsheet->getActiveSheet();
			//dd($d['data']);
			foreach($d['data'] as $row_number => $rows) {
				foreach($rows as $column_number => $row) {
					$sheet->getColumnDimension($this->getNameFromNumber($column_number))->setAutoSize(true);
				}
				
			}

			// $d['styles'] = [0 => [
			// 	'font' => [
			// 		'bold' => true,
			// 	]
			// ]];


			if(isset($d['styles'])) {
				//dd(count($d['data'][$row_number]));
				foreach($d['styles'] as $row_number => $style){
					if($style) {
						$sheet->getStyle('A'.($row_number + 1).':'.$this->getNameFromNumber(count($d['data'][$row_number])).($row_number + 1).'')->applyFromArray($this->setStyling($style));
					}
				}		
			}
	    }
		
		$spreadsheet->setActiveSheetIndex(0);

		$this->download($spreadsheet, $filename);
	}

	public function download($spreadsheet, $filename='excel')
	{
		$writer = new Xlsx($spreadsheet);
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'. $filename .'.xlsx"'); /*-- $filename is  xsl filename ---*/
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}

	public function returnData($file) {
		$return = [];
		$spreadsheet = IOFactory::load($file);
		
		$sheets = $spreadsheet->getSheetNames();
		foreach($sheets as $sheetname) {
			$worksheet = $spreadsheet->getSheetByName($sheetname);
			$no_of_rows = $worksheet->getHighestDataRow();
			$no_of_columns = $worksheet->getHighestDataColumn();
			$no_of_columns = Coordinate::columnIndexFromString($no_of_columns); 
			$data = [];

			for ($currentRow = 1; $currentRow <= $no_of_rows; $currentRow++){
				for ($currentCol = 1; $currentCol <= $no_of_columns; $currentCol++){
					$data[$currentRow-1][$currentCol-1] = $worksheet->getCellByColumnAndRow($currentCol, $currentRow)->getCalculatedValue();
				}
			}

			$data = $this->convertRow($data, true);		
			$return[$sheetname] = $data;
		}
		
		return $return;	
	}

	public function getNameFromNumber($num) {
		$numeric = $num % 26;
		$letter = chr(65 + $numeric);
		$num2 = intval($num / 26);
		if ($num2 > 0) {
			return $this->getNameFromNumber($num2 - 1) . $letter;
		} else {
			return $letter;
		}

		//https://stackoverflow.com/questions/58393315/how-to-add-borders-to-phpspreadsheet-generated-excel-file
	}

	public function styles() {
		//Set table outer borders
		$styleArray = [
						'font' => [
							'bold' => true,
						],
						'alignment' => [
							'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
						],
						'borders' => [
							'top' => [
								'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
							],
							'right' => [
								'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
							],
							'bottom' => [
								'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
							],
							'left' => [
								'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
							],
						],
					];
	
		$sheet->getStyle('A1:D'.$dataCount.'')->applyFromArray($styleArray);
	
		//Set header outer borders
		$styleArray = [
				'font' => [
					'bold' => true,
					'size' => 18,
				],
				'alignment' => [
					'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				],
				'borders' => [
					'top' => [
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
					],
					'right' => [
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
					],
					'bottom' => [
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
					],
					'left' => [
						'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
					],
				],
			];
	
		$sheet->getStyle('A1:D1')->applyFromArray($styleArray);
	
	
		//Fill data
		$sheet->fromArray($header, NULL, 'A1');
		$sheet->fromArray($data, NULL, 'A2');
	}

	public function getUploadExcel()
	{
		return view('excel.upload-excel');
	}



	public function postUploadExcel()
	{
		$input = \Input::all();
		$spreadsheet = IOFactory::load(\Input::file('file'));
		try
		{
			\DB::beginTransaction();
				if(isset($input['sheet']))
				{
					foreach($input['sheet'] as $sheet_name)
					{

						$worksheet = $spreadsheet->getSheetByName($sheet_name);
						$data = $worksheet->toArray();
						if($sheet_name == 'Feature Options' || $sheet_name == 'Dynamic Feature Options')
						{							$data = $this->convertRow($data, false);
						}
						else
						{
							$data = $this->convertRow($data, true);	
						}
						
						if($sheet_name == 'Parent Category')
						{
							$this->updateParentCategory($data);
						}
						elseif($sheet_name == 'Sub Category')
						{
							$this->updateSubCategory($data);
						}
						elseif($sheet_name == 'Category Price Range')
						{
							$this->updateCategoryRange($data);
						}
						elseif($sheet_name == 'Features')
						{
							$this->updateFeatures($data);
						}
						elseif($sheet_name == 'Range Options')
						{
							$this->updateRangeOptions($data);
						}
						elseif($sheet_name == 'Feature Options')
						{
							$this->mapFeatureOptions($data);
						}
						elseif($sheet_name == 'Dynamic Feature Options')
						{
							$this->mapDynamicOptions($data);
						}
						elseif($sheet_name == 'Map Category Feature')
						{
							$this->updateMapCategoryFeature($data);
						}
						elseif($sheet_name == 'Location')
						{
							$this->updateLocation($data);
						}
						elseif($sheet_name == 'Feature Unit')
						{
							$this->updateFeatureUnit($data);
						}
						elseif($sheet_name == 'Map Category Keywords')
						{
							$this->updateMapCategoryKeywords($data);
						}
						elseif($sheet_name == 'Dynamic Feature Options Alias')
						{
							$this->updateDynamicFeatureOptionsAlias($data);
						}
						elseif($sheet_name == 'SEO')
						{
							$this->updateSEOContents($data);
						}
					}

					\Session::flash('success-msg', implode(' ,', $input['sheet']).' successfully updated');
				}
				else
				{
					\Session::flash('error-msg', 'No Sheet selected');	
				}

			\DB::commit();	
		}
		catch(\Exception $e)
		{
			\Session::flash('error-msg', $e->getMessage());
		}
		
		return redirect()->back();
	}

	public function downloadMasterExcelSheet()
	{
		$spreadsheet = new Spreadsheet();
	
		$spreadsheet->getProperties()->setCreator('PhpOffice')
	        ->setLastModifiedBy('PhpOffice')
	        ->setTitle('Fitkiri Mater Excel File')
	        ->setSubject('Office 2007 XLSX Test Document')
	        ->setDescription('PhpOffice')
	        ->setKeywords('PhpOffice')
	        ->setCategory('PhpOffice');
		// Add some data
		$spreadsheet->setActiveSheetIndex(0)->fromArray($this->getParentCategories());
		$spreadsheet->getActiveSheet()->setTitle('Parent Category');

		$spreadsheet->createSheet();
		$spreadsheet->setActiveSheetIndex(1)->fromArray($this->getSubCategories());
		$spreadsheet->getActiveSheet()->setTitle('Sub Category');

		$spreadsheet->createSheet();
		$spreadsheet->setActiveSheetIndex(2)->fromArray($this->getCategoryRange());
		$spreadsheet->getActiveSheet()->setTitle('Category Price Range');

		$spreadsheet->createSheet();
		$spreadsheet->setActiveSheetIndex(3)->fromArray($this->getFeatures());
		$spreadsheet->getActiveSheet()->setTitle('Features');

		$spreadsheet->createSheet();
		$spreadsheet->setActiveSheetIndex(4)->fromArray($this->getRangeFeatures());
		$spreadsheet->getActiveSheet()->setTitle('Range Options');

		$spreadsheet->createSheet();
		$spreadsheet->setActiveSheetIndex(5)->fromArray($this->getFeatureOptionsAndDynamicOptions());
		$spreadsheet->getActiveSheet()->setTitle('Feature Options');

		$spreadsheet->createSheet();
		$spreadsheet->setActiveSheetIndex(6)->fromArray($this->getFeatureDynamicOption());
		$spreadsheet->getActiveSheet()->setTitle('Dynamic Feature Options');

		$spreadsheet->createSheet();
		$spreadsheet->setActiveSheetIndex(7)->fromArray($this->mapCategoryFeatures());
		$spreadsheet->getActiveSheet()->setTitle('Map Category Feature');

		$spreadsheet->createSheet();
		$spreadsheet->setActiveSheetIndex(8)->fromArray($this->getLocation());
		$spreadsheet->getActiveSheet()->setTitle('Location');

		$spreadsheet->createSheet();
		$spreadsheet->setActiveSheetIndex(9)->fromArray($this->getFeatureUnit());
		$spreadsheet->getActiveSheet()->setTitle('Feature Unit');

		$spreadsheet->createSheet();
		$spreadsheet->setActiveSheetIndex(10)->fromArray($this->getMapCategoryKeywords());
		$spreadsheet->getActiveSheet()->setTitle('Map Category Keywords');

		$spreadsheet->createSheet();
		$spreadsheet->setActiveSheetIndex(11)->fromArray($this->getDynamicFeatureOtionsAlias());
		$spreadsheet->getActiveSheet()->setTitle('Dynamic Feature Options Alias');

		$spreadsheet->createSheet();
		$spreadsheet->setActiveSheetIndex(12)->fromArray($this->getSEO());
		$spreadsheet->getActiveSheet()->setTitle('SEO');
		
		$spreadsheet->setActiveSheetIndex(0);

		$this->download($spreadsheet);
	}

	public function getSEO()
	{
		$contents = (new \App\SEOController)->getStaticPageSeoContents();
		$rows = [];
		$rows[] = ['SN', 'Name', 'Keywords', 'Description', 'Title'];

		foreach($contents as $index => $c)
		{
			$rows[] = [$index + 1, $c['name'], $c['keywords'], $c['description'], $c['title']];
		}

		return $rows;
	}

	public function updateSEOContents($data)
	{
		//getStaticPageSEOJsonPath
		$contents = [];
		foreach($data as $row)
		{
			$contents[] = ['name' => $row['Name'], 'keywords' => $row['Keywords'], 'description' => $row['Description'], 'title' => $row['Title']];
		}

		$contents = json_encode($contents);

		\File::put((new \App\SEOController)->getStaticPageSEOJsonPath(), $contents);
	}

	public function getDynamicFeatureOtionsAlias()
	{
		$feature_options_table = (new \Modules\Feature\Entities\FeatureOptionModel)->getTable();
		$feature_table = (new \Modules\Feature\Entities\FeatureModel)->getTable();

		$data = \DB::table($feature_table)
					->join($feature_options_table, $feature_options_table.'.feature_id', '=', $feature_table.'.id')
					->select($feature_options_table.'.id', 'feature_name', 'option_name', $feature_options_table.'.alias')
					->where('feature_type', 'has_dynamic_options')
					->whereNull('parent_option_id')
					->orderBy('feature_name', 'ASC')
					->orderBy('parent_option_id', 'ASC')
					->orderBy('option_name', 'ASC')
					->get();

		$rows[] = ['ID', 'Feature Name', 'Option Name', 'Alias'];

		foreach($data as $d)
		{
			$rows[] = [$d->id, $d->feature_name, $d->option_name, $d->alias];
		}

		return $rows;

	}

	public function updateDynamicFeatureOptionsAlias($data)
	{
		foreach($data as $row)
		{
			$record = \Modules\Feature\Entities\FeatureOptionModel::where('id', $row['ID'])
																	->firstOrFail();

			$record->alias = $row['Alias'];
			$record->save();
		}	
	}

	public function getMapCategoryKeywords()
	{
		$category_table = (new \Modules\Category\Entities\CategoryModel)->getTable();
		$map_keywords_category_table = (new \Modules\Category\Entities\MapKeywordsCategoryModel)->getTable();
		$map_category_category_table = (new \Modules\Category\Entities\MapCategoryCategoryModel)->getTable();

		$categories = \DB::table($category_table)
							->orderBy('category_name', 'ASC')
							->get();

		$keywords = \DB::table($map_keywords_category_table)
						->get();

		$_map_categories = \DB::table($map_category_category_table)
								->get();

		$map_categories = [];
		foreach($_map_categories as $m)
		{
			$map_categories[$m->category_id] = $m->parent_category_id;
		}

		$keywords_data = [];
		foreach($keywords as $c)
		{
			$keywords_data[$c->category_id][] = $c->suggested_keyword;
		}

		$category_data = [];
		foreach($categories as $c)
		{
			$category_data[$c->id] = [$c->id, '', $c->category_name, isset($keywords_data[$c->id]) ? implode(',', $keywords_data[$c->id]) : ''];
		}

		foreach($category_data as $category_id => $c)
		{
			$category_data[$category_id][1] = isset($map_categories[$category_id]) ? $category_data[$map_categories[$category_id]][2] : '';
		}

		$data = [];
		$data[] = ['Category Id', 'Parent', 'Category', 'Keywords'];

		foreach($category_data as $c)
		{
			$data[] = $c;
		}

		return $data;
	}

	public function getFeatureUnit()
	{
		$feature_table = (new \Modules\Feature\Entities\FeatureModel)->getTable();
		$feature_unit_table = (new \Modules\Feature\Entities\FeatureUnitModel)->getTable();

		$_data = \DB::table($feature_table)
					->leftJoin($feature_unit_table, $feature_unit_table.'.feature_id', '=', $feature_table.'.id')
					->where('feature_type', 'has_unit')
					->select('feature_name', $feature_unit_table.'.*', $feature_table.'.id as f_id')
					->orderBy('feature_name', 'ASC')
					->orderBy('sort_order', 'ASC')
					->get();

		$return = [['Feature Id', 'Feature Name', 'Unit']];

		$data = [];
		foreach($_data as $d)
		{
			if(!isset($data[$d->f_id]))
			{
				$data[$d->f_id] = $d;	
			}
			else
			{
				$data[$d->f_id]->unit .= ','.$d->unit;
			}
			
		}

		foreach($data as $d)
		{
			$return[] = [$d->f_id, $d->feature_name, $d->unit];
		}

		return $return;

	}

	public function updateMapCategoryKeywords($data)
	{
		\Modules\Category\Entities\MapKeywordsCategoryModel::where('id', '>', 0)->delete();

		foreach($data as $d)
		{
			$keywords = explode(',', $d['Keywords']);
			foreach($keywords as $k)
			{
				if(strlen(trim($k)))
				{
					\Modules\Category\Entities\MapKeywordsCategoryModel::create([
						'category_id' => $d['Category Id'],
						'suggested_keyword' => $k
					]);	
				}
			}
			
		}
	}

	public function updateFeatureUnit($data)
	{
		foreach($data as $row)
		{
			$units = explode(',', $row['Unit']);
			\Modules\Feature\Entities\FeatureUnitModel::where('feature_id', $row['Feature Id'])
														->whereNotIn('unit', $units)
														->delete();

			foreach($units as $index => $u)
			{
				$record = \Modules\Feature\Entities\FeatureUnitModel::firstOrNew([
					'feature_id' => $row['Feature Id'],
					'unit'	=>	$u
				]);
				$record->sort_order = $index;
				$record->save();
			}
		}		
	}

	public function getParentCategories()
	{
		$data = (new CategoryModel)->getParentCategories();

		$return = [['Category ID', 'Category Name', 'Sort Order', 'icon', 'SEO Keywords', 'SEO Description', 'SEO Title']];
		foreach($data as $d)
		{
			$return[] = [$d->id, $d->category_name, $d->sort_order, $d->icon, $d->seo_keywords, $d->seo_description, $d->seo_title];
		}

		return $return;
	}

	public function getSubCategories()
	{
		$return = [['Sub Category ID', 'Category Name', 'Sub Category', 'Sort Order', 'Icon', 'Show Condition', 'Show Rent', 'Price Alias', 'Price Alias To Show', 'SEO Keywords', 'SEO Description', 'SEO Title']];
		$category_table = (new CategoryModel)->getTable();
		$map_category_table = (new MapCategoryCategoryModel)->getTable();
		$price_alias_table = (new MapCategoryPriceAliasModel)->getTable();

		$categories = (new CategoryModel)->getParentCategories();

		foreach($categories as $category)
		{
			$parent = $category;
			$sub_categories = \DB::table($map_category_table)
								->join($category_table, $category_table.'.id', '=', 'category_id')
								->where('parent_category_id', $parent->id)
								->get();

			foreach($sub_categories as $sub_category)
			{
				$_price_alias = MapCategoryPriceAliasModel::where('category_id', $sub_category->category_id)->first();
				if(!is_null($_price_alias))
				{
					$price_alias = $_price_alias->price_alias;
					$price_alias_to_show = $_price_alias->price_alias_to_show;
				}
				else
				{
					$price_alias = NULL;
					$price_alias_to_show = NULL;
				}
				
				$return[] = [$sub_category->category_id, $parent->category_name, $sub_category->category_name, $sub_category->sort_order, $sub_category->icon, $sub_category->show_condition, $sub_category->show_rent, $price_alias, $price_alias_to_show, $sub_category->seo_keywords, $sub_category->seo_description, $sub_category->seo_title];
				
				$sub_sub_categories = 	\DB::table($map_category_table)
								->join($category_table, $category_table.'.id', '=', 'category_id')
								->where('parent_category_id', $sub_category->id)
								->get();

				foreach($sub_sub_categories as $s)
				{
					$price_alias = MapCategoryPriceAliasModel::where('category_id', $s->category_id)->first();
					$_price_alias = MapCategoryPriceAliasModel::where('category_id', $s->category_id)->first();
					if(!is_null($_price_alias))
					{
						$price_alias = $_price_alias->price_alias;
						$price_alias_to_show = $_price_alias->price_alias_to_show;
					}
					else
					{
						$price_alias = NULL;
						$price_alias_to_show = NULL;
					}
					$return[] = [$s->category_id, $sub_category->category_name, $s->category_name, $s->sort_order, $s->icon, $s->show_condition, $s->show_rent, $price_alias, $price_alias_to_show, $s->seo_keywords, $s->seo_description, $s->seo_title];	
				}
			}
			
		}

		return $return;
	}

	public function getFeatures()
	{
		$data = (new FeatureModel)->getFeatures();

		$return = [['Feature ID', 'Feature Name',	'Feature Type',	'Placeholder Text',	'Feature Text',	'Is Required',	'Sort Order']];

		foreach($data as $d)
		{
			$return[] = [$d->id, $d->feature_name, $d->feature_type, $d->placeholder_text, $d->feature_text, $d->is_required];
		}

		return $return;
	}

	public function getCategoryRange()
	{
		$data = CategoryModel::orderBy('category_name', 'ASC')
							->get();

		$return = [['Category ID', 'Category Name',	'Range Name', 'Min', 'Max']];

		foreach($data as $d)
		{
			$json = json_decode($d->range_options, true);
			$range_name = [];
			$range_min = [];
			$range_max = [];

			if($json)
			{
				foreach($json as $j)
				{
					$range_name[] = $j['range_name'];
					$range_min[] = $j['min'];
					$range_max[] = $j['max'];
				}
			}

			$range_name = implode(';', $range_name);
			$range_min = implode(';', $range_min);
			$range_max = implode(';', $range_max);
			$return[] = [$d->id, $d->category_name, $range_name, $range_min, $range_max];
		}	

		return $return;	
	}

	public function getRangeFeatures()
	{
		$data = FeatureModel::where('feature_type', 'range')		
							->orderBy('feature_name', 'ASC')
							->get();

		$return = [['Feature ID', 'Feature Name',	'Range Name', 'Min', 'Max']];

		foreach($data as $d)
		{
			$json = json_decode($d->range_options, true);
			$range_name = [];
			$range_min = [];
			$range_max = [];

			if($json)
			{
				foreach($json as $j)
				{
					$range_name[] = $j['range_name'];
					$range_min[] = $j['min'];
					$range_max[] = $j['max'];
				}
			}

			$range_name = implode(',', $range_name);
			$range_min = implode(',', $range_min);
			$range_max = implode(',', $range_max);
			$return[] = [$d->id, $d->feature_name, $range_name, $range_min, $range_max];
		}

		return $return;
	}

	public function getFeatureOptionsAndDynamicOptions()
	{
		$feature_table = (new FeatureModel)->getTable();
		$feature_option_table = (new FeatureOptionModel)->getTable();

		$data = \DB::table($feature_table)
					->leftJoin($feature_option_table, $feature_option_table.'.feature_id', '=', $feature_table.'.id')
					->whereIn('feature_type', ['has_options', 'has_options_value', 'has_dynamic_options'])
					->select('feature_name', $feature_option_table.'.*', $feature_table.'.id as feature_id')
					->whereNull('parent_option_id')
					->orderBy($feature_table.'.feature_name', 'ASC')
					->orderBy($feature_option_table.'.sort_order', 'ASC')
					->get();

		$header = [];
		$value = [];
		foreach($data as $d)
		{
			if(!in_array($d->feature_name.' - '.$d->feature_id, $header))
			{
				$header[] = 'Option ID Of '.$d->feature_name.' - '.$d->feature_id;
				$header[] = $d->feature_name.' - '.$d->feature_id;
			}

			$index = array_search($d->feature_name.' - '.$d->feature_id, $header);
			$value[$index - 1][] = $d->id;
			$value[$index][] = $d->option_name;
		}

		$largest = 0;
		$largest_index = 0;
		foreach($value as $index => $v)
		{
			/*echo '<pre>';
			print_r($v);
			echo '</pre>';*/
			if(count($v) > $largest)
			{
				$largest = count($v);
				$largest_index = $index;
			}
		}

		$return = [];
		$return[] = $header;

		foreach($value[$largest_index] as $value_index => $v)
		{
			$temp = [];
			
			foreach($header as $header_index => $h)
			{
				$temp[$header_index] = '';
			}

			foreach($header as $header_index => $h)
			{
				if(isset($value[$header_index][$value_index]))
				{
					$temp[$header_index] = $value[$header_index][$value_index];
					//$temp[] = $value[$header_index][$value_index]['option_name'];
				}
				
			}
			$return[] = $temp;
		}

		return $return;
	}

	public function getFeatureDynamicOption()
	{
		$feature_option_table = (new FeatureOptionModel)->getTable();
		$feature_table = (new FeatureModel)->getTable();

		$data = \DB::table($feature_option_table)
					->join($feature_option_table.' as t2', 't2.parent_option_id', '=', $feature_option_table.'.id')
					->whereNotNull('t2.parent_option_id')
					->select($feature_option_table.'.option_name as parent_option', 't2.parent_option_id as parent_id', 't2.option_name as child_option', 't2.id as child_id')
					->orderBy('parent_option', 'ASC')
					->orderBy('t2.sort_order', 'ASC')
					->get();

		$header = [];
		$value = [];
		foreach($data as $d)
		{
			if(!in_array($d->parent_option.' - '.$d->parent_id, $header))
			{
				$header[] = 'Option ID of'.$d->parent_option.' - '.$d->parent_id;
				$header[] = $d->parent_option.' - '.$d->parent_id;
			}

			$index = array_search($d->parent_option.' - '.$d->parent_id, $header);
			$value[$index - 1][] = $d->child_id;
			$value[$index][] = $d->child_option;
		}

		$largest = 0;
		$largest_value = 0;

		foreach($value as $index => $v)
		{
			if(count($v) > $largest_value)
			{
				$largest = $index;
				$largest_value = count($v);
			}
		}

		$return = [];
		$return[] = $header;
		
		foreach($value[$largest] as $value_index => $v)
		{
			foreach($header as $header_index => $h)
			{
				$temp[$header_index] = '';
			}

			foreach($header as $header_index => $h)
			{
				if(isset($value[$header_index][$value_index]))
				{
					$temp[$header_index] = $value[$header_index][$value_index];
					//$temp[] = $value[$header_index][$value_index]['option_name'];
				}
				
			}
			$return[] = $temp;
		}

		$parent_options = \DB::table($feature_option_table)
							->join($feature_table, $feature_table.'.id', '=', $feature_option_table.'.feature_id')
							->where('feature_type', 'has_dynamic_options')
							->whereNull('parent_option_id')
							->select('option_name as parent_option', $feature_option_table.'.id as parent_id')
							->get();

		$return[0] = isset($return[0]) ? $return[0] : [];
		foreach($parent_options as $d)
		{
			if(!in_array($d->parent_option.' - '.$d->parent_id, $header))
			{
				$return[0][] = 'Option ID of'.$d->parent_option.' - '.$d->parent_id;
				$return[0][] = $d->parent_option.' - '.$d->parent_id;
			}
		}

		return $return;
	}

	public function mapCategoryFeatures()
	{
		$category_feature_table = (new MapCategoryFeatureModel)->getTable();
		$category_table = (new CategoryModel)->getTable();
		$feature_table = (new FeatureModel)->getTable();

		$data = \DB::table($category_feature_table)
					->join($category_table, $category_table.'.id', '=', $category_feature_table.'.category_id')
					->join($feature_table, $feature_table.'.id', '=', $category_feature_table.'.feature_id')
					->select($category_feature_table.'.*', $category_table.'.category_name', $feature_table.'.feature_name')
					->get();

		$return = [['ID', 'Category ID', 'Category', 'Feature ID', 'Feature', 'Show In Search', 'Is Required', 'Show With Image', 'Sort Order']];

		foreach($data as $d)
		{
			$return[] = [$d->id,$d->category_id,  $d->category_name, $d->feature_id, $d->feature_name, $d->show_in_search, $d->is_required, $d->show_with_image, $d->sort_order];
		}

		return $return;
	}

	public function getLocation()
	{
		$data = LocationModel::orderBy('sort_order', 'ASC')
							->get();

		$return = [['ID', 'Location Name', 'Sort Order']];

		foreach($data as $d)
		{
			$return[] = [$d->id, $d->location_name, $d->sort_order];
		}

		return $return;
	}

	public function updateParentCategory($data)
	{
		$ids_to_retain = [];

		try
		{
			foreach($data as $index => $row)
			{
				if($row['Category ID'])
				{
					$record = CategoryModel::where('id', $row['Category ID'])->firstOrFail();
					$record->category_name = $row['Category Name'];
					$record->sort_order = $row['Sort Order'];
					$record->icon = $row['icon'];
					$record->seo_keywords = $row['SEO Keywords'];
					$record->seo_description = $row['SEO Description'];
					$record->seo_title = $row['SEO Title'];
					$record->slug = $record->id.'-'.str_slug($row['Category Name'], '-');
					$record->save();
				}
				else
				{
					$record = CategoryModel::create([
						'category_name' => $row['Category Name'],
						'icon'	=>	$row['icon'],
						'is_parent' => 'yes',
						'sort_order' => $row['Sort Order'],
						'seo_keywords' => $row['SEO Keywords'],
						'seo_description' => $row['SEO Description'],
						'seo_title' => $row['SEO Title']
					]);

					$record->slug = $record->id.'-'.str_slug($row['Category Name'], '-');
				}

				$ids_to_retain[] = $record->id;
			}

			CategoryModel::whereNotIn('id', $ids_to_retain)->where('is_parent', '=', 'yes')->delete();
			//\Cache::forget('menu-parent-categories');
		}
		catch(\Exception $e)
		{
			throw new \Exception($e->getMessage().' in sheet Parent Category row no. '.($index + 2));
		}
	}

	public function updateLocation($data)
	{
		try
		{
			foreach($data as $index => $row)
			{
				if($row['ID'])
				{
					$record = LocationModel::where('id', $row['ID'])->firstOrFail();
					$record->location_name = $row['Location Name'];
					$record->sort_order = (int) $row['Sort Order'];
					$record->save();
				}
				else
				{
					$max_value = (int) LocationModel::max('sort_order');
					$max_value += 1;
					LocationModel::create([
						'location_name' => $row['Location Name'],
						'sort_order'	=> (int) $row['Sort Order'] ? (int) $row['Sort Order'] : $max_value
					]);
				}
			}
		}
		catch(\Exception $e)
		{
			throw new \Exception($e->getMessage().' in sheet Location row no. '.($index + 2));
		}
	}

	public function updateFeatures($data)
	{
		$ids_to_retain = [];
		try
		{
			foreach($data as $index => $row)
			{
				if($row['Feature ID'])
				{
					$record = FeatureModel::where('id', $row['Feature ID'])->firstOrFail();
					$record->feature_name = $row['Feature Name'];
					$record->feature_type = $row['Feature Type'];
					$record->placeholder_text = $row['Placeholder Text'];
					$record->feature_text = $row['Feature Text'];
					$record->is_required = $row['Is Required'];
					$record->save();
				}
				else
				{
					$record = FeatureModel::create([
						'feature_name'	=>	$row['Feature Name'],
						'feature_text'	=>	$row['Feature Text'],
						'placeholder_text'	=>	$row['Placeholder Text'],
						'feature_type'	=>	$row['Feature Type'],
						'is_required'	=>	$row['Is Required']
					]);
				}

				$ids_to_retain[] = $record->id;
			}

			FeatureModel::whereNotIn('id', $ids_to_retain)->delete();
		}
		catch(\Exception $e)
		{
			throw new \Exception($e->getMessage().' in sheet Features row no. '.($index + 2));
		}
	}

	public function updateRangeOptions($data)
	{
		try
		{
			foreach($data as $index => $row)
			{
				$json = NULL;
				$range_name = explode(',', $row['Range Name']);
				$range_min = explode(',', $row['Min']);
				$range_max = explode(',', $row['Max']);

				foreach($range_name as $_index => $r)
				{
					if(!empty($r))
					{
						$json[] = ['range_name' => $r, 'min' => $range_min[$_index], 'max' => $range_max[$_index]];	
					}
				}

				if($row['Feature ID'])
				{
					$record = FeatureModel::where('id', $row['Feature ID'])->firstOrFail();
					$record->feature_name = $row['Feature Name'];
					//$record->feature_type = $row['Feature Type'];
					//$record->placeholder_text = $row['Placeholder Text'];
					//$record->feature_text = $row['Feature Text'];
					//$record->is_required = $row['Is Required'];
					$record->range_options = !is_null($json) ? json_encode($json) : $json;
					$record->save();
				}
			}
		}
		catch(\Exception $e)
		{
			throw new \Exception($e->getMessage().' in sheet Features row no. '.($index + 2));
		}
	}

	public function updateCategoryRange($data)
	{
		try
		{
			foreach($data as $index => $row)
			{
				$json = NULL;
				$range_name = explode(';', $row['Range Name']);
				$range_min = explode(';', $row['Min']);
				$range_max = explode(';', $row['Max']);

				foreach($range_name as $_index => $r)
				{
					if(!empty($r))
					{
						$json[] = ['range_name' => $r, 'min' => $range_min[$_index], 'max' => $range_max[$_index]];	
					}
				}

				if($row['Category ID'])
				{
					$record = CategoryModel::where('id', $row['Category ID'])->firstOrFail();
					$record->category_name = $row['Category Name'];
					//$record->feature_type = $row['Feature Type'];
					//$record->placeholder_text = $row['Placeholder Text'];
					//$record->feature_text = $row['Feature Text'];
					//$record->is_required = $row['Is Required'];
					$record->range_options = !is_null($json) ? json_encode($json) : $json;
					$recod->slug = $record->id.'-'.str_slug($row['Category Name'], '-');
					$record->save();
				}
			}
		}
		catch(\Exception $e)
		{
			throw new \Exception($e->getMessage().' in sheet Category Price Range row no. '.($index + 2));
		}
	}

	public function updateSubCategory($data)
	{
		$ids_to_retain = [];
		try
		{
			foreach($data as $index => $row)
			{
				if($row['Sub Category ID'])
				{
					$sub_category_record = CategoryModel::where('id', $row['Sub Category ID'])->firstOrFail();
					$sub_category_record->category_name = $row['Sub Category'];
					$sub_category_record->slug = $sub_category_record->id.'-'.str_slug($row['Sub Category'], '-');
					$sub_category_record->icon = $row['Icon'];
					$sub_category_record->is_parent = 'no';
					$sub_category_record->show_condition = $row['Show Condition'];
					$sub_category_record->show_rent = $row['Show Rent'];
					$sub_category_record->sort_order = $row['Sort Order'];
					$sub_category_record->seo_keywords = $row['SEO Keywords'];
					$sub_category_record->seo_description = $row['SEO Description'];
					$sub_category_record->seo_title = $row['SEO Title'];
					$sub_category_record->save();
				}
				else
				{
					$sub_category_record = CategoryModel::create([
						'category_name'	=>	$row['Sub Category'],
						'is_parent'	=>	'no',
						'icon'	=>	$row['Icon'],
						'show_condition'	=>	$row['Show Condition'],
						'show_rent'	=>	$row['Show Rent'],
						'sort_order' => $row['Sort Order'],
						'seo_keywords' => $row['SEO Keywords'],
						'seo_description' => $row['SEO Description'],
						'seo_title' => $row['SEO Title']
					]);

					$sub_category_record->slug = $sub_category_record->id.'-'.str_slug($row['Sub Category'], '-');
				}

				if(strlen($row['Price Alias']))
				{
					$rec = MapCategoryPriceAliasModel::firstOrNew([
						'category_id'=> $sub_category_record->id
					]);
					$rec->price_alias = $row['Price Alias'];
					$rec->price_alias_to_show = $row['Price Alias To Show'];
					$rec->save();
				}
				else
				{
					MapCategoryPriceAliasModel::where('category_id', $sub_category_record->id)->delete();
				}

				$ids_to_retain[] = $sub_category_record->id;

				$parent_category_id = CategoryModel::where('category_name', $row['Category Name'])->first();

				if(is_null($parent_category_id))
				{
					throw new \Exception('Parent Category '.$row['Category Name'].' not found.');
				}
				else
				{
					$parent_category_id = $parent_category_id->id;
				}

				$map_record = MapCategoryCategoryModel::firstOrNew([
					'category_id'	=>	$sub_category_record->id
				]);

				$map_record->parent_category_id = $parent_category_id;
				$map_record->save();
			}

			CategoryModel::whereNotIn('id', $ids_to_retain)->where('is_parent', '!=', 'yes')->delete();
			\Cache::forget('menu-sub-categories');
		}
		catch(\Exception $e)
		{
			throw new \Exception($e->getMessage().' in sheet Sub Category row no. '.($index + 2));
		}
	}

	public function mapFeatureOptions($data)
	{
		$features = [];
		$feature_index = [];
		$ids_to_retain = [];
		$options = []; //['feature_id' => []];


		try
		{
			foreach($data as $index => $row)
			{
				if($index == 0)
				{
					foreach($row as $header_index => $r)
					{
						$feature_id = explode(' - ', $r);
						$feature_id = isset($feature_id[1]) ? $feature_id[1] : NULL;
						if(strpos($r, 'Option ID') === false )
						{
							$features[$header_index] = ['feature_index' => $header_index, 'option_index' => $header_index - 1, 'feature_name' => $r, 'feature_id' => $feature_id];
							$feature_index[] = $header_index;
						}
					}
				}
				else
				{
					foreach($row as $header_index => $r)
					{
						$row[$header_index] = trim($r);

						if(in_array($header_index, $feature_index))
						{
							if(strlen($r))
							{
								if(strlen($row[$features[$header_index]['option_index']]))
								{
									$record = FeatureOptionModel::where('id', $row[$features[$header_index]['option_index']])->firstOrFail();
									$record->option_name = $row[$header_index];
									$record->save();

									$options[$features[$header_index]['feature_id']][] = $record->id;

								}
								else
								{
									$max_value = (int) FeatureOptionModel::where('feature_id', $features[$header_index]['feature_id'])
																	->whereNull('parent_option_id')
																	->max('option_value');
									$max_value += 1;
									$record = FeatureOptionModel::create([
										'option_name'	=>	$row[$header_index],
										'option_value'	=>	$max_value,
										'feature_id' => $features[$header_index]['feature_id']
									]);

									$options[$features[$header_index]['feature_id']][] = $record->id;
								}
							}

							$ids_to_retain[] = $record->id;
						}
					}
				}
			}

			FeatureOptionModel::whereNotIn('id', $ids_to_retain)->whereNull('parent_option_id')->delete();

			foreach($options as $feature_id => $records)
			{
				foreach($records as $sort_order_value => $r)
				{
					FeatureOptionModel::where('id', $r)->update([
						'sort_order' => $sort_order_value
					]);	
				}
			}
		}
		catch(\Exception $e)
		{
			throw new \Exception($e->getMessage().' in sheet Feature Options row no. '.($index + 2));
		}
		
	}

	public function mapDynamicOptions($data)
	{
		$features = [];
		$feature_index = [];
		$ids_to_retain = [];
		$options = []; //['parent_option_id' => sort_order]
		try
		{
			foreach($data as $index => $row)
			{
				if($index == 0)
				{
					foreach($row as $header_index => $r)
					{
						$parent_option_id = explode(' - ', $r);
						$parent_option_id = isset($parent_option_id[1]) ? $parent_option_id[1] : NULL;
						
						if(strpos($r, 'Option ID') === false )
						{
							$features[$header_index] = ['dynamic_option_index' => $header_index, 'option_index' => $header_index - 1, 'dynamic_option_name' => $r, 'parent_option_id' => $parent_option_id];
							$dynamic_option_index[] = $header_index;
						}
					}
				}
				else
				{
					foreach($row as $header_index => $r)
					{
						$row[$header_index] = trim($r);
						
						if(in_array($header_index, $dynamic_option_index))
						{
							if(strlen($r))
							{
								if(strlen($row[$features[$header_index]['option_index']]))
								{
									$record = FeatureOptionModel::where('id', $row[$features[$header_index]['option_index']])->firstOrFail();
									$record->option_name = $row[$header_index];
									$record->save();
									$options[$features[$header_index]['parent_option_id']][] = $record->id;

								}
								else
								{
									$max_value = (int) FeatureOptionModel::where('parent_option_id', $features[$header_index]['parent_option_id'])
																	//->whereNull('parent_option_id')
																	->max('option_value');

									$feature_id = (int) FeatureOptionModel::where('id', $features[$header_index]['parent_option_id'])->first()->feature_id;

									$max_value += 1;
									$record = FeatureOptionModel::create([
										'option_name'	=>	$row[$header_index],
										'option_value'	=>	$max_value,
										'parent_option_id' => $features[$header_index]['parent_option_id'],
										'feature_id'	=>	$feature_id
									]);

									$options[$features[$header_index]['parent_option_id']][] = $record->id;

									/*if($r == 'New Feature of Maruti Suzuki')
									{
										echo '<pre>';
										print_r($record);
										die();
									}*/
								}

								$ids_to_retain[] = $record->id;
							}
						}
					}
				}
			}

			foreach($options as $parent_option_id => $records)
			{
				foreach($records as $sort_order => $r)
				{
					FeatureOptionModel::where('id', $r)->update([
						'sort_order' => $sort_order
					]);
				}
			}
			

			FeatureOptionModel::whereNotIn('id', $ids_to_retain)->whereNotNull('parent_option_id')->delete();
		}
		catch(\Exception $e)
		{
			throw new \Exception($e->getMessage().' in sheet Dynamic Feature Option row no. '.($index + 2));
		}
	}

	public function updateMapCategoryFeature($data)
	{
		$ids_to_retain = [];
		$_features = FeatureModel::get();
		$features = [];
		foreach($_features as $f)
		{
			$features[$f->id] = $f->id;
		}

		$_categories = CategoryModel::get();
		$categories = [];
		foreach($_categories as $c)
		{
			$categories[$c->id] = $c->id;
		}

		foreach($data as $index => $row)
		{
			$feature_index = array_search($row['Feature ID'], $features);
			$category_index = array_search($row['Category ID'], $categories);

			if($feature_index === false)
			{
				throw new \Exception('Feature '.$row['Feature'].' not found in sheet MapCategoryFeature row no. '.($index + 2));
			}

			if($category_index === false)
			{
				throw new \Exception('Category '.$row['Category'].' not found in sheet MapCategoryFeature row no. '.($index + 2));
			}
			
			if($row['ID'])
			{
				$record = MapCategoryFeatureModel::where('id', $row['ID'])->first();
				$record->feature_id = $feature_index;
				$record->category_id = $category_index;
				$record->show_in_search = $row['Show In Search'];
				$record->is_required = $row['Is Required'];
				$record->show_with_image = $row['Show With Image'];
				$record->sort_order = $row['Sort Order'];
				$record->save();
			}
			else
			{
				$record = MapCategoryFeatureModel::create([
					'feature_id' => $feature_index,
					'category_id' => $category_index,
					'show_in_search' => $row['Show In Search'],
					'is_required' => $row['Is Required'],
					'show_with_image' => $row['Show With Image'],
					'sort_order'	=>	$row['Sort Order']
				]);
			}	
			
			$ids_to_retain[] = $record->id;
		}

		MapCategoryFeatureModel::whereNotIn('id', $ids_to_retain)->delete();
	}

	private function convertRow($data, $should_convert)
	{
		$return = [];
		$header = [];

		foreach($data as $index => $row)
		{
			foreach($row as $i => $r)
			{
				$row[$i] = trim($r);
			}

			if(!$should_convert)
			{
				$return[] = $row;
			}
			else
			{
				if($index == 0)
				{
					$header = $row;
					continue;
				}

				foreach($row as $i => $d)
				{
					if($header[$i]){
						$temp[$header[$i]] = strlen($d) ? $d : NULL;	
					}			
				}
				$return[] = $temp;	
			}
			
		}

		return $return;
	}
}