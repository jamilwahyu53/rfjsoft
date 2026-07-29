<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Services\EmoLandServices;
use App\Models\MasterEkspresiModel;
use App\Models\LuxandResultModel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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

    public function SaveResult(Request $request){
        $validator = Validator::make($request->all(), [
            'expression' => 'required|string',
            'value' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors());
        }

        try {
            $expression = strtolower(trim($request->input('expression')));
            $value = $request->input('value');

            $result = MasterEkspresiModel::whereRaw('LOWER(expression) = ?', [$expression])
                ->where('min_value', '<=', $value)
                ->where('max_value', '>=', $value)
                ->where('active', true)
                ->first();

            if (! $result) {
                return ApiResponse::error("Data ekspresi tidak ditemukan", null, 404);
            }

            return ApiResponse::success($result->toArray(), "Success");
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    public function SaveLuxand(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string',
            'stage' => 'required|integer',
            'mode' => 'required|string',
            'value' => 'required|numeric',
            'created_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors());
        }

        try {
            $data = $request->only([
                'user_id',
                'stage',
                'mode',
                'value',
                'created_at',
            ]);

            $data['uuid'] = (string) Str::uuid();
            $data['created_at'] = empty($data['created_at']) ? now() : $data['created_at'];

            $result = LuxandResultModel::create($data);

            return ApiResponse::success($result->toArray(), "Success");
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }

    public function GetLuxandAverage(Request $request){
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|string',
            'mode' => 'required|string',
        ]);

        if ($validator->fails()) {
            return ApiResponse::validationError($validator->errors());
        }

        try {
            $userId = $request->input('user_id');
            $mode = $request->input('mode');

            $latestResults = LuxandResultModel::where('user_id', $userId)
                ->where('mode', $mode)
                ->orderBy('created_at', 'desc')
                ->get()
                ->unique('stage')
                ->sortBy('stage')
                ->values();

            if ($latestResults->isEmpty()) {
                return ApiResponse::error("Data luxand tidak ditemukan", null, 404);
            }

            return ApiResponse::success([
                'user_id' => $userId,
                'mode' => $mode,
                'average_value' => $latestResults->avg('value'),
                'total_stage' => $latestResults->count(),
                'stages' => $latestResults->toArray(),
            ], "Success");
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }
    }
}
