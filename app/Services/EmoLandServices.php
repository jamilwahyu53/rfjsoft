<?php

namespace App\Services;
use Illuminate\Support\Str;
use App\Models\videosModel;
use App\Models\UserEmoLand;
use App\Helpers\ServiceResponse;

class EmoLandServices
{
    public function emoLandLogin(array $data)
    {
        $userEmoLand = UserEmoLand::where("user_id", $data["user_id"])
                        ->orWhere("full_name", $data["user_id"])
                        ->where("password", $data["password"])->first();
        return $userEmoLand ? 
            ServiceResponse::success($userEmoLand->toArray(), "Success") : 
            ServiceResponse::error("User ID is Exist");
        
    }
    public function emoLandRegister(array $data){
        $existed = UserEmoLand::where("user_id", $data["user_id"])->count();

        if($existed > 0){
            return ServiceResponse::error("User ID is Exist");
        }
        
        $createData = UserEmoLand::create($data);
        return ServiceResponse::success($createData->toArray(), "Success");
        
    }

    public function videoUpsert($data){
        if(empty($data["url"])){
            throw new Exception('URL is required');
        }

        $existed = videosModel::where("url", $data["url"])->first();
        $arrExisted = $existed ? $existed->toArray() : [];
        
        $arrayMerge = array_merge($arrExisted, $data);

        $fillableData = array_filter($arrayMerge, function ($v) {
                return $v !== null && $v !== '' && $v !== 'null';
            });
        
        if(!$existed){
            $fillableData["video_id"] = (string) Str::uuid();
            $retUpsert = videosModel::create($fillableData);

            return $retUpsert ? 
                ServiceResponse::success($retUpsert, "Success") : 
                ServiceResponse::error("Gagal Delete");
        } else {
            $fillableData['video_id'] = $arrExisted['video_id'];
        }

        $retUpsert = videosModel::updateOrCreate(
                [
                    'video_id' => $fillableData['video_id']
                ],
                $fillableData
            );

        return $retUpsert ? 
                ServiceResponse::success($retUpsert, "Success") : 
                ServiceResponse::error("Gagal Delete");

    }
    public function videoDelete($data){
        if(empty($data["video_id"])){
            throw new Exception('Video ID is required');
        }

        $retDel = videosModel::where("video_id", $data["video_id"])->delete();
        return $retDel ? 
            ServiceResponse::success(null, "Success") : 
            ServiceResponse::error("Gagal Delete");
    }
    public function getAllVideo(){
        $retVideos = videosModel::all();
         return $retVideos ? 
            ServiceResponse::success($retVideos->toArray(), "Success") : 
            ServiceResponse::error("Gagal Ambil Data Video");
    }
}