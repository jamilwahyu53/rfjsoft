<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\visitor;
use App\Repositories\VisitorRepo;
use App\Helpers\ApiResponse;


class EntranceApiController extends Controller
{
    protected $visitorRepo;

    public function __construct(VisitorRepo $visitorRepo)
    {
        $this->visitorRepo = $visitorRepo;
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

}
