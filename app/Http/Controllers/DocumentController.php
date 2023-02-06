<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function all()
    {
        $api_response = makeIntervalsApiCall(Auth::user()->intervals_api_key, 'GET', 'document');

        if ($api_response->code != 200) {

            return response()->json($api_response, $api_response->code);
        }

        if (!empty($api_response) && isset($api_response)) {

            if ($api_response->code == 200) {
                return $api_response;
            }
        }
    }


    public function search(Request $request)
    {

        $search_query = '';

        foreach ($request->all() as $field => $field_value) {
            $search_query .= $field . '=' . $field_value . '&';
        }

        if ($search_query != '') {

            $search_query = rtrim($search_query, '&');
            $api_response = makeIntervalsApiCall(Auth::user()->intervals_api_key, 'GET', 'document/?' . $search_query);
        } else {

            $api_response = makeIntervalsApiCall(Auth::user()->intervals_api_key, 'GET', 'document');
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

    public function find(Request $request)
    {
        $api_response = makeIntervalsApiCall(Auth::user()->intervals_api_key, 'GET', 'document/' . $request->id);

        if ($api_response->code != 200) {

            return response()->json($api_response, $api_response->code);
        }

        if (!empty($api_response) && isset($api_response)) {

            if ($api_response->code == 200) {
                return $api_response;
            }
        }
    }


    public function download(Request $request)
    {
        $api_response = makeIntervalsApiCall(Auth::user()->intervals_api_key, 'GET', 'document/' . $request->id . '/download/');

        dd($api_response);

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
