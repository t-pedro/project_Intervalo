<template>
    <App>
        <Toast :toast="this.message" :type="this.labelType" @clear="clearMessage"></Toast>

        <div class="flex-grow flex flex-col w-10/12 mx-auto p-10">
            <div class="w-full mx-auto flex justify-between items-center">
                <h1 class="my-10 text-4xl font-bold">Editar Competencia</h1>
                <div>
                    <button class="btn btn-primary btn-sm mr-2" @click.prevent="submit">Guardar</button>
                    <a class="btn btn-primary btn-sm mr-2" :href="route('competencia')">Volver</a>
                </div>
            </div>        
            <form action="#" method="POST">
                <div class="grid grid-cols-3 mx-auto ">
                    <div class="col-span-3 text-lg px-5 py-4 font-bold">
                        <div>Información General</div> 
                    </div>
                    <div class="col-span-3 pt-6 px-6">
                        <div class="">
                            <label for="competencia" class="block text-sm font-semibold text-gray-700">Competencia</label>
                            <input v-model="form.competencia" type="text" name="competencia" id="competencia"  class="mt-1 input input-bordered w-full" />
                        </div>

                        <div class="mt-4">
                            <label for="category" class="block text-sm font-semibold text-gray-700">Categoria</label>
                            <select class="select w-full mt-1 input-bordered" v-model="form.category_id" name="category" id="category">
                                <option value="" disabled selected>Seleccione una categoría</option>
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{cat.title}}</option>
                            </select>
                        </div>

                        <div class="mt-4 ">
                            <label for="resume" class="block text-sm font-semibold text-gray-700">Introducción</label>
                            <input v-model="form.resume" type="text" name="resume" id="resume"  class="mt-1 input input-bordered w-full" />
                        </div>

                        <div class="mt-4 ">
                            <label for="definicion" class="block text-sm font-semibold text-gray-700">Definición</label>
                            <textarea v-model="form.definicion" type="text" name="definicion" id="definicion"  rows="5"  class="mt-1 textarea textarea-bordered w-full"></textarea>
                        </div>
                       
                        <div class="mt-4 ">
                            <label for="comportamiento" class="block text-sm font-semibold text-gray-700">Comportamientos Observables</label>
                            <textarea v-model="form.comportamiento" type="text" name="comportamiento" id="comportamiento" rows="5" class="mt-1 textarea textarea-bordered w-full"></textarea>
                        </div>

                        <div class="mt-4">
                            <label for="capsule" class="block text-sm font-semibold text-gray-700">Capsulas</label>
                            <Multiselect v-model="form.tags" mode="tags"
                                        :close-on-select="true"
                                        :searchable="true"
                                        :options="cap_list" />

                        </div> 
                        
                    </div>

                    <div class="col-span-3 w-full mx-auto border-b border-gray-300 py-5"></div>

                    <div class="col-span-3 pt-6 px-6">
                        <div class="w-full mx-auto flex justify-between items-center">
                            <div class="mt-4">
                                <label for="category" class="block text-sm font-semibold text-gray-700">Competencia Relacionada</label>
                                <select class="select w-full mt-1 input-bordered" v-model="this.competencia_select" name="competencia_select" id="competencia_select">
                                    <option value="" disabled selected>Seleccione una competencia</option>
                                    <option v-for="com in competencias" :key="com.id" :value="com.id">{{com.competencia}}</option>
                                </select>
                            </div>
                            <div>
                                <button class="btn btn-primary btn-sm mr-2 mt-6" @click.prevent="add_competencias_relacionadas">Agregar</button>
                            </div>
                        </div> 
                        

                        <div class="grid grid-cols-2 gap-2 mx-auto mt-4" v-for="cp in form.competencias_related">
                            <div class="col-span-2 w-full mx-auto border-b border-gray-300 py-5"></div>
                            <div class="col-span-2 w-full mx-auto flex justify-between items-center">
                                <div class="mt-4">
                                    <label v-if="cp.action != 'D'" for="comportamiento" class="col-span-2 block text-lx font-semibold text-gray-700 ">Competencia: <b>{{cp.competencia_relate}}</b></label>
                                    <label v-else for="comportamiento" class="col-span-2 block text-lx font-semibold text-red-700 ">Competencia: <b>{{cp.competencia_relate}} - Eliminado</b></label>
                                </div>
                                <div>
                                    <button v-if="cp.action != 'D'" class="btn btn-sm border-red-500 bg-red-500 hover:bg-red-700 hover:border-red-700 text-white mr-2 mt-6" @click.prevent="delete_competencias_relacionadas(cp.relate_id)">Eliminar</button>
                                    <button v-else class="btn btn-sm border-orange-500 bg-orange-500 hover:bg-orange-700 hover:border-orange-700 text-white mr-2 mt-6" @click.prevent="deshacer_competencias_relacionadas(cp.relate_id)">Deshacer</button>
                                </div>
                            </div> 
                            <label for="comportamiento" class="col-span-1 block text-sm font-semibold text-gray-700 ">Respuesta Aprobada:</label>
                            <label for="comportamiento" class="col-span-1 block text-sm font-semibold text-gray-700 ">Respuesta Desaprobado:</label>
                            <textarea v-model="cp.feedback_approve" type="text" name="comportamiento" id="comportamiento" rows="3" class="col-span-1 mt-1 textarea textarea-bordered w-full"></textarea>
                            <textarea v-model="cp.feedback_disapprove" type="text" name="comportamiento" id="comportamiento" rows="3" class="col-span-1 mt-1 textarea textarea-bordered w-full"></textarea>
                        </div>
                        
                    </div>

                    <div class="col-span-3 w-full mx-auto border-b border-gray-300 py-5"></div>
                    
                    <div class="col-span-3" v-if="competencia.afirmations">
                        <div class="col-span-3 text-lg px-5 py-4 font-bold">
                            <div>Afirmaciones</div> 
                        </div>
                        <div class="col-span-3">
                            <ul>
                                <li v-for="af in competencia.afirmations" :key="af.id"
                                    class="py-2 pl-6 border-b "
                                    >{{af.text}} <a class="link link-primary ml-2" :href="route('afirmation.edit', af.id)">Ver</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

            </form>
        </div>
   </App>
   
