<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GroupController extends Controller
{
    public function index()
    {

        $response  = createGuzzleRequest(Auth::user()->intervals_api_key,'GET','https://api.myintervals.com/group/');

        $body = $response->getBody()->getContents();
        $status = $response->getStatusCode();

        print_r($body);


    }
}
