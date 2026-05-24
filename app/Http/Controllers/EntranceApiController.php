<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\visitor;
use App\Models\dataAbsen;

use App\Repositories\VisitorRepo;
use App\Repositories\AbsenRepo;

use App\Helpers\ApiResponse;


class EntranceApiController extends Controller
{
    protected $visitorRepo, $absenRepo;

    public function __construct(VisitorRepo $visitorRepo, AbsenRepo $absenRepo)
    {
        $this->visitorRepo = $visitorRepo;
        $this->absenRepo = $absenRepo;
    }


    function Register(Request $request){
        $data = $request->all();

        try {

            $service = new VisitorRepo(new visitor());
            $getPhone = $service->findByPhone($data["phone"]);
            if($getPhone > 0){
                return ApiResponse::error("No Telphone sudah digunakan");
            }
            $dtModel = new visitor($data);
            $retInsert = $this->visitorRepo->upsert($dtModel->toArray());
            
            if (! $retInsert) {
                return ApiResponse::error("Gagal Register");
            }
            
            return ApiResponse::success($retInsert, "Success");
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }           
    }
    
    function PushQr(Request $request){
        $data = array(
            "code_staff" => $request->post("Kode"),
            "code_qr" => $request->post("code_qr"),
            "code_post" => $request->post("code_post"),
        );
        try {
            $isFound = $this->visitorRepo->countByQr($data["code_qr"]);
            if($isFound === 0){
                return ApiResponse::error("QR tidak ditemukan");    
            }
            $dtModel = new dataAbsen($data);
            $retInsert = $this->absenRepo->upsert($dtModel->toArray());

            return ApiResponse::success($retInsert, "Success");
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        }           
    }

}
