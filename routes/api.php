<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TimeController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\WorkTypesController;
use App\Http\Controllers\ImportHoursController;
use App\Http\Controllers\ProjectModuleController;
use App\Http\Controllers\ProjectWorkTypesController;
use App\Http\Controllers\ProjectMilestonesController;

/*
Project API https://www.myintervals.com/api/resource.php?r=project
 */

Route::controller(ProjectController::class)->group(function () {
    Route::get('/project', 'all');
    Route::get('/project/{id}', 'retrieve');
    Route::post('/project', 'create');
    Route::delete('/project/{id}', 'delete');
    Route::delete('/project/module/{id}', 'module');
    //Route::get('/project/search/{search}', 'search');
});

/*
Project Module API https://api.myintervals.com/projectmodule/
 */
Route::controller(ProjectModuleController::class)->group(function () {
    Route::get('/projectmodule', 'all');
});


/*
Project Milestone API https://www.myintervals.com/api/resource.php?r=milestone
 */
Route::controller(ProjectMilestonesController::class)->group(function () {
    Route::get('/projectmilestone', 'all');
});

/*
Time API https://www.myintervals.com/api/resource.php?r=time
 */
Route::controller(TimeController::class)->group(function () {
    Route::get('/time', 'all');
    Route::post('/time', 'create');
    Route::post('/time/{id}/', 'update');
});

/*
Task API https://www.myintervals.com/api/resource.php?r=task
 */
Route::controller(TaskController::class)->group(function () {
    Route::get('/task', 'all');
    Route::get('/task/{id}', 'getTask');
});

/*
Import CSV Data
 */
Route::controller(ImportHoursController::class)->group(function () {
    Route::post('/importcsv', 'index');
});


/*
Time API https://www.myintervals.com/api/resource.php?r=time
 */
Route::controller(ReportController::class)->group(function () {
    Route::get('/report-fields', 'clients');
});


/*
Work Type API https://www.myintervals.com/api/resource.php?r=worktype
 */
Route::controller(WorkTypesController::class)->group(function () {
    Route::get('/worktype', 'worktype');
});


/*
Project Work Type API https://www.myintervals.com/api/resource.php?r=projectworktype
 */
Route::controller(ProjectWorkTypesController::class)->group(function () {
    Route::get('/projectworktype', 'worktype');
});

/*
Module API https://www.myintervals.com/api/resource.php?r=module
 */
Route::controller(ModuleController::class)->group(function () {
    Route::get('/module', 'all');
});

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});

Route::controller(LogController::class)->group(function () {
    Route::post('log-data', 'log_data');
});

/*
Person API https://www.myintervals.com/api/resource.php?r=person
*/
Route::controller(PersonController::class)->group(function () {
    Route::get('/person', 'show');
    Route::get('/person-search-{id}', 'search');
    Route::delete('/person-delete-{id}', 'delete');
    Route::post('/person', 'create');
    Route::put('/person-update-{id}', 'update');
    Route::delete('/person-delete-{id}', 'delete');
});

/*
Document API https://www.myintervals.com/api/resource.php?r=document
*/
Route::controller(DocumentController::class)->group(function () {
    Route::get('/document', 'all');
    Route::get('/document/search/', 'search');
    Route::get('/document/find/{id}', 'find');
    Route::get('/document/{id}/download', 'download');
});

/*
Contact Descriptor API  https://www.myintervals.com/api/resource.php?r=contactdescriptor
Contact Type API https://www.myintervals.com/api/resource.php?r=contacttype
*/
Route::get('/contact-descriptor-{contacttypeid}', [ContactController::class, 'descriptor']);
Route::get('/contact-type', [ContactController::class, 'contact_type']);

/*
Client API https://www.myintervals.com/api/resource.php?r=client
*/
Route::get('/client', [ClientController::class, 'index']);
Route::get('/client/search/{value}', [ClientController::class, 'search']);
