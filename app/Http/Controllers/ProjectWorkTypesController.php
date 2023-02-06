<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProjectWorkTypesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function worktype(Request $request)
    {
        $search_query = '';

        foreach ($request->all() as $field => $field_value) {
            $search_query .= $field . '=' . $field_value . '&';
        }

        if ($search_query != '') {

            $search_query = rtrim($search_query, '&');
            $api_response = makeIntervalsApiCall(Auth::user()->intervals_api_key, 'GET', 'projectworktype/?' . $search_query);
        } else {

            $api_response = makeIntervalsApiCall(Auth::user()->intervals_api_key, 'GET', 'projectworktype/');
        }

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
