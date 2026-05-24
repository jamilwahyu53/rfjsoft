<!-- Modal -->
<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="{{ $id }}" 
    aria-hidden="true" style="background-color: rgba(128, 128, 128, 0.7);" >
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bgToska ">
        <label class="modal-title text-center" id="exampleModalLabel">{{ $title  }} 
            <strong class="text-danger">UKURAN JERSEY : @{{ visitor.size_jersey }}</strong></label>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button> 
      </div>


        <div class="modal-body">
            <div id="ktpCard">
                <img src="{{ asset('../assets/img/kemenag/id_card.jpeg') }}" alt="ID Card">
                <div class="name-overlay text-dark">@{{ visitor.full_name }}</div>
            </div>
        </div>


        <div class="modal-footer">
            <button @click="printKTP" class="btn btnOrange w-100">CETAK ID CARD</button>
        </div>

       
    </div>
  </div>
</div>