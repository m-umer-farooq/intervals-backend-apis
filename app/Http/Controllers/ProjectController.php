<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{

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
            $api_response = makeIntervalsApiCall(Auth::user()->intervals_api_key, 'GET', 'project/?' . $search_query);
        } else {

            $api_response = makeIntervalsApiCall(Auth::user()->intervals_api_key, 'GET', 'project');
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

    public function retrieve(Request $request)
    {

        $api_response = makeIntervalsApiCall(Auth::user()->intervals_api_key, 'GET', 'project/' . $request->id);

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

        $api_response = makeIntervalsApiCall(Auth::user()->intervals_api_key, 'POST', 'project/', $content);

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

        /* {
            "personid": 307191,
            "status": "OK",
            "code": 200,
            "project": {
                "id": "1122067",
                "localid": "00069",
                "localidunpadded": "69",
                "clientid": "347543",
                "name": "Admin Module Stripe Integration",
                "description": "",
                "datestart": "2020-03-03",
                "dateend": null,
                "budget": "",
                "alert_percent": "0",
                "alert_date": null,
                "billable": "t",
                "active": "t",
                "manager": [
                    {
                        "managerid": "308845",
                        "manager": "Wasif Ali",
                        "manageractive": "t",
                        "managerlocalid": "17"
                    }
                ],
                "managerid": "308845"
            }
        } */
    }


    //1302552

    public function delete(Request $request)
    {
        $api_response = makeIntervalsApiCall(Auth::user()->intervals_api_key, 'DELETE',  'project/' . $request->id);

        if ($api_response->code != 200) {

            $response = [
                'code' => $api_response->code,
                'message' => $api_response->error->message
            ];

            return response()->json($response, $api_response->code);
        }

        if (!empty($api_response) && isset($api_response)) {

            if ($api_response->code == 200) {
                /*  {
                    "personid": 307191,
                    "status": "OK",
                    "code": 200,
                    "project": []
                } */
                return $api_response;
            }
        }
    }


    public function update(Request $request)
    {

        $content = json_decode($request->getContent());

        $api_response = makeIntervalsApiCall(Auth::user()->intervals_api_key, 'PUT', 'project/' . $request->id, $content);

        if ($api_response->code != 202) {

            $response = [
                'code' => $api_response->code,
                'message' => $api_response->error->message
            ];

            return response()->json($response, $api_response->code);
        }

        if (!empty($api_response) && isset($api_response)) {

            if ($api_response->code == 202) {
                return $api_response;
            }
        }
    }
}
