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
                            @if($key === 'ticket')
                                <td><input class="form-control form-control-sm " 
                                    data-code="{{ $visitor->code }}"
                                    value="{{ $value }}" 
                                    @keydown.enter="connectingData($event)"/> </td>
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

    @include('EntranceComponents.ModalDefault', ['id' => 'modalDefault', 'title' => 'Info'])
    @include('EntranceComponents.ModalIdCard', ['id' => 'modalIdCard', 'title' => 'Info'])


</div>

@endsection

@section('scripts')
<script type="module">
import { BaseInput } from "{{ asset('../assets/js/components/BaseInput.js')}}";
const { createApp, ref, onMounted, onBeforeMount, reactive } = Vue;

createApp({
    components: { BaseInput },
    setup() {
        
        const afterReg = ref(false);
        const visitor = ref({
            "full_name": "",
            "size_jersey": ""
        });
        const message_modal = ref(null);

        const form_qr = reactive({
           Kode: "{{ $userLogin['data']->Kode ?? $userLogin['data']->code }}",
           code_qr: "",
           code_post: "ReRegis",
           ticket: "",
        });
        

        const connectingData = async (event) => {
            const input = event.target;
            const ticketValue = input.value; 
            const code = input.dataset.code; 
            form_qr.code_qr = code;
            form_qr.ticket = ticketValue;

            afterReg.value = false;
            try {
                
                const res = await ApiService.post('/PushQrKemenag', form_qr);
                if(res.status){

                    console.log(res.data);

                    visitor.value = res.data;

                    $("#modalIdCard").modal("show");
                } else {
                    message_modal.value = res.message;
                    $("#modalDefault").modal("show");
                }
            } catch (err) {
                message_modal.value = err.message;
                $("#modalDefault").modal("show");
            } finally {
                form_qr.code_qr = '';
                form_qr.ticket = '';
                afterReg.value = true;
            }

        };

        const pushQr = async () => {
            afterReg.value = false;
            try {
                
                const res = await ApiService.post('/PushQrKemenag', form_qr);
                if(res.status){

                    console.log(res.data);

                    visitor.value = res.data;

                    $("#modalIdCard").modal("show");
                } else {
                    message_modal.value = res.message;
                    $("#modalDefault").modal("show");
                }
            } catch (err) {
                message_modal.value = err.message;
                $("#modalDefault").modal("show");
            } finally {
                form_qr.code_qr = '';
                form_qr.ticket = ''; 
                afterReg.value = true;
            }
            
        };
        const printKTP = () => {
            window.print();
        };

        onMounted(() => {
           $('#tblPembayaran').DataTable({
                scrollX: true,
                autoWidth: false,
            });
        });
        onBeforeMount(() => {
            
        });

        return { 
            connectingData,
            pushQr,
            form_qr,
            message_modal,
            printKTP,
            afterReg,
            visitor
        };
    }
}).mount('#app');
</script>
@endsection