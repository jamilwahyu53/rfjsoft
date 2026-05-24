@extends('EntranceTemplate.main', ['sidebars' => $sidebars])

@section('content')
<div id="app">

@include('EntranceComponents.Loading')

<div class="container h-100 ">
    <div class="row h-100 justify-content-md-center align-items-center m-3" style="min-height: 100vh;">
        <div class="col-lg-6 col-md-12 col-sm-12 mx-auto border shadow rounded p-3">
            <div class="row">
                <div class="col-12 mb-2">
                    <h5><strong>FORM PENUKARAN TIKET<strong></h5>
                    <hr class="border border-dark border-1">
                </div>
                <div class="col-12 mb-2">
                    <div class="form-group">
                        <label for="code_group">Gate</label>
                        <select class="form-control" v-model="form_qr.code_gate" id="code_gate">
                            @foreach($groups as $p)
                            <option value="{{ $p->code_gate }}">{{ $p->code_gate }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="code_qr">QR CODE</label>
                        <input class="form-control" v-model="form_qr.code_qr"
                            ref="qrInput" @keydown.enter="pushDownQr" 
                            placeholder="code12345" type="text" />
                    </div>
                    <div class="form-group">
                        <label for="rfid">RFID</label>
                        <input class="form-control" v-model="form_qr.ticket" 
                            ref="rfidInput"
                            @keydown.enter="pushQr"
                            placeholder="code12345" type="text" />
                    </div>

                    <button class="btn btnOrange mt-3 float-end" @click="pushQr">SUBMIT</button>
                </div>
            </div>
        </div>
    </div>
    <!--
    <div class="row">
        <input v-model="tag" placeholder="Tag...">
    </div>
-->
</div>
    
    @include('EntranceComponents.ModalDefault', ['id' => 'modalDefault', 'title' => 'Info'])
    @include('EntranceComponents.ModalIdCard', ['id' => 'modalIdCard', 'title' => 'Info'])


</div>

@endsection

@section('scripts')
<script type="module">
import { BaseInput } from "{{ asset('../assets/js/components/BaseInput.js')}}";
const { createApp, ref, onMounted, reactive, watch, nextTick } = Vue;

createApp({
    components: { BaseInput },
    setup() {
        const result = ref('');
        const dtRfid = ref('');
        const rfidInput = ref(null);
        const qrInput = ref(null);
        const afterReg = ref(false);
        const visitor = ref({
            "full_name": "",
            "size_jersey": ""
        });

        const tag = ref({});

        const message_modal = ref(null);
        const form_qr = reactive({
           Kode: "{{ $userLogin['data']->Kode ?? $userLogin['data']->code }}",
           code_qr: "",
           code_post: "ReRegis",
           ticket: "",
           code_gate: "",
        });

        const pushDownQr = () => {
            focusRfid();
        };

        const pushQr = async () => {
            afterReg.value = false;
            form_qr.payment = true;
            try {
                const res = await ApiService.post('/PushQrKemenag', form_qr);
                if(res.status){
                    
                    visitor.value = res.data;
                    tag.value = res.data;

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
                qrInput.value.focus();
                afterReg.value = true;
            }
            
        };

        const focusRfid = (code_qr = "") => {
            setTimeout(() => {
                rfidInput.value?.focus();
            }, 100);
             
        };

        const printKTP = () => {
            window.print();
        };

        watch(form_qr.code_qr, (newVal, oldVal) => {
            focusRfid(newVal);
        });
        watch(afterReg, (newVal) => {
            if (newVal) {
                $('#modalDefault').one('hidden.bs.modal', () => {
                    qrInput.value.focus();
                });
                $('#modalIdCard').one('hidden.bs.modal', () => {
                    qrInput.value.focus();
                });
            }
        });

        watch(tag, (v) => {
            localStorage.setItem("sharedTag", JSON.stringify(v));
        });


        onMounted(() => {
            setTimeout(() => {
                if (qrInput.value) {
                    qrInput.value.focus();
                }
            }, 500);
        });
        
        return { 
            pushQr, message_modal, form_qr,
            dtRfid, rfidInput, printKTP,
            visitor, qrInput, pushDownQr,
            tag
        };
    }
}).mount('#app');
</script>
@endsection