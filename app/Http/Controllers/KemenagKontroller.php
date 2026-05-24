<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\UndanganRepo;
use App\Repositories\SidebarRepo;
use App\Repositories\AdminRepo;
use App\Repositories\AbsenRepo;
use App\Repositories\PembayaranRepo;
use App\Repositories\PosRepo;

use App\Helpers\ApiResponse;
use App\Models\KemenagVisitorModel;
use App\Models\dataAbsen;
use App\Models\Pembayaran;

use DB;

class KemenagKontroller extends Controller
{
    protected $undanganRepo, $sidebarRepo, $adminRepo, $absenRepo, $paymentRepo,
        $posRepo;
     public function __construct(
        UndanganRepo $undanganRepo,
        SidebarRepo $sidebarRepo,
        AdminRepo $adminRepo,
        AbsenRepo $absenRepo,
        PembayaranRepo $paymentRepo,
        PosRepo $posRepo
        )
    {
        $this->undanganRepo = $undanganRepo;
        $this->sidebarRepo = $sidebarRepo;
        $this->adminRepo = $adminRepo;
        $this->absenRepo = $absenRepo;
        $this->paymentRepo = $paymentRepo;
        $this->posRepo = $posRepo;
    }
    public function Register()
    {
        return view('Entrance.kemenag.Register', [
                "sidebars" => null,
            ]);
    }
    public function RegisterTest()
    {
        return view('Entrance.kemenag.RegisterTest', [
                "sidebars" => null,
            ]);
    }
    function FirstLoadDtReg(Request $request){
        try {
            $dtUnitKerja = $this->undanganRepo->distinct_unit_kerja();
            
            return ApiResponse::success($dtUnitKerja, "Success");
        } catch (\Exception $e) {
            return ApiResponse::error("Internal Server Error");
        }   
    }
    function GetSatker(Request $request){
        $data = $request->all();
        try {
            $dtSatker = $this->undanganRepo->find_satker_by_unit_kerja($data["unit_kerja"] ?? null);
            return ApiResponse::success($dtSatker, "Success");
        } catch (\Exception $e) {
            return ApiResponse::error("Internal Server Error");
        }   
    }
    function InsertKemenagVisitor(Request $request){
        $data = $request->all();
        try {
            $dtAvailable = $this->undanganRepo->checkIsAvailable($data);
            if(!$dtAvailable){
                return ApiResponse::error("Internal server error");    
            }
            
            if((int) $dtAvailable->sisa <= 0){
                return ApiResponse::error("Batas jumlah perwakilan sudah penuh");
            } 

            $dtPhoneIsAvailable = $this->undanganRepo->findByPhone($data);

            if($dtPhoneIsAvailable > 0){
                return ApiResponse::error("No Telphone sudah digunakan");
            }

            $dtUpsert = $this->undanganRepo->upsertUndangan($data);
            

            return ApiResponse::success($dtUpsert, "Success");
        } catch (\Exception $e) {
            return ApiResponse::error($e->getMessage());
        } 
    }

    function Login(Request $request){
        return view('Entrance.kemenag.Login', [
                "sidebars" => null,
            ]);
    }
    function PostLoginKemenag(Request $request){
        $credentials = $request->all();
        try {
            $dtModel = new KemenagVisitorModel($credentials);
          
            //$getUser = $this->undanganRepo->findByUserPass($dtModel);
            $getUserRaw = $this->undanganRepo->findByUserPassNew($dtModel);
            $getUser = KemenagVisitorModel::make((array) $getUserRaw);
            $getStaff = $this->adminRepo->findByPhonePass($dtModel);
             
            if(!$getUserRaw && !$getStaff){
                return \Redirect::back()->withErrors('User atau Password tidak ditemukan');
            }

            if($getStaff){
                $dtSession = array(
                    "data" => $getStaff,
                    "stRole" => $getUserRaw ? 'VISITOR' : 'STAFF'
                );
            } else {
                $dtSession = array(
                    "data" => $getUser,
                    "stRole" => $getUserRaw ? 'VISITOR' : 'STAFF'
                );
            }
           
            $request->session()->put('dataUser', $dtSession);
            $userLogin = $request->session()->get('dataUser');
           
            if($getStaff){
                return redirect('/ValidasiPembayaran');
            }
            return redirect('/DashboardPengunjung');
         
           
        } catch (\Exception $e) {
            return \Redirect::back()->withErrors($e->getMessage());
        }
    }
    public function DashboardPengunjung(Request $request){
        $resData = $this->sidebarRepo->findByCompany("Kemenag");
        $userLogin = $request->session()->get('dataUser');
        $isStHotel = $this->undanganRepo->findStBayarByCodeUser($userLogin["data"]["code"] ?? "");
        $isStHotel->bukti_bayar = $this->base64Full($isStHotel->bukti_bayar ?? "");
        $dtAbsen = $this->absenRepo->getDataAbsensiByCode($userLogin["data"]["code"] ?? "");
        $myColumn = collect($dtAbsen->first())->keys();
        return view('Entrance.kemenag.Dashboard', [
            "sidebars" => $resData, 
            "userLogin" => $userLogin,
            "isStHotel" => $isStHotel,
            "dtAbsens" => $dtAbsen,
            "myColumn" => $myColumn
        ]);
    }
    public function ReRegis(Request $request){
        $resData = $this->sidebarRepo->findByCompany("Kemenag");
        $userLogin = $request->session()->get('dataUser');
        return view('Entrance.kemenag.ReRegis', [
            "sidebars" => $resData, 
            "userLogin" => $userLogin
        ]);
    }
    public function ReRegisScanner(Request $request){
        $resData = $this->sidebarRepo->findByCompany("Kemenag");
        $userLogin = $request->session()->get('dataUser');
        return view('Entrance.kemenag.ReRegisScanner', [
            "sidebars" => $resData, 
            "userLogin" => $userLogin
        ]);
    }
    
