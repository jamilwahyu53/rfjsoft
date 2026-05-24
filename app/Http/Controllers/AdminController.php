<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\DataStaff;
use App\Models\MasterProfileCompany;
use App\Models\DataService;
use App\Models\Clients;
use App\Models\DataPortofolio;
use App\Models\DataTopMenu;
use App\Models\masterseoheader;
use App\Models\masterslideshow;
use Session;
use Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/* 
1. password register harus di hash dulu
2. column jwt harus pakai 'password' (besar kecil harus seperti itu)
*/
class AdminController extends Controller
{
    protected function GetSidebar()
    {
        $resTmp = DB::select("select * from mastersidebar");
        return json_encode($resTmp);
        
    }
    public function StaffResetPass()
    {
        return view('Admin.staffResetPass', [
            "sidebars" => null,
        ]);
    }
    public function ActionStaffResetPass(Request $request)
    {
        $resTmp = DB::select("SELECT Kode FROM masterstaff 
                                WHERE IFNULL(IsActive,0) = 1 
                                    AND (Email = '".$request->post('EmailOrPhone')."' OR Phone = '".$request->post('EmailOrPhone')."')
                                LIMIT 1");
        if(count($resTmp) > 0)
        {
            $newPass = Hash::make($request->post('Password'));
            $retData = DB::statement("UPDATE masterstaff SET password = '".$newPass."' 
                                    WHERE Kode = (
                                        SELECT Kode FROM masterstaff 
                                        WHERE IFNULL(IsActive,0) = 1 
                                            AND (Email = '".$request->post('EmailOrPhone')."' OR Phone = '".$request->post('EmailOrPhone')."')
                                        LIMIT 1
                                    )");
            if($retData)
            {
                return redirect('/Login');
            }
            else
            {
                return \Redirect::back()->withErrors('Ada Kesalahan, Coba Lagi!'); 
            }
        }
        else
        {
            return \Redirect::back()->withErrors('Account Tidak Ditemukan');
        }
    }
    public function Login()
    {
        $mySession = Session::get('dataUser');
        if(empty($mySession)) 
        {
            return view('Admin.login', [
                "sidebars" => null,
            ]);
        }
        else
        {
            return redirect('/DashAdmin');
        }
        
    }
    public function LoginStaff(Request $request)
    {
        $credentials = $request->only('Username', 'password');
        
        $rules = [
            'Username' => 'required|email',
            'password' => 'required',
        ];

        //echo Hash::make($credentials["password"]);
        
        /*
        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            return \Redirect::back()->withErrors($validator);
        }
            */
        
        try {
            $credentialsJwt["StaffName"] = $credentials["Username"];
            $credentialsJwt["password"] = $credentials["password"];

            $token = JWTAuth::attempt($credentialsJwt);

            if (! $token = JWTAuth::attempt($credentialsJwt)) {
                return \Redirect::back()->withErrors('Account Tidak Ditemukan');
            }
            else {
                $credentials["token"] = $token;
                $request->session()->put('dataUser', $credentials);
                return redirect('/DashAdmin');
            }
            
            
        } catch (JWTException $e) {
            return \Redirect::back()->withErrors('Ada Kesalahan Jaringan, Coba Lagi!');
        }
        

    }
    public function DashAdmin()
    {
        $resData = $this->GetSidebar();
        return view('Admin.dashboardAdmin', [
            "sidebars" => $resData,
        ]);
    }
    public function DataStaff()
    {
        $resData = $this->GetSidebar();
        //$resStaffs = DataStaff::all()->where('IsActive', '=', 1);
        $resStaffs = DataStaff::all();
        return view('Admin.dataStaff', [
            "sidebars" => $resData,
            "staffs" => $resStaffs,
        ]);
    }
    public function DataStaffWihtoutLogin()
    {
        return view('Admin.dataStaffWithoutLogin', [
            "sidebars" => null,
            "staffs" => null,
        ]);
    }
    public function SettingCompany()
    {
        $resData = $this->GetSidebar();
        $resProfileCompanies = MasterProfileCompany::all();
        return view('Admin.dataProfileCompany', [
            "sidebars" => $resData,
            "companies" => $resProfileCompanies,
        ]);
    }
    public function SettingService()
    {
        $resData = $this->GetSidebar();
        $resServices = DataService::all();
        return view('Admin.dataService', [
            "sidebars" => $resData,
            "services" => $resServices,
        ]);
    }
    
    public function InsertDetailCompany(Request $request)
    {
        try {
            $myData = $this->GenerateId("DPC", "detailcompany");
            $resInsert = DB::table('detailcompany')->insert([
                            'Kode' => $myData[0]->NewId,
                            'KodeCompany' => $request->post('KodeDetail'),
                            'Name' => $request->post('NameDetail'),
                            'Icon' => $request->post('IconDetail'),
                            'Link' => $request->post('LinkDetail')
                        ]);   
            if( $resInsert )
            {
                return \Redirect::back();
            }
            else 
            {
                return \Redirect::back()->withErrors('Ada Kesalahan Input Data, Coba Lagi!');
            }
        } catch (JWTException $e) {
            return \Redirect::back()->withErrors('Ada Kesalahan Jaringan, Coba Lagi!');
        }
    }
    public function InsertService(Request $request)
    {
        try {
            if($request->filled('Kode'))
            {
                $resInsert = DB::table('masterservice')
                            ->where('Kode',$request->post('Kode'))
                            ->update([
                                'ServiceName' => $request->post('ServiceName'),
                                'DetailService' => $request->post('DetailService'),
                                'Icon' => $request->post('Icon'),
                                'LinkDetail' => $request->post('LinkDetail')
                            ]);
            }
            else
            {
                $myData = $this->GenerateId("MS", "masterservice");
                $resInsert = DB::table('masterservice')->insert([
                                'Kode' => $myData[0]->NewId,
                                'ServiceName' => $request->post('ServiceName'),
                                'DetailService' => $request->post('DetailService'),
                                'Icon' => $request->post('Icon'),
                                'LinkDetail' => $request->post('LinkDetail')
                            ]);
            }
            if( $resInsert )
            {
                return \Redirect::back();
            }
            else 
            {
                return \Redirect::back()->withErrors('Ada Kesalahan Input Data, Coba Lagi!');
            }
        } catch (JWTException $e) {
            return \Redirect::back()->withErrors('Ada Kesalahan Jaringan, Coba Lagi!');
        }
    }
    public function InsertSettingCompany(Request $request)
    {
        try {

            if($request->filled('Kode'))
            {
                $resInsert = DB::table('masterprofilecompany')
                            ->where('Kode',$request->post('Kode'))
                            ->update([
                                'CompanyName' => $request->post('CompanyName'),
                                'Owner' => $request->post('Owner'),
                                'TagLine' => $request->post('TagLine'),
                                'Icon' => $request->post('Icon'),
                                'AboutUs' => $request->post('AboutUs')
                            ]);
            }            
            else 
            {
                $myData = $this->GenerateId("MPC", "masterprofilecompany");
                $resInsert = DB::table('masterprofilecompany')->insert([
                                'Kode' => $myData[0]->NewId,
                                'CompanyName' => $request->post('CompanyName'),
                                'Owner' => $request->post('Owner'),
                                'TagLine' => $request->post('TagLine'),
                                'Icon' => $request->post('Icon'),
                                'AboutUs' => $request->post('AboutUs')
                            ]);
            }
            if( $resInsert )
            {
                return \Redirect::back();
            }
            else 
            {
                return \Redirect::back()->withErrors('Ada Kesalahan Input Data, Coba Lagi!');
            }
            
        } catch (JWTException $e) {
            return \Redirect::back()->withErrors('Ada Kesalahan Jaringan, Coba Lagi!');
        }
    }
    public function GetServiceCompany(Request $request)
    {
        $kodeService = $request->post('Kode');  
        $resService = DataService::all()->where('Kode', '=',$kodeService)->first();
        echo json_encode($resService);
    }
    public function GetProfileCompany(Request $request)
    {
        $kodeCompany = $request->post('Kode');  
        $resCompany = MasterProfileCompany::all()->where('IsActive', '=', 1)
                                                ->where('Kode', '=',$kodeCompany)
                                                ->first();

        echo json_encode($resCompany);
    }
    public function SetActive(Request $request)
    {
        $retData = DB::statement("UPDATE ".$request->Table." SET IsActive = (
            SELECT (!IsActive) IsActive FROM ".$request->Table." WHERE Kode = '".$request->Kode."' ) 
            WHERE Kode = '".$request->Kode."'");

        if($retData)
        {
            return \Redirect::back();
        }
        else
        {
            return \Redirect::back()->withErrors('Ada Kesalahan, Coba Lagi!'); 
        }
    }
    public function InsertDataStaff(Request $request)
    {
        try {
            $myData = $this->GenerateId("ST", "masterstaff");
            $newPass = Hash::make($request->post('Password'));
            $resInsert = DB::table('masterstaff')->insert([
                            'Kode' => $myData[0]->NewId,
                            'StaffName' => $request->post('Username'),
                            'password' => $newPass,
                            'Phone' => $request->post('Phone'),
                            'Email' => $request->post('Email'),
                            'Address' => $request->post('Address'),
                            'Position' => $request->post('Position')
                        ]);
            if( $resInsert )
            {
                return \Redirect::back();
            }
            else 
            {
                return \Redirect::back()->withErrors('Ada Kesalahan Input Data, Coba Lagi!');
            }
            
            
        } catch (JWTException $e) {
            return \Redirect::back()->withErrors('Ada Kesalahan Jaringan, Coba Lagi!');
        }
    }
    public function InsertDataStaffNoLogin(Request $request)
    {
        try {
            $myData = $this->GenerateId("ST", "masterstaff");
            
            $newPass = Hash::make($request->post('Password'));
            $resInsert = DB::table('masterstaff')->insert([
                            'Kode' => $myData[0]->NewId,
                            'StaffName' => $request->post('Username'),
                            'password' => $newPass,
                            'Phone' => $request->post('Phone'),
                            'Email' => $request->post('Email'),
                            'Address' => $request->post('Address'),
                            'Position' => $request->post('Position')
                        ]);
            if( $resInsert )
            {
                return \Redirect::back();
            }
            else 
            {
                return \Redirect::back()->withErrors('Ada Kesalahan Input Data, Coba Lagi!');
            }
            
            
        } catch (JWTException $e) {
            return \Redirect::back()->withErrors('Ada Kesalahan Jaringan, Coba Lagi!');
        }
    }
    protected function GenerateId($Kode, $Table)
    {
        $resData = DB::select("SELECT IF( 
                                (
                                    SELECT COUNT(Kode) FROM ".$Table." 
                                    WHERE CONVERT(CreateDate, DATE) = CURDATE() AND IFNULL(IsActive,0) = 1 
                                ) > 0, 	
                                (
                                    SELECT	CONCAT('".$Kode."', '-', date_format(curdate(), '%Y%m%d'), '-', LPAD(CONVERT( CONVERT(
                                                        REPLACE(Kode,CONCAT('".$Kode."', '-', date_format(curdate(), '%Y%m%d'), '-'), '')
                                                        , INT) + 1, CHAR(100)), 4, 0) ) NewKode FROM ".$Table."
                                                    WHERE CONVERT(CreateDate, DATE) = CURDATE() AND IFNULL(IsActive,0) = 1	
                                                    ORDER BY CreateDate DESC LIMIT 1	
                                ),
                                (
                                    SELECT CONCAT('".$Kode."', '-', date_format(curdate(), '%Y%m%d'), '-', LPAD(1, 4, 0))
                                )				
                            ) NewId" );
        return $resData;
    }

    public function Logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/LoginKemenag');
    }

    public function DataClient()
    {
        $resData = $this->GetSidebar();
        $resClients = Clients::all();
        return view('Admin.dataClient', [
            "sidebars" => $resData,
            "clients" => $resClients,
        ]);
    }
    public function InsertClient(Request $request)
    {
        try {
            $file = $request->file('Logo');
            $path = 'assets\img\clients';
            $resultUpload = $file->move($path,$file->getClientOriginalName());

            if($request->filled('Kode'))
            {
                $resInsert = DB::table('masterclient')
                            ->where('Kode',$request->post('Kode'))
                            ->update([
                                'ClientName' => $request->post('ClientName'),
                                'Address' => $request->post('Address'),
                                'Phone' => $request->post('Phone'),
                                'NPWP' => $request->post('NPWP'),
                                'Email' => $request->post('Email'),
                                'Logo' => $file->getClientOriginalName()
                            ]);
            }            
            else
            {
                try {
                    $myData = $this->GenerateId("MC", "masterclient");
                    
                    $resInsert = DB::table('masterclient')->insert([
                                    'Kode' => $myData[0]->NewId,
                                    'ClientName' => $request->post('ClientName'),
                                    'Address' => $request->post('Address'),
                                    'Phone' => $request->post('Phone'),
                                    'NPWP' => $request->post('NPWP'),
                                    'Email' => $request->post('Email'),
                                    'Logo' => $file->getClientOriginalName()
                                ]);
                    
                } catch (JWTException $e) {
                    return \Redirect::back()->withErrors('Ada Kesalahan Jaringan, Coba Lagi!');
                }
            }
            if( $resInsert )
            {
                return \Redirect::back();
            }
            else 
            {
                return \Redirect::back()->withErrors('Ada Kesalahan Input Data, Coba Lagi!');
            }

        }catch (Throwable $e) {
            return \Redirect::back()->withErrors('Ada Kesalahan Jaringan, Coba Lagi!');
        }

    }
    public function GetDataClient(Request $request)
    {
        $kode = $request->post('Kode');  
        $res = Clients::all()->where('Kode', '=',$kode)->first();
        echo json_encode($res);
    }
    public function SettingPortofolio()
    {
        $resData = $this->GetSidebar();
        $resPortofolio["datas"] = DB::select("SELECT pf.*, ms.ServiceName
                                    FROM portofolio AS pf 
                                    LEFT JOIN masterservice AS ms ON ms.Kode = pf.KodeService");
        $resPortofolio["services"] = DB::select("SELECT Kode, ServiceName FROM masterservice WHERE IsActive = 1");
        return view('Admin.dataPortofolio', [
            "sidebars" => $resData,
            "portofolios" => $resPortofolio,
        ]);
    }
    public function InsertPortofolio(Request $request)
    {
        try {
            $file = $request->file('Photo');
            $path = 'assets\img\portfolio';
            $resultUpload = $file->move($path,$file->getClientOriginalName());

            if($request->filled('Kode'))
            {
                $resInsert = DB::table('portofolio')
                            ->where('Kode',$request->post('Kode'))
                            ->update([
                                'KodeService' => $request->post('ServiceName'),
                                'PortofolioName' => $request->post('PortofolioName'),
                                'Link' => $request->post('Link'),
                                'DetailPortofolio' => $request->post('DetailPortofolio'),
                                'Photo' => $file->getClientOriginalName()
                            ]);
            }            
            else
            {
                try {
                    $myData = $this->GenerateId("MP", "portofolio");
                    
                    $resInsert = DB::table('portofolio')->insert([
                                    'Kode' => $myData[0]->NewId,
                                    'KodeService' => $request->post('ServiceName'),
                                    'PortofolioName' => $request->post('PortofolioName'),
                                    'Link' => $request->post('Link'),
                                    'DetailPortofolio' => $request->post('DetailPortofolio'),
                                    'Photo' => $file->getClientOriginalName()
                                ]);
                    
                } catch (JWTException $e) {
                    return \Redirect::back()->withErrors('Ada Kesalahan Jaringan, Coba Lagi!');
                }
            }
            if( $resInsert )
            {
                return \Redirect::back();
            }
            else 
            {
                return \Redirect::back()->withErrors('Ada Kesalahan Input Data, Coba Lagi!');
            }

        }catch (Throwable $e) {
            return \Redirect::back()->withErrors('Ada Kesalahan Jaringan, Coba Lagi!');
        }

    }
    public function GetDataPortofolio(Request $request)
    {
        $kode = $request->post('Kode');  
        $res = DataPortofolio::all()->where('Kode', '=',$kode)->first();
        echo json_encode($res);
    }
    public function SettingTopMenu()
    {
        $resData = $this->GetSidebar();
        $resMenu["datas"] = DB::select("SELECT * FROM mastertopbar");
        return view('Admin.dataTopMenu', [
            "sidebars" => $resData,
            "menus" => $resMenu,
        ]);
    }
    public function InsertTopMenu(Request $request)
    {
        try {
            if($request->filled('Kode'))
            {
                $resInsert = DB::table('mastertopbar')
                            ->where('Kode',$request->post('Kode'))
                            ->update([
                                'Menu' => $request->post('Menu'),
                                'Link' => $request->post('Link'),
                                'Icon' => $request->post('Icon'),
                                'Isi' => $request->post('Isi'),
                            ]);
            }            
            else
            {
                    
                $myData = $this->GenerateId("TM", "mastertopbar");
                
                $resInsert = DB::table('mastertopbar')->insert([
                                'Kode' => $myData[0]->NewId,
                                'Menu' => $request->post('Menu'),
                                'Link' => $request->post('Link'),
                                'Icon' => $request->post('Icon'),
                                'Isi' => $request->post('Isi')
                            ]);
            }
                        
            if( $resInsert )
            {
                return \Redirect::back();
            }
            else 
            {
                return \Redirect::back()->withErrors('Ada Kesalahan Input Data, Coba Lagi!');
            }

        }catch (Throwable $e) {
            return \Redirect::back()->withErrors('Ada Kesalahan Jaringan, Coba Lagi!');
        }

    }
    public function GetDataTopMenu(Request $request)
    {
        $kode = $request->post('Kode');  
        $res = DataTopMenu::all()->where('Kode', '=',$kode)->first();
        echo json_encode($res);
    }
    public function SeoHeader(Request $request)
    {
        $resData = $this->GetSidebar();
        $resRow["datas"] = DB::select("SELECT * FROM masterseoheader");
        return view('Admin.dataSeoHeader', [
            "sidebars" => $resData,
            "datas" => $resRow,
        ]);
    }
    public function InsertSeoHeader(Request $request)
    {
        try {
            if($request->filled('Kode'))
            {
                $resInsert = DB::table('masterseoheader')
                            ->where('Kode',$request->post('Kode'))
                            ->update([
                                'LinkParam' => $request->post('LinkParam'),
                                'Isi' => $request->post('Isi'),
                                'Name' => $request->post('Nama'),
                                'Grup' => $request->post('Grup'),
                            ]);
            }            
            else
            {
                    
                $myData = $this->GenerateId("SEO", "masterseoheader");
                
                $resInsert = DB::table('masterseoheader')->insert([
                                'Kode' => $myData[0]->NewId,
                                'LinkParam' => $request->post('LinkParam'),
                                'Isi' => $request->post('Isi'),
                                'Name' => $request->post('Nama'),
                                'Grup' => $request->post('Grup')
                            ]);
            }
                        
            if( $resInsert )
            {
                return \Redirect::back();
            }
            else 
            {
                return \Redirect::back()->withErrors('Ada Kesalahan Input Data, Coba Lagi!');
            }

        }catch (Throwable $e) {
            return \Redirect::back()->withErrors('Ada Kesalahan Jaringan, Coba Lagi!');
        }
    }
    public function GetSeoHeader(Request $request)
    {
        $kode = $request->post('Kode');  
        $res = masterseoheader::all()->where('Kode', '=',$kode)->first();
        echo json_encode($res);
    }
    public function SlideShow(Request $request)
    {
        $resData = $this->GetSidebar();
        $resRow["datas"] = DB::select("SELECT * FROM masterslideshow");
        return view('Admin.dataSlideShow', [
            "sidebars" => $resData,
            "datas" => $resRow,
        ]);
    }
    public function InsertSlideshow(Request $request)
    {
        try {
            $file = $request->file('Link');
            $path = 'assets\img\slideshow';
            $resultUpload = $file->move($path,$file->getClientOriginalName());

            if($request->filled('Kode'))
            {
                $resInsert = DB::table('masterslideshow')
                            ->where('Kode',$request->post('Kode'))
                            ->update([
                                'Nama' => $request->post('NamaSlideshow'),
                                'Description' => $request->post('Description'),
                                'Link' => $file->getClientOriginalName()
                            ]);
            }            
            else
            {
                try {
                    $myData = $this->GenerateId("SS", "masterslideshow");
                    
                    $resInsert = DB::table('masterslideshow')->insert([
                                    'Kode' => $myData[0]->NewId,
                                    'Nama' => $request->post('NamaSlideshow'),
                                    'Description' => $request->post('Description'),
                                    'Link' => $file->getClientOriginalName()
                                ]);
                    
                } catch (JWTException $e) {
                    return \Redirect::back()->withErrors('Ada Kesalahan Jaringan, Coba Lagi!');
                }
            }
            if( $resInsert )
            {
                return \Redirect::back();
            }
            else 
            {
                return \Redirect::back()->withErrors('Ada Kesalahan Input Data, Coba Lagi!');
            }

        }catch (Throwable $e) {
            return \Redirect::back()->withErrors('Ada Kesalahan Jaringan, Coba Lagi!');
        }       
    }
    public function GetDataSlideshow(Request $request)
    {
        $kode = $request->post('Kode');  
        $res = masterslideshow::all()->where('Kode', '=',$kode)->first();
        echo json_encode($res);
    }
}