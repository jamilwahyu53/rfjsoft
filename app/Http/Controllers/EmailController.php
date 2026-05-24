<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use App\Mail\TestEmail;
use App\Models\MasterOtp;
use App\Models\KemenagVisitorModel;

use Illuminate\Support\Str;
use Carbon\Carbon;



class EmailController extends Controller
{
    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
     
        // generate OTP 6 digit
        $otp_code = rand(100000, 999999);

        // simpan ke database
        MasterOtp::create([
            'email' => $request->email,
            'otp' => $otp_code,
            'expires_at' => Carbon::now()->addMinutes(5),
            'used' => false,
        ]);

        // kirim email OTP
        Mail::to($request->email)->send(new TestEmail($otp_code));

        return redirect('/VerifyOtp');
    }
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'otp' => 'required',
            'newPass' => 'required',
        ]);

        $otp_record = MasterOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('used', false)
            ->where('expires_at', '>=', now())
            ->first();

        if (!$otp_record) {
            return \Redirect::back()->withErrors('Email/ OTP tidak valid atau sudah kadaluarsa');
            
        }

        // tandai OTP sudah digunakan
        $otp_record->update(['used' => true]);

        $resPassData = KemenagVisitorModel::where("email", $request->email)
                ->update([
                    "password" => $request->newPass,
                    "updated_at" =>  Carbon::now('Asia/Jakarta')
                ]);

        if(!$resPassData){
            return \Redirect::back()->withErrors('Gagal Update');
        }        
        return redirect('/LoginKemenag');
    }


    public function send()
    {
        try {
            Mail::to("yhoora.group@gmail.com")->send(new TestEmail());

            return response()->json([
                "status" => "success",
                "message" => "Email berhasil dikirim!"
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
    }
}
