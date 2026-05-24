@extends('EntranceTemplate.main', ['sidebars' => null])

@section('content')
<div id="app">
    <div class="container-fluid m-0 p-0">
    <div class="fullscreen-bg d-flex justify-content-center align-items-center">
        <div class="text-center">
            <div class="card shadow-lg fullscreen-card">
                <div class="card-body">
                    <img 
                        :src="groups.foto" 
                        alt="User Photo"
                    >
                    <h3>@{{ groups.full_name }}</h3>
                    <p>@{{ groups.satker }}</p>
                    <p class="text-muted">@{{ groups.organization }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

</div>

@endsection

@section('scripts')
<script type="module">
const { createApp, ref, onMounted, reactive, watch, nextTick } = Vue;

createApp({
    setup() {
        const groups = ref({});
        const code_gate = ref('{{ $code_gate }}');

        const fetchGroups = async () => {
            try { 
                const res = await ApiService.get('/GetRealtime/' + code_gate.value);
                if(res.status){

                   console.log(res.data);
                   groups.value = res.data;

                } else {
                    console.log(res.message);
                }
            } catch (err) {
                console.log(err.message);
            }  
        };

        onMounted(() => {
            fetchGroups(); // fetch pertama
            

        });
        
        return { 
            groups,
            code_gate,
            fetchGroups
        };
    }
}).mount('#app');
</script>
@endsection