@extends('EntranceTemplate.main', ['sidebars' => $sidebars])

@section('content')

<div id="app">

@include('EntranceComponents.Loading')


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
          <label class="float-start"><strong>Report Data Tapping</strong></label>
        </div>
        <div class="card-body">
          <table class="table table-striped table-hover 
            align-middle mb-0 w-100" id="tblReportTap">
            <thead class="table-light">
                @foreach($myColumn as $col)
                    <td><strong>{{ $col }}</strong></td>
                @endforeach
            </thead>
            <tbody>
                @foreach($dtAbsens as $visitor)
                    <tr>
                        @foreach((array) $visitor as $key => $value)
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
           $('#tblReportTap').DataTable({
                scrollX: true,
                autoWidth: false,
               
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