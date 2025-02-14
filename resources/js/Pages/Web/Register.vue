<template>
    <div>

        <div class="w-5/5 mx-auto mt-10 mb-20">
            <div class="my-10 text-4xl">
                <h1 class="my-10 text-2xl font-bold">Datos del Solicitante</h1>
            </div>
            <form
                class="card bg-base-100 shadow-xl transition duration-300 ease-in  px-8 pt-6 pb-8 mb-4 grid grid-cols-2 gap-4">
                <div class="mb-4 col-span-1">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                        Nombre
                    </label>
                    <input
                        class="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="name" name="name" type="text" v-model="form_register.name">
                </div>
                <div class="mb-4 col-span-1">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="lastname">
                        Apellido
                    </label>
                    <input
                        class="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        id="lastname" name="lastname" type="text" v-model="form_register.lastname">
                </div>
                <div class="mb-4 col-span-2">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="mail">
                        Correo Electronico
                    </label>
                    <input
                        class="shadow appearance-none border border-gray-300 rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                        id="mail" name="mail" type="email" v-model="form_register.email">
                </div>
                <div class="items-center text-center mb-4 col-span-2">
                    <a @click="$emit('return_start')" class="btn btn-secondary space-x-2 m-2">
                        <span class="ml-2">Inicio</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                    </a>
                    <a @click="registerPerson" class="btn btn-primary space-x-2">
                        <span class="ml-2">Continuar</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                        </svg>
                    </a>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            form_register: {
                name: '',
                lastname: '',
                email: ''
            }
        };
    },
    methods: {
        registerPerson() {
            this.$emit("register", this.form_register)
        }
    },
};
</script>

