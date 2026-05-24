@extends('EntranceTemplate.main', ['sidebars' => $sidebars])

@section('content')
<div id="app">

@include('EntranceComponents.Loading')


<div class="container h-100 ">
    <div class="row h-100 justify-content-md-center align-items-center m-3" style="min-height: 100vh;">
        <div class="col-lg-6 col-md-12 col-sm-12 mx-auto border shadow rounded p-3">
            <div class="row">
                <div class="col-12 text-center ">
                    <img src="{{ asset('../assets/img/kemenag/kemenag_logo.png') }}" 
                        style="width: 25% !important;" alt="Logo">
                </div>
                <div class="col-12 text-center">
                    <label class="text-center mt-1">
                        <small><strong>
                        KEMENTERIAN AGAMA RI <br> 
                        SEKRETARIAT JENDERAL 
                        </strong></small>   
                    </label>
                    <hr class="border border-dark border-1">
                </div>
                <div class="col-12 text-center">
                    <label class="text-center mt-1">
                        <small>
                        RAPAT KERJA NASIONAL TAHUN 2025 <br>
                        “Mempersiapkan Umat Masa Depan”
                        </strong>   
                    </label>
                    <br>
                    <label >
                        <i class="bi bi-geo-alt-fill text-danger"></i> 
                            <small>
                            Atria Hotel, Gading Serpong
                            </small>
                    </label>
                    <br>
                    <label > <small> 14-17 Desember 2025</small> </label>
                    <hr class="border border-dark border-1">
                </div>
            </div>
            
            <form id="register-form" @submit.prevent="saveAndSubmit">
                <div class="row">
                    <base-input
                        v-for="field in field_register"
                        :key="field.model"
                        v-model="form_register[field.model]"
                        :label="field.label"
                        :type="field.type"
                        :options="field.options"
                        :col="field.col"
                        :required= "field.required"
                        :readonly="field.readonly"
                        :hide="field.hide"
                        @change="onChangeData(field.model, $event)"
                    ></base-input>   
                </div>
                
                <div class="row">
                    <div class="col-12">
                        <label class="text-danger float-end"><small>* Wajib di isi</small></label>
                        <button class="btn btnToska w-100 mt-2" :disabled="loading" >REGISTER</button>
                    </div>
                    <div class="col-12 text-center mt-2">
                        <p> Sudah Punya Account ? 
                        <a class="text-success" href="/LoginKemenag" ><strong>Login</strong></a>
                        </p>
                    </div>
                </div>
    
            </form>
        </div>
    </div>
</div>
    
@include('EntranceComponents.ModalDefault', ['id' => 'modalDefault', 'title' => 'Info'])
@include('EntranceComponents.ModalQr', ['id' => 'modalQr', 
        'title' => 'Register Success, Download QR Untuk Ditukar Dengan Ticket'])
    
    
</div>

@endsection

@section('scripts')
<script type="module">
import { BaseInput } from "{{ asset('../assets/js/components/BaseInput.js')}}";
const { createApp, ref, onMounted, onBeforeMount, reactive, watch } = Vue;

