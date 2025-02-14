<template>
    <div v-if="users.total > 0">
            <div class="w-full mx-auto mt-4">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th class="px-6 py-4 text-center">Nombre</th>
                            <th class="px-6 py-4 text-center">Email</th>
                            <th class="px-6 py-4 text-center">Rol</th>
                            <th class="px-6 py-4 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    <!-- row 1 -->
                        <tr v-for="u in users.data" :key="u.id" class="bg-white hover:bg-gray-50 border-b border-gray-100 ">
                            <td class="w-3/13 text-center">
                                {{u.name}}
                            </td>
                            <td class="w-2/13 text-center">
                                {{u.email ?? '-'}}
                            </td>
                            <td class="w-1/13 text-center">
                                {{u.rol ?? '-'}}
                            </td>
                            <td class="w-2/13 text-center">
                                <!-- <a :href="route('companie.edit', u.id)" title="Editar Usuario">
                                    <Icons class="w-5 h-5 inline" name="edit" />
                                </a> -->
                                <button class="ml-2" @click="resetPassword(u.email)" title="Recuperar Contraseña">
                                    <Icons class="w-5 h-5" name="envelope" />
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

           <div class="w-11/12 mx-auto my-5 flex justify-between items-center">
                <div>
                    Mostrando: {{this.users.from}} a {{this.users.to}} - Entradas encontradas: {{this.users.total}}
                </div>
                <div class="flex flex-wrap -mb-1">
                    <div v-for="link in users.links" :key="link.id">
                        <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded-md" v-html="link.label"> </div>
                        <div v-else 
                            class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border border-gray-300 rounded-md hover:bg-blue-500 hover:text-white cursor-pointer" 
                            :class="{ 'bg-blue-500': link.active },{ 'text-white': link.active }" 
                            @click="getUserCompaniePaginate(link.url)"
                            v-html="link.label"> </div>
                    </div> 
                </div>   
            </div>      
        </div>
        <div v-else>
            <h4 class="text-red-300">La empresa no posee Usuarios relacionados</h4>
        </div>
</template>

<script>
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import App from '@/Layouts/App.vue'
    import Pageheader from '@/Layouts/Pageheader.vue'
    import Pagination from '@/Layouts/Pagination'
    import Icons from '@/Layouts/Components/Icons.vue'    

    export default {
        props:{
             idCompany: Number
        },
        components: {
            App,
            Icons,
            Link,
            Pagination
        },

        data(){
            return{
                length:   "10",
                search: "",
                users: "",
                filter: {},            
            }
        },
        methods:{
            clearFilter(){
                this.filter = {}
                this.getUserCompanie()
            },
            async getUserCompanie(){

                let filter = `&length=${this.length}` 

                const get = `${route('companie.listUserByCompanie', this.idCompany)}?${filter}` 

                const response = await fetch(get, {method:'GET'})
                this.users = await response.json() 
            },

            async getUserCompaniePaginate(link){

                var get = `${link}`;
                const response = await fetch(get,{method: 'GET'})
                this.users = await response.json()  
            },
            async resetPassword($email) {
                try {
                    let rt = route('user.sendResetLink');
                    this.labelType = "info";
                    this.message = 'Se esta procesando el reseteo de contraseña. Por favor aguarde!';
                    const response = await axios.post(rt, { email: $email });
                    if (response.status === 200) {
                        this.labelType = "success";
                        this.message = response.data.message;
                    } else {
                        this.labelType = "danger";
                        this.message = response.data.message;
                    }

                    this.open = false;
                } catch (error) {
                    console.error(error);
                }
            },
        },
        created(){
        },
        mounted(){
            this.getUserCompanie()
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