    public function PushQrKemenag(Request $request){
        $data = array(
            "code_staff" => $request->post("Kode"),
            "code_qr" => $request->post("code_qr"),
            "code_post" => $request->post("code_post"),
            "ticket" => $request->post("ticket"),
            "payment" => $request->post("payment"),
            "code_gate" => $request->post("code_gate")
        );

        DB::beginTransaction();
        try {

            $dtPelanggan = $this->undanganRepo->findByCode($data["code_qr"]);
            if(!$dtPelanggan){
                return ApiResponse::error("Pengunjung Tidak Ditemukan");
            }

            //APAKAH TERMASUK SEWA HOTEL MANDIRI
            $isRent = $this->paymentRepo->isCustRentHotel($data["code_qr"]);
            if($isRent > 0){
                //CARI PEMBAYARAN DAN APPROVE
                if(!empty($data["payment"])){
                    $paymentIsDone = $this->paymentRepo->pembayaranIsDoneFromQr($data["code_qr"]);
                    if($paymentIsDone === 0){
                        return ApiResponse::error("Stage Pembayaran Belum Selesai");
                    }
                }
            }

            $isFound = $this->absenRepo->countQrInStage($data);
            if ($isFound) {
                return ApiResponse::error("Stage Sudah Pernah Digunakan Pengunjung");
            }

            if($data["code_post"] === "ReRegis"){
                $updateTicket = $this->undanganRepo->updateTicketByCode($data);
            }

            $dtModel = new dataAbsen($data);
            $retInsert = $this->absenRepo->upsert($dtModel->toArray());
            if(!$retInsert){
                return ApiResponse::error("Tidak Ada Yg Terupdate");
            }

            $retRealtime = $this->absenRepo->updateRealtime($data["code_gate"], $dtPelanggan);


            $dtPelanggan->full_name = strtoupper($dtPelanggan->full_name);
            $getBase64 = $this->base64Full($dtPelanggan->foto);
            
            $dtPelanggan->foto = $dtPelanggan->foto !== ""
                ? $getBase64
                : "https://ui-avatars.com/api/?name=".$dtPelanggan->full_name."&background=random&color=fff";
            DB::commit();
            return ApiResponse::success($dtPelanggan, "Success");
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage());
        }   
    }
    public function PushAbsensi(Request $request){
        DB::beginTransaction();
        try {
            $data = array(
                "code_staff" => $request->post("Kode"),
                "code_post" => $request->post("code_post"),
                "ticket" => $request->post("ticket"),
                "code_gate" => $request->post("code_gate")
            );

            $dtPelanggan = $this->undanganRepo->findByTicketDb($data["ticket"]);
            if(!$dtPelanggan){
                return ApiResponse::error("Pengunjung Tidak Ditemukan");
            }

            $data["code_qr"] = $dtPelanggan->code;

            //FIND PELANGGAN DENGAN CURRENT POST
            $dtStaffAbsensi = $this->absenRepo->findCustWithTicketAndPost($data);

            $isRent = $this->paymentRepo->isCustRentHotel($data["code_qr"]);
            if($isRent > 0){
                //CARI PEMBAYARAN DAN APPROVE
                $paymentIsDone = $this->paymentRepo->pembayaranIsDone($data["ticket"]);
                if($paymentIsDone == 0){
                    return ApiResponse::error("Stage Pembayaran Belum Selesai", $errors = $dtStaffAbsensi);
                }
            }

            
            $isFound = $this->absenRepo->countQrInStage($data);
            if ($isFound) {
                return ApiResponse::error("Stage Sudah Pernah Digunakan Pengunjung", $errors = $dtStaffAbsensi);
            }

            $dtModel = new dataAbsen($data);
            $retInsert = $this->absenRepo->upsert($dtModel->toArray());
            if(!$retInsert){
                return ApiResponse::error("Tidak Ada Yg Terupdate");
            }

            $retRealtime = $this->absenRepo->updateRealtime($data["code_gate"], $dtPelanggan);

            DB::commit();
            return ApiResponse::success($dtPelanggan, "Success"); 
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage());
        }   
    }

    public function InsertBuktiTransfer(Request $request){
        $data = $request->all();
        DB::beginTransaction();
        try {
            $dtModel = new Pembayaran($data);
            $retInsert = $this->undanganRepo->upsertPembayaran($dtModel->toArray());
            if(!$retInsert){
                return ApiResponse::error("Tidak Ada Yg Terupdate");
            }
            DB::commit();
            return ApiResponse::success($data, "Success");
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage());
        }            
    }

    //DETECT MEME
    function base64Full($rawBase64)
    {
        if ($rawBase64 == "") {
            return "";
        } else {
            $data = base64_decode($rawBase64);

            // Deteksi tipe file dari signature
            if (substr($data, 0, 8) === "\x89PNG\x0D\x0A\x1A\x0A") {
                $mime = 'image/png';
            } elseif (substr($data, 0, 2) === "\xFF\xD8") {
                $mime = 'image/jpeg';
            } elseif (substr($data, 0, 3) === "GIF") {
                $mime = 'image/gif';
            } elseif (substr($data, 0, 2) === "BM") {
                $mime = 'image/bmp';
            } elseif (substr($data, 8, 4) === "WEBP") {
                $mime = 'image/webp';
            } elseif (substr($data, 0, 4) === "%PDF") {
                $mime = 'application/pdf';
            }  else {
                $mime = 'application/octet-stream'; // fallback
            }

            return "data:$mime;base64,$rawBase64";
        }

    }

    function ValidasiPembayaran(Request $request){
        $resData = $this->sidebarRepo->findByCompany("Kemenag");
        $userLogin = $request->session()->get('dataUser');
        $dtPembayaran = $this->paymentRepo->getAll();
        $myColumn = collect($dtPembayaran->first())->keys();

        return view('Entrance.kemenag.Pembayaran', [
            "sidebars" => $resData, 
            "userLogin" => $userLogin,
            "dtPayments" => $dtPembayaran,
            "myColumn" => $myColumn
        ]);
    }

    function ApprovePembayaran(Request $request){
        DB::beginTransaction();
        try {

            $data = array(
                "st_valid" => $request->post("status") == 'APPROVED' ? true : false,
                "code" => $request->post("code"),
                "approve_by" => $request->post("approve_by")
            );
        
            $dtModel = new Pembayaran($data);
           
            $retInsert = $this->paymentRepo->updateApproval($dtModel->toArray());
            if(!$retInsert){
                return ApiResponse::error("Tidak Ada Yg Terupdate");
            }
             
            DB::commit();
            return ApiResponse::success($retInsert, "Success");
        } catch (\Exception $e) {
            DB::rollBack();
            return ApiResponse::error($e->getMessage());
        }      
    }

    public function FormScan(Request $request){
        $resData = $this->sidebarRepo->findByCompany("Kemenag");
        $posData = $this->posRepo->findPosByEvent("Kemenag");
        $userLogin = $request->session()->get('dataUser');
        $groups = $this->posRepo->findAllGroup();
        return view('Entrance.kemenag.FormScan', [
            "sidebars" => $resData, 
            "userLogin" => $userLogin,
            "pos" => $posData,
            "groups" => $groups
        ]);
    }

    public function ReRegisCustWithScanner(Request $request){
        $resData = $this->sidebarRepo->findByCompany("Kemenag");
        $userLogin = $request->session()->get('dataUser');
        $groups = $this->posRepo->findAllGroup();
        return view('Entrance.kemenag.ReRegisCustWithScanner', [
            "sidebars" => $resData, 
            "userLogin" => $userLogin,
            "groups" => $groups
        ]);        
    }
    public function ViewScreen(Request $request){
        $userLogin = $request->session()->get('dataUser');
        return view('Entrance.kemenag.ViewScreen', [
            "sidebars" => null, 
            "userLogin" => $userLogin
        ]);        
    }
    public function ReportVisitor(Request $request){
        $resData = $this->sidebarRepo->findByCompany("Kemenag");
        $userLogin = $request->session()->get('dataUser');
        $dtVisitor = $this->undanganRepo->getAllAbsensi();
        $myColumn = collect($dtVisitor->first())->keys();
        return view('Entrance.kemenag.ReportVisitor', [
            "sidebars" => $resData, 
            "userLogin" => $userLogin,
            "dtVisitors" => $dtVisitor,
            "myColumn" => $myColumn
        ]);       
    }
    public function DataPeserta(Request $request){
        $resData = $this->sidebarRepo->findByCompany("Kemenag");
        $userLogin = $request->session()->get('dataUser');
        $dtVisitor = $this->undanganRepo->getAllPeserta();
        $myColumn = collect($dtVisitor->first())->keys();
        return view('Entrance.kemenag.ListPeserta', [
            "sidebars" => $resData, 
            "userLogin" => $userLogin,
            "dtVisitors" => $dtVisitor,
            "myColumn" => $myColumn
        ]);       
    }
    public function Profile(Request $request){
        $resData = $this->sidebarRepo->findByCompany("Kemenag");
        $userLogin = $request->session()->get('dataUser');
        return view('Entrance.kemenag.Profile', [
            "sidebars" => $resData, 
            "userLogin" => $userLogin
        ]);       
    }
    public function GetProfile(Request $request){
        $data = $request->all();
        try {
            $retData = $this->undanganRepo->findByCode($data["code"]);
            $retData->foto = $this->base64Full($retData->foto);
            $retData->paper_work = $this->base64Full($retData->paper_work);

            return ApiResponse::success($retData, "Success");
        } catch (\Exception $e) {
            return ApiResponse::error("Internal Server Error");
        }   
    }
    public function UpdateDataVisitor(Request $request){
        try {
            $data = array_filter($request->all(), function ($value) {
                return $value !== null && $value !== '';
            });

            //APAKAH TERMASUK SEWA HOTEL MANDIRI
            $isRent = $this->paymentRepo->isCustRentHotel($data["code"]);
            if($isRent > 0){
                
                //CHECK APAKAH ADA PEMBAYARAN ATAU BELUM
                $isPayment = $this->paymentRepo->pembayaranIsDoneFromQr($data["code"]);
                if($isPayment){
                    return ApiResponse::error("Sudah Ada Pembayaran, Tidak bisa Update data. Hubungi ADMIN");
                }
            }

            $updated = $this->undanganRepo->updateVisitor($data["code"], $data);
            return ApiResponse::success($updated, "Success");
        } catch (\Exception $e) {
            return ApiResponse::error("Internal Server Error");
        }
    }
    public function ResetPassword(Request $request){
        $userLogin = $request->session()->get('dataUser');
        $decUser = $userLogin["data"]["code"];

        $data = array(
            "password" => $request->post("password"),
            "code" => $decUser,
        );
        
        DB::beginTransaction();
        try {
            $updated = $this->undanganRepo->resetPass($data);

            DB::commit();
            return redirect('/Profile');
        } catch (\Exception $e) {
            DB::rollBack();
            return \Redirect::back()->withErrors('Gagal Update');
        }   
    }
    public function ConnectingQrToCard(Request $request){
        $resData = $this->sidebarRepo->findByCompany("Kemenag");
        $userLogin = $request->session()->get('dataUser');
        $dtVisitor = $this->undanganRepo->getAllPesertaForConnecting();
        $myColumn = collect($dtVisitor->first())->keys();
        return view('Entrance.kemenag.ConnectingQrToCard', [
            "sidebars" => $resData, 
            "userLogin" => $userLogin,
            "dtVisitors" => $dtVisitor,
            "myColumn" => $myColumn
        ]);       
    }
    function VerifyOtp(Request $request){
        return view('Entrance.kemenag.VerifyOtp', [
                "sidebars" => null,
            ]);
    }
    function RealTimeData($code_gate){
        return view('Entrance.kemenag.ViewScreenRealtime', [
            "sidebars" => null, 
            "code_gate" => $code_gate
        ]);   
    }
    function GetRealtime($code_gate){
        $dtPelanggan = $this->absenRepo->getRealtime($code_gate);
        $dtPelanggan->foto = $this->base64Full($dtPelanggan->foto);
        return ApiResponse::success($dtPelanggan, "Success");
    }
    function ReportTapping(Request $request){
        $resData = $this->sidebarRepo->findByCompany("Kemenag");
        $userLogin = $request->session()->get('dataUser');
        $dtAbsen = $this->absenRepo->getDataAbsensiAll();
        $myColumn = collect($dtAbsen->first())->keys();
        return view('Entrance.kemenag.ReportTapping', [
            "sidebars" => $resData, 
            "userLogin" => $userLogin,
            "dtAbsens" => $dtAbsen,
            "myColumn" => $myColumn
        ]); 
    }
    function ChangeTicket($code_qr, $ticket){
        $data = array(
            "code_qr" => $code_qr,
            "ticket" => $ticket
        );
        $retChange = $this->undanganRepo->updateTicketByCode($data);

        return ApiResponse::success($retChange, "Success"); 
    }






    public function DeleteVisitor(Request $request){
        $code_qr = $request->get("code_qr");
        echo json_encode($code_qr);
    }
    

}
