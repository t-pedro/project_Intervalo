<template>
    <App>
        <Toast :toast="this.message" :type="this.labelType" @clear="clearMessage"></Toast>
        <!-- CONTENT -->
        <div class="flex-grow flex flex-col">
            <div class="w-11/12 mx-auto flex justify-between items-center">
                <h1 class="my-5 text-4xl font-bold">Empresas</h1>
                <div>
                    <a class="btn btn-primary btn-sm" :href="route('companie.create')">Nuevo</a>
                </div>
            </div>
            <hr>
            <div class="w-11/12 mx-auto mt-4">
                <div class="shadow sm:rounded-md sm:overflow-hidden">
                    <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
                        <div class="flex items-center justify-between flex-wrap sm:flex-nowrap ">
                            <div class="flex">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Filtro</h3>
                            </div>
                            <div class="flex mt-4 sm:mt-0">
                            
                            <div>
                                <button v-if="Object.keys(this.filter).length" class="text-xs font-medium text-gray-500 hover:text-gray-700 mr-2" 
                                    @click="clearFilter">Limpiar Filtro</button>
                                <button type="button"
                                    class="btn btn-primary btn-outline btn-sm mr-4" @click="getCompanie()">Aplicar Filtro</button>
                            </div>
                            <div>
                                <label class="font-semibold mr-2" for="">Ver: </label>
                                <select class="text-sm border-gray-300 rounded-md" v-model="length" @change="getCompanie">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>
                        </div>
                        <div class="grid grid-cols-12 gap-6">
                            <div class="col-span-12 sm:col-span-3 ">
                                <input v-model="filter.description" type="text" name="name" id="name" autocomplete="name-level2" placeholder="Buscar Empresa."
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                            </div>
                            <div class="col-span-12 sm:col-span-3 ">
                                <input v-model="filter.contact" type="text" name="email" id="email" autocomplete="name-level2" placeholder="Buscar Contacto."
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                            </div>
                            <!-- <div class="col-span-12 sm:col-span-3 ">
                                <select class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" 
                                    v-model="filter.status" placeholder="Buscar Estado.">
                                    <option value="" disabled selected>Seleccione un estado</option>
                                    <option value="1">Activo</option>
                                    <option value="0">Inactivo</option>
                                </select>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-11/12 mx-auto mt-4">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-center" style="z-index: 0;">Empresa</th>
                            <th class="px-6 py-4 text-center">Contacto</th>
                            <th class="px-6 py-4 text-center">Email</th>
                            <th class="px-6 py-4 text-center">Telefono</th>
                            <th class="px-6 py-4 text-center">Estado</th>
                            <th class="px-6 py-4 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <!-- row 1 -->
                        <tr v-for="c in companies.data" :key="c.id" class="bg-white hover:bg-gray-50 border-b border-gray-100 ">
                            <td class="w-3/13 text-center">
                                {{c.description}}
                            </td>
                            <td class="w-2/13 text-center">
                                {{c.contact_name ?? '-'}}
                            </td>
                            <td class="w-2/13 text-center">
                                {{c.contact_email ?? '-'}}
                            </td>
                            <td class="w-2/13 text-center">
                                {{c.contact_phone ?? '-'}}
                            </td>
                            <td class="w-1/13 text-center">
                                <div v-if="c.active == 1" class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                    Activo
                                </div>
                                <div v-else class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">
                                    Inactivo
                                </div>
                            </td>
                            <td class="w-2/13 text-center">
                                <a :href="route('companie.edit', c.id)" title="Editar Empresa">
                                    <Icons class="w-5 h-5 inline" name="edit" />
                                </a>
                                <a class="ml-2" @click="this.form = c, this.editing = true, this.open = true" title="Detalle Empresa">
                                    <Icons class="w-5 h-5 inline" name="details" />
                                </a>

                                <a class="ml-2 cursor-pointer" v-if="c.active == 1" @click="this.companie = c, this.showConfirmed = true, title='¿Desea deshabilitar la Empresa?'" title="Deshabilitar Empresa" >
                                    <Icons class="w-6 h-6 inline text-green-500 hover:text-green-800" name="badge-check" />
                                </a>
                                <a class="ml-2 cursor-pointer" v-else @click="this.companie = c, this.showConfirmed = true, title='¿Desea habilitar la Empresa?'" title="Habilitar Empresa">
                                    <Icons class="w-6 h-6 inline text-gray-500 hover:text-gray-800" name="badge-check" />
                                </a>

                                <a class="ml-2 cursor-pointer" @click="this.companie = c, this.showDelete = true, title='¿Desea eliminar la Empresa?'" title="Eliminar Empresa">
                                    <Icons class="w-6 h-6 inline text-red-500 hover:text-red-800" name="trash" />
                                </a>

                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

           <div class="w-11/12 mx-auto my-5 flex justify-between items-center">
                <div>
                    Mostrando: {{this.companies.from}} a {{this.companies.to}} - Entradas encontradas: {{this.companies.total}}
                </div>
                <div class="flex flex-wrap -mb-1">
                    <div v-for="link in companies.links" :key="link.id">
                        <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded-md" v-html="link.label"> </div>
                        <div v-else 
                            class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border border-gray-300 rounded-md hover:bg-blue-500 hover:text-white cursor-pointer" 
                            :class="{ 'bg-blue-500': link.active },{ 'text-white': link.active }" 
                            @click="getCompaniePaginate(link.url)"
                            v-html="link.label"> </div>
                    </div> 
                </div>   
            </div>  
                
        </div>

        <!-- / CONTENT -->

        <!-- MANEJO DE ALTA DE USUARIO- -->
        <TransitionRoot as="template" :show="open">
            <Dialog as="div" class="fixed inset-0 overflow-hidden" @close="open = false">
                <div class="absolute inset-0 overflow-hidden">
                    <DialogOverlay class="absolute inset-0" />

                    <div class="fixed inset-y-0 pl-16 max-w-full right-0 flex">
                        <TransitionChild as="template"
                            enter="transform transition ease-in-out duration-500 sm:duration-700"
                            enter-from="translate-x-full" enter-to="translate-x-0"
                            leave="transform transition ease-in-out duration-500 sm:duration-700"
                            leave-from="translate-x-0" leave-to="translate-x-full">
                            <div class="w-screen max-w-md">
                                <form class="h-full divide-y divide-gray-200 flex flex-col bg-white shadow-xl"
                                    enctype="multipart/form-data">
                                    <div class="flex-1 h-0 overflow-y-auto">
                                        <div class="py-6 px-4 bg-blue-500 sm:px-6">
                                            <div class="flex items-center justify-between">
                                                <DialogTitle
                                                    class="text-lg font-medium text-white"> Detalle
                                                </DialogTitle>
                                                <div class="ml-3 h-7 flex items-center">
                                                    <button type="button"
                                                        class="bg-blue-500 rounded-md text-indigo-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-white"
                                                        @click="open = false">
                                                        <span class="sr-only">Cerrar</span>
                                                        <Icons class="w-5 h-5" name="closed" />
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-1 flex flex-col justify-between">
                                            <div class="px-4 divide-y divide-gray-200 sm:px-6">

                                                <div class="space-y-5 pt-2 pb-5">
                                                    <div>
                                                        <label for="time"
                                                            class="block text-xl font-medium text-gray-700 "><b>Empresa: {{ form.description }}</b></label>
                                                    </div>
                                                    <hr>
                                                    <div class="flex text-sm text-gray-700">
                                                        <label class="text-bold w-24 font-bold">Dirección:</label>
                                                        <span>{{ form.address}}</span>
                                                    </div>
                                                    
                                                    <hr>
                                                    <div>
                                                        <label class="block text-xl font-medium text-gray-800">Datos de Contacto</label>
                                                    </div>

                                                    <div class="flex text-sm text-gray-700">
                                                        <label class="text-bold w-24 font-bold">Nombre:</label>
                                                        <span>{{  form.contact_name ?? '-'  }}</span>
                                                    </div>

                                                    <div class="flex text-sm text-gray-700">
                                                        <label class="text-bold w-24 font-bold">Email:</label>
                                                        <span>{{  form.contact_email ?? '-'  }}</span>
                                                    </div>

                                                    <div class="flex text-sm text-gray-700">
                                                        <label class="text-bold w-24 font-bold">Teléfono:</label>
                                                        <span>{{  form.contact_phone ?? '-'  }}</span>
                                                    </div>

                                                    <hr class="my-6">
                                                    <div>
                                                        <label class="block text-xl font-medium text-gray-800">Competencias</label>
                                                    </div>
                                                    

                                                    <div v-for="c in this.form.competencias" :key="c.id" class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10 mr-2">
                                                        {{ c.competencia}}
                                                    </div>
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 px-4 py-4 flex justify-end">
                                        <button type="button"
                                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                            @click="open = false, this.vaciarForm()">Cerrar</button>
                                    </div>
                                </form>
                            </div>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>

        <!-- <TransitionRoot as="template" :show="openDelete">
            <Dialog as="div" class="relative z-20" @close="openDelete = false">
                <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0"
                    enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100"
                    leave-to="opacity-0">
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
                </TransitionChild>
                <div class="fixed z-20 inset-0 overflow-y-auto">
                    <div class="flex items-end sm:items-center justify-center min-h-full p-4 text-center sm:p-0">
                        <TransitionChild as="template" enter="ease-out duration-300"
                            enter-from="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                            enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-200"
                            leave-from="opacity-100 translate-y-0 sm:scale-100"
                            leave-to="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
                            <DialogPanel
                                class="relative bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full">
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="sm:flex sm:items-start">
                                        <div :class="this.companie.active == 1 ? 'bg-red-100' : 'bg-green-100'"
                                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full  sm:mx-0 sm:h-10 sm:w-10">
                                            <Icons class="h-6 w-6" :class="this.companie.active == 1 ? 'text-red-600' : 'text-green-600'" name="info" aria-hidden="true" />
                                        </div>
                                        <div v-if="this.companie.active == 1" class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                            <DialogTitle as="h3"
                                                class="text-lg leading-6 font-medium text-gray-900"> Deshabilitar
                                                Empresa </DialogTitle>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-500">Desea Deshabilitar la Empresa:
                                                    {{ this.companie.description }}</p>
                                            </div>
                                        </div>

                                        <div v-else class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                            <DialogTitle as="h3"
                                                class="text-lg leading-6 font-medium text-gray-900"> Habilitar
                                                Empresa </DialogTitle>
                                            <div class="mt-2">
                                                <p class="text-sm text-gray-500">Desea Habilitar la Empresa:
                                                    {{ this.companie.description }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button type="button" v-if="this.companie.active == 1"
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                                        @click="deshabilitar()">Deshabilitar</button>
                                    <button type="button" v-else
                                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm"
                                        @click="deshabilitar()">Habilitar</button>
                                    <button type="button"
                                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                        @click="openDelete = false" ref="cancelButtonRef">Cancelar</button>
                                </div>
                            </DialogPanel>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot> -->

        <ConfirmModal :show="showConfirmed" :id="companie.id"
        :title="this.title" @viewConfirmed="fnShowConfirmed"
        @responseConfirmed="fnConfirmed" />

        <DestroyModal :show="showDelete" :id="companie.id"
        :title="this.title" @viewDestroy="fnShowDestroy"
        @responseDestroy="fnDestroy" />
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

    import ConfirmModal from '@/Layouts/Components/Modals/ConfirmModal.vue';
    import DestroyModal from '@/Layouts/Components/Modals/DestroyModal.vue';

    export default {
        props:{
             toast: Object
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
            ConfirmModal,
            DestroyModal
        },

        data(){
            return{
                loading: true,
                length:   "10",
                search: "",
                companies:"",
                companie: "",
                filter: {},
                open: false,
                editing: false,
                openDelete: false,
                user: {},
                form: {},
                showToast: false,
                message: "",
                labelType: "success",     
                
                showConfirmed:false, // Modal Confirm
                showDelete:false, // Modal Confirm Destroy
            }
        },
        methods:{
            clearMessage() {
                this.message = ""
            },
            fnShowConfirmed(){
                this.showConfirmed = false
            },
            fnShowDestroy(){
                this.showDelete = false
            },
            clearFilter(){
                this.filter = {}
                this.getCompanie()
            },
            vaciarForm(){
                this.form = {};
            },
            async getCompanie(){

                let filter = `&length=${this.length}` 
                
                if(this.filter.description){
                    filter += `&description=${this.filter.description}`
                }

                if(this.filter.contact){
                    filter += `&contact=${this.filter.contact}`
                }

                if(this.filter.status){
                    filter += `&status=${this.filter.status}`
                }

                const get = `${route('companie.list')}?${filter}` 

                const response = await fetch(get, {method:'GET'})
                this.companies = await response.json() 
            },

            async getCompaniePaginate(link){

                var get = `${link}`;
                const response = await fetch(get,{method: 'GET'})

                this.companies = await response.json()
                console.log(this.companies)   
            },
            guardarCompanie() {
                let rt = '';
                if (this.editing) {
                    rt = route('companie.update', this.form.id);
                } else {
                    rt = route('companie.store');
                }

                axios.post(rt, this.form)
                    .then(response => {
                        this.open = false,
                            this.getCompanie()
                        if (response.status == 200) {
                            this.labelType = "success"
                            this.message = response.data['message']
                        } else {
                            this.labelType = "danger"
                            this.message = response.data['message']
                        }
                    })
                    this.vaciarForm();
            },
            async fnConfirmed() {
                try {
                    let rt = route('companie.active', this.companie.id);
                    const response = await axios.put(rt);
                    if (response.status === 200) {
                        this.labelType = "success"
                        this.message = response.data.message
                        this.getCompanie()
                    } else {
                        this.labelType = "danger"
                        this.message = response.data.message
                    }
                } catch (error) {
                    this.labelType = "danger"
                    this.message = error.response.data.message
                }
                this.fnShowConfirmed()
            },
            async fnDestroy() {
                try {
                    let rt = route('companie.destroy', this.companie.id);
                    const response = await axios.delete(rt);
                    if (response.status === 200) {
                        this.labelType = "success"
                        this.message = response.data.message
                        this.getCompanie()
                    } else {
                        this.labelType = "danger"
                        this.message = response.data.message
                    }
                } catch (error) {
                    this.labelType = "danger"
                    this.message = error.response.data.message
                }
                this.fnShowDestroy()
            }
        },
        created(){
            this.getCompanie()
        },
        mounted(){
            if(this.toast){
                if(this.toast.status == 200){
                    this.labelType = "success"
                    this.message = this.toast.message
                }else{
                    this.labelType = "danger"
                    this.message = this.toast.message
                }
            }
        }
    }

</script>

<style>
    .dropdown:hover .dropdown-menu {
        display: block;
        z-index: 999;
    }

    ul.dropdown-menu {
        right: 0px;
    }

</style>