<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    public function register(Request $request)
    {

        $rules = [
            'intervals_user_name' => 'required|regex:/^[a-zA-Z0-9._]*$/',
            'intervals_api_key'   => 'required|regex:/^[a-zA-Z0-9]*$/',
            'name'                => 'required|string',
            'email'               => 'required|email:rfc,dns|unique:users,email'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {

            $response = ['message' => $validator->messages()];

            return response()->json($response, Response::HTTP_BAD_REQUEST);
        } else {

            $input = $validator->validated();

            //$user = User::create($input);

            $user = User::create([
                'name'                  => $input['name'],
                'email'                 => $input['email'],
                'intervals_user_name'   => $input['intervals_user_name'],
                'intervals_api_key'     => $input['intervals_api_key'],
                'password'              => bcrypt($input['intervals_api_key']),
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            $response = [
                'user' => $user,
                'token' => $token
            ];

            return response()->json($response, Response::HTTP_CREATED);
        }
    }

    public function login(Request $request)
    {
        $rules = ['intervals_api_key' => 'required|regex:/^[a-zA-Z0-9]*$/'];
        $validator = Validator::make($request->all(), $rules);
        /* $request->validate(['intervals_api_key' => 'required|regex:/^[a-zA-Z0-9]*$/']);
        $credentials = $request->only('intervals_api_key');
        $token = Auth::attempt($credentials);
        dd($token); */

        if ($validator->fails()) {
            $errors = $validator->errors();
            $response = ['message' => $errors->first('intervals_api_key')];
            return response()->json($response, 401);
        } else {

            $input = $validator->validated();

            $api_response = makeIntervalsApiCall($input['intervals_api_key'], 'GET', 'me');

            if ($api_response->code != 200) {

                $response = [
                    'code' => $api_response->code,
                    'message' => $api_response->error->message
                ];

                return response()->json($response, $api_response->code);
            }

            if (!empty($api_response) && isset($api_response)) {

                if ($api_response->code == 200) {

                    $me = $api_response->me[0];

                    $user_exists = User::where('intervals_api_key', $input['intervals_api_key'])->first();

                    if (empty($user_exists)) {

                        $user = User::create([
                            'intervals_id'          => $me->id,
                            'localid'               => $me->localid,
                            'clientid'              => $me->clientid,
                            'title'                 => $me->title,
                            'name'                  => $me->firstname . ' ' . $me->lastname,
                            'email'                 => '', // Email don't exist in api response
                            'firstname'             => $me->firstname,
                            'lastname'              => $me->lastname,
                            'primaryaccount'        => $me->primaryaccount,
                            'notes'                 => $me->notes,
                            'allprojects'           => $me->allprojects,
                            'private'               => $me->private,
                            'tips'                  => $me->tips,
                            'groupid'               => $me->groupid,
                            'group'                 => $me->group,
                            'client'                => $me->client,
                            'numlogins'             => $me->numlogins,
                            'numlogins'             => $me->numlogins,
                            'lastlogin'             => $me->lastlogin,
                            'timezone'              => $me->timezone,
                            'timezone_offset'       => $me->timezone_offset,
                            'clientlocalid'         => $me->clientlocalid,
                            'calendarorientation'   => $me->calendarorientation,
                            'editordisabled'        => $me->editordisabled,
                            'intervals_user_name'   =>  $me->username,
                            'intervals_api_key'     => $input['intervals_api_key'],
                            'password'              => bcrypt($input['intervals_api_key']),
                        ]);

                        //$token = $user->createToken('auth_token')->plainTextToken;
                        $token = Auth::login($user);

                        log_data([
                            'api_key' => $input['intervals_api_key'],
                            'log_type' => 'login',
                            'log_detail' => 'user login'
                        ]);

                        $response = [
                            'user'  => $user,
                            'token' => $token
                        ];
                        return response()->json($response, Response::HTTP_OK);
                        //dd($response);
                    } else {


                        $update_data = [
                            'intervals_id'          => $me->id,
                            'localid'               => $me->localid,
                            'clientid'              => $me->clientid,
                            'title'                 => $me->title,
                            'name'                  => $me->firstname . ' ' . $me->lastname,
                            'email'                 => '', // Email don't exist in api response
                            'firstname'             => $me->firstname,
                            'lastname'              => $me->lastname,
                            'primaryaccount'        => $me->primaryaccount,
                            'notes'                 => $me->notes,
                            'allprojects'           => $me->allprojects,
                            'private'               => $me->private,
                            'tips'                  => $me->tips,
                            'groupid'               => $me->groupid,
                            'group'                 => $me->group,
                            'client'                => $me->client,
                            'numlogins'             => $me->numlogins,
                            'numlogins'             => $me->numlogins,
                            'lastlogin'             => $me->lastlogin,
                            'timezone'              => $me->timezone,
                            'timezone_offset'       => $me->timezone_offset,
                            'clientlocalid'         => $me->clientlocalid,
                            'calendarorientation'   => $me->calendarorientation,
                            'editordisabled'        => $me->editordisabled,
                            'intervals_user_name'   =>  $me->username,
                            'intervals_api_key'     => $input['intervals_api_key'],
                            'password'              => bcrypt($input['intervals_api_key']),
                        ];

                        log_data([
                            'api_key' => $input['intervals_api_key'],
                            'log_type' => 'login',
                            'log_detail' => 'user login'
                        ]);

                        $user = $user_exists->update($update_data);

                        //$token = $user_exists->createToken('auth_token')->plainTextToken;
                        $enc_password   = bcrypt($input['intervals_api_key']);
                        $credentials    = array('intervals_api_key' => $input['intervals_api_key'], 'password' =>  $enc_password);

                        $token = Auth::login($user_exists);
                        //dd($token);
                        $response = [
                            'user'  => $user_exists,
                            'token' => $token
                        ];

                        return response()->json($response, Response::HTTP_OK);
                    }

                    /* {#299 // app\Http\Controllers\AuthController.php:82
                    +"id": "307191"
                    +"localid": "16"
                    +"clientid": null
                    +"title": ""
                    +"firstname": "Oliver"
                    +"lastname": "Gudmand"
                    +"primaryaccount": "f"
                    +"notes": ""
                    +"allprojects": "t"
                    +"private": "f"
                    +"tips": "f"
                    +"username": "oliver_gudmand"
                    +"groupid": "2"
                    +"group": "Administrator"
                    +"client": null
                    +"numlogins": "502"
                    +"lastlogin": "2022-10-06 15:52:37.141327"
                    +"timezone": "Europe/Brussels"
                    +"timezone_offset": "Brussels, Copenhagen, Madrid, Paris"
                    +"clientlocalid": null
                    +"calendarorientation": "1"
                    +"editordisabled": "0"
                    } */

                    //return response()->json($api_response, Response::HTTP_OK);
                }
            } else {
                // No API response
                $response = [
                    'api_key' => $input['intervals_api_key'],
                    'message' => 'No Record Found'
                ];

                return response()->json($response, Response::HTTP_NOT_FOUND);
            }
        }
    }

    public function logout()
    {
        Auth::logout();

        $response = [
            'status'    => 'success',
            'message'   => 'Successfully logged out',
        ];

        return response()->json($response, Response::HTTP_OK);
    }


    public function refresh()
    {
        return response()->json([
            'status'    => 'success',
            'user'      => Auth::user(),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type'  => 'bearer',
            ]
        ]);
    }
}
