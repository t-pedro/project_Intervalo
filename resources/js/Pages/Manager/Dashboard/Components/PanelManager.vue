<template>
    <div class="flex-grow flex flex-col">
        <div class="w-11/12 mx-auto mt-4">
            <div class="shadow sm:rounded-md sm:overflow-hidden">
                <div class="bg-white py-6 px-4 space-y-6 sm:p-6">
                    <div class="flex items-center justify-between flex-wrap sm:flex-nowrap ">
                        <div class="flex">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Dashboard | Manager</h3>
                        </div>
                        <div class="flex mt-4 sm:mt-0">
                            <label class="font-semibold mr-2" for="">Bienvenido: {{ $page.props.user.name }} </label>
                        </div>
                    </div>
                    <hr>
                    <Loading v-show="loading"></Loading>

                    <div v-show="!loading">
                        <div>
                            <div class="px-4 sm:px-0">
                                <h3 class="text-base font-semibold leading-7 text-gray-900">Detalle</h3>

                                <ul role="list"
                                    class="grid grid-cols-1 gap-4 sm:gap-6 sm:grid-cols-2 xl:grid-cols-3 mt-3">
                                    <li class="relative col-span-1 flex shadow-sm rounded-md">
                                        <div
                                            class="flex-shrink-0 flex items-center justify-center w-16 text-white text-sm font-medium rounded-l-md bg-orange-500">
                                            UA
                                        </div>
                                        <div
                                            class="flex-1 flex items-center justify-between border-t border-r border-b border-gray-200 bg-white rounded-r-md truncate">
                                            <div class="flex flex-col px-4 py-2 text-sm truncate">
                                                <a href="#" class="text-gray-900 font-medium hover:text-gray-600">
                                                    <b>USUARIOS ACTIVAS</b>
                                                </a>
                                                <p class="text-gray-400 mt-1 text-gray-700 text-lg">{{ data.total_user
                                                    }}
                                                </p>
                                                <!-- <a :href="route('user')" class="mt-1 text-gray-500 hover:underline hover:text-gray-700">Ver Usuarios</a> -->
                                                -
                                            </div>
                                        </div>
                                    </li>
                                    <li class="relative col-span-1 flex shadow-sm rounded-md">
                                        <div
                                            class="flex-shrink-0 flex items-center justify-center w-16 text-white text-sm font-medium rounded-l-md bg-green-500">
                                            CA
                                        </div>
                                        <div
                                            class="flex-1 flex items-center justify-between border-t border-r border-b border-gray-200 bg-white rounded-r-md truncate">
                                            <div class="flex flex-col px-4 py-2 text-sm truncate">
                                                <a href="#" class="text-gray-900 font-medium hover:text-gray-600">
                                                    <b>COMPETENCIAS ACTIVAS</b>
                                                </a>
                                                <p class="text-gray-400 mt-1 text-gray-700 text-lg">
                                                    {{ data.total_competencias }}</p>
                                                <!-- <a :href="route('competencia')" class="mt-1 text-gray-500 hover:underline hover:text-gray-700">Ver Competencias</a> -->
                                                -
                                            </div>
                                        </div>
                                    </li>
                                    </ul>
                                    <ul role="list"
                                    class="grid grid-cols-1 gap-4 sm:gap-6 sm:grid-cols-2 xl:grid-cols-3 mt-3">

                                    <li class="relative col-span-1 flex shadow-sm rounded-md">
                                        <div
                                            class="flex-shrink-0 flex items-center justify-center w-16 text-white text-sm font-medium rounded-l-md bg-blue-500">
                                            ER
                                        </div>
                                        <div
                                            class="flex-1 flex items-center justify-between border-t border-r border-b border-gray-200 bg-white rounded-r-md truncate">
                                            <div class="flex flex-col px-4 py-2 text-sm truncate">
                                                <a href="#" class="text-gray-900 font-medium hover:text-gray-600">
                                                    <b>EXAMENES REALIZADOS</b>
                                                </a>
                                                <p class="text-gray-400 mt-1 text-gray-700 text-lg">
                                                    {{ test_details.total }}</p>
                                                <!-- <a :href="route('test')" class="mt-1 text-gray-500 hover:underline hover:text-gray-700">Ver Examenes</a> -->
                                                -
                                            </div>
                                        </div>
                                    </li>

                                    <li class="relative col-span-1 flex shadow-sm rounded-md">
                                        <div
                                            class="flex-shrink-0 flex items-center justify-center w-16 text-white text-sm font-medium rounded-l-md bg-gray-500">
                                            EF
                                        </div>
                                        <div
                                            class="flex-1 flex items-center justify-between border-t border-r border-b border-gray-200 bg-white rounded-r-md truncate">
                                            <div class="flex flex-col px-4 py-2 text-sm truncate">
                                                <a href="#" class="text-gray-900 font-medium hover:text-gray-600">
                                                    <b>EXAMENES FINALIZADOS (%)</b>
                                                </a>
                                                <p class="text-gray-400 mt-1 text-gray-700 text-lg">
                                                    {{ validarNumero(((test_details.finish / test_details.total) *
                                                    100).toFixed(2)) }}%
                                                </p>
                                                <!-- <a :href="route('test')" class="mt-1 text-gray-500 hover:underline hover:text-gray-700">Ver Examenes</a> -->
                                                -
                                            </div>
                                            <div class="stat-figure m-2">
                                                <div class="radial-progress bg-white text-green-700 border-4 border-gray-100"
                                                    :style="{ '--value': validarNumero((test_details.finish / test_details.total) * 100) }">
                                                    {{ validarNumero(((test_details.finish / test_details.total) *
                                                    100).toFixed(2)) }}%
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="relative col-span-1 flex shadow-sm rounded-md">
                                        <div
                                            class="flex-shrink-0 flex items-center justify-center w-16 text-white text-sm font-medium rounded-l-md bg-red-300">
                                            EI
                                        </div>
                                        <div
                                            class="flex-1 flex items-center justify-between border-t border-r border-b border-gray-200 bg-white rounded-r-md truncate">
                                            <div class="flex flex-col px-4 py-2 text-sm truncate">
                                                <a href="#" class="text-gray-900 font-medium hover:text-gray-600">
                                                    <b>EXAMENES INCOMPLETOS (%)</b>
                                                </a>
                                                <p class="text-gray-400 mt-1 text-gray-700 text-lg">
                                                    {{ validarNumero(((test_details.abandoned / test_details.total) *
                                                    100).toFixed(2)) }}%
                                                </p>
                                                <!-- <a :href="route('test')" class="mt-1 text-gray-500 hover:underline hover:text-gray-700">Ver Examenes</a> -->
                                                -
                                            </div>
                                            <div class="stat-figure m-2">
                                                <div class="radial-progress bg-white text-green-700 border-4 border-gray-100"
                                                    :style="{ '--value': validarNumero((test_details.abandoned / test_details.total) * 100) }">
                                                    {{ validarNumero(((test_details.abandoned / test_details.total) *
                                                    100).toFixed(2)) }}%
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>

                                <ul role="list"
                                    class="grid grid-cols-1 gap-4 sm:gap-6 sm:grid-cols-2 xl:grid-cols-3 mt-3">

                                    <li class="relative col-span-1 flex shadow-sm rounded-md">
                                        <div
                                            class="flex-shrink-0 flex items-center justify-center w-16 text-white text-sm font-medium rounded-l-md bg-blue-500">
                                            DR
                                        </div>
                                        <div
                                            class="flex-1 flex items-center justify-between border-t border-r border-b border-gray-200 bg-white rounded-r-md truncate">
                                            <div class="flex flex-col px-4 py-2 text-sm truncate">
                                                <a href="#" class="text-gray-900 font-medium hover:text-gray-600">
                                                    <b>DIAGNOSTICOS REALIZADOS</b>
                                                </a>
                                                <p class="text-gray-400 mt-1 text-gray-700 text-lg">
                                                    {{ test_diagnostico_details.total }}</p>
                                                <!-- <a :href="route('test')" class="mt-1 text-gray-500 hover:underline hover:text-gray-700">Ver Examenes</a> -->
                                                -
                                            </div>
                                        </div>
                                    </li>

                                    <li class="relative col-span-1 flex shadow-sm rounded-md">
                                        <div
                                            class="flex-shrink-0 flex items-center justify-center w-16 text-white text-sm font-medium rounded-l-md bg-gray-500">
                                            DF
                                        </div>
                                        <div
                                            class="flex-1 flex items-center justify-between border-t border-r border-b border-gray-200 bg-white rounded-r-md truncate">
                                            <div class="flex flex-col px-4 py-2 text-sm truncate">
                                                <a href="#" class="text-gray-900 font-medium hover:text-gray-600">
                                                    <b>DIAGNOSTICO FINALIZADOS (%)</b>
                                                </a>
                                                <p class="text-gray-400 mt-1 text-gray-700 text-lg">
                                                    {{ validarNumero(((test_diagnostico_details.finish /
                                                    test_diagnostico_details.total) * 100).toFixed(2)) }}%
                                                </p>
                                                <!-- <a :href="route('test')" class="mt-1 text-gray-500 hover:underline hover:text-gray-700">Ver Examenes</a> -->
                                                -
                                            </div>
                                            <div class="stat-figure m-2">
                                                <div class="radial-progress bg-white text-green-700 border-4 border-gray-100"
                                                    :style="{ '--value': validarNumero((test_diagnostico_details.finish / test_diagnostico_details.total) * 100) }">
                                                    {{ validarNumero(((test_diagnostico_details.finish /
                                                    test_diagnostico_details.total) * 100).toFixed(2)) }}%
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="relative col-span-1 flex shadow-sm rounded-md">
                                        <div
                                            class="flex-shrink-0 flex items-center justify-center w-16 text-white text-sm font-medium rounded-l-md bg-red-300">
                                            DI
                                        </div>
                                        <div
                                            class="flex-1 flex items-center justify-between border-t border-r border-b border-gray-200 bg-white rounded-r-md truncate">
                                            <div class="flex flex-col px-4 py-2 text-sm truncate">
                                                <a href="#" class="text-gray-900 font-medium hover:text-gray-600">
                                                    <b>DIAGNOSTICO INCOMPLETOS (%)</b>
                                                </a>
                                                <p class="text-gray-400 mt-1 text-gray-700 text-lg">
                                                    {{ validarNumero(((test_diagnostico_details.abandoned /
                                                    test_diagnostico_details.total) * 100).toFixed(2)) }}%
                                                </p>
                                                <!-- <a :href="route('test')" class="mt-1 text-gray-500 hover:underline hover:text-gray-700">Ver Examenes</a> -->
                                                -
                                            </div>
                                            <div class="stat-figure m-2">
                                                <div class="radial-progress bg-white text-green-700 border-4 border-gray-100"
                                                    :style="{ '--value': validarNumero((test_diagnostico_details.abandoned / test_diagnostico_details.total) * 100) }">
                                                    {{ validarNumero(((test_diagnostico_details.abandoned /
                                                    test_diagnostico_details.total) * 100).toFixed(2)) }}%
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <hr class="mt-4">
                        <h3 class="text-base font-semibold leading-7 text-gray-900">Resumen General</h3>
                        <p class="text-sm">Detalle de Examenes realizados por competencias.</p>

                        <ul role="list" class="grid grid-cols-1 gap-4 sm:gap-6 sm:grid-cols-2 xl:grid-cols-3 mt-3">

                            <li class="relative col-span-1 flex shadow-sm rounded-md">
                                <div
                                    class="flex-shrink-0 flex items-center justify-center w-16 text-white text-sm font-medium rounded-l-md bg-blue-300">
                                    ER
                                </div>
                                <div
                                    class="flex-1 flex items-center justify-between border-t border-r border-b border-gray-200 bg-white rounded-r-md truncate">
                                    <div class="flex flex-col px-4 py-2 text-sm truncate">
                                        <a href="#" class="text-gray-900 font-medium hover:text-gray-600">
                                            <b>REALIZADOS</b>
                                        </a>
                                        <p class="text-gray-400 mt-1 text-gray-700 text-lg">
                                            {{ test_total_details.total_test_score }}</p>
                                        <!--  <a :href="route('test')" class="mt-1 text-gray-500 hover:underline hover:text-gray-700">Ver Examenes</a> -->
                                        -
                                    </div>
                                </div>
                            </li>

                            <li class="relative col-span-1 flex shadow-sm rounded-md">
                                <div
                                    class="flex-shrink-0 flex items-center justify-center w-16 text-white text-sm font-medium rounded-l-md bg-green-700">
                                    EA
                                </div>
                                <div
                                    class="flex-1 flex items-center justify-between border-t border-r border-b border-gray-200 bg-white rounded-r-md truncate">
                                    <div class="flex flex-col px-4 py-2 text-sm truncate">
                                        <a href="#" class="text-gray-900 font-medium hover:text-gray-600">
                                            <b>APROBADOS (%)</b>
                                        </a>
                                        <p class="text-gray-400 mt-1 text-gray-700 text-lg">
                                            {{ validarNumero(((test_total_details.approbe /
                                            test_total_details.total_test_score) * 100).toFixed(2)) }}%
                                        </p>
                                        <!-- <a :href="route('test')" class="mt-1 text-gray-500 hover:underline hover:text-gray-700">Ver Examenes</a> -->
                                        -
                                    </div>
                                    <div class="stat-figure m-2">
                                        <div class="radial-progress bg-white text-green-700 border-4 border-gray-100"
                                            :style="{ '--value': validarNumero((test_total_details.approbe / test_total_details.total_test_score) * 100) }">
                                            {{ validarNumero(((test_total_details.approbe /
                                            test_total_details.total_test_score) * 100).toFixed(2)) }}%
                                        </div>
                                    </div>
                                </div>
                            </li>

                            <li class="relative col-span-1 flex shadow-sm rounded-md">
                                <div
                                    class="flex-shrink-0 flex items-center justify-center w-16 text-white text-sm font-medium rounded-l-md bg-yellow-500">
                                    ED
                                </div>
                                <div
                                    class="flex-1 flex items-center justify-between border-t border-r border-b border-gray-200 bg-white rounded-r-md truncate">
                                    <div class="flex flex-col px-4 py-2 text-sm truncate">
                                        <a href="#" class="text-gray-900 font-medium hover:text-gray-600">
                                            <b>DESAPROBADOS (%)</b>
                                        </a>
                                        <p class="text-gray-400 mt-1 text-gray-700 text-lg">
                                            {{ validarNumero(((test_total_details.disapprobe /
                                            test_total_details.total_test_score) * 100).toFixed(2)) }}%
                                        </p>
                                        <!-- <a :href="route('test')" class="mt-1 text-gray-500 hover:underline hover:text-gray-700">Ver Examenes</a> -->
                                        -
                                    </div>
                                    <div class="stat-figure m-2">
                                        <div class="radial-progress bg-white text-green-700 border-4 border-gray-100"
                                            :style="{ '--value': validarNumero((test_total_details.disapprobe / test_total_details.total_test_score) * 100) }">
                                            {{ validarNumero(((test_total_details.disapprobe /
                                            test_total_details.total_test_score) * 100).toFixed(2)) }}%
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import { useForm } from '@inertiajs/inertia-vue3'
import Loading from '@/Layouts/Components/Loading.vue'

