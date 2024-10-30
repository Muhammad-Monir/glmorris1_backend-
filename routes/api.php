<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\SocialAuthController;
use App\Http\Controllers\Api\Backend\DataController;
use App\Http\Controllers\Api\Backend\LocationController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ROUTE FOR USER ACCOUNT LOGIN AND REGISTER
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::post('/forgot-password', [AuthController::class, 'sendResetOtp']);
Route::post('/verify-otp', [AuthController::class, 'verifyResetOtp']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);


// ROUTE FOR SOCIAL LOGIN
Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirectToProvider']);
Route::get('/auth/{provider}/callback', [SocialAuthController::class, 'handleProviderCallback']);


Route::group(['middleware' => 'jwt.auth'], function () {

    // ROUTE FOR USER PROFILE UPDATE
    Route::get('/profile/show', [AuthController::class, 'show']);
    Route::post('/update-profile', [AuthController::class, 'updateProfile']);
    Route::post('/update-password', [AuthController::class, 'updatePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    Route::post('/location/create', [LocationController::class, 'store']);
    Route::get('/location/show', [LocationController::class, 'show']);

    Route::get('/data/show/{id}', [DataController::class, 'show']);
    Route::post('/data/store', [DataController::class, 'store']);
    Route::post('/data/update/{id}', [DataController::class, 'update']);
    Route::delete('/data/delete/{id}', [DataController::class, 'destroy']);

    Route::get('/rooms', [DataController::class, 'index']);
    Route::get('/items', [DataController::class, 'getItemsByRoomId']);
    Route::get('/sections', [DataController::class, 'getSectionsByItemId']);

    Route::get('/search', [DataController::class, 'search']);

    Route::post('/section/update', [DataController::class, 'updateSection']);

    Route::post('/room/section/update/{id}', [DataController::class, 'SectionRoomUpdate']);
});
