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
                                            <DialogTitle class="text-lg font-medium text-white"> Editar Diagnostico
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

                                            <div class="space-y-6 pb-5">
                                                <div>
                                                    <label for="fullname"
                                                        class="block text-sm font-medium text-gray-900">Fecha
                                                        Inicio</label>
                                                    <div class="mt-1">
                                                        <Datepicker class="block w-full shadow-sm sm:text-sm rounded-md"
                                                            :class="this.errors.name ? 'focus:border-red-500 border-red-300 focus:ring-red-500' : 'focus:border-indigo-500 border-gray-300 focus:ring-indigo-500'"
                                                            v-model="form.date_start" :enableTimePicker="false"
                                                            :monthChangeOnScroll="false" autoApply :format="format">
                                                        </Datepicker>
                                                        <span v-if="this.errors.date_start"
                                                            class="text-xs text-red-500">{{
                                                                this.errors.date_start[0] ?? '' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="space-y-6 pb-5">
                                                <div>
                                                    <label for="fullname"
                                                        class="block text-sm font-medium text-gray-900">Fecha
                                                        Finalización</label>
                                                    <div class="mt-1">
                                                        <Datepicker class="block w-full shadow-sm sm:text-sm rounded-md"
                                                            :class="this.errors.date_finish ? 'focus:border-red-500 border-red-300 focus:ring-red-500' : 'focus:border-indigo-500 border-gray-300 focus:ring-indigo-500'"
                                                            v-model="form.date_finish" :enableTimePicker="false"
                                                            :monthChangeOnScroll="false" autoApply :format="format">
                                                        </Datepicker>
                                                        <span v-if="this.errors.date_finish"
                                                            class="text-xs text-red-500">{{
                                                                this.errors.date_finish[0] ?? '' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="space-y-6 pb-5">
                                                <div>
                                                    <label for="fullname"
                                                        class="block text-sm font-medium text-gray-900">Diagnóstico
                                                        360</label>
                                                    <div class="mt-1">
                                                        <Switch v-model="form.diagnostico_360"
                                                            @click="form.diagnostico_360 = !form.diagnostico_360"
                                                            :class="form.diagnostico_360 ? 'bg-blue-600' : 'bg-gray-200'"
                                                            class="relative inline-flex h-6 w-11 items-center rounded-full">
                                                            <span
                                                                :class="form.diagnostico_360 ? 'translate-x-6' : 'translate-x-1'"
                                                                class="inline-block h-4 w-4 transform rounded-full bg-white transition" />
                                                        </Switch>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="px-4 divide-y divide-gray-200 sm:px-6 mt-2">
                                            <div class="space-y-6 pb-5">
                                                <div>
                                                    <div class="flex items-center">
                                                        <label for="fullname"
                                                            class="block text-sm font-medium text-gray-900">
                                                            Sectores
                                                        </label>
                                                        <button type="button" @click="add_sector_relacionado()" 
                                                        class="ml-2 px-3 py-1 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-green-800 focus:outline-none focus:ring-green-300 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                                        Agregar
                                                        </button>

                                                        <!-- <Icons class="w-5 h-5 ml-2 text-green-800 cursor-pointer"
                                                            @click="add_competencia_relacionada()" name="plus-circle"
                                                            title="Agregar Competencia" /> -->
                                                    </div>
                                                    <div class="mt-1">
                                                        <select
                                                            class="block w-full shadow-sm sm:text-sm rounded-md focus:border-indigo-500 border-gray-300 focus:ring-indigo-50"
                                                            v-model="this.sector_select" name="competencia"
                                                            id="competencia">
                                                            <option value="" disabled selected>Seleccione un Sector</option>
                                                            <option v-for="item in sectores" :key="item.id" :value="item">
                                                                {{ item.name }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <hr>

                                        <div class="px-4 divide-y divide-gray-200 sm:px-6">
                                            <div class="space-y-6 pb-5">
                                                <div>
                                                    <div class="flex items-center mt-2">
                                                        <label for="fullname"
                                                            class="block text-sm font-medium text-gray-900">
                                                            Categorias
                                                        </label>
                                                        <button type="button" @click="add_competencia_for_category()" 
                                                        class="ml-2 px-3 py-1 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                        Agregar
                                                        </button>

                                                       <!--  <Icons class="w-5 h-5 ml-2 text-green-800 cursor-pointer"
                                                            @click="add_competencia_for_category()" name="plus-circle"
                                                            title="Agregar Categoria" /> -->
                                                    </div>
                                                    <div class="mt-1">
                                                        <select
                                                            class="block w-full shadow-sm sm:text-sm rounded-md focus:border-indigo-500 border-gray-300 focus:ring-indigo-50"
                                                            v-model="this.category_select" name="competencia"
                                                            id="competencia">
                                                            <option value="" disabled selected>Seleccione una Categoria
                                                            </option>
                                                            <option v-for="c in categorias" :key="c.id" :value="c">
                                                                {{ c.title }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="px-4 divide-y divide-gray-200 sm:px-6">
                                            <div class="space-y-6 pb-5">
                                                <div>
                                                    <div class="flex items-center">
                                                        <label for="fullname"
                                                            class="block text-sm font-medium text-gray-900">
                                                            Competencias
                                                        </label>
                                                        <button type="button" @click="add_competencia_relacionada()" 
                                                        class="ml-2 px-3 py-1 text-xs font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                                        Agregar
                                                        </button>
                                                        <!-- <Icons class="w-5 h-5 ml-2 text-green-800 cursor-pointer"
                                                            @click="add_competencia_relacionada()" name="plus-circle"
                                                            title="Agregar Competencia" /> -->
                                                    </div>
                                                    <div class="mt-1">
                                                        <select
                                                            class="block w-full shadow-sm sm:text-sm rounded-md focus:border-indigo-500 border-gray-300 focus:ring-indigo-50"
                                                            v-model="this.competencia_select" name="competencia"
                                                            id="competencia">
                                                            <option value="" disabled selected>Seleccione una
                                                                Competencia</option>
                                                            <option v-for="c in competencias" :key="c.id" :value="c">
                                                                {{ c.competencia }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="px-4 divide-y divide-gray-200 sm:px-6 mt-1">
                                            <label for="fullname"
                                                class="block text-sm font-medium text-gray-900">Competencias
                                                Seleccionadas</label>
                                        </div>

                                        <div class="px-4 divide-y divide-gray-200 sm:px-6 mt-1 mb-2">
                                            <div v-for="cs in this.competencias_select" :key="cs.id"
                                                class="mt-1 inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10 mr-2">
                                                {{ cs.competencia }} <button
                                                    @click="remove_competencia_relaciona(cs.id)">
                                                    <Icons name="trash" class="h-6 w-6 ml-2 text-red-500" />
                                                </button>
                                            </div>
                                        </div>

                                        <hr>
                                        <div class="px-4 divide-y divide-gray-200 sm:px-6 mt-2">
                                            <label for="fullname"
                                                class="block text-sm font-medium text-gray-900">Sectores
                                                Seleccionados</label>
                                        </div>

                                        <div class="px-4 divide-y divide-gray-200 sm:px-6 mt-1">
                                            <div v-for="item in this.sectores_select" :key="item.id"
                                                class="mt-1 inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-700/10 mr-2">
                                                {{ item.name }} <button
                                                    @click="remove_sector_relacionado(item.id)">
                                                    <Icons name="trash" class="h-6 w-6 ml-2 text-red-500" />
                                                </button>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="flex-shrink-0 px-4 py-4 flex justify-end">
                                    <button type="button"
                                        class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                                        @click="close()">Cancelar</button>
                                    <button @click.prevent="update"
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
import { Switch } from '@headlessui/vue'
import { Dialog, DialogOverlay, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

export default {
    components: {
        Icons, Dialog, DialogOverlay, DialogTitle, TransitionChild, TransitionRoot, Datepicker, Switch
    },
    props: {
        open: Boolean,
        diagnostico: Object,
        competencias: Object,
        categorias: Object,
        sectores: Object
    },
    data() {
        return {
            form: {},
            errors: {},
            category: {},
            competencia_select: '',
            competencias_select: [],
            category_select: '',
            sectores_select: '',
            sector_select: [],
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
        async update() {
            let data = {}
            try {
                let competencias_id = []
                Object.entries(this.competencias_select).forEach(([clave, valor]) => {
                    competencias_id.push(valor.id);
                });
                this.form.competencias_id = competencias_id

                let sectores_id = []
                Object.entries(this.sectores_select).forEach(([clave, valor]) => {
                    sectores_id.push(valor.id);
                });
                this.form.sectores_id = sectores_id

                let rt = route('diagnostico.update', this.form.id);
                const response = await axios.put(rt, this.form);
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
        add_competencia_relacionada() {
            let data = {}
            let resultado = this.competencias.find(competencia => competencia.id === this.competencia_select.id);
            //Verificar que competencia no se encuentre incluida previamente.
            if (resultado) {
                let existe_competencia = this.competencias_select.find(competencia => competencia.id === this.competencia_select.id);
                if (!existe_competencia) {
                    let comp = {
                        'competencia': this.competencia_select.competencia,
                        'id': this.competencia_select.id,
                    }
                    this.competencias_select.push(comp)
                }
            } else {
                data.labelType = "danger"
                data.message = 'No se ha detectado una competencia valida'
            }
            this.competencia_select = ''
            this.$emit('message', data)
        },
        add_competencia_for_category() {
            let data = {}
            let competencias_category = this.category_select.competencias

            const compatenciasAsociadas = this.competencias.map(comp => comp.competencia);

            let resultado = ''
            if(competencias_category){
                resultado = competencias_category.filter(competencia =>
                    compatenciasAsociadas.includes(competencia.competencia)
                );
            }

            //Verificar que competencia no se encuentre incluida previamente.
            if (resultado) {
                resultado.forEach(element => {
                    let existe_competencia = this.competencias_select.find(competencia => competencia.id === element.id);
                    if (!existe_competencia) {
                        let comp = {
                            'competencia': element.competencia,
                            'id': element.id,
                        }
                        this.competencias_select.push(comp)
                    }
                });
            } else {
                data.labelType = "danger"
                data.message = 'No se ha detectado una competencia valida'
            }
            this.category_select = ''
            this.$emit('message', data)
        },
        remove_competencia_relaciona(id) {
            const index = this.competencias_select.findIndex(item => item.id === id);
            this.competencias_select.splice(index, 1);
        },

        add_sector_relacionado() {
            let data = {}
            let resultado = this.sectores.find(sector => sector.id === this.sector_select.id);
            //Verificar que competencia no se encuentre incluida previamente.
            if (resultado) {
                let existe_sector = this.sectores_select.find(sector => sector.id === this.sector_select.id);
                if (!existe_sector) {
                    let comp = {
                        'name': this.sector_select.name,
                        'id': this.sector_select.id,
                    }
                    this.sectores_select.push(comp)
                }
            } else {
                data.labelType = "danger"
                data.message = 'No se ha detectado un sector valido'
            }
            this.sector_select = ''
            this.$emit('message', data)
        },
        remove_sector_relacionado(id) {
            const index = this.sectores_select.findIndex(item => item.id === id);
            this.sectores_select.splice(index, 1);
        },

        close() {
            this.$emit('closeUpdate')
            this.form = {}
        },
    },
    activated() {

    },
    watch: {
        open(newVal) {
            if (newVal) {
                this.errors = {}
                this.form = JSON.parse(JSON.stringify(this.diagnostico));
                this.form.date_start = new Date(this.form.date_start + "T00:00:00.000-03:00")
                console.log(this.form.date_finish)
                this.form.date_finish = this.form.date_finish != null ? new Date(this.form.date_finish + "T00:00:00.000-03:00") : null

                this.competencias_select = this.form.competencias
                this.sectores_select = this.form.sectores
            }
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
