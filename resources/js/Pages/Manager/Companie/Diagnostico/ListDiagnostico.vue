<template>
    <div>

        <div class="w-full mx-auto mt-4">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-center" style="z-index: 0;">Nombre</th>
                        <th class="px-6 py-4 text-center">Fecha Inicio</th>
                        <th class="px-6 py-4 text-center">Fecha Fin</th>
                        <th class="px-6 py-4 text-center">Diagnostico 360</th>
                        <th class="px-6 py-4 text-center">Estado</th>
                        <th class="px-6 py-4 text-center">Sectores / Competencias</th>
                        <th class="px-6 py-4 text-center">Avance (%)</th>
                        <th class="px-6 py-4 text-center">Acciones</th>

                    </tr>
                </thead>
                <tbody>
                    <!-- row 1 -->
                    <tr v-for="d in diagnosticos.data" :key="d.id"
                        class="bg-white hover:bg-gray-50 border-b border-gray-100">
                        <td class="w-3/12 text-center">{{ d.name }}</td>
                        <td class="w-2/12 text-center">{{ d.date_start ?? '-' }}</td>
                        <td class="w-2/12 text-center">{{ d.date_finish ?? '-' }}</td>
                        <td class="w-1/12 text-center">{{ d.diagnostico_360 ? 'SI' : 'NO' }}</td>
                        <td class="w-2/12 text-center">
                            <div v-if="d.status?.id === 1"
                                class="inline-flex items-center rounded-md bg-gray-50 px-2 py-1 text-xs font-medium text-gray-700 ring-1 ring-inset ring-gray-600/20">
                                {{ d.status?.description ?? '-' }}</div>
                            <div v-else-if="d.status?.id === 2"
                                class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                {{ d.status?.description ?? '-' }}</div>
                            <div v-else-if="d.status?.id === 3"
                                class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">
                                {{ d.status?.description ?? '-' }}</div>
                            <div v-else
                                class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-600/20">
                                {{ d.status?.description ?? '-' }}</div>
                        </td>
                        <td class="w-1/12 text-center font-bold">{{ d.sectores.length }} / {{ d.competencias.length }}
                        </td>
                        <td class="w-1/12 text-center font-bold">{{ d.users_for_diagnostico !== 0 ? ((d.users_have_test / d.users_for_diagnostico) * 100) + '%' : 'N/A' }}

                        </td>
                        <td class="w-2/12 text-center">
                            <Menu as="div" class="inline-node">
                                <div>
                                    <MenuButton class="btn-blue h-7">
                                        <Icons name="ellipsis-vertical"
                                            class="w-7 h-7 inline-flex items-center bg-blue-100 p-1 rounded-full shadow-sm text-gray-600 hover:bg-blue-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" />
                                    </MenuButton>
                                </div>
                                <transition enter-active-class="transition ease-out duration-100"
                                    enter-from-class="transform opacity-0 scale-95"
                                    enter-to-class="transform opacity-100 scale-100"
                                    leave-active-class="transition ease-in duration-75"
                                    leave-from-class="transform opacity-100 scale-100"
                                    leave-to-class="transform opacity-0 scale-95">
                                    <MenuItems
                                        class="origin-top-left absolute z-50 right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 focus:outline-none">
                                        <div class="px-1 py-1 text-left">
                                            <MenuItem v-slot="{ active }">
                                            <a @click="this.diagnostico = d, this.showDetail = true"
                                                class="block px-4 py-2 text-sm cursor-pointer hover:bg-blue-50">Ver
                                                Detalle</a>
                                            </MenuItem>
                                            <MenuItem v-slot="{ active }">
                                            <a @click="this.diagnostico = d, this.showUpdate = true"
                                                class="block px-4 py-2 text-sm cursor-pointer hover:bg-blue-50">Editar</a>
                                            </MenuItem>
                                            <MenuItem v-slot="{ active }">
                                            <a @click="this.diagnostico = d, this.showDelete = true, title = '¿Desea eliminar el Diagnóstico?'"
                                                class="block px-4 py-2 text-sm cursor-pointer hover:bg-blue-50">Eliminar</a>
                                            </MenuItem>
                                        </div>
                                        <div class="px-1 py-1 text-left">
                                            <MenuItem v-slot="{ active }" v-if="d.status_id != 1">
                                            <a @click="updateEstado(d.id, 1)"
                                                class="cursor-pointer block px-4 py-2 text-sm cursor-pointer hover:bg-blue-50">Borrador</a>
                                            </MenuItem>
                                            <MenuItem v-slot="{ active }" v-if="d.status_id != 2">
                                            <a @click="updateEstado(d.id, 2)"
                                                class="cursor-pointer block px-4 py-2 text-sm cursor-pointer hover:bg-blue-50">Habilitar</a>
                                            </MenuItem>
                                            <MenuItem v-slot="{ active }" v-if="d.status_id != 3">
                                            <a @click="updateEstado(d.id, 3)"
                                                class="cursor-pointer block px-4 py-2 text-sm cursor-pointer hover:bg-blue-50">Cancelar</a>
                                            </MenuItem>
                                        </div>
                                    </MenuItems>
                                </transition>
                            </Menu>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>


        <div class="w-11/12 mx-auto my-5 flex justify-between items-center">
            <div>
                Mostrando: {{ this.diagnosticos.from }} a {{ this.diagnosticos.to }} - Entradas encontradas:
                {{ this.diagnosticos.total }}
            </div>
            <div class="flex flex-wrap -mb-1">
                <div v-for="link in diagnosticos.links" :key="link.id">
                    <div v-if="link.url === null"
                        class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded-md"
                        v-html="link.label"> </div>
                    <div v-else
                        class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border border-gray-300 rounded-md hover:bg-blue-500 hover:text-white cursor-pointer"
                        :class="{ 'bg-blue-500': link.active }, { 'text-white': link.active }"
                        @click="getDiagnosticosCompanyPaginate(link.url)" v-html="link.label"> </div>
                </div>
            </div>
        </div>
    </div>

    <DetailComponent :open="showDetail" :data="this.diagnostico" @closeDetail="closeDetail" />

    <keep-alive>
        <EditComponent :open="showUpdate" :diagnostico="this.diagnostico" :competencias="competencias"
            :categorias="categorias" :sectores="sectores" @message="messageToast" @refreshData="getDiagnosticos()"
            @closeUpdate="closeUpdate" />
    </keep-alive>

    <DestroyModal :show="showDelete" :id="diagnostico.id" :title="this.title" @viewDestroy="fnShowDestroy"
        @responseDestroy="fnDestroy" />

