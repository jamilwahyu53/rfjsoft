@extends('Admin.main', ['sidebars' => $sidebars])

@section('content')
    <div class="card m-2">
        <div class="card-body">
            <div class="row">
                @if($errors->any())
                <div class="col-12 mb-2">
                    <small><label class="text-xs text-white bg-danger rounded">{{ $errors->first() }}</label></small>
                </div>
                @endif
                <div class="col-12">
                    <h4 class="float-start"><strong>Data Staff</strong></h4>
                   
                </div>
                <div class="col-12">
                    <form action="{{ route('InsertDataStaffNoLogin') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="Username">Username</label>
                <input class="form-control form-control-sm " type="text" id="Username" name="Username" placeholder="Username" required />
            </div> 
            <div class="form-group">
                <label for="Password">Password</label>
                <input class="form-control form-control-sm " type="password" id="Password" name="Password" placeholder="Password" required />
            </div>
            <div class="form-group">
                <label for="Phone">Phone</label>
                <input class="form-control form-control-sm " type="phone" id="Phone" name="Phone" placeholder="Phone" required />
            </div> 
            <div class="form-group">
                <label for="Email">Email</label>
                <input class="form-control form-control-sm " type="email" id="Email" name="Email" placeholder="Email" required />
            </div> 
            <div class="form-group">
                <label for="Address">Address</label>
                <input class="form-control form-control-sm " type="text" id="Address" name="Address" placeholder="Address" required />
            </div> 
            <div class="form-group">
                <label for="Position">Position</label>
                <input class="form-control form-control-sm " type="text" id="Position" name="Position" placeholder="Position" required />
            </div> 
            <button class="btn btn-sm btn-danger rounded my-2 float-end">Register</button>
        </div>
                </div>
            </div>
        </div>
    </div>

@endsection