@extends('EntranceTemplate.main', ['sidebars' => $sidebars])

@section('content')
<div id="app">

<div class="container h-100 ">
    <div class="row h-100 justify-content-md-center align-items-center m-3" style="min-height: 100vh;">
        <div class="col-lg-6 col-md-12 col-sm-12 mx-auto border shadow rounded p-3">
            <div class="row">
                <div class="col-12 mb-2">
                    <h5><strong>FORM SCAN POST<strong></h5>
                    <hr class="border border-dark border-1">
                </div>
                <div class="col-12 mb-2">
                    <div class="form-group">
                        <label for="idPos">Pilih Post</label>
                        <select class="form-control" v-model="form_qr.code_post" id="idPos">
                            @foreach($pos as $p)
                            <option value="{{ $p->Kode }}">{{ $p->pos_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 mb-2">
                    <div class="card d-flex justify-content-center align-items-center" style="height: 35vh;">
                        <div id="reader" class="w-100"></div>
                    </div>
                </div>
                <div class="col-12 mb-2">
                    <p class="text-break">Hasil Scan: <strong>@{{ result }}</strong></p>
                    <input type="hidden" name="Kode" id="Kode" v-model="form_qr.Kode" />
                    <button class="btn btnOrange m-2 float-end" @click="startScan">Mulai Scan</button>
                    <button class="btn btnToska m-2 float-start" @click="stopScan">Stop Scan</button>
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
const { createApp, ref, onMounted, reactive, watch } = Vue;

createApp({
    components: { BaseInput },
    setup() {
        const result = ref('');
        let scanner = null;
        const message_modal = ref(null);
        const form_qr = reactive({
           Kode: "{{ $session->Kode }}",
           code_qr: "",
           code_post: "",
        });


        const startScan = () => {

            if(form_qr.code_post === "")
            {
                alert("Pilih Kode Pos");
            } else {
                if (!scanner) {
                    scanner = new Html5Qrcode("reader");
                }

                scanner.start(
                    { facingMode: "environment" },
                    { fps: 10, qrbox: 250 },
                    qr => {
                        result.value = qr;
                        //console.log("QR:", qr);
                    },
                    err => {
                        //console.warn(err);
                    }
                );
            }
            
        };
        const stopScan = () => {
            if (scanner) {
                scanner.stop();
            }
        };
        
        const pushQr = async (code_qr) => {
            try {
                form_qr.code_qr = code_qr;
                const res = await ApiService.post('/PushQr', form_qr);
                if(res.status){
                    message_modal.value = res.message;
                    $("#modalDefault").modal("show");
                    setTimeout(() => {
                        form_qr.code_qr = "";
                        stopScan();
                    }, 500);
                } else {
                    message_modal.value = res.message;
                    $("#modalDefault").modal("show");
                }
                
            } catch (err) {
                message_modal.value = err.message;
                $("#modalDefault").modal("show");
            }
        };

        watch(result, (newVal, oldVal) => {
            //console.log("QR berubah dari:", oldVal, "ke:", newVal);
            pushQr(newVal);
        });
        
        return { 
            result, startScan, stopScan,
            pushQr, message_modal, form_qr
        };
    }
}).mount('#app');
</script>
@endsection