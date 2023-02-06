<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class TimeController extends Controller
{
    public $url = 'time';


    public function __construct()
    {
        $this->middleware('auth:api');
    }


    public function all(Request $request)
    {
        $search_query = '';

        foreach ($request->all() as $field => $field_value) {
            $search_query .= $field . '=' . $field_value . '&';
        }

        if ($search_query != '') {

            $search_query = rtrim($search_query, '&');
            $api_response = makeIntervalsApiCall(Auth::user()->intervals_api_key, 'GET', $this->url . '/?' . $search_query);
        } else {

            $api_response = makeIntervalsApiCall(Auth::user()->intervals_api_key, 'GET', $this->url);
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


    public function create(Request $request)
    {
        $content = json_decode($request->getContent());

        $api_response = makeIntervalsApiCall(Auth::user()->intervals_api_key, 'POST', $this->url, $content);

        //dd($api_response);

        if ($api_response->code != 201) {

            $response = [
                'code' => $api_response->code,
                'message' => $api_response->error->message,
                'message_detail' => $api_response->error->verbose->value
            ];

            return response()->json($response, $api_response->code);
        }

        if (!empty($api_response) && isset($api_response)) {

            if ($api_response->code == 201) {
                return $api_response;
            }
        }
    }

    public function update($id, Request $request)
    {
        $content = json_decode($request->getContent(), true);

        foreach ($content as $key => $value) {
            if ($key == 'taskid') {
                unset($content[$key]);
            }
        }

        $content = json_encode($content);
        $content = json_decode($content);

        $api_response = makeIntervalsApiCall(Auth::user()->intervals_api_key, 'PUT', $this->url . '/' . $id, $content);

        if ($api_response->code != 202) {

            $response = [
                'code' => $api_response->code,
                'error_message' => $api_response->error->message,
                'message_detail' => $api_response->error->verbose->value
            ];

            return response()->json($response, $api_response->code);
        }

        if (!empty($api_response) && isset($api_response)) {

            if ($api_response->code == 202) {

                $response = [
                    'code' => $api_response->code,
                    'success_message' => 'Record updated successfully.'
                ];
                return response()->json($response, $api_response->code);
                //return $api_response;
            }
        }
    }
}
