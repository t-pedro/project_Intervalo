<template>
    <App>
        <div class="flex-grow flex flex-col w-10/12 mx-auto p-10">
            <div class="w-11/12 mx-auto flex justify-between items-center">
                <h1 class="my-10 text-4xl font-bold">Editar Afirmación</h1>
                <div>
                    <button class="btn btn-primary btn-sm" @click.prevent="submit">Guardar</button>
                    <a class="btn btn-primary btn-sm mr-2" :href="route('afirmation')">Volver</a>
                </div>
            </div>        
            <form action="#" method="POST">
                <div class="col-span-3 text-lg px-5 py-4 font-bold">
                    <div>Información General</div> 
                </div>
                <div class="">
                    <label for="text" class="block text-sm font-semibold text-gray-700">Afirmación</label>
                    <input v-model="form.text" type="text" name="text" id="text"  class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" />
                </div>
                <div class="mt-4">
                    <label for="ponderacion" class="block text-sm font-semibold text-gray-700">Ponderación</label>
                    <select v-model="form.ponderacion" id="ponderacion" name="ponderacion" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option disabled value="">Seleccione una opción</option>
                        <option value="ASC">Ascendente</option>
                        <option value="DESC">Descendente</option>
                    </select>
                </div>
                <div class="mt-4">
                    <label for="competencia" class="block text-sm font-semibold text-gray-700">Competencia</label>
                    <Multiselect v-model="form.tags" mode="tags"
                                :close-on-select="true"
                                :searchable="true"
                                :options="com_list" />

                </div>

            </form>
        </div>
   </App>
   
</template>

<script>
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import App from '@/Layouts/App.vue'
    import Multiselect from '@vueform/multiselect'


    export default {
        props: {
            afirmation: Object,
            competencias: Object
        },
        components: {
            Head,
            App,
            Link,
            Multiselect
        },
        data(){
            var data ={
                text:     this.afirmation.text,
                ponderacion: this.afirmation.ponderacion,
                tags: this.afirmation.competencias.map((e)=>{
                    return e.id
                })
            }

            return{
                form:this.$inertia.form(data),
                com_list: this.competencias
            }
        },
        methods:{

            submit(){
                this.$inertia.put(route('afirmation.update', this.$props.afirmation.id), this.form)
            }
        }
    }
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
