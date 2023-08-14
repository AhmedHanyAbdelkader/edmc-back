<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WorkPlaceController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\FollowUpController;
use App\Http\Controllers\ImportanceController;
use App\Http\Controllers\StatusController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    //Route::post('logout', 'AuthController@logout');
    //Route::post('refresh', 'AuthController@refresh');
   // Route::post('me', 'AuthController@me');

    Route::get('/users-with-sectors', [AuthController::class, 'getUsersWithSectors']);
    Route::get('/users-profile-data', [AuthController::class, 'getUserProfileData']);


    Route::post('/sector',[WorkPlaceController::class,'createSector']);
    Route::get('/sectors',[WorkPlaceController::class,'getSectorByNameOrId']);
    Route::get('/all-sectors',[WorkPlaceController::class,'getAllSectors']);

    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::put('/update-user-profile', [AuthController::class, 'updateProfile']);
    Route::post('/upload', [ImageController::class, 'uploadImage']);

    Route::get('/all-docs', [DocumentController::class, 'getAllDocuments']);
    Route::get('/all-sent-docs', [DocumentController::class, 'getAllSentDocuments']);
    Route::get('/all-received-docs', [DocumentController::class, 'getAllReceivedDocuments']);
    Route::post('/send-document', [DocumentController::class, 'sendDodument']);
    Route::get('/get-pdf', [DocumentController::class, 'getPdf']);

    Route::post('/send-follow-up', [FollowUpController::class,'sendFollowUp']);
    Route::get('/doc-follow-ups', [FollowUpController::class,'getDocFollowUps']);


    Route::post('/create-importance', [ImportanceController::class, 'createNewImportance']);
    Route::get('/get-importances', [ImportanceController::class, 'getAllImportances']);

    Route::get('/get-all-status', [StatusController::class, 'getAllStatus']);

});
