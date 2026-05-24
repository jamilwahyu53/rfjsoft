<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EntranceController extends Controller
{
    public function Register()
    {
        return view('Entrance.Register', [
                "sidebars" => null,
            ]);
    }
    public function RegisterTest()
    {
        return view('Entrance.RegisterTest', [
                "sidebars" => null,
            ]);
    }
}
