<template>
    <App>
        
        <PanelAdministrador v-if="this.rol.name === 'Administrador'"></PanelAdministrador>
        <PanelManager v-else-if="this.rol.name === 'Manager'"></PanelManager>
        <PanelEmpleado v-else></PanelEmpleado>

    </App>
</template>

<script>
    import { Head } from '@inertiajs/inertia-vue3';
    import App from '@/Layouts/App.vue'
    import Pageheader from '@/Layouts/Pageheader.vue'
    import Icons from '@/Layouts/Components/Icons.vue'
    import PanelAdministrador from './Components/PanelAdministrador.vue'
    import PanelManager from './Components/PanelManager.vue'
    import PanelEmpleado from './Components/PanelEmpleado.vue'


    export default {
        props:{
            rol: Object
        },
        components: {
            Head,
            App,
            Pageheader,
            Icons,
            PanelAdministrador,
            PanelManager,
            PanelEmpleado
        },
    }

</script>