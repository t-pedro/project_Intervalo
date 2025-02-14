<template>
    <App>
        <Toast :toast="this.message" :type="this.labelType" @clear="clearMessage"></Toast>
        <!-- CONTENT -->
        <div class="flex-grow flex flex-col">
            <div class="w-11/12 mx-auto flex justify-between items-center">
                <h1 class="my-5 text-4xl font-bold">Empresa | {{this.companie.description}}</h1>
            </div>
            <hr>

            <div class="w-11/12 mx-auto mt-4">
                <div class="card w-full bg-base-100 shadow-xl">
                   
                    <div class="card-body">
                        <h2 class="card-title">Datos de Empresa</h2>
                        <hr>
                        <div class="grid grid-cols-1 md:grid-cols-2 sm:grid-cols-1 lg:grid-cols-3 gap-4 ">
                            <div>
                                <label for="time"
                                    class="block text-sm font-medium text-gray-700 "><b>Empresa: </b>{{ this.companie.description }}</label>
                                <label for="time"
                                    class="block text-sm font-medium text-gray-700 "><b>Dirección: </b>{{ this.companie.address ?? '-' }}</label>
                            </div>
                        </div>
                        <h2 class="card-title mt-4">Datos de Contacto</h2>
                        <hr>
                        <div class="grid grid-cols-1 md:grid-cols-2 sm:grid-cols-1 lg:grid-cols-3 gap-4 ">
                            <div>
                                <label for="time"
                                    class="block text-sm font-medium text-gray-700 "><b>Nombre: </b>{{ this.companie.contact_name ?? '-' }}</label>
                                <label for="time"
                                    class="block text-sm font-medium text-gray-700 "><b>Email: </b>{{ this.companie.contact_email ?? '-' }}</label>
                                <label for="time"
                                    class="block text-sm font-medium text-gray-700 "><b>Teléfono: </b>{{ this.companie.contact_phone ?? '-' }}</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card w-full bg-base-100 shadow-xl mt-4 mb-4">
                   <div class="card-body">
                       <h2 class="card-title">Competencias Relacionadas</h2>
                        <div>
                            <label for="time"
                            class="block text-xl font-medium text-gray-800">Detalle de Competencias</label>
                        </div>
                        <div class="space-y-2 pt-2 pb-5">
                            <div v-for="cs in this.competencias_select" :key="cs.id" class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-md font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10 mr-2">
                                {{ cs.competencia}}
                            </div>
                        </div>
                   </div>
               </div>

               <div class="card w-full bg-base-100 shadow-xl mt-4 mb-4">
                    <div class="card-body">
                        <div class="w-full mx-auto flex justify-between items-center">
                            <div class="mt-4">
                                <h2 class="card-title">Diagnósticos</h2>
                            </div>
                            <div>
                                <a class="btn btn-primary btn-sm" @click="showCreateDiagnostico = true">Agregar</a>
                            </div>
                        </div>
                        <ListDiagnosticoComponent :diagnosticos=diagnosticos 
                            :competencias="this.competencias_asociadas"
                            :categorias="categorias"
                            :sectores="sectores.data"
                            @message="messageToast"
                            @refreshData="getDiagnosticosCompany()"
                            @getDiagnosticosCompanyPaginate="getDiagnosticosCompanyPaginate" />
                    </div>
                </div>


               <div class="card w-full bg-base-100 shadow-xl mt-4 mb-4">
                   <div class="card-body">
                        <div class="w-full mx-auto flex justify-between items-center">
                            <div class="mt-4">
                                <h2 class="card-title">Usuarios Relacionados</h2>
                            </div>
                            <div>
                                <a class="btn btn-primary btn-sm" :href="route('user')">Agregar/Editar Usuario</a>
                            </div>
                        </div> 
                       
                       <ListUser :idCompany=companie.id></ListUser>
                   </div>
               </div>

               <div class="card w-full bg-base-100 shadow-xl mt-4 mb-4">
                    <div class="card-body">
                        <div class="w-full mx-auto flex justify-between items-center">
                            <div class="mt-4">
                                <h2 class="card-title">Sectores</h2>
                            </div>
                            <div>
                                <a class="btn btn-primary btn-sm" @click="showCreateSector = true">Agregar</a>
                            </div>
                        </div>
                        <ListSectoresComponent :sectores=sectores
                            @message="messageToast"
                            @refreshData="getSectoresCompany()"
                            @getSectoresCompanyPaginate="getSectoresCompanyPaginate()"/>
                    </div>
                </div>
            </div>  
        </div>

        <!-- / CONTENT -->

        <CreateDiagnosticoComponent :open="showCreateDiagnostico" :idCompany=companie.id 
            :competencias="this.competencias_asociadas"
            :categorias="categorias"
            :sectores="sectores.data"
            @message="messageToast"
            @refreshData="getDiagnosticosCompany()" 
            @closeCreateDiagnostico="closeCreateDiagnostico()" />

        <CreateSectorComponent :open="showCreateSector" :idCompany=companie.id 
            @message="messageToast"
            @refreshData="getSectoresCompany()" 
            @closeCreateSector="closeCreateSector()" />

   </App>
  
