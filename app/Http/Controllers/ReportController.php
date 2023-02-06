<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function reportFields()
    {
        //Clients
        //Projects
        //Milestones
        //Module
        //Work Type
        //Billable or UnBillable time
        //dd(Auth::user()->intervals_api_key);
        $this->clients();
    }


    public function clients()
    {

        $api_response = makeIntervalsApiCall(Auth::user()->intervals_api_key, 'GET', 'client/?limit=1');

        if ($api_response->code != 200) {

            return response()->json($api_response, $api_response->code);
        }

        if (!empty($api_response) && isset($api_response)) {

            if ($api_response->code == 200) {
                return $api_response;
            }
        }
    }
}
