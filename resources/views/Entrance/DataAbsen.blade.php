@extends('EntranceTemplate.main', ['sidebars' => $sidebars])

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
                    <h4 class="float-start"><strong>Data Absen</strong></h4>
                </div>
                <hr class="border border-dark border-1">
                <div class="col-12 table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0" id="tblAbsen">
                        <thead>
                        <tr>
                             @foreach($absens->first() as $key => $value)
    <th>{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
@endforeach


                        </tr>
                        </thead>
                        <tbody>
                            @foreach($absens as $absen)
                                <tr>
                                    @foreach((array) $absen as $key => $value)
                                        <td>{{ $value }}</td>
                                    @endforeach
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                            
        
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script>
$(document).ready(function() {
    $('#tblAbsen').DataTable({
        dom: 'Bfrtip', // letak tombol
        scrollX: true,
        autoWidth: false,
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
        let table = $('#tblAbsen').DataTable();
        table.columns.adjust().draw();
    }, 2000);
});
</script>

@endsection