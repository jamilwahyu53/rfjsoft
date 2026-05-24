<?php

namespace App\Repositories;

use App\Models\Pembayaran;
use Illuminate\Support\Str;

use Carbon\Carbon;

use DB;

class PembayaranRepo
{
    protected $model;

    public function __construct(
        Pembayaran $model
    )
    {
        $this->model = $model;
    }
   
    
    public function getAll()
    {
        return collect(DB::select("SELECT p.code, p.bukti_bayar, CASE WHEN p.st_valid = 0 THEN 'BELUM VALID' ELSE 'VALID' END st_valid, 
                p.create_date, p.approve_date, kv.full_name, ms.StaffName, kv.code code_v
            FROM pembayaran p
            LEFT JOIN kemenag_visitor AS kv ON kv.code = p.create_by
            LEFT JOIN masterstaff AS ms ON ms.Kode = p.approve_by
            ORDER BY p.create_date ASC"));
    }

    public function updateApproval($data){
        return $this->model->where("code", $data["code"])
            ->update([
                "st_valid" => $data["st_valid"],
                "approve_by" => $data["approve_by"],
                "approve_date" => DB::raw('NOW()')
            ]);
    }
    public function pembayaranIsDone($data){
        return collect(DB::select("SELECT kv.code, kv.organization, mu.satker, mu.st_hotel, p.st_valid, p.approve_by
            FROM kemenag_visitor kv
            LEFT JOIN master_undangan AS mu ON mu.code = kv.satker
            LEFT JOIN pembayaran AS p ON p.create_by = kv.code
            WHERE kv.ticket = '".$data."' AND mu.st_hotel = 1
                AND p.st_valid = 1 AND IFNULL(approve_by,'') != ''"))->count();
    }
    public function pembayaranIsDoneFromQr($data){
        return collect(DB::select("SELECT kv.code, kv.organization, mu.satker, mu.st_hotel, p.st_valid, p.approve_by
            FROM kemenag_visitor kv
            LEFT JOIN master_undangan AS mu ON mu.code = kv.satker
            LEFT JOIN pembayaran AS p ON p.create_by = kv.code
            WHERE kv.code = '".$data."' AND mu.st_hotel = 1
                AND p.st_valid = 1 AND IFNULL(approve_by,'') != ''"))->count();
    }
    public function isCustRentHotel($data){
        return collect(DB::Select("
            SELECT kv.code, kv.organization, mu.satker, mu.st_hotel
            FROM kemenag_visitor kv
            LEFT JOIN master_undangan AS mu ON mu.code = kv.satker
            WHERE kv.code = '".$data."' AND mu.st_hotel = 1
        "))->count();
    }
    
}
