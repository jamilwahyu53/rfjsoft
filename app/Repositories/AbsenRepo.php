<?php

namespace App\Repositories;

use App\Models\dataAbsen;
use App\Models\masterstaff;
use App\Models\group;
use Illuminate\Support\Str;
use DB;

class AbsenRepo
{
    protected $model, $modelStaff, $group;

    public function __construct(dataAbsen $model, 
        masterstaff $modelStaff,
        group $group)
    {
        $this->model = $model;
        $this->modelStaff = $modelStaff;
        $this->group = $group;
    }
   
    public function upsert(array $data, string $uniqueKey = 'Kode')
    {
        $data["Kode"] = (string) Str::uuid();
        return $this->model->updateOrCreate(
            [$uniqueKey => $data[$uniqueKey]], 
            $data
        );
    }

    public function findAbsensi(){
        $data = collect(DB::Select("SELECT a.Kode, v.qr_code, v.status_mesengger ormas, v.status_mesengger_other, v.full_name, v.gender, p.pos_name,
            CONVERT(DATE_ADD(a.created_at, INTERVAL 7 HOUR), DATETIME) Waktu
            FROM dataabsen a
            LEFT JOIN visitor AS v ON v.qr_code = a.code_qr
            LEFT JOIN masterpos AS p ON p.Kode = a.code_post"));
        return $data;
    }

    public function getContentDashboard(){
        $pendaftaran = collect(DB::select("SELECT COUNT(qr_code) Pendaftar FROM visitor"))->first();
        $jmlPerPos = collect(DB::select("
            SELECT COUNT(a.code_qr) Jml, a.code_post, MAX(ms.pos_name) pos_name
            FROM dataabsen a
            LEFT JOIN masterpos AS ms ON ms.Kode = a.code_post
            GROUP BY a.code_post"
        ));

        $staffs = $this->modelStaff->all()->reject(function($staff){
            return $staff->Position === 'Administrator';
        });
        return array(
            "pendaftar" => $pendaftaran, 
            "jmlPerPos" => $jmlPerPos, 
            "staffs" =>$staffs
        );
    }

    public function countQrInStage($data){
        return $this->model->where("code_qr", $data["code_qr"])
                        ->where("code_post", $data["code_post"])->count();
    }

    public function findCustWithTicketAndPost($data){
        return collect(DB::Select("SELECT kv.code, kv.ticket, kv.full_name, kv.organization, mu.satker, 
                    kv.foto, kv.size_jersey, kv.grade, kv.role, da.code_post,
                    DATE_ADD(da.created_at, INTERVAL 7 HOUR) created_at 
            FROM kemenag_visitor kv
            LEFT JOIN master_undangan AS mu ON mu.code = kv.satker
            LEFT JOIN dataabsen AS da ON da.code_qr = kv.code 
            WHERE kv.ticket = '".$data["ticket"]."' AND da.code_post = '".$data["code_post"]."'"))->first();
    }
    public function updateRealtime($code_gate, $data){
        return $this->group->where("code_gate", $code_gate)
            ->update([
            "code_visitor" => $data->code,
            "full_name" => $data->full_name,
            "organization" => $data->organization,
            "satker" => $data->satker,
        ]);
    }
    public function getRealtime($code_gate){
        return collect(DB::Select("SELECT g.code_gate, g.full_name, g.organization, IFNULL(mu.satker, g.satker) satker, kv.foto  
            FROM `groups` g
            LEFT JOIN master_undangan AS mu ON mu.code = g.satker
            LEFT JOIN kemenag_visitor AS kv ON kv.code = g.code_visitor OR kv.ticket = g.code_visitor
            where g.code_gate = '".$code_gate."'"))->first();
    }
    public function getDataAbsensiByCode($code){
        return collect(DB::Select("SELECT kv.full_name, kv.organization, mu.satker, IFNULL(mp.pos_name, da.code_post) session_name, DATE_ADD(da.created_at,INTERVAL 7 HOUR) created_at 
            FROM dataabsen da
            LEFT JOIN kemenag_visitor AS kv ON kv.code = da.code_qr
            LEFT JOIN master_undangan AS mu ON mu.code = kv.satker
            LEFT JOIN masterpos AS mp ON mp.Kode = da.code_post
            WHERE da.code_qr = '".$code."'
            ORDER BY da.created_at DESC"));
    }
    public function getDataAbsensiAll(){
        return collect(DB::Select("SELECT kv.full_name, kv.organization, mu.satker, IFNULL(mp.pos_name, da.code_post) session_name, DATE_ADD(da.created_at,INTERVAL 7 HOUR) created_at 
            FROM dataabsen da
            LEFT JOIN kemenag_visitor AS kv ON kv.code = da.code_qr
            LEFT JOIN master_undangan AS mu ON mu.code = kv.satker
            LEFT JOIN masterpos AS mp ON mp.Kode = da.code_post
            ORDER BY da.created_at DESC"));
    }
}