</template>

<script>
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import App from '@/Layouts/App.vue'
    import Pageheader from '@/Layouts/Pageheader.vue'
    import Icons from '@/Layouts/Components/Icons.vue'
    import Toast from '@/Layouts/Components/Toast.vue'
    import ListUser from '../User/ListUser.vue'

    import CreateDiagnosticoComponent from '../Diagnostico/Create.vue';
    import ListDiagnosticoComponent from '../Diagnostico/ListDiagnostico.vue'

    import CreateSectorComponent from '../Sectores/Create.vue';
    import ListSectoresComponent from '../Sectores/List.vue'

    export default {
        props:{
             competencias: Object,
             companie: Object,
             competencias_asociadas: Object,
             categorias: Object
        },
        components: {
            App,
            Icons,
            Toast,
            ListUser,
            CreateDiagnosticoComponent,
            ListDiagnosticoComponent,
            CreateSectorComponent,
            ListSectoresComponent
        },

        data(){
            return{
                form: {},
                competencias_select: [],
                showToast: false,
                message: "",
                labelType: "success",
                showCreateDiagnostico: false,
                showCreateSector: false,
                diagnosticos: {},
                sectores: {}
            }
        },
        methods:{
            clearMessage() {
                this.message = ""
            },
            closeCreateDiagnostico() {
                this.showCreateDiagnostico = false
            },
            closeCreateSector() {
                this.showCreateSector = false
            },
            submit(){
                this.form.competencias_select = this.competencias_select
                this.$inertia.post(route('companie.update', this.form.id), this.form)
            },
            add_competencias_relacionadas(){
                let resultado = this.competencias.find( competencia => competencia.id === this.competencia_select.id );
                //Verificar que competencia no se encuentre incluida previamente.
                if(resultado){
                    let existe_competencia = this.competencias_select.find( competencia => competencia.id === this.competencia_select.id );
                    if(existe_competencia){
                        this.labelType = "danger"
                        this.message = 'La competencia fue incluida previamente'
                    }else{
                        let comp = {
                            'competencia' : this.competencia_select.competencia,
                            'id' : this.competencia_select.id,
                        }
                        this.competencias_select.push(comp)
                        this.competencia_select = ''
                        this.labelType = "success"
                        this.message = 'Se ha agregado correctamente'
                    }
                }else{
                    this.labelType = "danger"
                    this.message = 'No se ha detectado una competencia valida'
                }
            },
            async getDiagnosticosCompany() {

                const get = `${route('company.diagnosticos', this.companie.id)}`

                const response = await fetch(get, { method: 'GET' })
                this.diagnosticos = await response.json()
            },
            async getDiagnosticosCompanyPaginate(data) {
                var get = `${data}`;
                const response = await fetch(get, { method: 'GET' })
                this.diagnosticos = await response.json()
            },
            async getSectoresCompany() {

                const get = `${route('company.sectores', this.companie.id)}`

                const response = await fetch(get, { method: 'GET' })
                this.sectores = await response.json()
            },
            async getSectoresCompanyPaginate(data) {
                var get = `${data}`;
                const response = await fetch(get, { method: 'GET' })
                this.sectores = await response.json()
            }
        },
        created(){
            this.getDiagnosticosCompany(),
            this.getSectoresCompany()
        },
        mounted(){
            this.competencias_select = JSON.parse(JSON.stringify(this.competencias_asociadas));
            this.form.id = this.companie.id
            this.form.description = this.companie.description
            this.form.address = this.companie.address
            this.form.contact_name = this.companie.contact_name
            this.form.contact_email = this.companie.contact_email
            this.form.contact_phone = this.companie.contact_phone
        }     
    }

</script>

