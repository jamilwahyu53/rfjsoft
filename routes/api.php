<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EntranceApiController;
use App\Http\Controllers\KemenagKontroller;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\EmoLandController;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//ENTRANCE
Route::post('/Register', [EntranceApiController::class, 'Register'])->name('Register');
Route::post('/PushQr', [EntranceApiController::class, 'PushQr'])->name('PushQr');

//KEMENAG
Route::post('/FirstLoadDtReg', [KemenagKontroller::class, 'FirstLoadDtReg'])->name('FirstLoadDtReg');
Route::post('/GetSatker', [KemenagKontroller::class, 'GetSatker'])->name('GetSatker');
Route::post('/InsertKemenagVisitor', [KemenagKontroller::class, 'InsertKemenagVisitor'])->name('InsertKemenagVisitor');
Route::post('/PushQrKemenag', [KemenagKontroller::class, 'PushQrKemenag'])->name('PushQrKemenag');
Route::post('/InsertBuktiTransfer', [KemenagKontroller::class, 'InsertBuktiTransfer'])->name('InsertBuktiTransfer');
Route::post('/ApprovePembayaran', [KemenagKontroller::class, 'ApprovePembayaran'])->name('ApprovePembayaran');
Route::post('/PushAbsensi', [KemenagKontroller::class, 'PushAbsensi'])->name('PushAbsensi');
Route::post('/GetProfile', [KemenagKontroller::class, 'GetProfile'])->name('GetProfile');
Route::post('/UpdateDataVisitor', [KemenagKontroller::class, 'UpdateDataVisitor'])->name('UpdateDataVisitor');


Route::get('/SendEmail', [EmailController::class, 'send'])->name('SendEmail');
Route::post('/sendOtp', [EmailController::class, 'sendOtp'])->name('sendOtp');
Route::post('/verifyOtp', [EmailController::class, 'verifyOtp'])->name('verifyOtp');

Route::get('/GetRealtime/{code_gate}', [KemenagKontroller::class, 'GetRealtime'])->name('GetRealtime');


Route::get('/ChangeTicket/{code_qr}/{ticket}', [KemenagKontroller::class, 'ChangeTicket'])->name('ChangeTicket');


//START EMO LAND PROJECT
Route::post('/login_emo_land', [EmoLandController::class, 'Login'])->name('login_emo_land');
Route::post('/register_emo_land', [EmoLandController::class, 'Register'])->name('register_emo_land');
Route::post('/upsert_video', [EmoLandController::class, 'UpsertVideo'])->name('upsert_video');
Route::post('/delete_video', [EmoLandController::class, 'DeteleVideo'])->name('delete_video');
Route::get('/get_video', [EmoLandController::class, 'GetAllVideo'])->name('get_all_video');
Route::post('/get_video_by_id', [EmoLandController::class, 'GetVideoById'])->name('get_video_by_id');
Route::post('/save-result-emo', [EmoLandController::class, 'SaveResult'])->name('SaveResult');