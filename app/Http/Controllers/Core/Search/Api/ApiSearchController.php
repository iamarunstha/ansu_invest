<?php

namespace App\Http\Controllers\Core\Search\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Company\CompanyModel;
use App\Http\Controllers\Core\News\NewsModel;
use Illuminate\Http\Request;
use Validator;

class ApiSearchController extends Controller
{
    public function getSearch() {
        $input = request()->all();
        $input['type'] = isset($input['type']) && $input['type'] ? $input['type'] : 'Company';
        $input['search_term'] = isset($input['search_term']) && strlen($input['search_term']) >= 3 ? $input['search_term'] : $input['search_term'];

        $data = [];
        if($input['search_term']) {
            switch ($input['type']) {
                case 'Company' :
                    $_data = CompanyModel::where('company_name', 'LIKE', '%'.$input['search_term'].'%')
                                        ->orWhere('short_code', 'LIKE', '%'.$input['search_term'].'%')
                                        ->get();

                    foreach($_data as $d) {
                        $data[] = ['label' => $d->company_name.' ( '.$d->short_code.' )', 'short_code' => $d->short_code, 'url' => $d->slug, 'stock_data' => new \stdClass, 'type' => 'company'];
                    }

                    if(empty($data)) {
                        $data[] = ['label' => 'No result found', 'url' => '', 'type' => 'no result'];
                    } else {
                        $data[] = ['label' => 'View all companies', 'url' => '', 'type' => 'view all companies'];
                    }
                break;

                case 'News':

                    $_data = NewsModel::where('title', 'LIKE', '%'.$input['search_term'].'%')
                                    ->orWhere('summary', 'LIKE', "%".$input['search_term']."%")
                                    ->get();

                    foreach($_data as $d) {
                        $data[] = ['label' => $d->title, 'id' => $d->id, 'type' => 'news'];
                    }
                    if(empty($data)) {
                        $data[] = ['label' => 'No result found', 'id' => '', 'type' => 'no result'];
                    } else {
                        $data[] = ['label' => 'View all news', 'url' => '', 'type' => 'view all news'];
                    }
                break;
            }
        }

        return response()->json(['data' => $data]);
    }
}