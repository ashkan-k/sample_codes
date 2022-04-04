<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CandidController;
use App\Http\Controllers\API\DocumentionController;
use App\Http\Controllers\API\InterestController;
use App\Http\Controllers\API\MutimediaController;
use App\Http\Controllers\API\ScoreController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\StateAndCityController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\RefereeController;
use App\Http\Controllers\API\SpecialController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\SeenDocumentController;
use App\Http\Controllers\API\CommentDocumentController;
use App\Http\Controllers\API\SettingController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\ScoreItemController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group(['middleware' => 'auth'], function () {
    Route::get('payment' , [PaymentController::class , 'Pay']);
    Route::get('export/BestCandidates' , [CandidController::class , 'ExportBestCandidates']);
    Route::get('best/scores' , [CandidController::class , 'GetBestCandidates']);

    Route::resource('referee', RefereeController::class);
    Route::resource('special', SpecialController::class);
    Route::resource('comment', CommentController::class);
    Route::resource('comment_document' , CommentDocumentController::class);
    Route::resource('seen_document' , SeenDocumentController::class);
    Route::resource('setting' , SettingController::class);
    Route::resource('scoreItem' , ScoreItemController::class);

    Route::get('states', [StateAndCityController::class, 'GetStates']);
    Route::get('cities/{state_id}', [StateAndCityController::class, 'GetCities']);

    Route::get('interest/{Interest_category_id}', [InterestController::class, 'GetInterest']);
    Route::get('referees/isSpecial' , [RefereeController::class , 'isSpecial']);
    Route::get('referees/isSpecial/check' , [RefereeController::class , 'isSpecial']);
    Route::post('referees/getspecials/{type?}' , [RefereeController::class , 'getspecials']);
    Route::post('referees/getOzviatTypesCount/' , [RefereeController::class , 'GetOzviatTypesCount']);
    Route::get('referees/{special_id}' , [RefereeController::class , 'GetRefereesBySpecialID']);
    Route::post('referees/ozviat_type/{type}' , [RefereeController::class , 'GetRefereesByOzviatType']);

    Route::post('dashboardData' , [UserController::class , 'dashboardData']);
    Route::post('getDatainMutimedia' , [UserController::class , 'getDatainMutimedia']);

    Route::post('payments' , [PaymentController::class , 'index']);
    Route::delete('payments/delete/{id}' , [PaymentController::class , 'destroy']);
    Route::delete('mutimedia/delete/{id}' , [MutimediaController::class , 'destroy']);
    Route::post('/mutimedia/detail/{id}' , [MutimediaController::class , 'detail']);
    Route::get('mutimedia/download/{id}' , [MutimediaController::class , 'download']);

//    Route::get('CanAddNewScoreItem' , [ScoreItemController::class , 'CanAddNewOne']);

    ###########      Prefix Score      ###########

    Route::group([
        'prefix' => 'score'
    ], function () {
        Route::post('store' , [ScoreController::class , 'store']);
        Route::post('getUser/{user}' , [ScoreController::class , 'getUser']);
        Route::post('getScore/{id}' , [ScoreController::class , 'getScore']);
        Route::post('create' , [ScoreController::class , 'store']);
        Route::post('set_verified' , [ScoreController::class , 'SetVerified']);
    });

    ///////////////////////////////////////////////////////////////////////////////////////////


    ###########      Prefix Mutimedia      ###########

    Route::group([
        'prefix' => 'mutimedia'
    ], function () {
        Route::post('store' , [MutimediaController::class , 'store']);
        Route::post('index' , [MutimediaController::class , 'index']);
    });

    ///////////////////////////////////////////////////////////////////////////////////////////

    ###########      Prefix Documention      ###########
    Route::group([
        'prefix' => 'documention'
    ], function () {
        Route::post('store', [DocumentionController::class, 'store']);
        Route::post('index', [DocumentionController::class, 'index']);
        Route::get('GetTypesCount/{id}' , [DocumentionController::class , 'GetTypesCount']);
        Route::post('edit/{documention}', [DocumentionController::class, 'edit']);
        Route::post('destroy/{documention}', [DocumentionController::class, 'destroy']);
        Route::get('candid/unseen/{id}/{type}' , [DocumentionController::class , 'GetUnSeenDocuments']);
        Route::get('candid/seen/{id}/{type}' , [DocumentionController::class , 'GetSeenDocuments']);
        Route::get('get_url/{id}' , [DocumentionController::class , 'GetDocumentUrlFile']);
    });

    ###########      Prefix Candid      ###########
    Route::group([
        'prefix' => 'candid'
    ], function () {
        Route::post('store', [CandidController::class, 'store']);
        Route::post('index' , [CandidController::class , 'index']);
        Route::post('{special_id}', [CandidController::class, 'GetCandidatesBySpecialID']);
        Route::post('show/{candid}' , [CandidController::class , 'show']);
        Route::patch('update/{candid}' , [CandidController::class , 'update']);
        Route::delete('destroy/{user}', [CandidController::class, 'destroy']);
        Route::get('best_referee/scores' , [CandidController::class  , 'GetBestCandidatesOfReferee']);
        Route::get('best/scores' , [CandidController::class , 'GetBestCandidates']);
        Route::post('comments/all/{id?}' , [CommentController::class , 'GetCommentsByCandidID']);
        Route::post('comments_documents/all/{id?}' , [CommentDocumentController::class , 'GetCommentsByCandidID']);
        Route::post('comments/storeAnswer' , [CommentController::class , 'StoreAnswer']);
        Route::post('comments_documents/storeAnswer' , [CommentDocumentController::class , 'StoreAnswer']);
        Route::post('Active/DeActive' , [CandidController::class , 'ChangeActiveStatus']);
    });
});

###########      Prefix auth      ###########
Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('confirmation', [AuthController::class, 'confirmation']);

    Route::post('register', [AuthController::class, 'register']);
    Route::post('resetPass', [AuthController::class, 'resetPass']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('islogin', [AuthController::class, 'islogin']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('profile', [AuthController::class, 'profile']);
    Route::post('updateProfile', [UserController::class, 'update']);
    Route::post('get_level' , [AuthController::class , 'GetLevel']);
    Route::post('get_payment' , [PaymentController::class , 'GetPaymentOfUser']);
    Route::post('changePassword' , [AuthController::class , 'SetNewPassword']);
});
