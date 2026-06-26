<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\rjsoft;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EntranceController;
use App\Http\Controllers\KemenagKontroller;
use App\Http\Controllers\EmoLandController;

use App\Models\group;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [rjsoft::class, 'index'])->name('index'); 
Route::get('/DetailService', [rjsoft::class, 'detailService'])->name('DetailService');
Route::post('/InsertMessageCust', [rjsoft::class, 'InsertMessageCust'])->name('InsertMessageCust')->middleware('csrf');

//gak di pakai
Route::post('/GetSeoHeader', [rjsoft::class, 'GetSeoHeader'])->name('GetSeoHeader')->middleware('csrf');


Route::group(['middleware' => ['usersession']], function() {
    /* ROUTE FOR ADMIN */
    Route::get('/DashAdmin', [AdminController::class, 'DashAdmin'])->name('DashboardAdmin'); 
    Route::get('/DataStaff', [AdminController::class, 'DataStaff'])->name('DataStaff'); 
    Route::get('/SettingCompany', [AdminController::class, 'SettingCompany'])->name('SettingCompany'); 
    Route::get('/test/{Kode}/{Table}', [AdminController::class, 'GenerateId']); 
    Route::get('/SetActive/{Kode}/{Table}', [AdminController::class, 'SetActive'])->name('SetActive'); 
    Route::get('/SettingService', [AdminController::class, 'SettingService'])->name('SettingService'); 
    Route::get('/DataClient', [AdminController::class, 'DataClient'])->name('DataClient'); 
    Route::get('/SettingPortofolio', [AdminController::class, 'SettingPortofolio'])->name('SettingPortofolio'); 
    Route::get('/SettingTopMenu', [AdminController::class, 'SettingTopMenu'])->name('SettingTopMenu'); 
    Route::get('/SeoHeader', [AdminController::class, 'SeoHeader'])->name('SeoHeader'); 
    Route::get('/SlideShow', [AdminController::class, 'SlideShow'])->name('SlideShow'); 

    //with csrf data
    Route::post('/InsertDataStaff', [AdminController::class, 'InsertDataStaff'])->name('InsertDataStaff')->middleware('csrf');
    Route::post('/InsertSettingCompany', [AdminController::class, 'InsertSettingCompany'])->name('InsertSettingCompany')->middleware('csrf');
    Route::post('/GetProfileCompany', [AdminController::class, 'GetProfileCompany'])->name('GetProfileCompany')->middleware('csrf');
    Route::post('/InsertDetailCompany', [AdminController::class, 'InsertDetailCompany'])->name('InsertDetailCompany')->middleware('csrf');
    Route::post('/InsertService', [AdminController::class, 'InsertService'])->name('InsertService')->middleware('csrf');
    Route::post('/GetServiceCompany', [AdminController::class, 'GetServiceCompany'])->name('GetServiceCompany')->middleware('csrf');
    Route::post('/InsertClient', [AdminController::class, 'InsertClient'])->name('InsertClient')->middleware('csrf');
    Route::post('/GetDataClient', [AdminController::class, 'GetDataClient'])->name('GetDataClient')->middleware('csrf');
    Route::post('/InsertPortofolio', [AdminController::class, 'InsertPortofolio'])->name('InsertPortofolio')->middleware('csrf');
    Route::post('/GetDataPortofolio', [AdminController::class, 'GetDataPortofolio'])->name('GetDataPortofolio')->middleware('csrf');
    Route::post('/InsertTopMenu', [AdminController::class, 'InsertTopMenu'])->name('InsertTopMenu')->middleware('csrf');
    Route::post('/GetDataTopMenu', [AdminController::class, 'GetDataTopMenu'])->name('GetDataTopMenu')->middleware('csrf');
    Route::post('/InsertSeoHeader', [AdminController::class, 'InsertSeoHeader'])->name('InsertSeoHeader')->middleware('csrf');
    Route::post('/GetSeoHeader', [AdminController::class, 'GetSeoHeader'])->name('GetSeoHeader')->middleware('csrf');
    Route::post('/InsertSlideshow', [AdminController::class, 'InsertSlideshow'])->name('InsertSlideshow')->middleware('csrf');
    Route::post('/GetDataSlideshow', [AdminController::class, 'GetDataSlideshow'])->name('GetDataSlideshow')->middleware('csrf');
     
});

Route::get('/Login', [AdminController::class, 'Login'])->name('login');
Route::get('/StaffResetPass', [AdminController::class, 'StaffResetPass'])->name('StaffResetPass');
Route::post('/LoginStaff', [AdminController::class, 'LoginStaff'])->name('LoginStaff');
Route::post('/ActionStaffResetPass', [AdminController::class, 'ActionStaffResetPass'])->name('ActionStaffResetPass');


