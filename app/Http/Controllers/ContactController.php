<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function descriptor(Request $request)
    {

        $api_response = makeIntervalsApiCall(Auth::user()->intervals_api_key, 'GET', 'contactdescriptor?contacttypeid=' . $request->contacttypeid);

        if ($api_response->code != 200) {

            $response = [
                'code' => $api_response->code,
                'message' => $api_response->error->message,
                'message_detail' => (isset($api_response->error->verbose)) ? $api_response->error->verbose : ''
            ];

            return response()->json($response, $api_response->code);
        }

        if (!empty($api_response) && isset($api_response)) {

            if ($api_response->code == 200) {
                return $api_response;
            }
        }
    }

    public function contact_type(Request $request)
    {
        $api_response = makeIntervalsApiCall(Auth::user()->intervals_api_key, 'GET', 'contacttype');

        if ($api_response->code != 200) {

            $response = [
                'code' => $api_response->code,
                'message' => $api_response->error->message,
                'message_detail' => (isset($api_response->error->verbose)) ? $api_response->error->verbose : ''
            ];

            return response()->json($response, $api_response->code);
        }

        if (!empty($api_response) && isset($api_response)) {

            if ($api_response->code == 200) {
                return $api_response;
            }
        }
    }
}
