@extends('EntranceTemplate.main', ['sidebars' => $sidebars])

@section('content')
<div class="container h-100 ">
    <div class="row h-100 justify-content-md-center align-items-center m-2 " style="min-height: 80vh;">
        
        <div class="col-lg-6 col-md-6 col-sm-9 mx-auto border shadow rounded">
            @if($errors->any())
            <div class="row text-center w-100">
                <div class="alert alert-danger" role="alert">
                    <small>{{ $errors->first() }}</small>
                </div>
            </div>
            @endif
            <div class="row m-2">
                <div class="col-12">
                    <h5><strong>Login Staff</strong></h5>
                </div>
                <div class="col-12">
                    <form method="post" action="{{ route('LoginEntrance') }}" >
                        @csrf
                        <div class="form-group ">
                            <label for="Username" class="form-label">Username</label>
                            <input class="form-control" type="text" placeholder="Username" name="phone" required />
                        </div>
                        
                        <div class="form-group ">
                            <label for="Password">Password</label>
                            <input class="form-control rounded " type="password" placeholder="Password" name="password" required />
                        </div>

                        <a href="https://tamasoft.cloud/RegisterEntrance" class="float-start text-info mt-2">Register</a>

                        <button class="btn btn-primary text-white float-end mt-2" >LOGIN</button>

                    </form>
                </div>
            </div>
            
            
        </div>
    </div>
</div>

@endsection