</template>

<script>
    import { Head, Link } from '@inertiajs/inertia-vue3';
    import App from '@/Layouts/App.vue'
    import Multiselect from '@vueform/multiselect'
    import Toast from '@/Layouts/Components/Toast.vue'

    export default {
        props: {
            competencia: Object,
            categories:Object,
            capsules: Object,
            competencias: Object,
            competencias_related: Object,
        },
        components: {
            Head,
            App,
            Link,
            Multiselect,
            Toast,            
        },
        data(){
            var data = {
                competencia: this.competencia.competencia,
                resume: this.competencia.resume,
                definicion: this.competencia.definicion,
                comportamiento: this.competencia.comportamiento,
                category_id: this.competencia.category_id,
                tags: this.competencia.capsules.map( e => e.id),
                competencias_related: this.competencias_related
            }
            
            return{
                form:this.$inertia.form(data),
                cap_list: this.capsules,
                competencia_select: '',
                showToast: false,
                message: "",
                labelType: "success"
                //competencias_related: this.competencias_related, 
            }
        },
        methods:{
            clearMessage() {
                this.message = ""
            },
            submit(){
                this.$inertia.put(route('competencia.update', this.$props.competencia.id), this.form)
            },
            add_competencias_relacionadas(){
                let resultado = this.competencias.find( competencia => competencia.id === this.competencia_select );
                //Verificar que competencia no se encuentre incluida previamente.
                if(resultado){
                    let existe_competencia = this.form.competencias_related.find( competencia => competencia.relate_id === this.competencia_select );
                    if(existe_competencia){
                        this.labelType = "danger"
                        this.message = 'La competencia fue incluida previamente'
                    }else{
                        let comp = {
                            'competencia' : '',
                            'competencia_id' : this.competencia.id,
                            'competencia_relate' : resultado.competencia,
                            'relate_id' : resultado.id,
                            'feedback_approve' : '',
                            'feedback_disapprove' : '',
                            'action' : 'N'
                        }
                        this.form.competencias_related.push(comp)
                        this.labelType = "success"
                        this.message = 'Se ha agregado correctamente'
                    }
                }   
            },
            delete_competencias_relacionadas(id){
                let index_competencia = this.form.competencias_related.findIndex( competencia => competencia.relate_id === id );
                this.form.competencias_related[index_competencia]['action'] = 'D'
                this.labelType = "success"
                this.message = 'Se ha eliminado correctamente'
            },
            deshacer_competencias_relacionadas(id){
                let index_competencia = this.form.competencias_related.findIndex( competencia => competencia.relate_id === id );
                this.form.competencias_related[index_competencia]['action'] = 'U'
                this.labelType = "success"
                this.message = 'Se ha incluido correctamente'
            }
        }
    }
</script>

<style src="@vueform/multiselect/themes/default.css"></style>