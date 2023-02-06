<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function log_data(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'log_type'      => 'required',
            'log_detail'    => 'required',
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors();

            $validation_error_message = '';

            if ($errors->first('log_type')) {
                $validation_error_message .= 'log_type, ';
            }

            if ($errors->first('log_detail')) {
                $validation_error_message .= 'log_detail, ';
            }

            $validation_error_message = rtrim($validation_error_message, ', ');

            $response = [
                'code' => 200,
                'data' => '',
                'error_message' => $validation_error_message . ' fields are required.'
            ];

            return response()->json($response, 200);
        }

        $logout = log_data([
            'api_key'    => Auth::user()->intervals_api_key,
            'log_type'   => $request->log_type,
            'log_detail' => $request->log_detail,
        ]);

        if ($logout) {

            $response = [
                'status'    => 'success',
                'message'   => 'Successfully logged out',
            ];

            return response()->json($response, Response::HTTP_OK);
        }
    }
}
