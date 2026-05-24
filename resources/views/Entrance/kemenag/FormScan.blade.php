@extends('EntranceTemplate.main', ['sidebars' => $sidebars])

@section('content')
<div id="app">

<div class="container h-100 ">
    <div class="row h-100 justify-content-md-center align-items-center m-3" style="min-height: 100vh;">
        <div class="col-lg-6 col-md-12 col-sm-12 mx-auto border shadow rounded p-3">
            <div class="row">
                <div class="col-12 mb-2">
                    <h5><strong>FORM SCAN SESI<strong></h5>
                    <hr class="border border-dark border-1">
                </div>
                
                <div class="col-12 mb-2">
                    <input type="hidden" name="Kode" id="Kode" v-model="form_qr.Kode" />

                    <div class="form-group">
                        <div class="form-group">
                            <label for="idPos">Pilih Sesi</label>
                            <select class="form-control" v-model="form_qr.code_post" id="idPos">
                                @foreach($pos as $p)
                                <option value="{{ $p->Kode }}">{{ $p->pos_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="code_group">Gate</label>
                        <select class="form-control" v-model="form_qr.code_gate" id="code_gate">
                            @foreach($groups as $p)
                            <option value="{{ $p->code_gate }}">{{ $p->code_gate }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="rfid">RFID</label>
                        <input class="form-control" v-model="dtRfid" 
                            ref="rfidInput"
                            @keydown.enter="pushQr"
                            placeholder="code12345" type="text" />
                    </div>

                </div>

                <div class ="col-12  mt-3">
                    <div class="row card">
                        <div class="col-12 mt-2 text-center">
                            <label>RESULT PENCARIAN</label>
                            <hr>
                        </div>
                        <div class="col-12 text-center">

                            <div v-if="notfound != ''" class="alert alert-danger" role="alert">
                                @{{ notfound }}
                            </div>
                        </div>
                        
                        <div class="col-12">
                            <base-input
                                v-for="field in field_visitor"
                                :key="field.model"
                                v-model="form_visitor[field.model]"
                                :label="field.label"
                                :placeholder="field.placeholder"
                                :type="field.type"
                                :options="field.options"
                                :col="field.col"
                                :hide="field.hide"
                                :readonly="true"
                            ></base-input>   
                        </div>
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
const { createApp, ref, onMounted, reactive, watch, nextTick } = Vue;

createApp({
    components: { BaseInput },
    setup() {
        const dtRfid = ref('');
        const rfidInput = ref(null);
        const visitor = ref({
            "full_name": "",
            "size_jersey": ""
        });
        
        const notfound = ref('')
        const message_modal = ref(null);
        const form_qr = reactive({
           Kode: "{{ $userLogin['data']->Kode ?? $userLogin['data']->code }}",
           code_qr: "",
           code_post: "",
           ticket: "",
           code_gate: "",
        });

        const form_visitor = reactive({});
        const field_visitor = reactive([
            {
                model: "created_at",
                label: "Datetime Tapping",
                type: "text",
                readonly:true,
                col: "12",
            },
            {
                model: "full_name",
                label: "Nama Lengkap",
                type: "text",
                readonly:true,
                col: "12",
            },
            {
                model: "organization",
                label: "Unit Kerja/ Instansi",
                type: "text",
                readonly:true,
                col: "12",
            },
            {
                model: "satker",
                label: "Satuan kerja",
                type: "text",
                readonly:true,
                col: "12",
            },
            {
                model: "grade",
                label: "Pangkat/ Gol",
                type: "text",
                readonly:true,
                col: "12",
            },
            {
                model: "role",
                label: "Jabatan",
                type: "text",
                readonly:true,
                col: "12",
            },
        ]);
        field_visitor.forEach((field) => {
            form_visitor[field.model] = "";
        });


        const pushQr = async () => {
            
            if(form_qr.code_post){

                try {
                    notfound.value = '';

                    form_qr.ticket = dtRfid.value;
                    const res = await ApiService.post('/PushAbsensi', form_qr);
                    
                    if(res.status){
                        Object.assign(form_visitor, res.data);
                    } else {
                        notfound.value = res.message;
                        if(!res.errors){
                            field_visitor.forEach((field) => {
                                form_visitor[field.model] = "";
                            });
                        } else {
                            Object.assign(form_visitor, res.errors);
                        }
                    }
                        
                } catch (err) {
                    notfound.value = err.message;
                } 
                
                finally {
                    dtRfid.value = '';
                    setTimeout(() => {
                        rfidInput.value?.focus();
                    }, 500);
                }
                    
            } else {
                form_qr.ticket = "";
                alert("KODE SESI MASIH KOSONG");
            }
        };

        const focusRfid = (code_qr) => {
            form_qr.code_qr = code_qr;
            setTimeout(() => {
                rfidInput.value?.focus();
            }, 500);
             
        };


        onMounted(() => {
            setTimeout(() => {
                rfidInput.value?.focus();
            }, 500);
        });


        return { 
           
            pushQr, message_modal, form_qr,
            dtRfid, rfidInput, 
            visitor,
            form_visitor,
            field_visitor,
            notfound
        };
    }
}).mount('#app');
</script>
@endsection