</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3';
import App from '@/Layouts/App.vue'
import Pagination from '@/Layouts/Pagination'
import Icons from '@/Layouts/Components/Icons.vue'
import { Menu, MenuButton, MenuItems, MenuItem } from "@headlessui/vue";

import DetailComponent from './Detail.vue';
import EditComponent from './Edit.vue';
import DestroyModal from '@/Layouts/Components/Modals/DestroyModal.vue';

export default {
    props: {
        diagnosticos: Object,
        competencias: Object,
        categorias: Object,
        sectores: Object
    },
    components: {
        App,
        Icons,
        Link,
        Pagination, Menu, MenuButton, MenuItems, MenuItem,
        DetailComponent, EditComponent, DestroyModal
    },

    data() {
        return {
            showDetail: false,
            showUpdate: false,
            showDelete: false,
            diagnostico: {}
        }
    },
    methods: {
        messageToast(data) {
            this.$emit('message', data)
        },
        closeDetail() {
            this.showDetail = false
        },
        closeUpdate() {
            this.showUpdate = false
        },
        fnShowDestroy() {
            this.showDelete = false
        },
        getDiagnosticosCompanyPaginate(link) {
            console.log(link)
            this.$emit('getDiagnosticosCompanyPaginate', link)
        },
        getDiagnosticos() {
            this.$emit('refreshData')
        },
        async updateEstado(id, status_id) {
            let data = {}
            let form = {}
            form.status_id = status_id
            let rt = route("diagnostico.updateEstado", id);

            try {
                const response = await axios.put(rt, form);
                if (response.status == 200) {
                    data.labelType = "success"
                    data.message = response.data.message
                    this.$emit('refreshData')
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
        async fnDestroy() {
            let data = {}
            try {
                let rt = route('diagnostico.destroy', this.diagnostico.id);
                const response = await axios.delete(rt);
                if (response.status === 200) {
                    data.labelType = "success"
                    data.message = response.data.message
                    this.$emit('refreshData')
                } else {
                    data.labelType = "danger"
                    data.message = response.data.message
                }
            } catch (error) {
                data.labelType = "danger"
                data.message = error.response.data.message
            }
            this.fnShowDestroy()
            this.$emit('message', data)
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