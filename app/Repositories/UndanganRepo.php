<?php

namespace App\Repositories;

use App\Models\MasterUndanganModel;
use App\Models\KemenagVisitorModel;
use App\Models\Pembayaran;
use Illuminate\Support\Str;

use Carbon\Carbon;

use DB;

class UndanganRepo
{
    protected $model, $modelVisitor, $modelPembayaran;

    public function __construct(
        MasterUndanganModel $model,
        KemenagVisitorModel $modelVisitor,
        Pembayaran $modelPembayaran
    )
    {
        $this->model = $model;
        $this->modelVisitor = $modelVisitor;
        $this->modelPembayaran = $modelPembayaran;
    }
   
    
    public function distinct_unit_kerja()
    {
        return $this->model::select('unit_kerja')->distinct()->get();
    }
    public function find_satker_by_unit_kerja($unit_kerja)
    {
        return $this->model::where('unit_kerja', $unit_kerja)->get();
    }
    public function checkIsAvailable($data){
        return collect(DB::Select("SELECT mu.code, (mu.jml - IFNULL(kv.jml, 0)) sisa
            FROM master_undangan mu
            LEFT JOIN (
                SELECT v.satker AS satker, 
                    COUNT(code) jml 
                FROM kemenag_visitor v
                WHERE v.satker = '".$data["satker"]."'
                GROUP BY v.satker
            ) kv ON kv.satker = mu.code 
            WHERE mu.code = '".$data["satker"]."'"))->first();

    }
    public function upsertUndangan($data, string $uniqueKey = 'phone'){
        $data["code"] = (string) Str::uuid();
        return $this->modelVisitor->updateOrCreate(
            [$uniqueKey => $data[$uniqueKey]], 
            $data
        );
    }

    public function findByUserPassNew($data){
        return collect(DB::Select("
            SELECT l.* 
                FROM (
                    SELECT code,
                        CONCAT('0', SUBSTRING(REPLACE(REPLACE(REPLACE(phone, '-', ''), ' ', ''), '+62', '0'), 2)) phone, password
                    FROM kemenag_visitor
                ) n 
                JOIN kemenag_visitor AS l ON l.code = n.code
                WHERE n.phone = CONCAT('0', SUBSTRING(REPLACE(REPLACE(REPLACE('".$data["phone"]."', '-', ''), ' ', ''), '+62', '0'), 2)) 
                    AND n.password = '".$data["password"]."'
        "))->first();
    }
    public function findByUserPass($data){
        return $this->modelVisitor::where('phone', $data["phone"])
            ->where('password', $data["password"])->first();
           
    }

    public function findByPhone($data){
        return $this->modelVisitor::where('phone', $data["phone"])->count();
    }

    public function updateTicketByCode($data){
        return $this->modelVisitor->where("code", $data["code_qr"])
                ->update([
                    "ticket" => $data["ticket"],
                    "updated_at" =>  Carbon::now('Asia/Jakarta')
                ]);
    }

    public function findByCode($code){
        return $this->modelVisitor->where("code", $code)->first();
    } 
    public function findByTicketDb($ticket){
        return collect(DB::Select("SELECT kv.code, kv.ticket, kv.full_name, kv.organization, mu.satker, 
                kv.foto, kv.size_jersey, kv.grade, kv.role 
            FROM kemenag_visitor kv
            LEFT JOIN master_undangan AS mu ON mu.code = kv.satker
            WHERE kv.ticket = '".$ticket."'"))->first();
    }

    public function findUndanganByCode($code){
        return $this->model->where("code", $code)->first();
    }
    public function findStBayarByCodeUser($code){
        $dtbayar = collect(DB::select("SELECT mv.code, mu.st_hotel, IFNULL(p.bukti_bayar, '') 
            bukti_bayar, p.approve_by,
            CASE
                    WHEN IFNULL(p.st_valid,0) = 0 AND IFNULL(p.bukti_bayar, '') != '' AND IFNULL(p.approve_by, '') != '' THEN 'Pembayaran Tidak Valid' 
                    WHEN IFNULL(p.bukti_bayar, '') != '' AND IFNULL(p.approve_by, '') = '' THEN 'Proses Validasi'
                    WHEN IFNULL(p.bukti_bayar, '') != '' AND IFNULL(p.approve_by, '') != '' THEN 'Pembayaran Tervalidasi'
                    ELSE 'Tagihan Pembayaran'
            END st_pembayaran, tk.keterangan jenis_kamar, mk.harga
                ,3 per_day, mk.harga * 3 sub_total
            FROM kemenag_visitor mv
            JOIN master_undangan mu 
                    ON mu.code  = mv.satker 
            LEFT JOIN pembayaran AS p ON p.create_by = mv.code
            LEFT JOIN detail_grup_to_tipe_kamar AS tk ON tk.unit_kerja = mu.unit_kerja
            LEFT JOIN masterkamar AS mk ON mk.code = tk.keterangan 
            WHERE mv.code = '".$code."';
            "))->first();
        return $dtbayar;
    }

    public function upsertPembayaran($data){
        $data["code"] = $data["code"] ?? (string) Str::uuid();
        return $this->modelPembayaran->updateOrCreate(
            ["create_by" => $data["create_by"]], 
            $data
        );        
    }
    public function getAllAbsensi(){
        return collect(DB::select("SELECT da.code_qr, kv.full_name, mu.satker, kv.organization, da.code_post, mp.pos_name, da.created_at
            FROM dataabsen da
            LEFT JOIN kemenag_visitor AS kv ON kv.code = da.code_qr
            LEFT JOIN masterpos AS mp ON mp.Kode = da.code_post
            LEFT JOIN master_undangan AS mu ON mu.code = kv.satker
            WHERE mp.information = 'Kemenag'"));
    }
    public function getAllPeserta(){
        return collect(DB::select("SELECT kv.code, kv.full_name, kv.email, kv.phone, kv.password, kv.bank_account_name, kv.bank_number, kv.bank_name, 
                mu.satker, kv.organization, kv.foto,
                kv.created_at, km.keterangan jenis_kamar, 
                CASE WHEN mu.st_hotel = 1 THEN 'PEMBAYARAN PRIBADI' ELSE '-' END status_hotel
            FROM kemenag_visitor kv
            LEFT JOIN master_undangan AS mu ON mu.code = kv.satker
            LEFT JOIN detail_grup_to_tipe_kamar km ON km.unit_kerja = mu.unit_kerja"));
    }

    public function updateVisitor($code, $dataInput){
        $user = KemenagVisitorModel::findOrFail($code);
        $user->update($dataInput);
        return $user;
    }

    public function resetPass($data){
        return $this->modelVisitor->where("code", $data["code"])
                ->update([
                    "password" => $data["password"],
                    "updated_at" =>  Carbon::now('Asia/Jakarta')
                ]);
    }
    public function getAllPesertaForConnecting(){
        return collect(DB::Select("SELECT code, ticket, full_name, phone, email, organization, satker,
                CASE IFNULL(ticket,'') WHEN '' THEN 'NOT CONNECTED' ELSE 'CONNECTED' END status_connected
            FROM kemenag_visitor 
            ORDER BY ticket ASC"));

    }

}
