<template>
    <div>
        <div class="w-5/5 mx-auto" id="printMe">
            <div class="my-10 text-4xl">Resultado</div>
            <!--  <p>{{ this.resultado[0] }}</p>  -->
            <div v-for="r in this.resultado" :key="r.id" class="w-full mx-auto bg-white rounded-2xl p-10 shadow mb-6 ">
                <div v-for="re, key in r">
                    <div class="grid grid-cols-3 gap-4">
                        <div class="col-span-3 text-xl text-academy-accent">COMPETENCIA: {{ key }}</div>
                        <div class="col-span-1 flex justify-between items-center">
                            <div class="mx-auto">
                                <div class="radial-progress bg-blue-500  text-blue-200 border-4 border-blue-500"
                                    :style="`--value:${parseInt(re.promedio)}; --size:12rem; --thickness: 2rem;`">
                                    <span class="font-bold text-3xl">{{ re.promedio }} %</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-2 items-center">
                            <div class="w-full mx-auto">
                                <div class="card w-12/12 bg-gray-100 cursor-pointer">
                                    <div class="card-body p-4">
                                        <h2 class="card-title text-sm text-justify">{{ re.texto }}</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full mx-auto mt-2">
                                <div class="card w-12/12 bg-blue-50 cursor-pointer">
                                    <div class="card-body p-4 grid grid-cols-3 gap-4">
                                        <h2 class="card-title col-span-1">Capsulas Recomendadas:</h2>
                                        <div class="card-actions justify-center col-start-2 col-span-2">
                                            <div v-for="capsula in re.capsulas" :key="capsula.id">
                                                <div class="badge badge-outline py-5 px-5 hover:bg-blue-500 hover:text-white"><a :href="capsula.url" target="_blank">{{ capsula.title }}</a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-3 text-xl text-academy-accent w-full"
                            v-for="cr in re.competencia_relacionada" :key="cr.id">
                            <div class="p-4 w-full">
                                <div
                                    class=" flex items-center  justify-between p-4  rounded-lg  bg-gray-50 shadow-indigo-50 shadow-md ">
                                    <div>
                                        <h3 class="mt-2 text-sm font-bold text-green-500">{{ cr.competencia }}</h3>
                                        <p class="text-sm font-semibold text-gray-400"><b>Resultado:</b> {{
                                        ((cr.suma / cr.cantidad) / 5) * 5 }} %</p>
                                        <p class="text-sm font-semibold text-gray-400 mt-4 text-justify"><b>Devolución:
                                                {{ cr.texto }}</b></p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-5 mb-20 items-center text-center">
            <a @click="$emit('return_start')" class="btn btn-secondary items-center text-center">
                <span class="mr-2">Inicio</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
            </a>
        </div>
    </div>
</template>

<script>

export default {
    props: {
        resultado: Object
    },
    components: {

    },
    data() {

    },
    methods: {
        printResultado() {
            let rt = route('quiz.print');
            axios.get(rt, {
                resultado: this.resultado,
            })
                .then(response => {
                    if (response.status == 200) {

                    } else {

                    }
                })
        }
    },
    mounted() {

    }
}

</script>

