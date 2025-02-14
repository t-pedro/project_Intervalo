<template>
    <App>
        <Toast :toast="this.message" :type="this.labelType" @clear="clearMessage"></Toast>

        <div class="flex-grow flex flex-col">
            <div class="w-11/12 mx-auto flex justify-between items-center">
                <h1 class="my-10 text-4xl font-bold">Cápsulas</h1>
                <div>
                    <a class="btn btn-primary btn-sm mr-2" href="#" @click.prevent="vaciarCapsula(), editing=false, open = true">Crear</a>
                </div>
            </div>

            <div class="w-11/12 mx-auto ">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th class="w-1/7 px-6 py-4 text-center">Título</th>
                            <th class="w-3/7 px-6 py-4 text-center">Descripción</th>
                            <th class="w-2/7 px-6 py-4 text-center">Url</th>
                            <th class="w-1/7 px-6 py-4 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="c in capsules.data" :key="c.id" class="hover">
                            <td class="text-center">{{ c.id }} - {{ c.title }}</td>
                            <td class="text-center">{{ c.description }}</td>
                            <td class="text-center">{{ c.url }}</td>
                            <td class="text-center">
                                <a class="link" @click="
                                                capsule.id = c.id,
                                                capsule.title = c.title,
                                                capsule.url = c.url,
                                                capsule.description = c.description,
                                                editing = true,
                                                open = true">
                                Detalle</a>
                                <button class="ml-2" @click="idDelete = c.id, openDelete = true">
                                    <Icons class="w-5 h-5" name="trash" />
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
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
                                                <DialogTitle v-if="editing == false"
                                                    class="text-lg font-medium text-white"> Nueva Capsula
                                                </DialogTitle>
                                                <DialogTitle v-else class="text-lg font-medium text-white"> Editar
                                                    Capsula
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

                                                <div class="space-y-6 pt-6 pb-5">
                                                    <div>
                                                        <label for="fullname"
                                                            class="block text-sm font-medium text-gray-900">Titulo</label>
                                                        <div class="mt-1">
                                                            <input type="text" v-model="capsule.title" name="titulo"
                                                                id="titulo"
                                                                class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md" />
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <label for="url"
                                                            class="block text-sm font-medium text-gray-900">Url</label>
                                                        <div class="mt-1">
                                                            <input type="text" name="url" v-model="capsule.url" id="url"
                                                                class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md" />
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label for="descripcion"
                                                            class="block text-sm font-medium text-gray-900">Descripcion</label>
                                                        <div class="mt-1">
                                                            <textarea type="text" name="descripcion" id="descripcion"
                                                                rows="4" v-model="capsule.description"
                                                                class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md"></textarea>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <label for="truck"
                                                            class="block text-sm font-medium text-gray-900">Imagen</label>
                                                        <div class="mt-1">
                                                            <input
                                                                class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md"
                                                                id="file_input" type="file" name="file_input" ref="file"
                                                                @change="obtenerImagen">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0 px-4 py-4 flex justify-end">
                                        <button type="button"
                                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                            @click="open = false">Cancelar</button>
                                        <button @click.prevent="guardarCapsule"
                                            class="ml-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Guardar</button>
                                    </div>
                                </form>
                            </div>
                        </TransitionChild>
                    </div>
                </div>
            </Dialog>
        </TransitionRoot>


            <TransitionRoot as="template" :show="openDelete">
                <Dialog as="div" class="relative z-10" @close="openDelete = false">

                    <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0"
                        enter-to="opacity-100" leave="ease-in duration-200" leave-from="opacity-100"
                        leave-to="opacity-0">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" />
                    </TransitionChild>

                    <div class="fixed z-10 inset-0 overflow-y-auto">
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
                                            <div
                                                class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                <Icons class="h-6 w-6 text-red-600" name="info" aria-hidden="true" />
                                            </div>
                                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                                <DialogTitle as="h3"
                                                    class="text-lg leading-6 font-medium text-gray-900"> Eliminar
                                                    Capsula </DialogTitle>
                                                <div class="mt-2">
                                                    <p class="text-sm text-gray-500">Desea Eliminar la Capsula ID:
                                                        {{ idDelete }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                        <button type="button"
                                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
                                            @click="eliminar()">Eliminar</button>
                                        <button type="button"
                                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                                            @click="openDelete = false" ref="cancelButtonRef">Cancelar</button>
                                    </div>
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </div>
                </Dialog>
            </TransitionRoot>

    </App>

</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3';
import App from '@/Layouts/App.vue'
import Icons from '@/Layouts/Components/Icons.vue'
import { Dialog, DialogOverlay, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import Toast from '@/Layouts/Components/Toast.vue'
import ToastConfirm from '@/Layouts/Components/ToastConfirm.vue';


export default {
    props: {
        capsules: Object
    },
    components: {
        App,
        Icons,
        Link,
        Dialog,
        DialogOverlay,
        DialogTitle,
        TransitionChild,
        TransitionRoot,
        Toast,
        ToastConfirm
    },

    data() {
        return {
            capsule: {
                id: '',
                title: '',
                url: '',
                description: '',
                image: ''
            },
            loading: true,
            open: false,
            showToast: false,
            capsules: "",
            editing: false,
            capsules: '',
            message: "",
            methodConfirm: "",
            messageConfirm: "DELETE",
            openDelete: false,
            idDelete: '',
        }
    },
    methods: {
        clearMessage() {
            this.message = ""
        },
        clearMethodsConfirm() {
            this.messageConfirm = ""
            this.methodConfirm = ""
            this.ver = false
        },
        async getCapsules() {
            const get = `${route('capsule.list')}`
            const response = await fetch(get, { method: 'GET' })
            this.capsules = await response.json()
        },
        guardarCapsule() {
            let formData = new FormData();
            formData.append('id', this.capsule.id);
            formData.append('title', this.capsule.title);
            formData.append('url', this.capsule.url);
            formData.append('description', this.capsule.description);
            formData.append('image', this.capsule.image);
            let rt = '';
            if (this.editing) {
                rt = route('capsule.edit', this.capsule.id);
            } else {
                rt = route('capsule.store');
            }

            axios.post(rt, formData)
                .then(response => {
                    this.open = false,
                        this.getCapsules()
                    if (response.status == 200) {
                        this.labelType = "success"
                        this.message = response.data['message']
                    } else {
                        this.labelType = "danger"
                        this.message = response.data['message']
                    }
                })
            this.vaciarCapsula();
        },
        vaciarCapsula() {
            this.capsule.id = '';
            this.capsule.title = '';
            this.capsule.url = '';
            this.capsule.description = '';
            this.capsule.image = '';
        },
        obtenerImagen(e) {
            let file = e.target.files[0];
            this.capsule.image = file;
        },
        eliminar: function () {
            axios.delete(`/capsule/${this.idDelete}`
            ).then(response => {
                this.getCapsules()
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
        }
    },
        created() {
            this.getCapsules()
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

.table th:first-child {
  position: initial !important;
  left: 0px;
  z-index: 11;
}
</style>
