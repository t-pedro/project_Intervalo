<template>
    <App>
        <!-- CONTENT -->
        <div class="flex-grow flex flex-col">
            <div class="w-11/12 mx-auto flex justify-between items-center">
                <h1 class="my-10 text-4xl font-bold">Competencias</h1>
                <div>
                    <a class="btn btn-primary btn-sm mr-2" :href="route('competencia.import')">Importar</a>
                    <a class="btn btn-primary btn-sm" :href="route('competencia.create')">Nuevo</a>
                </div>
            </div>

            <div class="w-11/12 mx-auto my-2 flex justify-between align-middle">
                <div>
                    <label class="font-semibold mr-2" for="search">Buscar:</label>
                    <input class="input input-sm" type="text" id="search" v-model="search" placeholder="Buscar...">
                    <button class="ml-2 btn btn-primary btn-outline btn-sm" @click="getCompetencias">Buscar</button>
                </div>

                <div>
                    <label class="font-semibold mr-2" for="">Ver: </label>
                    <select class="text-sm border-gray-300 rounded-md" v-model="length" @change="getCompetencias">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>

            <div class="w-11/12 mx-auto">
                <table class="w-full my-7">
                    <!-- head -->
                    <thead class="bg-blue-100 text-left text-sm font-semibold">
                        <tr>
                            <th class="py-2 px-2">COMPETENCIAS</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- row 1 -->
                        <tr v-for="a in competencias.data" :key="a.id"
                            class="bg-white hover:bg-gray-50 border-b border-gray-100 ">
                            <td class="whitespace-normal p-3">
                                
                                <div class="flex">
                                    <Icons v-if="a.has_self_relation" name="badge-check" class="w-6 h-6 text-green-700" />
                                    <Icons v-else name="x" class="w-6 h-6 text-red-700" />
                                    <p class="mb-2 pl-1 font-semibold" :class="a.has_self_relation ? 'text-green-700' : 'text-red-700'"> {{ a.competencia }} - <span
                                        class="text-xs font-normal"> Afirmaciones: {{ a.afirmations }} - <strong>"la compentecia no se encuentra autorelacionada"</strong></span></p>
                                </div>
                                <div class="flex flex-wrap items-center">
                                    <span v-for="rel in a.related" :key="rel"
                                        class="mr-2 bg-indigo-100 border-white rounded-md text-sm flex item-center py-1 px-2 mb-2">
                                        <label class="mr-2 inline-block">{{ rel.competencia }}</label>
                                        <i
                                            :class="{ 'text-green-400': rel.feedback_approve, 'text-gray-400': !rel.feedback_approve }">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 inline-block">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </i>
                                        <i
                                            :class="{ 'text-green-400': rel.feedback_disapprove, 'text-gray-400': !rel.feedback_disapprove }">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 inline-block">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </i>
                                    </span>
                                </div>
                            </td>
                            <td class="w-1/6 text-center">
                                <a :href="route('competencia.edit', a.id)"
                                    class="bg-blue-600 border text-blue-50 border-gray-100 px-3 py-3 rounded-lg text-sm hover:bg-blue-700 hover:text-gray-200 hover:border-transparent font-medium ">Detalle
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="w-11/12 mx-auto my-5 flex justify-between items-center">
                <div>
                    Mostrando: {{ this.competencias.from }} a {{ this.competencias.to }} - Entradas encontradas:
                    {{ this.competencias.total }}
                </div>
                <div class="flex flex-wrap -mb-1">
                    <div v-for="link in competencias.links" :key="link.id">
                        <div v-if="link.url === null"
                            class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded-md"
                            v-html="link.label"> </div>
                        <div v-else
                            class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border border-gray-300 rounded-md hover:bg-blue-500 hover:text-white cursor-pointer"
                            :class="{ 'bg-blue-500': link.active }, { 'text-white': link.active }"
                            @click="getCompetenciasPaginate(link.url)" v-html="link.label"> </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- / CONTENT -->
    </App>

</template>

<script>
import { Head, Link } from '@inertiajs/inertia-vue3';
import App from '@/Layouts/App.vue'
import Pageheader from '@/Layouts/Pageheader.vue'
import Pagination from '@/Layouts/Pagination'
import Icons from '@/Layouts/Components/Icons.vue'

export default {
    // props:{
    //     afirmaciones: Object
    // },
    components: {
        App,
        Icons,
        Link,
        Pagination
    },

    data() {
        return {
            loading: true,
            length: "10",
            search: "",
            competencias: "",
            // nextpage: "",
            // prevpage: "",                

        }
    },
    methods: {
        async getCompetencias() {

            let filter = `&length=${this.length}`

            if (this.search) {
                filter += `&search=${this.search}`
            }

            const get = `${route('competencia.list')}?${filter}`

            const response = await fetch(get, { method: 'GET' })
            this.competencias = await response.json()
        },

        async getCompetenciasPaginate(link) {

            var get = `${link}`;
            const response = await fetch(get, { method: 'GET' })

            this.competencias = await response.json()
            // this.nextpage = this.afirmaciones.next_page_url
            // this.prevpage = this.afirmaciones.prev_page_url
            console.log(this.competencias)

        }

    },
    created() {
        this.getCompetencias()

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