createApp({
    components: { BaseInput },
    setup() {
        const loading = ref(false);
        const message_modal = ref(null);
        const QrCanvas = ref(null);
        const afterReg = ref(false);

        const form_register = reactive({});

        const error_register = reactive([]);
        const options_satker = ref([]);
        
        const fieldBank = [
            "bank_name",
            "bank_number",
            "bank_account_name",
            "npwp"
        ];

        const field_register = reactive([
            {
                model: "full_name",
                label: "Nama Lengkap",
                placeholder: "john",
                type: "text",
                col: "6",
                required: true,
            },
            {
                model: "nip",
                label: "NIP",
                placeholder: "123456",
                type: "text",
                col: "6",
                required: true,
            },            
            {
                model: "place_of_birth",
                label: "Tempat Lahir",
                placeholder: "Bogor",
                type: "text",
                col: "6",
                required: true,
            },
            {
                model: "birth_date",
                label: "Tanggal Lahir",
                type: "date",
                col: "6",
                required: true,
            },
            {
                model: "gender",
                label: "Gender",
                type: "select",
                options: [
                    { label: "Laki-laki", value: "MALE" },
                    { label: "Perempuan", value: "FEMALE" },
                ],
                col: "6",
                required: true,
            },
            {
                model: "organization",
                label: "Unit Kerja/ Instansi",
                type: "select",
                options: [],
                col: "6",
                required: true,
            },
            {
                model: "satker",
                label: "Satuan kerja",
                type: "select",
                options: [],
                col: "6",
                required: true,
            },
            {
                model: "grade",
                label: "Pangkat/Gol",
                type: "text",
                col: "6",
                required: true,
            },
            {
                model: "role",
                label: "Jabatan",
                type: "text",
                col: "6",
                required: true,
            },
            
            {
                model: "email",
                label: "Email",
                type: "email",
                placeholder: "",
                col: "6",
                required: true,
                rules: { email: true },
            },
            {
                model: "address",
                label: "Alamat Rumah",
                type: "textarea",
                col: "6",
                required: true,
            },
            {
                model: "office_address",
                label: "Alamat Kantor",
                type: "textarea",
                placeholder: "",
                col: "6",
                required: true,
            },
            {
                model: "phone",
                label: "No Telp",
                type: "tel",
                placeholder: "0855555xxx",
                col: "6",
                required: true,
            }, 
            {
                model: "password",
                label: "password",
                type: "password",
                col: "6",
                required: true,
            }, 
            
            {
                model: "bank_name",
                label: "Nama Bank",
                type: "text",
                col: "6",
                readonly: true,
                required: false,
            },
            {
                model: "bank_number",
                label: "No Rekening",
                type: "text",
                col: "6",
                readonly: true,
                required: false,
            },
            {
                model: "bank_account_name",
                label: "Atas Nama",
                type: "text",
                col: "6",
                readonly: true,
                required: false,
            },
            {
                model: "npwp",
                label: "NPWP",
                placeholder: "123456",
                type: "text",
                col: "6",
                required: true,
            },
            {
                model: "paper_work",
                label: "Surat Tugas (PDF atau images JPG/PNG)",
                type: "input_image",
                col: "12",
                required: true,
            },
            {
                model: "foto",
                label: "PasFoto (images JPG/PNG)",
                type: "input_image",
                col: "12",
                required: true,
            },
            {
                model: "size_jersey",
                label: "Size Jersey",
                type: "select",
                options: [
                    { label: "S   ( Lebar:45cm   Tinggi:67cm )", value: "S" },
                    { label: "M   ( Lebar:48cm   Tinggi:69cm )", value: "M" },
                    { label: "L   ( Lebar:50cm   Tinggi:72cm )", value: "L" },
                    { label: "XL  ( Lebar:53cm   Tinggi:74cm )", value: "XL" },
                    { label: "XXL ( Lebar:55cm   Tinggi:76cm )", value: "XXL" },
                    { label: "3XL ( Lebar:58cm   Tinggi:78cm )", value: "3XL" },
                    { label: "4XL ( Lebar:60cm   Tinggi:80cm )", value: "4XL" },
                    { label: "5XL ( Lebar:62cm   Tinggi:80cm )", value: "5XL" },
                ],
                col: "12",
                required: true,
            },
            
        ]);

        field_register.forEach((field) => {
            error_register[field.model] = "";
            form_register[field.model] = "";
        });

        const firstLoadDtReg = async () => {
            const res = await ApiService.post('/FirstLoadDtReg');
            if(res.status){
               field_register.forEach((field) => {
                    if (field.model === "organization") {
                        field.options = res.data.map(item => ({
                            label: item.unit_kerja,
                            value: item.unit_kerja
                        }));
                    }
                });

            } else {
                message_modal.value = res.message;
                $("#modalDefault").modal("show");
            }
        };
        const getSatker = async (unit_kerja) => {
            const form_undangan = {unit_kerja : unit_kerja };
            const res = await ApiService.post('/GetSatker', form_undangan);
            if(res.status){
               field_register.forEach((field) => {
                    if (field.model === "satker") {
                        
                        options_satker.value = res.data;
                        field.options = res.data.map(item => ({
                            label: item.satker,
                            value: item.code
                        }));
                    }
                });
            } else {
                message_modal.value = res.message;
                $("#modalDefault").modal("show");
            }
        };
        
        const onChangeData = (key, event) => {
            //console.log(key, event.target.value);
            if(key === "organization"){
                getSatker(event);
            }
            if(key === "satker"){
                const selected_satker = options_satker.value.find(item => item.code === event);                
                fieldBank.forEach((name) => {
                    const field = field_register.find((f) => f.model === name);
                    if (field) {
                        field.required = selected_satker.st_bank === 1;
                        field.readonly = selected_satker.st_bank !== 1;
                        field.hide = selected_satker.st_bank !== 1;
                        form_register[field.model] = "";
                    }
                });

                
            }
        };

        const validateForm = () => {
            let isValid = true;
            
            field_register.forEach((field) => {
                const key = field.model;

                // hanya cek field yang required
                if (field.required) {
                    if (!form_register[key] || form_register[key].toString().trim() === "") {
                        error_register[key] = "Field ini wajib diisi";
                        isValid = false;
                    } else {
                        error_register[key] = "";
                    }
                } else {
                    // jika tidak required, hapus error sebelumnya
                    error_register[key] = "";
                }
            });
            
            return isValid;
        };

        const downloadQr = () => {
            const canvas2 = document.getElementById("MyCanvas");
            const imageUrl = canvas2.toDataURL("image/png");
            const link = document.createElement("a");
            link.href = imageUrl;
            link.setAttribute("download", "QR-Register.png");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            setTimeout(() => {
                window.location.href = '/LoginKemenag';
            }, 1000);

        };

        const saveAndSubmit = async () => {
            if (validateForm()) {   
                
                loading.value = true;
                
                const res = await ApiService.post('/InsertKemenagVisitor', form_register);
                if(res.status){
                    afterReg.value = true;
                    /*
                    QRCode.toCanvas(QrCanvas.value, res.data.code, { width: 250 }, function (error) {
                            if (error) {
                                console.log(error);
                                message_modal.value = "Gagal Generate QR Code";
                                $("#modalDefault").modal("show");
                            } else {
                                $("#modalQr").modal("show");
                            }
                        });
                    */
                   message_modal.value = "Silahkan Login Untuk Mendapatkan PASSCODE dan Informasi Lainya";
                   $("#modalDefault").modal("show");
                } else {
                    message_modal.value = res.message;
                    $("#modalDefault").modal("show");
                }
                setTimeout(() => {
                    loading.value = false;
                }, 1000);
            } else {
                message_modal.value = "Ada field yang belum di isi";
                $("#modalDefault").modal("show");
            }

        }

        onMounted(() => {

        });
        onBeforeMount(() => {
            firstLoadDtReg();
        });
        watch(afterReg, (newVal) => {
            if (newVal) {
                $('#modalDefault').one('hidden.bs.modal', () => {
                    window.location.href = "https://tamasoft.cloud/LoginKemenag";
                });
            }
        });


        return { 
            form_register, field_register, error_register, QrCanvas,
            firstLoadDtReg,
            onChangeData, 
            getSatker,
            fieldBank,
            saveAndSubmit,
            validateForm,
            message_modal,
            downloadQr,
            loading,
            afterReg
        };
    }
}).mount('#app');
</script>
@endsection