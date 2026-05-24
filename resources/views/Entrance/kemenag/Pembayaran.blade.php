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
          <label class="float-start"><strong>Report Data Pembayaran</strong></label>
        </div>
        <div class="card-body">
          <table class="table table-striped table-hover 
            align-middle mb-0 w-100" id="tblPembayaran">
            <thead class="table-light w-100">
                <tr>
                @foreach($myColumn as $col)
                    @if($col != 'code_v')
                    <td><strong>{{ $col }}</strong></td>
                    @endif
                @endforeach
                    <td><strong>action</action></td>
                </tr>

            </thead>
            <tbody>
                @foreach($dtPayments as $payment)
                    <tr>
                        @foreach((array) $payment as $key => $value)
                            @if($key != 'code_v')
                            @if($key == "bukti_bayar")
                            <td>
                                <img src="data:image/png;base64,{{ $value }}" alt="image" style="width: 150px;">
                            </td>
                            @else
                            <td>{{ $value }}</td>
                            @endif
                            @endif
                        @endforeach
                        <td>
                            <button class="btn btn-sm btn-danger text-white m-1" @click="submitApproval('APPROVED', '{{ $payment->code }}')">APPROVE</button>
                            <button class="btn btn-sm btn-info text-white m-1" @click="submitApproval('REJECTED', '{{ $payment->code }}')">REJECTED</button>
                        </td>
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


  @include('EntranceComponents.ModalDefault', ['id' => 'modalDefault', 'title' => 'Info'])


</div>

@endsection

@section('scripts')
<script type="module">
import { BaseInput } from "{{ asset('../assets/js/components/BaseInput.js')}}";
const { createApp, ref, onMounted, onBeforeMount, reactive } = Vue;

createApp({
    components: { BaseInput },
    setup() {
        const message_modal = ref(null);

        const submitApproval = async (status, code) => {
            try {
                const form_data = {
                    "status" : status,
                    "code" : code,
                    "approve_by" : "{{ $userLogin['data']->Kode ?? $userLogin['data']->code }}"
                };

                const res = await ApiService.post('/ApprovePembayaran', form_data);
                if(res.status){
                    message_modal.value = res.message;
                    $("#modalDefault").modal("show");

                    setTimeout(() => {
                        window.location.href = '/ValidasiPembayaran';
                    }, 1000);
                } else {
                    message_modal.value = res.message;
                    $("#modalDefault").modal("show");
                }
            } catch (err) {
                message_modal.value = err.message;
                $("#modalDefault").modal("show");
            }
        };

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
            submitApproval,
            message_modal
        };
    }
}).mount('#app');
</script>
@endsection