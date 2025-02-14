<template>
    <App>
        <Toast :toast="this.message" :type="this.labelType" @clear="clearMessage"></Toast>
        <!-- CONTENT -->
        <div class="flex-grow flex flex-col">
            <div class="bg-white border-b border-gray-200">
                <div class="w-11/12 mx-auto flex justify-between items-center">
                    <h1 class="my-5 text-4xl font-bold">Editar Empresa</h1>
                    <div>
                        <button button class="btn btn-primary btn-sm mr-2" @click.prevent="submit">Guardar</button>
                        <a class="btn btn-primary btn-sm" :href="route('companie')">Volver</a>
                    </div>
                </div>
            </div>


            <div class="w-11/12 mx-auto mt-4">
                <div class="card w-full bg-base-100 shadow-xl">

                    <div class="card-body">
                        <h2 class="card-title">Datos de Empresa</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 sm:grid-cols-1 lg:grid-cols-3 gap-4 ">
                            <div class="mt-4 ">
                                <label for="description"
                                    class="block text-sm font-semibold text-gray-700">Nombre</label>
                                <input v-model="form.description" type="text" name="description" id="description"
                                    class="mt-1 input input-bordered w-full" />
                            </div>

                            <div class="mt-4 col-span-2">
                                <label for="address" class="block text-sm font-semibold text-gray-700">Dirección</label>
                                <input v-model="form.address" type="text" name="address" id="address"
                                    class="mt-1 input input-bordered w-full" />
                            </div>
                        </div>
                        <h2 class="card-title mt-4">Datos de Contacto</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 sm:grid-cols-1 lg:grid-cols-3 gap-4 ">
                            <div class="mt-4 ">
                                <label for="contact_name" class="block text-sm font-semibold text-gray-700">Apellido y
                                    Nombre</label>
                                <input v-model="form.contact_name" type="text" name="contact_name" id="contact_name"
                                    class="mt-1 input input-bordered w-full" />
                            </div>

                            <div class="mt-4 ">
                                <label for="contact_email"
                                    class="block text-sm font-semibold text-gray-700">Email</label>
                                <input v-model="form.contact_email" type="text" name="contact_email" id="contact_email"
                                    class="mt-1 input input-bordered w-full" />
                            </div>

                            <div class="mt-4 ">
                                <label for="contact_phone"
                                    class="block text-sm font-semibold text-gray-700">Teléfono</label>
                                <input v-model="form.contact_phone" type="text" name="contact_phone" id="contact_phone"
                                    class="mt-1 input input-bordered w-full" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card w-full bg-base-100 shadow-xl mt-4 mb-4">
                    <div class="card-body">
                        <h2 class="card-title mb-3">Competencias</h2>
                        <div class="w-full mx-auto flex items-center">
                            <div class="mr-3">
                                <label for="category"
                                    class="block text-sm font-semibold text-gray-700">Competencia</label>
                                <select class="select w-full mt-1 input-bordered" v-model="this.competencia_select"
                                    name="competencia_select" id="competencia_select">
                                    <option value="" disabled selected>Seleccione una competencia</option>
                                    <option v-for="com in competencias" :key="com.id" :value="com">{{ com.competencia }}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <button class="btn btn-primary btn-sm mt-2"
                                    @click.prevent="add_competencias_relacionadas">Agregar</button>
                            </div>
                        </div>

                        <h3 class="block text-xl font-medium text-gray-800 mt-6">Competencias Asignadas</h3>

                        <div class="space-y-4 pt-2 pb-5">
                            <div v-for="cs in this.competencias_select" :key="cs.id"
                                class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-md font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10 mr-2">
                                {{ cs.competencia }} <button @click="remove_competencia_relaciona(cs.id)">
                                    <Icons name="trash" class="h-6 w-6 ml-2 text-red-400 hover:text-red-600" />
                                </button>
                            </div>
                        </div>


                        <hr class="my-4">

                        <div class="form-control w-6/12">
                            <label class="label cursor-pointer justify-start">
                                <input v-model="form.show_competencias" 
                                type="checkbox" :checked="form.show_competencias" class="checkbox checkbox-primary mr-4" />
                                <span class="text-lg text-gray-800 ">Mostrar las competencias en el Home</span>
                            </label>
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
                        <ListDiagnosticoComponent :diagnosticos=diagnosticos :competencias="this.competencias_asociadas"
                            :categorias="categorias" :sectores="this.sectores.data" @message="messageToast" @refreshData="getDiagnosticosCompany()"
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
                                <a class="btn btn-primary btn-sm" :href="route('user')">Agregar Usuario</a>
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
                        <ListSectoresComponent :sectores=this.sectores @message="messageToast"
                            @refreshData="getSectoresCompany()"
                            @getSectoresCompanyPaginate="getSectoresCompanyPaginate()" />
                    </div>
                </div>
            </div>
        </div>
        <!-- / CONTENT -->
        <CreateDiagnosticoComponent :open="showCreateDiagnostico" :idCompany=companie.id
            :competencias="this.competencias_asociadas" :categorias="categorias" 
            :sectores="this.sectores.data" @message="messageToast"
            @refreshData="getDiagnosticosCompany()" @closeCreateDiagnostico="closeCreateDiagnostico()"/>

        <CreateSectorComponent :open="showCreateSector" :idCompany=companie.id @message="messageToast"
            @refreshData="getSectoresCompany()" @closeCreateSector="closeCreateSector()" />

    </App>

