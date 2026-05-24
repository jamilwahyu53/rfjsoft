@extends('EntranceTemplate.main', ['sidebars' => $sidebars])

@section('content')
<div id="app">

@include('EntranceComponents.Loading')


<div class="container-fluid m-0 p-0">
    <div class="fullscreen-bg d-flex justify-content-center align-items-center">
        <div class="text-center">
            <div class="card shadow-lg" style="width: 500px;">
                <div class="card-body">
                    <img 
                        :src="imgData" 
                        class="rounded-circle mb-2"
                        width="80"
                        height="80"
                        alt="User Photo"
                        style="object-fit: cover;"
                    >

                    <h3>@{{ tag.full_name }}</h3>
                    <p class="mb-1">@{{ tag.email }}</p>
                    <p class="text-muted">@{{ tag.organization }}</p>
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
const { createApp, ref, onMounted, onBeforeMount, reactive, watch } = Vue;

createApp({
    components: { BaseInput },
    setup() {
        
        const tag = ref(JSON.parse(localStorage.getItem("sharedTag") || "{}"));
        const imgData = ref('');


        onMounted(() => {
            window.addEventListener("storage", (event) => {
                if (event.key === "sharedTag") {
                    // ubah string menjadi object
                    
                    tag.value = JSON.parse(event.newValue);
                    imgData.value = tag.value.foto;
                }
            });
        });
        onBeforeMount(() => {
            
        });
        

        return { 
           tag, imgData
        };
    }
}).mount('#app');
</script>
@endsection