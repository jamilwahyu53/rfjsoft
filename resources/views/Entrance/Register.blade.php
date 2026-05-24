@extends('EntranceTemplate.main', ['sidebars' => $sidebars])

@section('content')
<div id="app">

<div class="container h-100 ">
    <div class="row h-100 justify-content-md-center align-items-center m-3" style="min-height: 100vh;">
        <div class="col-lg-6 col-md-12 col-sm-12 mx-auto border shadow rounded p-3">
            <div class="row">
                <div class="col-12 text-center ">
                    <img src="{{ asset('../assets/img/wonder_harmony/wonder_harmony.png') }}" 
                        style="width: 50% !important;" alt="Logo">
                    <h5 class="text-center"><strong>Register</strong></h5>
                    <hr class="border border-dark border-1">

                </div>
            </div>
            
            <form id="register-form" @submit.prevent="saveSignature">
                <div class="row">
                    <base-input
                        v-for="field in field_register"
                        :key="field.model"
                        v-model="form_register[field.model]"
                        :label="field.label"
                        :placeholder="field.placeholder"
                        :type="field.type"
                        :options="field.options"
                        :col="field.col"
                        :required= "field.required"
                        @change="onChangeData(field.model, $event)"
                    ></base-input>   
                </div>
            </form>


            <div class="row">
                <div class="col-12">
                    <img class="w-100 rounded-2" :src="'https://tamasoft.cloud/assets/img/wonder_harmony/jersey_with_size.png'">
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label for="ttd"><strong><small><span class="text-danger">* </span>Tanda Tangan</small></strong></label>
                        <button class="btn btn-sm btnOrange-sm float-end m-2" @click="clearCanvas">Clear</button>
                        <canvas class="w-100 rounded" id="ttd"
                            ref="canvas"
                            @mousedown="startDrawing"
                            @mouseup="stopDrawing"
                            @mouseout="stopDrawing"
                            @mousemove="draw"
                            @touchstart.prevent="startDrawingTouch"
                            @touchmove.prevent="drawTouch"
                            @touchend="stopDrawing"
                        ></canvas>
                    </div>
                    
                </div>
            </div>
            <div class="row">
                <label class="text-danger"><strong>
                    * Follow Instagram Subdit Kemitraan</strong></label>
            </div>
            
            <button class="btn btnToska w-100 mt-2" @click="saveSignature" >REGISTER</button>

            
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
const { createApp, ref, onMounted, reactive } = Vue;

