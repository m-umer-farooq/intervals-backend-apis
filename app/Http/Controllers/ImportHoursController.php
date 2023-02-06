<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ImportHoursController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $errors = '';

        $client_id      = $request->client_id;
        $project_id     = $request->project_id;
        $module_id      = $request->module_id;
        $work_type_id   = $request->work_type_id;
        $task_id        = $request->task_id;
        $person_id      = $request->person_id;

        $validator = Validator::make($request->all(), [
            'client_id'     => 'required',
            'project_id'    => 'required',
            'module_id'     => 'required',
            'work_type_id'  => 'required',
            'task_id'       => 'required',
            'person_id'     => 'required',
            'importdata'    => 'required',
        ]);

        if ($validator->fails()) {

            $errors = $validator->errors();

            $validation_error_message = '';

            if ($errors->first('client_id')) {
                $validation_error_message .= 'Client, ';
            }
            if ($errors->first('project_id')) {
                $validation_error_message .= 'Project, ';
            }
            if ($errors->first('module_id')) {
                $validation_error_message .= 'Module, ';
            }
            if ($errors->first('work_type_id')) {
                $validation_error_message .= 'Work Type, ';
            }
            if ($errors->first('person_id')) {
                $validation_error_message .= 'Person, ';
            }
            if ($errors->first('task_id')) {
                $validation_error_message .= 'Task, ';
            }

            $validation_error_message = rtrim($validation_error_message, ', ');

            $response = [
                'code' => 200,
                'data' => '',
                'error_message' => $validation_error_message . ' fields are required.'
            ];

            return response()->json($response, 200);
        }

        if (!empty($request->importdata) && isset($request->importdata)) {

            $importData = $request->importdata;
            $importData = json_decode($importData);

            $validation = $this->dataValidation($importData);

            if ($validation) {

                $api_post_errors = '';
                $post_data = [];
                $write_data = [];

                $file_name = Auth::user()->intervals_api_key . '_' . date('YmdHis') . '.json';

                foreach ($importData as $key => $rows) {

                    if (!empty($rows) && count($rows) > 1) {

                        $date           = $rows[0];
                        $time           = $rows[1];
                        $description    = $rows[2];
                        $billable       = $rows[3];
                        $date_modified  = $rows[4];

                        $post_data = [
                            'projectid' => $project_id,
                            'moduleid' => $module_id,
                            'worktypeid' => $work_type_id,
                            'taskid' => $task_id,
                            'personid' => $person_id,
                            'date' => date('Y-m-d', strtotime($date)),
                            'time' => $time,
                            'description' => $description,
                            'billable' => ($billable == 'Yes') ? 't' : 'f',
                            'datemodified' => $date_modified
                        ];

                        $write_data[] = $post_data;

                        $api_response = makeIntervalsApiCall(Auth::user()->intervals_api_key, 'POST', 'time/', $post_data);

                        if ($api_response->code != 201) {
                            $api_post_errors .= '-';
                        }
                    }
                }

                if ($api_post_errors == '') {
                    $response = [
                        'code' => 200,
                        'data' => '',
                        'success_message' => 'Import Successful.'
                    ];

                    Storage::disk('local')->put($file_name, json_encode($write_data));

                    log_data([
                        'api_key' => Auth::user()->intervals_api_key,
                        'log_type' => 'hours_import',
                        'log_detail' => 'log in ' . $file_name . ' file'
                    ]);
                } else {
                    $response = [
                        'code' => 200,
                        'data' => '',
                        'error_message' => 'Error Importing Data.'
                    ];
                }



                /*  $response = [
                    'code' => 200,
                    'data' => $post_data,
                    'success_message' => 'Import Successfull.'
                ]; */

                return response()->json($response, 200);
            } else {

                $response = [
                    'code' => 200,
                    'data' => '',
                    'error_message' => 'Invlaid Data Provided.'
                ];

                return response()->json($response, 200);
            }
        }
    }

    public function dataValidation($data)
    {
        $errors = '';

        foreach ($data as $key => $rows) {

            if (!empty($rows) && count($rows) > 1) {

                $date           = $rows[0];
                $time           = $rows[1];
                $description    = $rows[2];
                $billable       = $rows[3];
                $date_modified  = $rows[4];

                if ((!isset($date) || $date == '') || (!isset($time) || $time == '') || (!isset($billable) || $billable == '')) {
                    $errors .= '-';
                }

                if (isset($date) && $date != '' && !preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date)) {
                    $errors .= '-';
                }

                if (isset($time) && $time != '' && !preg_match('/^\d*\.?\d*$/', $time)) {
                    $errors .= '-';
                }

                if (isset($date_modified) && $date_modified != '' && !preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date_modified)) {
                    $errors .= '-';
                }
            }
        }

        if ($errors == '') {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
