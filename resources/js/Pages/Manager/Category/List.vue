<template>
    <App>
        <Toast :toast="this.message" :type="this.labelType" @clear="clearMessage"></Toast>
        <!-- CONTENT -->
        <div class="flex-grow flex flex-col">
            <div class="w-11/12 mx-auto flex justify-between items-center">
                <h1 class="my-5 text-4xl font-bold">Categorias</h1>
                <div>
                    <a class="btn btn-primary btn-sm" @click="showCreate=true">Nueva</a>
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
                                    class="btn btn-primary btn-outline btn-sm mr-4" @click="getCategory()">Aplicar Filtro</button>
                            </div>
                            <div>
                                <label class="font-semibold mr-2" for="">Ver: </label>
                                <select class="text-sm border-gray-300 rounded-md" v-model="length" @change="getCategory">
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
                                <input v-model="filter.title" type="text" name="title" id="title" autocomplete="off" placeholder="Buscar Categoria."
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                            </div>
                            <div class="col-span-12 sm:col-span-3 ">
                                <input v-model="filter.description" type="text" name="description" id="description" autocomplete="off" placeholder="Buscar Descripción."
                                    class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-11/12 mx-auto mt-4">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-center" style="z-index: 0;">Titulo</th>
                            <th class="px-6 py-4 text-left">Descripción</th>
                            <th class="px-6 py-4 text-center">Estado</th>
                            <th class="px-6 py-4 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <!-- row 1 -->
                        <tr v-for="c in categories.data" :key="c.id" class="bg-white hover:bg-gray-50 border-b border-gray-100">
                            <td class="w-2/12 text-center uppercase">
                                {{c.title}}
                            </td>
                            <td class="truncate w-4/12 text-left">
                                <div class="truncate">
                                    {{c.description ?? '-'}}
                                </div>
                            </td>
                            <td class="w-3/12 text-center">
                                <div v-if="c.active == 1" class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                    Activo
                                </div>
                                <div v-else class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">
                                    Inactivo
                                </div>
                            </td>
                            <td class="w-3/12 text-center">
                                <a @click="this.category = c, this.showUpdate = true" title="Editar Categoria" class="cursor-pointer">
                                    <Icons class="w-5 h-5 inline" name="edit" />
                                </a>
                                <a class="ml-2 cursor-pointer" @click="this.category = c, this.showDetail = true" title="Detalle Categoria">
                                    <Icons class="w-5 h-5 inline" name="details" />
                                </a>
                                <a class="ml-2 cursor-pointer" v-if="c.active == 1" @click="this.category = c, this.showConfirmed = true, title='¿Desea deshabilitar la categoria?'" title="Deshabilitar Categoria" >
                                    <Icons class="w-6 h-6 inline text-green-500 hover:text-green-800" name="badge-check" />
                                </a>
                                <a class="ml-2 cursor-pointer" v-else @click="this.category = c, this.showConfirmed = true, title='¿Desea habilitar la categoria?'" title="Habilitar Categoria">
                                    <Icons class="w-6 h-6 inline text-gray-500 hover:text-gray-800" name="badge-check" />
                                </a>
                                <a class="ml-2 cursor-pointer" @click="this.category = c, this.showDeleteConfirmed = true, title='¿Desea eliminar la categoria?'" title="Eliminar Categoria">
                                    <Icons class="w-6 h-6 inline text-red-500 hover:text-red-800" name="trash" />
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

           <div class="w-11/12 mx-auto my-5 flex justify-between items-center">
                <div>
                    Mostrando: {{this.categories.from}} a {{this.categories.to}} - Entradas encontradas: {{this.categories.total}}
                </div>
                <div class="flex flex-wrap -mb-1">
                    <div v-for="link in categories.links" :key="link.id">
                        <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded-md" v-html="link.label"> </div>
                        <div v-else 
                            class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border border-gray-300 rounded-md hover:bg-blue-500 hover:text-white cursor-pointer" 
                            :class="{ 'bg-blue-500': link.active },{ 'text-white': link.active }" 
                            @click="getCategoryPaginate(link.url)"
                            v-html="link.label"> </div>
                    </div> 
                </div>   
            </div>      
        </div>

        <CreateComponent :open="showCreate" @message="messageToast" @refreshData="getCategory()" @closeCreate="closeCreate"/>

        <keep-alive>
            <EditComponent :open="showUpdate" :category="this.category" @message="messageToast" @refreshData="getCategory()" @closeUpdate="closeUpdate"/>
        </keep-alive>
        
        <DetailComponent :open="showDetail" :data="this.category" @closeDetail="closeDetail"/>

        <ConfirmModal :show="showConfirmed" :id="category.id"
        :title="this.title" @viewConfirmed="fnShowConfirmed"
        @responseConfirmed="fnConfirmed" />

        <DestroyModal :show="showDeleteConfirmed" :id="category.id"
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

    import CreateComponent from './Components/Create.vue'
    import EditComponent from './Components/Edit.vue'
    import DetailComponent from './Components/Detail.vue'
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
            CreateComponent,
            EditComponent,
            DetailComponent,
            ConfirmModal,
            DestroyModal
        },

        data(){
            return{
                loading: true,
                length:   "10",
                search: "",
                categories:"",
                category: "",
                filter: {},
                open: false,
                editing: false,
                openDelete: false,
                user: {},
                form: {},
                showToast: false,
                message: "",
                labelType: "success",
                
                showCreate: false, // Create
                showUpdate: false, // Update
                showDetail: false, // Detalle Categoria
                showConfirmed:false, // Modal Confirm
                showDeleteConfirmed:false, // Modal Confirm Destroy
                message: "",
                labelType: "info",
                titleConfirmed: '',

            }
        },
        methods:{
            clearMessage() {
                this.message = ""
            },
            messageToast(data) {
                this.labelType = data.labelType;
                this.message = data.message;
            },
            closeCreate(){
                this.showCreate = false
            },
            closeUpdate(){
                this.showUpdate = false
            },
            closeDetail(){
                this.showDetail = false
            },
            clearFilter(){
                this.filter = {}
                this.getCategory()
            },
            vaciarForm(){
                this.form = {};
            },
            fnShowConfirmed() {
                this.showConfirmed = false
            },
            fnShowDestroy() {
                this.showDeleteConfirmed = false
            },
            async getCategory(){

                let filter = `&length=${this.length}` 
                
                if(this.filter.description){
                    filter += `&description=${this.filter.description}`
                }

                if(this.filter.title){
                    filter += `&title=${this.filter.title}`
                }

                const get = `${route('category.list')}?${filter}` 

                const response = await fetch(get, {method:'GET'})
                this.categories = await response.json() 
            },

            async fnConfirmed() {
                try {
                    let rt = route('category.active', this.category.id);
                    const response = await axios.put(rt);
                    if (response.status === 200) {
                        this.labelType = "success"
                        this.message = response.data.message
                        this.getCategory()
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
                    let rt = route('category.destroy', this.category.id);
                    const response = await axios.delete(rt);
                    if (response.status === 200) {
                        this.labelType = "success"
                        this.message = response.data.message
                        this.getCategory()
                    } else {
                        this.labelType = "danger"
                        this.message = response.data.message
                    }
                } catch (error) {
                    this.labelType = "danger"
                    this.message = error.response.data.message
                }
                this.fnShowDestroy()
            },

            async getCategoryPaginate(link){

                var get = `${link}`;
                const response = await fetch(get,{method: 'GET'})

                this.categories = await response.json()
                console.log(this.categories)   
            },
            /* guardarcategory() {
                let rt = '';
                if (this.editing) {
                    rt = route('category.update', this.form.id);
                } else {
                    rt = route('category.store');
                }

                axios.post(rt, this.form)
                    .then(response => {
                        this.open = false,
                            this.getCategory()
                        if (response.status == 200) {
                            this.labelType = "success"
                            this.message = response.data['message']
                        } else {
                            this.labelType = "danger"
                            this.message = response.data['message']
                        }
                    })
                    this.vaciarForm();
            }, */
            /* deshabilitar() {
                axios.delete(`/category/${this.category.id}`
                ).then(response => {
                    this.getCategory()
                    if (response.status == 200) {
                        this.labelType = "success"
                        this.message = response.data['message']
                    } else {
                        this.labelType = "danger"
                        this.message = response.data['message']
                    }
                }).catch(error => {
                    this.labelType = "danger"
                    this.message = 'Comuniquese con el administrador'
                })
            this.openDelete=false;
            } */
        },
        created(){
            this.getCategory()
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

    .truncate {
            white-space: nowrap;       /* Prevents the text from wrapping */
            overflow: hidden;          /* Hides overflowed text */
            text-overflow: ellipsis;   /* Displays ellipsis (...) */
            width: 400px;              /* Set a width to apply truncation */
        }

</style>