@extends('EntranceTemplate.main', ['sidebars' => $sidebars])

@section('content')
<div id="app">

<div class="container h-100 ">
    <div class="row h-100 justify-content-md-center align-items-center m-3" style="min-height: 70vh;">
        <div class="col-lg-6 col-md-12 col-sm-12 mx-auto border shadow rounded p-3">
            <div class="row">
                <div class="col-12 text-center ">
                    <img src="{{ asset('../assets/img/wonder_harmony/wonder_harmony.png') }}" 
                        style="width: 50% !important;" alt="Logo">
                    <h5 class="text-center"><strong>Register</strong></h5>
                    <hr class="border border-dark border-1">

                </div>
            </div>
            @if($errors->any())
            <div class="row text-center w-100 m-0 p-0">
                <div class="alert alert-danger" role="alert">
                    <small>{{ $errors->first() }}</small>
                </div>
            </div>
            @endif
            <form id="register-form" method="post" action="{{ route('RegisterEntrance') }}">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="staffName">Staff Name</label>
                            <input class="form-control form-control-sm " id="staffName" name="StaffName" 
                                placeholder="john" type="text" required />
                        </div>   
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input class="form-control form-control-sm " id="password" name="password" 
                                placeholder="password" type="password" required />
                        </div>   
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input class="form-control form-control-sm " id="phone" name="Phone" 
                                placeholder="08998999" type="tel" required />
                        </div>   
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input class="form-control form-control-sm " id="email" name="Email" 
                                placeholder="asdf@asdf.com" type="email" required />
                        </div>   
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="address">Address</label>
                            <input class="form-control form-control-sm " id="address" name="Address" 
                                placeholder="Jl.asdf" type="textarea" required />
                        </div>   
                    </div>
                </div>
                
                <button class="btn btnToska w-100 mt-2" >REGISTER</button>
            </form>



            
        </div>
    </div>
</div>
    

</div>

@endsection
