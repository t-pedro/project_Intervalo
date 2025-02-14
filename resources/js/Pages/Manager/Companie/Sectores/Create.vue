<template>
    <TransitionRoot as="template" :show="open">
        <Dialog as="div" class="fixed inset-0 overflow-hidden">
            <div class="absolute inset-0 overflow-hidden">
                <DialogOverlay class="absolute inset-0 bg-gray-200 bg-opacity-40" />
                <div class="fixed inset-y-0 pl-16 max-w-full right-0 flex">
                    <TransitionChild as="template" enter="transform transition ease-in-out duration-500 sm:duration-700"
                        enter-from="translate-x-full" enter-to="translate-x-0"
                        leave="transform transition ease-in-out duration-500 sm:duration-700" leave-from="translate-x-0"
                        leave-to="translate-x-full">
                        <div class="w-screen max-w-md">
                            <form class="h-full divide-y divide-gray-200 flex flex-col bg-white shadow-xl"
                                enctype="multipart/form-data">
                                <div class="flex-1 h-0 overflow-y-auto">
                                    <div class="py-6 px-4 bg-gray-500 sm:px-6">
                                        <div class="flex items-center justify-between">
                                            <DialogTitle class="text-lg font-medium text-white"> Nuevo Sector
                                            </DialogTitle>
                                            <div class="ml-3 h-7 flex items-center">
                                                <button type="button"
                                                    class="bg-gray-500 rounded-md text-indigo-200 hover:text-white focus:outline-none focus:ring-2 focus:ring-white"
                                                    @click="close()">
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
                                                        class="block text-sm font-medium text-gray-900">Nombre</label>
                                                    <div class="mt-1">
                                                        <input type="text" v-model="form.name" name="name" id="name"
                                                            class="block w-full shadow-sm sm:text-sm rounded-md"
                                                            :class="this.errors.name ? 'focus:border-red-500 border-red-300 focus:ring-red-500' : 'focus:border-indigo-500 border-gray-300 focus:ring-indigo-500'" />
                                                        <span v-if="this.errors.name" class="text-xs text-red-500">{{
                                                            this.errors.name[0] ?? '' }}</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="flex-shrink-0 px-4 py-4 flex justify-end">
                                    <button type="button"
                                        class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                                        @click="close()">Cancelar</button>
                                    <button @click.prevent="store"
                                        class="ml-4 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-gray-600 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">Guardar</button>
                                </div>
                            </form>
                        </div>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
<script>
import Icons from '@/Layouts/Components/Icons.vue'
import { Dialog, DialogOverlay, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'

export default {
    components: {
        Icons, Dialog, DialogOverlay, DialogTitle, TransitionChild, TransitionRoot
    },
    props: {
        open: Boolean,
        idCompany: Number
    },
    data() {
        return {
            form: {},
            errors: {},
        }
    },
    setup() {
        const format = (date) => {
            const day = date.getDate();
            const month = date.getMonth() + 1;
            const year = date.getFullYear();

            return `${day}/${month}/${year}`;
        };
        return {
            format,
        };
    },
    methods: {
        async store() {
            let data = {}
            try {

                let rt = route('sector.store');
                this.form.company_id = this.idCompany
                const response = await axios.post(rt, this.form);
                if (response.status == 200) {
                    data.labelType = "success"
                    data.message = response.data.message
                    this.$emit('refreshData')
                    this.close()
                } else {
                    data.labelType = "danger"
                    data.message = response.data.message
                }
            } catch (error) {
                this.errors = error.response.data.errors
                data.labelType = "danger"
                data.message = "Se ha producido un error al procesar | Comuniquese con el administrador"
            }

            this.$emit('message', data)
        },
        close() {
            this.$emit('closeCreateSector')
            this.form = {}
        }
    }
}
</script>

<style>
/*
    Enter and leave animations can use different
    durations and timing functions.
    */
.slide-fade-enter-active {
    transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.8s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(20px);
    opacity: 0;
}
</style>
