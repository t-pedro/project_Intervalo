<template>
    <App>
        <Toast :toast="this.message" :type="this.labelType" @clear="clearMessage"></Toast>
        <!-- CONTENT -->
        <div class="flex-grow flex flex-col">
            <div class="w-11/12 mx-auto flex justify-between items-center">
                <h1 class="my-5 text-4xl font-bold">Importar Usuarios</h1>
                <div>
                    <a class="btn btn-primary btn-sm" :href="route('user.donwloadTemplate')" _blank>Plantilla</a>
                    <a class="btn btn-primary btn-sm ml-4" :href="route('user')">Volver</a>
                </div>
            </div>
            <hr>
            <div class="w-11/12 mx-auto mt-4">
                <div class="card w-full bg-base-100 shadow-xl mt-4 mb-4">
                   
                   <div class="card-body">
                       <h2 class="card-title">Carga Masiva de Usuarios</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 sm:grid-cols-1 lg:grid-cols-3 gap-4 ">
                            <div class="mt-4">
                                <label for="category" class="block text-sm font-semibold text-gray-700">Empresa</label>
                                <select class="select w-full mt-1 input-bordered" v-model="this.idCompanie" name="competencia_select" id="competencia_select">
                                    <option value="" disabled selected>Seleccione una Empresa</option>
                                    <option v-for="c in companies" :key="c.id" :value="c.id">{{c.description}}</option>
                                </select>
                            </div>
                            
                            <div class="mt-4">
                                <label for="address" class="block text-sm font-semibold text-gray-700">Archivo</label>
                                <input @change="handleFileChange" type="file" name="file" id="file" class="mt-1 file-input file-input-bordered w-full max-w-xs"
                                    ref="inputfile" autocomplete="off"  />
                            </div>
                        </div>
                        <div class="text-right">
                            <button class="btn btn-primary btn-sm" @click.prevent="importar()">Importar</button>
                        </div>
                        <hr>
                        <div>
                            <label for="time"
                            class="block text-xl font-medium text-gray-800">Resultados</label>
                        </div>
                        <div v-if="this.status" class="px-4 mt-6 sm:px-6 lg:px-8 bg-orange-100 border-l-4 border-orange-500 text-orange-700 p-4 rounded-md"
                            role="alert" v-html="status">
                        </div>
                        <div v-else >
                            --
                        </div>
                   </div>
               </div>
            </div>
        </div>
   </App>  
</template>

<script>
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import App from '@/Layouts/App.vue'
    import Pageheader from '@/Layouts/Pageheader.vue'
    import Pagination from '@/Layouts/Pagination'
    import Icons from '@/Layouts/Components/Icons.vue'        
    import Toast from '@/Layouts/Components/Toast.vue'
    import { Dialog, DialogOverlay, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'

    export default {
        props:{
             companies: Object,
        },
        components: {
            App,
            Icons,
            Link,
            Pagination,
            Dialog,
            DialogOverlay,
            DialogTitle,
            TransitionChild,
            TransitionRoot,
            Toast,
        },

        data(){
            return{
                idCompanie: '',
                file: '',
                fileName: '',
                status: '',
                showToast: false,
                message: "",
                labelType: "success"  
            }
        },
        methods:{
            clearMessage() {
                this.message = ""
            },
            handleFileChange(event) {
                this.file = event.target.files[0];
                this.fileName = this.file ? this.file.name : '';
            },
            async importar() {
            if (this.idCompanie != '') {
                if (this.file != '') {
                    //this.loading = true
                    this.status = ''
                    let rt = route("user.import");
                    const formData = new FormData();
                    formData.append('file', this.file);
                    formData.append('idCompanie', this.idCompanie);
                    try {
                        const response = await axios.post(rt, formData);
                        if (response.status == 200) {
                            this.labelType = "success";
                            this.message = response.data.message;
                            this.status = response.data.status;
                            console.log(response)
                        } else {
                            this.labelType = "danger";
                            this.message = response.data.message;
                        }
                    } catch (error) {
                        console.log(error);
                        this.labelType = "info";
                        this.message = "El proceso de importación continuará ejecutandose en segundo plano, puede verificarlo en los Logs.";
                    }
                    //this.loadingDependencia = false
                } else {
                    this.labelType = "info";
                    this.message = "Debe seleccionar un archivo";
                }
            } else {
                console.log('DEBE EMPRESA')
                this.labelType = "info";
                this.message = "Debe seleccionar una Empresa";
            }
        },
        },
        created(){

        }        
    }

</script>
