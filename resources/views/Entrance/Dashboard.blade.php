@extends('EntranceTemplate.main', ['sidebars' => $sidebars])

@section('content')

            <div class="row my-3 mx-3">
                @if($errors->any())
                <div class="col-12 mb-2">
                    <small><label class="text-xs text-white bg-danger rounded">{{ $errors->first() }}</label></small>
                </div>
                @endif
                

<div class="container ">

  <!-- Cards -->
  <div class="row justify-content-left gx-5 gy-4">
    <div class="col-6 col-sm-6 col-md-6 col-lg-4 d-flex">
      <div class="card shadow-lg rounded-4 border-0 p-4 h-100 w-100 text-center">
        <div class="card-body">
          <h1 class="fw-bold display-4">{{ $contentDashboard['pendaftar']->Pendaftar }}</h1>
          <p class="text-muted mb-0">Calon Peserta</p>
        </div>
      </div>
    </div>

    @foreach($contentDashboard['jmlPerPos'] as $pos)
    <div class="col-6 col-sm-6 col-md-6 col-lg-4 d-flex">
      <div class="card shadow-lg rounded-4 border-0 p-4 h-100 w-100 text-center">
        <div class="card-body">
          <h1 class="fw-bold display-4">{{ $pos->Jml }}</h1>
          <p class="text-muted mb-0">{{ $pos->pos_name }}</p>
        </div>
      </div>
    </div>
    @endforeach

  <!-- Table -->
  <div class="card shadow-lg rounded-4 border-0 mt-4">
    <div class="card-body">
      <div class="table-responsive">
       
      
      <table class="table table-striped table-hover align-middle mb-0" id="tblStaffs">
    <thead class="table-light">
        <tr>
            @if($contentDashboard['staffs']->isNotEmpty())
                @foreach($contentDashboard['staffs']->first()->toArray() as $key => $value)
                    @if($key !== 'password' && !($key === 'position' && $value === 'Administrator'))
                        <th>{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                    @endif
                @endforeach
            @else
              <th>No Data</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach($contentDashboard['staffs'] as $staff)
            <tr>
                @foreach($staff->toArray() as $key => $value)
                    @if($key !== 'password' && !($key === 'position' && $value === 'Administrator'))
                        <td>{{ $value ?? '-' }}</td>
                    @endif
                @endforeach
            </tr>
        @endforeach
    </tbody>
</table>


      </div>
    </div>
  </div>

</div>








                        
            </div>
@endsection
@section('scripts')
<script>
$(document).ready(function() {
    $('#tblStaffs').DataTable({
        dom: 'Bfrtip', // letak tombol
        scrollX: true,
        autoWidth: true,
        responsive: true,
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'Visitors',
                text: 'Export Excel',
                exportOptions: {
                    columns: ':not(:last-child)' // jika ada kolom image ingin skip
                }
            }
        ]
    });

    setTimeout(() => {
        let table = $('#tblStaffs').DataTable();
        table.columns.adjust().draw();
    }, 2000);
});
</script>

@endsection