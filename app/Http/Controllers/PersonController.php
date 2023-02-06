<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;


class PersonController extends Controller
{
    public $url = 'person';

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function show(Request $request)
    {
        $query_string       = '';

        $query_string_options = [
            'localid',
            'username',
            'email',
            'firstname',
            'lastname',
            'excludegroupids',
            'projectid',
            'clientid',
            'projectclientid',
            'search',
            'active',
            'groupid',
            'allprojects',
            'restricttasks',
            'offset',
            'limit',
        ];

        foreach ($query_string_options as $query_string_option) {
            if (isset($request->$query_string_option) && !empty($request->$query_string_option)) {
                $query_string .= $query_string_option . '=' . $request->$query_string_option . '&';
            }
        }

        $url = $this->url;
        if (!empty($query_string) && $query_string != '') {
            $url = $url . '/?' . $query_string;
            $url = rtrim($url, '&');
        }

        $api_response = makeIntervalsApiCall(Auth::user()->intervals_api_key, 'GET', $url, $data = null);

        if ($api_response->code != 200) {

            $response = [
                'code' => $api_response->code,
                'message' => $api_response->error->message
            ];

            return response()->json($response, $api_response->code);
        }

        if (!empty($api_response) && isset($api_response)) {

            if ($api_response->code == 200) {

                //ini_set('default_charset', 'UTF-8');

                $person_data = [];
                $persons = $api_response->person;

                foreach ($persons as $person) {
                    $person_data[] = [
                        'id'        => $person->id,
                        'firstname' => toUTF8($person->firstname),
                        'lastname'  => toUTF8($person->lastname)
                    ];
                }

                $response_data = [
                    'personid'  => $api_response->personid,
                    'status'    => $api_response->status,
                    'code'      => $api_response->code,
                    'listcount' => $api_response->listcount,
                    'person'    => $person_data
                ];

                return response()->json($response_data, Response::HTTP_OK);
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

    public function update(Request $request)
    {

        $content = json_decode($request->getContent());

        $api_response = makeIntervalsApiCall(Auth::user()->intervals_api_key, 'PUT', $this->url . '/' . $request->id, $content);

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

    public function search(Request $request)
    {
        $api_response = makeIntervalsApiCall(Auth::user()->intervals_api_key, 'GET', $this->url . '/' . $request->id, $data = null);

        if ($api_response->code != 200) {

            $response = [
                'code' => $api_response->code,
                'message' => $api_response->error->message
            ];

            return response()->json($response, $api_response->code);
        }

        if (!empty($api_response) && isset($api_response)) {

            if ($api_response->code == 200) {
                return $api_response;
            }
        }
    }

    public function delete(Request $request)
    {
        $api_response = makeIntervalsApiCall(Auth::user()->intervals_api_key, 'DELETE', $this->url . '/' . $request->id);

        if ($api_response->code != 200) {

            $response = [
                'code' => $api_response->code,
                'message' => $api_response->error->message
            ];

            return response()->json($response, $api_response->code);
        }

        if (!empty($api_response) && isset($api_response)) {

            if ($api_response->code == 200) {
                /* {
                "personid": 307191,
                "status": "OK",
                "code": 200,
                "person": []
                } */
                return $api_response;
            }
        }
    }
}
