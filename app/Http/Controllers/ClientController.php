<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $api_response = makeIntervalsApiCall(Auth::user()->intervals_api_key, 'GET', 'client?limit=2000');

        if ($api_response->code != 200) {

            $response = [
                'code' => $api_response->code,
                'message' => $api_response->error->message
            ];

            return response()->json($response, $api_response->code);
        }

        if (!empty($api_response) && isset($api_response)) {

            if ($api_response->code == 200) {
                return response()->json($api_response, Response::HTTP_OK);
            }
        }
    }

    public function search(Request $request)
    {

        // ?search = [name, description, localid]

        $api_response = makeIntervalsApiCall(Auth::user()->intervals_api_key, 'GET', 'client/?');

        //dd(Auth::user());
        $response  = createGuzzleRequest(Auth::user()->intervals_api_key, 'GET', 'https://api.myintervals.com/client/?search=' . $request->value);

        $body = $response->getBody()->getContents();
        $status = $response->getStatusCode();

        print_r($body);
    }
}
