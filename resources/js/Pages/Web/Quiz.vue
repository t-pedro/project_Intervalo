<template>
    <div>
        <div class="w-5/5 mx-auto mt-10 mb-20">
            <div class="my-10 text-4xl">Diagnostico de Competencias</div>
            <button @click="fillQuests" class="btn btn-primary">Randomizar</button>
            <div class="flex px-6">
                <div class="w-3/5"></div>
                <div class="w-2/5 flex justify-between">
                    <span class="text-center text-sm text-accent">Altamente<br>Cierto</span>
                    <span class="text-center text-sm text-accent"></span>
                    <span class="text-center text-sm text-accent"></span>
                    <span class="text-center text-sm text-accent">No es<br>Cierto</span>
                </div>
            </div>
            <div v-for="item in quests" :key="item.id" class="mx-auto border-b border-gray-200 ">
                <div class="flex p-8 rounded-xl my-4" :class="{ 'bg-red-100': item.empty == 'true' }">
                    <div class="w-3/5">
                        {{ item.text }}
                    </div>


                    <div class="w-2/5 flex justify-between items-center pl-8" @click="item.empty = 'false'">
                        <input type="radio" :name="item.id" class="appearance-none" v-model="item.value"
                            :value="item.ponderacion == 'ASC' ? 10 : 100">
                        <input type="radio" :name="item.id" class="appearance-none" v-model="item.value"
                            :value="item.ponderacion == 'ASC' ? 25 : 75">
                        <input type="radio" :name="item.id" class="appearance-none" v-model="item.value"
                            :value="item.ponderacion == 'ASC' ? 50 : 50">
                        <input type="radio" :name="item.id" class="appearance-none" v-model="item.value"
                            :value="item.ponderacion == 'ASC' ? 75 : 25">
                        <input type="radio" :name="item.id" class="appearance-none" v-model="item.value"
                            :value="item.ponderacion == 'ASC' ? 100 : 10">
                    </div>

                </div>
            </div>

            <div class="mt-5 mb-20 items-center text-center">
                <a @click="$emit('return_start')" class="btn btn-secondary  m-2">
                    <span class="ml-2">Inicio</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                    </svg>
                </a>
                <a @click="processQuiz" class="btn btn-primary">
                    <span class="ml-2">Finalizar</span>
                    <Icons name="arrow" />
                </a>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        afirmations: Object
    },
    data() {

        let quests = this.afirmations.map(function (ele) {
            return { ...ele, value: '', empty: 'false' };
        })

        return {
            resultado: "",
            quests,
            empty: ""
        }
    },
    methods: {
        fillQuests() {
            this.quests = this.afirmations.map(function (ele) {
                let value = [10, 25, 50, 75, 100]
                return { ...ele, value: value[Math.floor(Math.random() * value.length)], empty: 'false' };
            })
        },
        processQuiz() {
            // Formateo los datos antes de enviar..
            this.quests = this.quests.map(function (ele) {
                if (ele['value'] == '') {
                    ele['empty'] = 'true'
                }
                return ele
            })

            let empty = this.quests.map(el => el['empty'])

            if (empty.includes('true')) return
            // Envio Datos al componente Padre..

            this.$emit("processQuiz", this.quests)
        }
    }
}
</script>

