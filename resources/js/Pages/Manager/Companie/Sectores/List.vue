<template>
    <div>
        <div class="w-full mx-auto mt-4">
            <table class="table w-full">
                <thead>
                    <tr>
                        <th class="px-6 py-4 text-center" style="z-index: 0;">Nombre</th>
                        <th class="px-6 py-4 text-center">Estado</th>
                        <th class="px-6 py-4 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="d in sectores.data" :key="d.id"
                        class="bg-white hover:bg-gray-50 border-b border-gray-100 ">
                        <td class="w-3/12 text-center">
                            {{ d.name }}
                        </td>
                        <td class="w-2/12 text-center">
                            <div v-if="d.active === 1"
                                class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                                ACTIVO
                            </div>
                            <div v-else
                                class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">
                                INACTIVO
                            </div>
                        </td>
                        <td class="w-2/12 text-center">
                            <Menu as="div" class="inline-node">
                                <div>
                                    <MenuButton class="btn-blue h-7">
                                        <Icons name="ellipsis-vertical"
                                            class="w-7 h-7 inline-flex items-center bg-blue-100 p-1 rounded-full shadow-sm text-gray-600  hover:bg-blue-300 hover:text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" />
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
                                            <a @click="this.sector = d, this.showDetail = true" class="block px-4 py-2 text-sm cursor-pointer hover:bg-blue-50">
                                                Ver Detalle</a>
                                            </MenuItem>
                                            <MenuItem v-slot="{ active }">
                                            <a @click="this.sector = d, this.showUpdate = true" class="block px-4 py-2 text-sm cursor-pointer hover:bg-blue-50">
                                                Editar</a>
                                            </MenuItem>
                                            <MenuItem v-slot="{ active }">
                                            <a @click="this.sector = d, this.showDelete = true, title='¿Desea eliminar el Sector?'" class="block px-4 py-2 text-sm cursor-pointer hover:bg-blue-50">
                                                Eliminar</a>
                                            </MenuItem>
                                        </div>
                                        <div class="px-1 py-1 text-left">
                                            <MenuItem v-slot="{ active }" v-if="d.active == 0">
                                            <a @click="activeSector(d.id)"
                                                class="cursor-pointer block px-4 py-2 text-sm cursor-pointer hover:bg-blue-50">
                                                Actvar</a>
                                            </MenuItem>
                                            <MenuItem v-slot="{ active }"  v-else>
                                            <a @click="activeSector(d.id)"
                                                class="cursor-pointer block px-4 py-2 text-sm cursor-pointer hover:bg-blue-50">
                                                Inactivar</a>
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
                Mostrando: {{ this.sectores.from }} a {{ this.sectores.to }} - Entradas encontradas:
                {{ this.sectores.total }}
            </div>
            <div class="flex flex-wrap -mb-1">
                <div v-for="link in sectores.links" :key="link.id">
                    <div v-if="link.url === null"
                        class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded-md"
                        v-html="link.label"> </div>
                    <div v-else
                        class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border border-gray-300 rounded-md hover:bg-blue-500 hover:text-white cursor-pointer"
                        :class="{ 'bg-blue-500': link.active }, { 'text-white': link.active }"
                        @click="getSectoresCompanyPaginate(link.url)" v-html="link.label"> </div>
                </div>
            </div>
        </div>
    </div>

    <DetailComponent :open="showDetail" :data="this.sector" @closeDetail="closeDetail"/>

    <keep-alive>
        <EditComponent 
            :open="showUpdate" 
            :sector="this.sector" 
            @message="messageToast" @refreshData="getSectores()" @closeUpdate="closeUpdate"/>
    </keep-alive>

    <DestroyModal :show="showDelete" :id="sector.id"
        :title="this.title" @viewDestroy="fnShowDestroy"
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
            sector: {}
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
        fnShowDestroy(){
            this.showDelete = false
        },
        getSectoresCompanyPaginate(link) {
            console.log(link)
            this.$emit('getSectoresCompanyPaginate', link)
        },
        getSectores(){
            this.$emit('refreshData')
        },
        async activeSector(id) {
            let data = {}
            let rt = route("sector.active", id);
            try {
                const response = await axios.put(rt);
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
                let rt = route('sector.destroy', this.sector.id);
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