Route::get('/DataStaffWihtoutLogin', [AdminController::class, 'DataStaffWihtoutLogin'])->name('DataStaffWihtoutLogin');
Route::post('/InsertDataStaffNoLogin', [AdminController::class, 'InsertDataStaffNoLogin'])->name('InsertDataStaffNoLogin');

Route::get('/Logout', [AdminController::class, 'Logout'])->name('Logout'); 
    

//ENTRANCE
Route::get('/Register', [EntranceController::class, 'Register'])->name('Register');
Route::get('/LoginEntrance', [EntranceController::class, 'LoginEntrance'])->name('LoginEntrance');
Route::get('/RegisterEntrance', [EntranceController::class, 'RegisterEntrance'])->name('RegisterEntrance');
Route::post('/RegisterEntrance', [EntranceController::class, 'RegisterEvent'])->name('RegisterEvent');
Route::post('/LoginEntrance', [EntranceController::class, 'LoginEvent'])->name('LoginEvent');

Route::group(['middleware' => ['entrancesession']], function() {
//ENTRANCE ADMIN
    Route::get('/DashAdminEntrance', [EntranceController::class, 'DashAdminEntrance'])->name('DashboardAdminEntrance'); 
    Route::get('/DataAbsen', [EntranceController::class, 'DataAbsen'])->name('DataAbsen'); 
    Route::get('/ScanQr', [EntranceController::class, 'ScanQr'])->name('ScamQr'); 
    Route::get('/DataPeserta', [EntranceController::class, 'DataPeserta'])->name('DataPeserta'); 

    //KEMENAG
    Route::get('/DashboardPengunjung', [KemenagKontroller::class, 'DashboardPengunjung'])->name('DashboardPengunjung');

});

//KEMENAG
Route::get('/RegisterEntKemenag', [KemenagKontroller::class, 'Register'])->name('RegisterEntKemenag');
Route::get('/LoginKemenag', [KemenagKontroller::class, 'Login'])->name('LoginKemenag');
Route::post('/PostLoginKemenag', [KemenagKontroller::class, 'PostLoginKemenag'])->name('PostLoginKemenag');


Route::group(['middleware' => ['kemenagsession']], function() {

    //KEMENAG
    Route::get('/DashboardPengunjung', [KemenagKontroller::class, 'DashboardPengunjung'])->name('DashboardPengunjung');
    Route::get('/ReRegis', [KemenagKontroller::class, 'ReRegis'])->name('ReRegis');
    Route::get('/ReRegisCustWithScanner', [KemenagKontroller::class, 'ReRegisCustWithScanner'])->name('ReRegisCustWithScanner');
    Route::get('/ReRegisScanner', [KemenagKontroller::class, 'ReRegisScanner'])->name('ReRegisScanner');
    Route::get('/ValidasiPembayaran', [KemenagKontroller::class, 'ValidasiPembayaran'])->name('ValidasiPembayaran');
    Route::get('/FormScan', [KemenagKontroller::class, 'FormScan'])->name('FormScan');
    Route::get('/ReportVisitor', [KemenagKontroller::class, 'ReportVisitor'])->name('ReportVisitor');
    Route::get('/DataPeserta', [KemenagKontroller::class, 'DataPeserta'])->name('DataPeserta');
    Route::get('/Profile', [KemenagKontroller::class, 'Profile'])->name('Profile');
    Route::get('/ConnectingQrToCard', [KemenagKontroller::class, 'ConnectingQrToCard'])->name('ConnectingQrToCard');
    Route::post('/ResetPassword', [KemenagKontroller::class, 'ResetPassword'])->name('ResetPassword');
    Route::get('/ReportTapping', [KemenagKontroller::class, 'ReportTapping'])->name('ReportTapping');
    Route::get('/DeleteVisitor/{code_qr}', [KemenagKontroller::class, 'DeleteVisitor'])->name('DeleteVisitor');
    
});

Route::get('/ViewScreen', [KemenagKontroller::class, 'ViewScreen'])->name('ViewScreen');
Route::get('/VerifyOtp', [KemenagKontroller::class, 'VerifyOtp'])->name('VerifyOtp');


Route::get('/ScreenView/{code_gate}', [KemenagKontroller::class, 'RealTimeData'])->name('RealTimeData');
Route::get('/visitorWithoutLogin', [EntranceController::class, 'visitorWithoutLogin'])->name('visitorWithoutLogin');



//emo_land 
Route::get('/Login-EmoLand', [EmoLandController::class, 'LoginScreen'])->name('LoginScreen');