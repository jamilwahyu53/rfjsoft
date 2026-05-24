@extends('EntranceTemplate.main', ['sidebars' => $sidebars])

@section('content')

<div id="app">
            <div class="row my-3 mx-3">
                @if($errors->any())
                <div class="col-12 mb-2">
                    <small><label class="text-xs text-white bg-danger rounded">{{ $errors->first() }}</label></small>
                </div>
                @endif
                
<div class="container ">
  <div class="row">
    <div class="col-12 mb-3 mt-4">
      <div class="card shadow-lg rounded-4 border-0 p-4 h-100 w-100 text-center">
        <div class="card-header bg-transparent">
          <label class="float-start"><strong>Data Peserta</strong></label>
        </div>
        <div class="card-body">
          <table class="table table-striped table-hover 
            align-middle mb-0 w-100" id="tblPembayaran">
            <thead class="table-light w-100">
                <tr>
                @foreach($myColumn as $col)
                    <td><strong>{{ $col }}</strong></td>
                @endforeach
                  
                </tr>

            </thead>
            <tbody>
                @foreach($dtVisitors as $visitor)
                    <tr>
                        @foreach((array) $visitor as $key => $value)
                            @if($key == 'foto') 
                                <td>
                                    <img src="data:image/png;base64,{{ $value }}" alt="image" style="width: 50px;">
                                </td>
                            @else
                            <td>{{ $value }}</td>
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

</div>



</div>

@endsection

@section('scripts')
<script type="module">
import { BaseInput } from "{{ asset('../assets/js/components/BaseInput.js')}}";
const { createApp, ref, onMounted, onBeforeMount, reactive } = Vue;

createApp({
    components: { BaseInput },
    setup() {
        
        onMounted(() => {
           $('#tblPembayaran').DataTable({
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
        });
        onBeforeMount(() => {
            
        });

        return { 
        };
    }
}).mount('#app');
</script>
@endsection