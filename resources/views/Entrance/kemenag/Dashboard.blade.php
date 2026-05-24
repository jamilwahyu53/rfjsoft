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
    <div class="col-lg-6 col-md-12 col-sm-12 mb-3 mt-4">
      <div class="row">
        <div class="col-12">
          <div class="card shadow-lg rounded-4 border-0 p-4 h-100 w-100">
            <div class="card-header bg-info">
              <label class="float-start text-white"><strong>ID Card</strong></label>
            </div>
            <div class="card-body">
              <div class="row">
                <base-input
                        v-for="field in field_profile"
                        :key="field.model"
                        v-model="form_profile[field.model]"
                        :label="field.label"
                        :placeholder="field.placeholder"
                        :type="field.type"
                        :options="field.options"
                        :col="field.col"
                    ></base-input>  
              </div>
              <div class="row">
                <hr>
                <div class="col-12">
                  <label class="text-info float-end">*PASSCODE dapat di download setelah pembayaran VALID</label>
                  <button class="btn btn-danger text-white btn-sm float-end" @click="eventDownload"
                  @if($isStHotel->st_hotel === 1 && $isStHotel->st_pembayaran != 'Pembayaran Tervalidasi')
                    disabled
                  @endif
                  >
                    DOWNLOAD PASSCODE
                  </button>
                </div>
                
              </div>
            </div>
          </div>
        </div>
      </div>  
    </div>
    
    @if($isStHotel->st_hotel == 1)
    <div class="col-lg-6 col-md-12 col-sm-12 mb-3 mt-4">
      <div class="card shadow-lg rounded-4 border-0 p-4 h-100 w-100">
        <div class="card-header 
          @if ($isStHotel->st_pembayaran == 'Proses Validasi')
              bg-warning
          @elseif ($isStHotel->st_pembayaran == 'Pembayaran Tervalidasi')
              bg-success
          @else
              bg-danger
          @endif
        ">
          <label class="float-start text-white"><strong>{{ $isStHotel->st_pembayaran }}</strong></label>
          <br>
          <p class="text-white m-0 p-0" style="font-size: 0.7rem;">
            * Sebesar {{ number_format($isStHotel->sub_total, 0, ',', '.') }} 
            untuk {{ $isStHotel->per_day }} malam dengan jenis {{ $isStHotel->jenis_kamar }} bed
            
          </p>
          <p class="text-white mt-2" style="font-size: 0.7rem;"> Rekening Tujuan <br>
            BRI 
            PT AVELIS HARMONI NUSANTARA
            005101888880302</p>
        </div>
        <div class="card-body">
          <div class="row">
            <form id="pembayaran-form" @submit.prevent="saveBuktiBayar">
              
              <base-input
                  v-for="field in field_pembayaran"
                  :key="field.model"
                  v-model="form_pembayaran[field.model]"
                  :label="field.label"
                  :placeholder="field.placeholder"
                  :type="field.type"
                  :options="field.options"
                  :col="field.col"
                  :required= "field.required"
                  :readonly="field.readonly"
                  :hide="field.hide"
              ></base-input>

              <button class="btn btn-success text-white btn-sm float-end" 
                @if($isStHotel->st_pembayaran != 'Tagihan Pembayaran' && $isStHotel->st_pembayaran != 'Pembayaran Tidak Valid')
                  disabled
                @endif
                >
                UPLOAD
              </button>
            </form>
            <hr class="m-2">
          </div>
          @if(!empty($isStHotel->bukti_bayar))
          <div class="row mt-2 mb-2">
            <div class="col-12">
              <label><strong>Preview Pembayaran</strong></label><br>
              <img src="{{ $isStHotel->bukti_bayar }}" style="height: 150px;" alt="Foto" class="img-fluid">
            </div>
          </div>
          @endif
        </div>
      </div>  
    </div>
    @endif



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


@include('EntranceComponents.ModalDefault', ['id' => 'modalDefault', 'title' => 'Info'])
@include('EntranceComponents.ModalQr', ['id' => 'modalQr', 
        'title' => 'Download QR Untuk Ditukar Dengan Ticket'])


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
        const QrCanvas = ref(null);

        const field_pembayaran = reactive([
            {
                model: "bukti_bayar",
                label: "Bukti Transfer",
                type: "input_image",
                col: "12",
                required: true,
            },
                
        ]);
        const field_profile = reactive([
            {
                model: "nip",
                label: "NIP",
                type: "label",
                col: "12",
            },  
            {
                model: "full_name",
                label: "Nama Lengkap",
                type: "label",
                col: "12",
            },
            {
                model: "organization",
                label: "Unit Kerja/ Instansi",
                type: "label",
                col: "12",
            },
            {
                model: "email",
                label: "Email",
                type: "label",
                col: "12",
            },
          ]);
        const form_pembayaran = reactive({});
        const form_profile = reactive({});

        field_pembayaran.forEach((field) => {
            form_pembayaran[field.model] = "";
        });
        field_profile.forEach((field) => {
            form_profile[field.model] = "";
        });

        const myData = {
          "nip" : '{{ data_get($userLogin['data'], 'nip') }}',
          "full_name" : '{{ data_get($userLogin['data'], 'full_name') }}',
          "organization" : '{{ data_get($userLogin['data'], 'organization') }}',
          "email" : '{{ data_get($userLogin['data'], 'email') }}',
        };
        Object.assign(form_profile, myData);
        
        const validateForm = () => {
            let isValid = true;
            
            field_pembayaran.forEach((field) => {
                const key = field.model;

                // hanya cek field yang required
                if (field.required) {
                    if (!form_pembayaran[key] || form_pembayaran[key].toString().trim() === "") {
                        isValid = false;
                    } 
                } 
            });
            
            return isValid;
        };

        const saveBuktiBayar = async () => {
          if(validateForm()){
            form_pembayaran.create_by = '{{ data_get($userLogin['data'], 'code', data_get($userLogin['data'], 'Kode')) }}'
            const res = await ApiService.post('/InsertBuktiTransfer', form_pembayaran);
            if(res.status){
                message_modal.value = "Pembayaran Berhasil";
                $("#modalDefault").modal("show");

                setTimeout(() => {
                    location.reload();
                }, 1000);

            } else {
                message_modal.value = res.message;
                $("#modalDefault").modal("show");
            }
          } else {
            alert("Lengkapi Form Anda !");
          }
        };
 
        const eventDownload = () => {
          QRCode.toCanvas(QrCanvas.value, '{{ data_get($userLogin['data'], 'code', data_get($userLogin['data'], 'Kode')) }}', { width: 250 }, function (error) {
              if (error) {
                  message_modal.value = "Gagal Generate QR Code";
                  $("#modalDefault").modal("show");
              } else {
                  $("#modalQr").modal("show");
              }
          });
        }
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
                $("#modalQr").modal("show");
            }, 1000);
          }

        onMounted(() => {
           $('#tblReportTap').DataTable({
                scrollX: true,
                autoWidth: false,
               
            });
        });
        onBeforeMount(() => {
            
        });

        return { 
          form_pembayaran,
          field_pembayaran,    
          saveBuktiBayar,
          validateForm,
          message_modal,
          form_profile,
          field_profile,
          QrCanvas,
          downloadQr,
          eventDownload
        };
    }
}).mount('#app');
</script>
@endsection