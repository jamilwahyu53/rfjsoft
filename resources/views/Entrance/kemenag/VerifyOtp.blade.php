@extends('EntranceTemplate.main', ['sidebars' => $sidebars])

@section('content')

<div class="container h-100 ">
    <div class="row h-100 justify-content-md-center align-items-center m-3" style="min-height: 70vh;">
        <div class="col-lg-6 col-md-12 col-sm-12 mx-auto border shadow rounded p-3">
            <div class="row">
                
                <div class="col-12 text-center">
                    <h3 class="text-center mt-1">
                        <small><strong>
                        VERIFY OTPS
                        </strong></small>   
                    </h3>
                    <hr class="border border-dark border-1">
                </div>
                
            </div>
            <div class="col-12">
                @if($errors->any())
                <div class="row text-center w-100">
                    <div class="alert alert-danger" role="alert">
                        <small>{{ $errors->first() }}</small>
                    </div>
                </div>
                @endif
            </div>
            <div class="col-12">
                <form id="register-form" method="post" action="/api/verifyOtp">
                    @csrf
                    <div class="form-group ">
                        <label for="Email" class="form-label">Email</label>
                        <input class="form-control" type="email" name="email" required />
                    </div>
                    <div class="form-group ">
                        <label for="Username" class="form-label">OTP</label>
                        <input class="form-control" type="text" name="otp" required />
                    </div>
                    <div class="form-group ">
                        <label for="Username" class="form-label">New Password</label>
                        <input class="form-control" type="password" name="newPass" required />
                    </div>
                    <button class="btn btn-primary text-white float-end mt-2" >VERIFY</button>

                </form>
            </div>
        </div>
    </div>
</div>
    
@endsection