createApp({
    components: { BaseInput },
    setup() {
        const canvas = ref(null);
        const ctx = ref(null);
        const drawing = ref(false);
        const signatureData = ref('');
        const message_modal = ref(null);

        const form_register = reactive({
           full_name: "",
           place_of_birth: "",
           birth_date: "",
           address: "",
           phone: "",
           email: "",
           gender: "",
           mesengger: "",
           status_mesengger_other: "",
           position: "",
           office_address: "",
           account_number: "",
           bank_name: "",
           account_bank_name: "",
           size_jersey: "",
           signature: "", 
        });

        const error_register = reactive({});

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
                model: "address",
                label: "Alamat Rumah",
                type: "textarea",
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
                model: "email",
                label: "Email",
                type: "email",
                placeholder: "john@gmail.com",
                col: "6",
                required: true,
                rules: { email: true },
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
            },
            {
                model: "status_mesengger",
                label: "Status Utusan Undangan",
                type: "select",
                options: [
                    { label: "KRISTEN", value: "KRISTEN" },
                    { label: "KATOLIK", value: "KATOLIK" },
                    { label: "HINDU", value: "HINDU" },
                    { label: "BUDDHA", value: "BUDDHA" },
                    { label: "KONGHUCU", value: "KONGHUCU" },
                    { label: "PKUB", value: "PKUB" },
                    { label: "ISLAM", value: "ISLAM" },
                ],
                col: "6",
            },
            {
                model: "status_mesengger_other",
                label: "Status Utusan ORMAS",
                type: "text",
                placeholder: "ORMAS A",
                col: "6",
            },
            {
                model: "office_address",
                label: "Alamat Kantor",
                type: "textarea",
                placeholder: "Jl. Maju Jaya No. 1",
                col: "6",
                required: true,
            },
            {
                model: "position",
                label: "Jabatan",
                type: "text",
                placeholder: "SPV",
                col: "6",
                required: true,
            },
            {
                model: "account_number",
                label: "No. Rekening",
                type: "text",
                col: "6",
                required: true,
            },
            {
                model: "bank_name",
                label: "Nama Bank",
                type: "text",
                placeholder: "Bank Indonesia",
                col: "6",
                required: true,
            },
            {
                model: "account_bank_name",
                label: "Nama Rekening",
                type: "text",
                placeholder: "john",
                col: "6",
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
            error_register[field.name] = "";
        });

        const QrCanvas = ref(null);

        onMounted(() => {
            ctx.value = canvas.value.getContext('2d');
            ctx.value.lineWidth = 2;
            ctx.value.lineCap = 'round';
            ctx.value.strokeStyle = 'black';
        });

        const startDrawing = () => drawing.value = true;
        const stopDrawing = () => {
            drawing.value = false;
            ctx.value.beginPath();
        };

        const draw = (e) => {
            if (!drawing.value) return;
            ctx.value.lineTo(e.offsetX, e.offsetY);
            ctx.value.stroke();
            ctx.value.beginPath();
            ctx.value.moveTo(e.offsetX, e.offsetY);
        };

        // Untuk touch device
        const startDrawingTouch = (e) => {
            drawing.value = true;
            const rect = canvas.value.getBoundingClientRect();
            const touch = e.touches[0];
            ctx.value.moveTo(touch.clientX - rect.left, touch.clientY - rect.top);
        };

        const drawTouch = (e) => {
            if (!drawing.value) return;
            const rect = canvas.value.getBoundingClientRect();
            const touch = e.touches[0];
            ctx.value.lineTo(touch.clientX - rect.left, touch.clientY - rect.top);
            ctx.value.stroke();
            ctx.value.beginPath();
            ctx.value.moveTo(touch.clientX - rect.left, touch.clientY - rect.top);
        };

        const clearCanvas = () => {
            ctx.value.clearRect(0, 0, canvas.value.width, canvas.value.height);
        };

        const isCanvasBlank = (myCanvas) => {
            const ctx = myCanvas.getContext('2d');
            const pixelBuffer = ctx.getImageData(0, 0, myCanvas.width, myCanvas.height).data;

            // pixelBuffer berupa array RGBA
            return !pixelBuffer.some(channel => channel !== 0);
        };


        const saveSignature = async () => {
            const form = document.querySelector("#register-form");

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            signatureData.value = canvas.value.toDataURL('image/png');
            form_register.signature = signatureData.value;
            if (isCanvasBlank(canvas.value)) {
                message_modal.value = "Lengkapi tanda tangan";
                $("#modalDefault").modal("show");
            }
            else {
                try {
                    const res = await ApiService.post('/Register', form_register);
                    if(res.status){
                        QRCode.toCanvas(QrCanvas.value, res.data.qr_code, { width: 250 }, function (error) {
                            if (error) {
                                message_modal.value = "Gagal Generate QR Code";
                                $("#modalDefault").modal("show");
                            } else {
                                $("#modalQr").modal("show");
                            }
                        });
                    } else {
                        message_modal.value = res.message;
                        $("#modalDefault").modal("show");
                    }
                    
                } catch (err) {
                    message_modal.value = err.message;
                    $("#modalDefault").modal("show");
                }
            }
            
        };
        const downloadQr = () => {
            const canvas2 = document.getElementById("MyCanvas");
            const imageUrl = canvas2.toDataURL("image/png");
            const link = document.createElement("a");
            link.href = imageUrl;
            link.setAttribute("download", "QR-wonder_harmony.png");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            setTimeout(() => {
                window.open('https://www.instagram.com/subditkemitraanumatislam?igsh=dTd2dmVkbWF0Zmhp', '_blank');
                window.location.reload();
            }, 1000);

        };

        const onChangeData = (key, event) => {
            console.log(key, event.target.value);
        };

        return { canvas, startDrawing, stopDrawing, draw, 
            startDrawingTouch, drawTouch, clearCanvas, saveSignature, signatureData,
            form_register, field_register, error_register, QrCanvas,
            downloadQr, message_modal, isCanvasBlank,
            onChangeData
        };
    }
}).mount('#app');
</script>
@endsection