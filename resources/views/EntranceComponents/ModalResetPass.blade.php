<!-- Modal -->
<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="{{ $id }}" 
    aria-hidden="true" style="background-color: rgba(128, 128, 128, 0.7);" >
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bgToska ">
        <label class="modal-title text-center" id="exampleModalLabel"><strong>{{ $title  }}</strong></label>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button> 
      </div>
      <div class="modal-body">
        <div class="row">
            <form action="ResetPassword" method="post">
                @csrf
                <div class="col-12 text-left">
                    <div class="row mb-2">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="password">New Password</label>
                                <input type="password"  name="password" class="form-control form-control-sm"  />
                            </div>
                        </div> 
                    </div>
                    <button class="btn btn-md w-100 btnOrange mb-2 ">Reset Password</button>
                </div>
            </form>
        </div> 
            
       </div>
    </div>
  </div>
</div>