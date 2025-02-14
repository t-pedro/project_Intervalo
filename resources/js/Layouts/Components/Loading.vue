<template>
    <div class="text-center justify-center">
        <div class="mt-10 flex items-center justify-center">
            <Icons name="loading" class="mr-4 w-8 h-8" ></Icons>
            <p class="text-base font-semibold text-blue-600">Cargando...</p>
        </div>
    </div>
</template>
<script>
    import Icons from '@/Layouts/Components/Icons.vue'
    export default {
        components:{
            Icons
        }
    }
</script>

