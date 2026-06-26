@extends('EmoLand.main', ['sidebars' => $sidebars])

@section('content')

<div class="container h-100 ">
    <div class="row h-100 justify-content-md-center align-items-center m-3" style="min-height: 70vh;">
        <div class="col-lg-6 col-md-12 col-sm-12 mx-auto border shadow rounded p-3">
            <div class="row">
                <div class="col-12 text-center">
                    <label class="text-center mt-1">
                        <small><strong>
                        KEMENTERIAN AGAMA RI <br> 
                        SEKRETARIAT JENDERAL 
                        </strong></small>   
                    </label>
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
                <form id="register-form" method="post" action="PostLoginKemenag">
                    @csrf
                    <div class="form-group ">
                        <label for="Username" class="form-label">Phone</label>
                        <input class="form-control" type="text" placeholder="08585858585" name="phone" required />
                    </div>
                    
                    <div class="form-group ">
                        <label for="Password">Password</label>
                        <input class="form-control rounded " type="password" placeholder="Password" name="password" required />
                    </div>

                    <a class="text-danger" href="/RegisterEntKemenag" >Register</a>
                    <button class="btn btn-primary text-white float-end mt-2" >LOGIN</button>

                </form>
            </div>
            <div class="col-12 mt-5 text-center">
                <buttaon class="btn btn-sm btn-transparent text-danger"  
                    data-bs-toggle="modal" data-bs-target="#otpModal" 
                >Lupa Password</button>
            </div>
        </div>
    </div>
</div>
    
<!-- Modal -->
<div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="otpModalLabel">Kirim OTP ke Email</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Form email -->
        <form id="otpForm" method="post" action="/api/sendOtp">
          <div class="mb-3">
            <label for="emailInput" class="form-label">Email</label>
            <input type="email" class="form-control"  name="email" id="emailInput" placeholder="Masukkan email Anda" required>
          </div>
          <button type="submit" class="btn btn-primary">Kirim OTP</button>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