</template>

<script>
import App from '@/Layouts/App.vue'
import Icons from '@/Layouts/Components/Icons.vue'
import Toast from '@/Layouts/Components/Toast.vue'
import ListUser from './User/ListUser.vue'
import ListDiagnosticoComponent from './Diagnostico/ListDiagnostico.vue'
import CreateDiagnosticoComponent from './Diagnostico/Create.vue';
import CreateSectorComponent from './Sectores/Create.vue';
import ListSectoresComponent from './Sectores/List.vue'

export default {
    props: {
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
        ListDiagnosticoComponent,
        CreateDiagnosticoComponent,
        CreateSectorComponent,
        ListSectoresComponent
    },

    data() {
        return {
            form: {},
            competencias_select: [],
            sectores_select: [],
            showToast: false,
            message: "",
            labelType: "success",
            showCreateDiagnostico: false,
            // Diagnosticos
            diagnosticos: {},
            showCreateSector: false,
            sectores: {}
        }
    },
    methods: {
        clearMessage() {
            this.message = ""
        },
        messageToast(data) {
            this.labelType = data.labelType;
            this.message = data.message;
        },
        closeCreateDiagnostico() {
            this.showCreateDiagnostico = false
        },
        closeCreateSector() {
            this.showCreateSector = false
        },
        submit() {
            this.form.competencias_select = this.competencias_select
            this.$inertia.post(route('companie.update', this.form.id), this.form)
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
        },
        add_competencias_relacionadas() {
            let resultado = this.competencias.find(competencia => competencia.id === this.competencia_select.id);
            //Verificar que competencia no se encuentre incluida previamente.
            if (resultado) {
                let existe_competencia = this.competencias_select.find(competencia => competencia.id === this.competencia_select.id);
                if (existe_competencia) {
                    this.labelType = "danger"
                    this.message = 'La competencia fue incluida previamente'
                } else {
                    let comp = {
                        'competencia': this.competencia_select.competencia,
                        'id': this.competencia_select.id,
                    }
                    this.competencias_select.push(comp)
                    this.competencia_select = ''
                    this.labelType = "success"
                    this.message = 'Se ha agregado correctamente'
                }
            } else {
                this.labelType = "danger"
                this.message = 'No se ha detectado una competencia valida'
            }
        },
        remove_competencia_relaciona(id) {
            const index = this.competencias_select.findIndex(item => item.id === id);
            this.competencias_select.splice(index, 1);
        },

        // DIAGNOSTICOS
        async getDiagnosticosCompany() {

            const get = `${route('company.diagnosticos', this.companie.id)}`

            const response = await fetch(get, { method: 'GET' })
            this.diagnosticos = await response.json()
        },
        async getDiagnosticosCompanyPaginate(data) {
            var get = `${data}`;
            const response = await fetch(get, { method: 'GET' })
            this.diagnosticos = await response.json()
        }
    },
    created() {
        this.getDiagnosticosCompany();
        this.getSectoresCompany()
    },
    mounted() {
        this.competencias_select = JSON.parse(JSON.stringify(this.competencias_asociadas));
        this.form.id = this.companie.id
        this.form.description = this.companie.description
        this.form.address = this.companie.address
        this.form.contact_name = this.companie.contact_name
        this.form.contact_email = this.companie.contact_email
        this.form.contact_phone = this.companie.contact_phone
        this.form.show_competencias = this.companie.show_competencias
    }
}

</script>
