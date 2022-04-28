<?php

namespace App\Http\Controllers\Core\Sector\Api;   

use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Sector\SectorModel;

class ApiSectorController extends Controller
{
    public function getSectorList() {
        $sector_list = SectorModel::orderBy('name')->get();
        return $sector_list;
    }
}