<template>
    <TransitionRoot as="template" :show="open">
        <Dialog as="div" class="fixed inset-0 overflow-hidden">
            <div class="absolute inset-0 overflow-hidden ">
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
                                            <DialogTitle class="text-lg font-medium text-white"> Detalle Diagnostico
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

                                            <div class="space-y-2 pt-2 pb-5">
                                                <div>
                                                    <label for="time"
                                                        class="block text-lg font-medium text-gray-700 "><b>Categoria:
                                                            {{ data.name }}</b></label>
                                                </div>
                                                <hr>
                                                <div class="flex text-md text-gray-700">
                                                    <label class="text-bold font-bold">Estado:</label>
                                                </div>
                                                <div v-if="data.status?.id === 1"
                                                    class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-700 ring-1 ring-inset ring-gray-600/20">
                                                    {{ data.status?.description ?? '-' }}
                                                </div>
                                                <div v-else-if="data.status?.id === 2"
                                                    class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                                    {{ data.status?.description ?? '-' }}
                                                </div>
                                                <div v-else-if="data.status?.id === 3"
                                                    class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">
                                                    {{ data.status?.description ?? '-' }}
                                                </div>
                                                <div v-else
                                                    class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-600/20">
                                                    {{ data.status?.description ?? '-' }}
                                                </div>

                                                <div class="flex text-md text-gray-700">
                                                    <label class="text-bold font-bold">Fecha Inicio:</label>
                                                </div>
                                                <div class="flex text-sm text-gray-700 text-justify">
                                                    <p>{{ data.date_start ?? '-' }}</p>
                                                </div>

                                                <div class="flex text-md text-gray-700">
                                                    <label class="text-bold font-bold">Fecha Finalización:</label>
                                                </div>
                                                <div class="flex text-sm text-gray-700 text-justify">
                                                    <p>{{ data.date_finish ?? '-' }}</p>
                                                </div>

                                                <div class="flex text-md text-gray-700">
                                                    <label class="text-bold font-bold">Diagnóstico 360:</label>
                                                </div>
                                                <div class="flex text-sm text-gray-700 text-justify">
                                                    <p>{{ data.diagnostico_360 ? 'Activo' : 'Desactivado' }}</p>
                                                </div>

                                                <div class="flex text-md text-gray-700">
                                                    <label class="text-bold font-bold">Estado:</label>
                                                </div>
                                                <div class="flex text-sm text-gray-700 text-justify">
                                                    <p>{{ data.diagnostico_360 ? 'Activo' : 'Desactivado' }}</p>
                                                </div>

                                                <hr>
                                                <div>
                                                    <label for="time"
                                                        class="block text-xl font-medium text-gray-800">Competencias
                                                        Relacionadas</label>
                                                </div>
                                                <hr>

                                                <div v-for="c in data.competencias" :key="c.id"
                                                    class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10 mr-2">
                                                    <Icons class="h-5 w-5 mr-1" name="badge-check" /> {{ c.competencia
                                                    }}
                                                </div>

                                                <hr>
                                                <div>
                                                    <label for="time"
                                                        class="block text-xl font-medium text-gray-800">Sectores
                                                        Relacionados</label>
                                                </div>
                                                <hr>

                                                <div v-for="item in data.sectores" :key="item.id"
                                                    class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-700/10 mr-2">
                                                    <Icons class="h-5 w-5 mr-1" name="badge-check" /> {{ item.name
                                                    }}
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-shrink-0 px-4 py-4 flex justify-end">
                                    <button type="button"
                                        class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                                        @click="close()">Cancelar</button>
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
        data: Object
    },
    data() {
        return {
        }
    },
    methods: {
        close() {
            this.$emit('closeDetail')
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
