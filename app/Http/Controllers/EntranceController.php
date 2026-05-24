<?php

namespace App\Http\Controllers;
use Session;

use Illuminate\Http\Request;

use App\Models\masterstaff;
use App\Repositories\AdminRepo;
use App\Repositories\SidebarRepo;
use App\Repositories\VisitorRepo;
use App\Repositories\PosRepo;
use App\Repositories\AbsenRepo;

use App\Repositories\UndanganRepo;
use App\Models\KemenagVisitorModel;

class EntranceController extends Controller
{
    protected $adminRepo, $sidebarRepo, $visitorRepo;
    protected $posRepo, $absenRepo, $undanganRepo;

    public function __construct(
        AdminRepo $adminRepo,
        SidebarRepo $sidebarRepo,
        VisitorRepo $visitorRepo,
        PosRepo $posRepo,
        AbsenRepo $absenRepo,
        UndanganRepo $undanganRepo
        )
    {
        $this->adminRepo = $adminRepo;
        $this->sidebarRepo = $sidebarRepo;
        $this->visitorRepo = $visitorRepo;
        $this->posRepo = $posRepo;
        $this->absenRepo = $absenRepo;
        $this->undanganRepo = $undanganRepo;
    }
    public function Register()
    {
        return view('Entrance.Register', [
                "sidebars" => null,
            ]);
    }
    public function LoginEntrance()
    {
        /*
        $mySession = Session::get('dataUser');
        if(empty($mySession)) 
        {
            return view('Entrance.Login', [
                "sidebars" => null,
            ]);
        }
        else
        {
            return redirect('/DashAdminEntrance');
        }
            */
        return view('Entrance.Login', [
                "sidebars" => null,
            ]);
        
    }
    public function LoginEvent(Request $request)
    {
        $credentials = $request->all();
        try {
            $dtModel = new KemenagVisitorModel($credentials);

            $getUserRaw = $this->undanganRepo->findByUserPassNew($dtModel);
            $getUser = KemenagVisitorModel::make((array) $getUserRaw);
            $getStaff = $this->adminRepo->findByPhonePass($dtModel);

           
            if(!$getUserRaw && !$getStaff){
                return \Redirect::back()->withErrors('User atau Password tidak ditemukan');
            }

            $dtSession = array(
                "data" => $getUser ?? $getStaff,
                "stRole" => $getUserRaw ? 'VISITOR' : 'STAFF'
            );

            $request->session()->put('dataUser', $dtSession);
            
            if($getStaff){
                return redirect('/ValidasiPembayaran');
            }
            return redirect('/DashboardPengunjung');
          
            
            /*
            $dtModel = new masterstaff($credentials);
            $getUser = $this->adminRepo->findByUserAndPass($dtModel->toArray());
            if(!$getUser){
                return \Redirect::back()->withErrors('User atau Password tidak ditemukan');
            }
            $request->session()->put('dataUser', $getUser);
            return redirect('/DashAdminEntrance');
            */

        } catch (\Exception $e) {
            return \Redirect::back()->withErrors($e->getMessage());
        }
        
    }
    public function RegisterEntrance()
    {
        return view('Entrance.RegisterEntrance', [
                "sidebars" => null,
            ]);
        
    }
    public function RegisterEvent(Request $request)
    {
        $data = $request->all();
        try {
            $service = new AdminRepo(new masterstaff());
            $getPhone = $service->findByPhone($data["Phone"]);
            if($getPhone > 0){
                return \Redirect::back()->withErrors('Phone sudah terdaftar');
            }
            $dtModel = new masterstaff($data);
            $retInsert = $this->adminRepo->upsert($dtModel->toArray());
            return redirect('/LoginEntrance');
            
        } catch (\Exception $e) {
            return \Redirect::back()->withErrors($e->getMessage());
        }        
    }
    public function DashAdminEntrance(Request $request){
        $resData = $this->sidebarRepo->findByCompany("Entrance");
        $resDashboard = $this->absenRepo->getContentDashboard();
        return view('Entrance.Dashboard', [
            "sidebars" => $resData,
            "contentDashboard" => $resDashboard,
        ]);
    }
    public function DataAbsen(Request $request){
        $resData = $this->sidebarRepo->findByCompany("Entrance");
        $resAbsen = $this->absenRepo->findAbsensi();
        return view('Entrance.DataAbsen', [
            "sidebars" => $resData,
            "absens" => $resAbsen
        ]);
    }
    public function ScanQr(Request $request){
        $resData = $this->sidebarRepo->findByCompany("Entrance");
        $resPos = $this->posRepo->getPos();
        $mySession = Session::get('dataUser');
        return view('Entrance.ScanQr', [
            "sidebars" => $resData,
            "session" => $mySession,
            "pos" => $resPos,
        ]);
    }
    public function DataPeserta(Request $request){
        $resData = $this->sidebarRepo->findByCompany("Entrance");
        $resVisitor = $this->visitorRepo->getAll();
        $mySession = Session::get('dataUser');
        return view('Entrance.Visitor', [
            "sidebars" => $resData,
            "session" => $mySession,
            "visitors" => $resVisitor,
        ]);
    }


    public function visitorWithoutLogin(Request $request){
        //$resData = $this->sidebarRepo->findByCompany("Entrance");
        $resVisitor = $this->visitorRepo->getAll();
        //$mySession = Session::get('dataUser');
        return view('Entrance.VisitorWithoutLogin', [
            "sidebars" => null,
            "session" => null,
            "visitors" => $resVisitor,
        ]);
    }
}