export default {
    props: {

    },
    components: {
        Loading
    },
    data() {
        return {
            loading: true,
            data: {},
            test_details: {},
            test_diagnostico_details: {},
            test_total_details: {}

        }
    },
    methods: {
        async dasboardEmpleado() {

            var rt = route('dashboard.details')
            const response = await fetch(rt, { method: 'GET' })

            this.data = await response.json()
            this.calculateTest()
            this.loading = false;
        },
        calculateTest() {
            let approbe = 0;
            let disapprobe = 0;
            let approbe_diagnostico = 0;
            let disapprobe_diagnostico = 0;

            this.data.test.forEach(element => {
                this.test_details.finish = this.data.test.filter(t => t.status_id == 2).length;
                this.test_details.abandoned = this.data.test.filter(t => t.status_id == 1).length;

                approbe += element.test_detail.filter(t => t.score >= 50).length;
                disapprobe += element.test_detail.filter(t => t.score < 50).length;

            });

            this.data.test_diagnosticos.forEach(element => {

                this.test_diagnostico_details.finish = this.data.test_diagnosticos.filter(t => t.status_id == 2).length;
                this.test_diagnostico_details.abandoned = this.data.test_diagnosticos.filter(t => t.status_id == 1).length;

                approbe_diagnostico += element.test_detail.filter(t => t.score >= 50).length;
                disapprobe_diagnostico += element.test_detail.filter(t => t.score < 50).length;
            });

            this.test_details.total = this.data.test.length;
            this.test_details.approbe = approbe;
            this.test_details.disapprobe = disapprobe;
            this.test_details.total_test_score = approbe + disapprobe;

            this.test_diagnostico_details.total = this.data.test_diagnosticos.length;
            this.test_diagnostico_details.approbe = approbe_diagnostico;
            this.test_diagnostico_details.disapprobe = disapprobe_diagnostico;
            this.test_diagnostico_details.total_test_score = approbe_diagnostico + disapprobe_diagnostico;

            this.test_total_details.total = this.test_details.total + this.test_diagnostico_details.total;
            this.test_total_details.approbe = this.test_details.approbe + this.test_diagnostico_details.approbe;
            this.test_total_details.disapprobe = this.test_details.disapprobe + this.test_diagnostico_details.disapprobe;
            this.test_total_details.total_test_score = this.test_details.total_test_score + this.test_diagnostico_details.total_test_score;


        },
        validarNumero(valor) {
            if (isNaN(valor)) {
                return 0;
            } else {
                return valor;
            }
        }
    },
    mounted() {
        this.dasboardEmpleado();
    }
}
</script>