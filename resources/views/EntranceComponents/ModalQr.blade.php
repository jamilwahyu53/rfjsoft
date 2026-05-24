<!-- Modal -->
<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="{{ $id }}" 
    aria-hidden="true" style="background-color: rgba(128, 128, 128, 0.7);" >
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bgToska ">
        <label class="modal-title text-center" id="exampleModalLabel">{{ $title  }}</label>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button> 
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-12 text-center">
                <canvas class="mb-2" id="MyCanvas" ref="QrCanvas"></canvas>
                <button class="btn btn-md w-100 btnOrange mb-2 " @click='downloadQr' >Download QR</button>
            </div>
        </div> 
            
       </div>
    </div>
  </div>
</div>