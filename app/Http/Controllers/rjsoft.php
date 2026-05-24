<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\MasterProfileCompany;
use App\Models\masterseoheader;
use App\Models\masterslideshow;

class rjsoft extends Controller
{
    public function InsertMessageCust(Request $request)
    {
        try {
            $myData = $this->GenerateId("MC", "MessageFromCust");
           
            $resInsert = DB::table('MessageFromCust')->insert([
                            'Kode' => $myData[0]->NewId,
                            'CustName' => $request->post('name'),
                            'EmailCust' => $request->post('email'),
                            'SubjectCust' => $request->post('subject'),
                            'Message' => $request->post('message')
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

    public function index()
    {
        $resProfileCompanies = DB::select("SELECT * FROM masterprofilecompany 
                                WHERE IsActive = 1 
                                ORDER BY CreateDate DESC 
                                LIMIT 1");
        $resServices = DB::select("SELECT * FROM masterservice WHERE IsActive = 1");
        $resClients = DB::select("SELECT * FROM masterclient WHERE IsActive = 1");
        $resPortfolios = DB::select("SELECT REPLACE(ms.ServiceName, ' ', '_') ServiceNameClass, ms.ServiceName, p.*
                            FROM portofolio AS p
                            LEFT JOIN masterservice AS ms ON ms.Kode = p.KodeService
                            WHERE p.IsActive = 1 AND ms.IsActive = 1");
        $resNamePortfolios = DB::select("SELECT REPLACE(ms.ServiceName, ' ', '_') ServiceNameClass, 
                                    MAX(ServiceName) ServiceName
                                FROM portofolio AS p
                                LEFT JOIN masterservice AS ms ON ms.Kode = p.KodeService
                                WHERE p.IsActive = 1 AND ms.IsActive = 1
                                GROUP BY ms.ServiceName");
        $resStaff = DB::select("SELECT StaffName, Photo, Position, Phone, Email FROM masterstaff WHERE IsActive = 1");
        $resDetailCompany = DB::select("SELECT Name, Icon, Link FROM detailcompany
                                WHERE KodeCompany = 
                                (
                                    SELECT Kode FROM masterprofilecompany 
                                    WHERE IsActive = 1 
                                    ORDER BY CreateDate DESC 
                                    LIMIT 1
                                ) AND IsActive = 1");
        $resTopMenu = DB::select("SELECT Menu, Link, Icon, Isi FROM mastertopbar WHERE IsActive = 1");
        $resSeoHeader = masterseoheader::all()->where('IsActive', '=', 1)->where('LinkParam', '=', '/');
        $resSlideshow = masterslideshow::all()->where('IsActive', '=', 1);
        return view('home', [
            "sliding" => true,
            "tagline" => $resProfileCompanies[0] ?? null,
            "companies" => $resProfileCompanies[0] ?? null,
            "services" => $resServices,
            "clients" => $resClients,
            "portfolios" => $resPortfolios,
            "portfoliosName" => $resNamePortfolios,
            "staffs" => $resStaff,
            "detailsCompanies" => $resDetailCompany,
            "topMenus" => $resTopMenu,
            "seoHeaders" => $resSeoHeader,
            "slideShows" => $resSlideshow,
        ]);
    }
    public function detailService()
    {
        return view('detailService', ["sliding" => false]);
    }
    public static function filteringObjReal($obj, $myValue)
    {
        foreach($obj as $row)
        {
            return $row->Name == $myValue ? $row->Isi : null;
        }
    }
    public static function filteringObj($obj, $myValue)
    {
        $resFilter = array_filter($obj, function ($item) use ($myValue) {
            return $item->Name == $myValue; 
        });
        if (count($resFilter) > 0)
        {
            foreach($resFilter as $row)
            {
                return $row->Link;
            }
        }
        else
        {
            return null;
        }

        
    }
    protected function GenerateId($Kode, $Table)
    {
        $resData = DB::select("SELECT IF( 
                                (
                                    SELECT COUNT(Kode) FROM ".$Table." 
                                    WHERE CONVERT(CreateDate, DATE) = CURDATE() 
                                ) > 0, 	
                                (
                                    SELECT	CONCAT('".$Kode."', '-', date_format(curdate(), '%Y%m%d'), '-', LPAD(CONVERT( CONVERT(
                                                        REPLACE(Kode,CONCAT('".$Kode."', '-', date_format(curdate(), '%Y%m%d'), '-'), '')
                                                        , INT) + 1, CHAR(100)), 4, 0) ) NewKode FROM ".$Table."
                                                    WHERE CONVERT(CreateDate, DATE) = CURDATE() 
                                                    ORDER BY CreateDate DESC LIMIT 1	
                                ),
                                (
                                    SELECT CONCAT('".$Kode."', '-', date_format(curdate(), '%Y%m%d'), '-', LPAD(1, 4, 0))
                                )				
                            ) NewId" );
        return $resData;
    }
    
}
