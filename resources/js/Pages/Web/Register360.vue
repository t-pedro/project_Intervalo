<template>
    <div>
        <div class="w-full mx-auto mt-10 mb-20">
            <div class="my-10 text-4xl">
                <h1 class="my-10 text-2xl font-bold">Hola, {{ $page.props.user.name }}</h1>
                <p class="text-sm text-justify">Estas por iniciar un diagnóstico 360, puedes realizar una
                    autoevalucación, o puedes completarlo en base a tus percepciones sobre otro usuario de tu Empresa.
                </p>
            </div>
            <div
                class="card bg-base-100 shadow-xl transition duration-300 ease-in  px-8 pt-6 pb-8 mb-4 grid grid-cols-1 gap-4">
                <p class="mb-6 text-lg font-normal text-gray-500 lg:text-xl dark:text-gray-400"><strong>Diagnóstico:
                    </strong> {{ diagnostico.name }}</p>
                <hr>
                <div class="flex flex-col gap-10">
                    <div class="inline-flex items-center">
                        <label class="relative flex items-center cursor-pointer" for="html-description">
                            <input v-model="form.tipo_diagnostico" name="framework" type="radio" :value="false"
                                class="peer h-5 w-5 cursor-pointer appearance-none rounded-full border border-slate-300 checked:border-slate-400 transition-all"
                                id="html-description" checked />
                            <span
                                class="absolute bg-slate-800 w-3 h-3 rounded-full opacity-0 peer-checked:opacity-100 transition-opacity duration-200 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></span>
                        </label>
                        <label class="ml-2 text-slate-600 cursor-pointer text-sm" for="html-description">
                            <div>
                                <p class="font-medium">
                                    Autoevaluación
                                </p>
                                <p class="text-slate-500">
                                    Realizarás un evaluación de tus conocimientos sobre temas abordados en el
                                    diagnóstico seleccionado.
                                </p>
                            </div>
                        </label>
                    </div>

                    <div class="inline-flex items-center">
                        <label class="relative flex items-center cursor-pointer" for="react-description">
                            <input v-model="form.tipo_diagnostico" name="framework" type="radio" :value="true"
                                class="peer h-5 w-5 cursor-pointer appearance-none rounded-full border border-slate-300 checked:border-slate-400 transition-all"
                                id="react-description" />
                            <span
                                class="absolute bg-slate-800 w-3 h-3 rounded-full opacity-0 peer-checked:opacity-100 transition-opacity duration-200 top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2"></span>
                        </label>
                        <label class="ml-2 text-slate-600 cursor-pointer text-sm" for="react-description">
                            <div>
                                <p class="font-medium">
                                    Diagnostico 360
                                </p>
                                <p class="text-slate-500">
                                    Realizarás una evaluación en base a las percepciones que posees sobre otro usuario
                                    de la Empresa.
                                </p>
                            </div>
                        </label>
                    </div>
                    <div v-if="form.tipo_diagnostico">
                        <hr class="mb-4">
                        <label class="mr-2 text-sm font-bold text-gray-700">Usuario 360:</label>
                        <select class="select input-bordered" v-model="this.form.user_id" name="competencia_select"
                            id="competencia_select">
                            <option value="" disabled>Seleccione un usuario</option>
                            <option v-for="item in users" :key="item.id" :value="item.id">{{ item.name }}</option>
                        </select>
                    </div>
                    <div class="card-actions justify-end ">
                        <button @click="storeDiagnostico()" class="btn btn-primary space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                class="h-6 w-6 stroke-current">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Iniciar Diagnóstico</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        diagnostico: Object
    },
    data() {
        return {
            form: {},
            users: {}
        };
    },
    methods: {
        storeDiagnostico() {
            this.$emit("storeDiagnostico360", this.form)
        },
        async getUsers(){
            let rt = route('diagnostico.users',  this.diagnostico.id);
            await axios.get(rt)
                .then(response => {
                    if (response.status == 200) {
                        this.users = response.data.data
                    }
                })
        }
    },
    created(){
        this.form.diagnostico_id = this.diagnostico.id
        this.form.user_id = null

        this.getUsers()
    }
};
</script>
