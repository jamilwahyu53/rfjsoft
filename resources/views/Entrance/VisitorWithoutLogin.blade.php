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
                    <h4 class="float-start"><strong>Data Peserta</strong></h4>

<button id="btnExport">Export Excel</button>

                </div>
                <hr class="border border-dark border-1">
                <div class="col-12 table-responsive">
                    <table class="table table-striped table-hover align-middle mb-0" id="tblDataPeserta">
                        <thead>
                            <tr>
                            
                                @if($visitors->isNotEmpty())
                                    @foreach($visitors->first()->toArray() as $key => $value)
                                        <th style="width: 200px;">{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                                    @endforeach
                                @else
                                    <th>No Data</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($visitors as $visitor)
                                <tr>
                                    @foreach($visitor->toArray() as $key => $value)
                                        <td>
                                           @if($key === 'signature' && $value)
                                                <img src="{{ $value }}" alt="signature" style="max-height:50px; max-width:100px;">
                                            @else
                                                {{ $value }}
                                            @endif
                                        </td>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>


<script>
$(document).ready(function() {

    /*
    $('#tblDataPeserta').DataTable({
        dom: 'Bfrtip', // letak tombol
        scrollX: true,
        autoWidth: true,
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
        let table = $('#tblDataPeserta').DataTable();
        table.columns.adjust().draw();
    }, 2000);

*/



    $('#btnExport').on('click', async function() {
        const zip = new JSZip();

    const rows = document.querySelectorAll('#tblDataPeserta tbody tr');
    for(let tr of rows){
        const name = tr.querySelector('td:nth-child(3)').innerText.trim();
        const img = tr.querySelector('img');

        if(img){
            const base64Data = img.src.split(',')[1]; // remove data:image/png;base64,
            zip.file(name + ".png", base64Data, {base64:true});
        }
    }

    const content = await zip.generateAsync({type:"blob"});
    saveAs(content, "peserta_images.zip");
    });

});
</script>

@endsection