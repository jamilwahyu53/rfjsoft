<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Services\EmoLandServices;

class EmoLandController extends Controller
{
    protected $emo_land_services;

    public function __construct(EmoLandServices $emo_land_services)
    {
        $this->emo_land_services = $emo_land_services;
    }

    public function Login(Request $request){
        $data = $request->all();
        try {
            $retUser = $this->emo_land_services->emoLandLogin($data);

            return !$retUser["status"] ? 
                ApiResponse::error($retUser["message"]) :
                ApiResponse::success($retUser["data"], "Success");

        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }  
    }

    public function Register(Request $request){
        $data = $request->all();

        try {
            $retUser = $this->emo_land_services->emoLandRegister($data);

            if (! $retUser["status"]) {
                return ApiResponse::error($retUser["message"]);
            }
            
            return ApiResponse::success($retUser["data"], "Success");
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }  
    }

    public function UpsertVideo(Request $request){
        $data = $request->all();

        try {
            $retUser = $this->emo_land_services->videoUpsert($data);

            if (! $retUser["status"]) {
                return ApiResponse::error($retUser["message"]);
            }
            
            return ApiResponse::success($retUser["data"], "Success");
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }  
    }
    public function DeteleVideo(Request $request){
        $data = $request->all();
        try {
            $retUser = $this->emo_land_services->videoDelete($data);

            return ApiResponse::success($retUser, "Success");

            if (! $retUser["status"]) {
                return ApiResponse::error($retUser["message"]);
            }
            
            return ApiResponse::success($retUser["data"], "Success");
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        } 
    }
    public function GetAllVideo(Request $request){
        try {
            $retVideos = $this->emo_land_services->getAllVideo();

            return !$retVideos["status"] ? 
                ApiResponse::error($retVideos["message"]) :
                ApiResponse::success($retVideos["data"], "Success");
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        } 
    }
    public function GetVideoById(Request $request){
        $data = $request->all();
        try {
            $retVideos = $this->emo_land_services->getVideobyId($data);

            return !$retVideos["status"] ? 
                ApiResponse::error($retVideos["message"]) :
                ApiResponse::success($retVideos["data"], "Success");
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        } 
    }

    public function LoginScreen()
    {
        $mySession = Session::get('dataUser');
        if(empty($mySession)) 
        {
            return view('EmoLand.login', [
                "sidebars" => null,
            ]);
        }
        else
        {
            return redirect('/DashAdmin');
        }
        
    }
}
