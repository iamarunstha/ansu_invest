<?php

namespace App\Http\Controllers\Core\Footer\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Core\Footer\ContactModel;
use App\Http\Controllers\Core\Footer\FooterLinkModel;

class ApiFooterController extends Controller {

    public function getFooter(){
        $_contacts = ContactModel::first();
        $_links = FooterLinkModel::orderBy('link_order')->get();

        $data = [
            'contacts' => [
                'address' => $_contacts->address,
                'phone' => $_contacts->phone,
                'email' => $_contacts->email
            ],
            'disclaimer' => $_contacts->disclaimer,
            'links' => $_links
        ];
        return response()->json($data);
    }
}
